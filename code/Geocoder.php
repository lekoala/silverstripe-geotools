<?php

/**
 * Geocoder
 *
 * @author lekoala
 */
class Geocoder extends Object
{

    /**
     * @var \Geocoder\ProviderAggregator
     */
    protected static $addressGeocoder;

    /**
     * @var array
     */
    protected static $ipGeocoder = array();

    /**
     * @var Zend_Cache_Frontend 
     */
    protected static $cache;

    /**
     * @var Exception
     */
    protected static $lastException;

    /**
     * @return Zend_Cache_Frontend
     */
    public static function getCache()
    {
        if (!self::$cache) {
            self::$cache = SS_Cache::factory('Geocoder');
        }
        return self::$cache;
    }

    public static function clearGeocoder()
    {
        self::$addressGeocoder = null;
    }

    /**
     *
     * @param \Geocoder\ProviderAggregator $geocoder
     */
    public static function setGeocoder(\Geocoder\ProviderAggregator $geocoder)
    {
        self::$addressGeocoder = $geocoder;
    }

    /**
     * @return \Geocoder\ProviderAggregator
     * @throws RuntimeException
     */
    public static function getGeocoder()
    {
        if (!self::$addressGeocoder) {
            $adapter = self::config()->adapter;
            if (!class_exists($adapter)) {
                throw new RuntimeException("Adapter class $adapter is not defined");
            }
            $adaperOptions = self::config()->adapter_options;
            $configuration = new \Ivory\HttpAdapter\Configuration();
            foreach ($adaperOptions as $adapterParam => $adapterValue) {
                $method = 'set' . ucfirst($adapterParam);
                $configuration->$method($adapterValue);
            }

            $geocoder = new \Geocoder\ProviderAggregator();

            $adapterInstance = new $adapter($configuration);

            $providers = self::config()->providers;

            foreach ($providers as $provider => $params) {
                if (isset($params['locale'])) {
                    $params['locale'] = i18n::get_locale();
                }
                array_unshift($params, $adapterInstance); //put the adapter as the first param

                $class = '\\Geocoder\\Provider\\' . $provider;
                if (!class_exists($class)) {
                    throw new RuntimeException("Provider class $class is not defined");
                }

                $reflectionClass = new ReflectionClass($class);
                $providerInstance = $reflectionClass->newInstanceArgs($params);
                $geocoder->registerProvider($providerInstance);
            }

            self::$addressGeocoder = $geocoder;
        }
        return self::$addressGeocoder;
    }

    /**
     * @param string $type
     * @return \Geocoder\Provider\GeoIP2
     * @throws InvalidArgumentException
     */
    public static function getIpGeocoder($type = 'city')
    {
        if (!isset(self::$ipGeocoder[$type])) {
            $reader = new \GeoIp2\Database\Reader(Director::baseFolder() . '/' . self::config()->geoip_data[$type]);

            $adapter = new \Geocoder\Adapter\GeoIP2Adapter($reader, $type);
            $geocoder = new \Geocoder\Provider\GeoIP2($adapter);

            // Save
            self::$ipGeocoder[$type] = $geocoder;
        }

        return self::$ipGeocoder[$type];
    }

    /**
     * @return Exception
     */
    public static function getLastException()
    {
        return self::$lastException;
    }

    /**
     * Get the ip of the client
     *
     * @return string
     */
    public static function getRealIp()
    {
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

            $ip = array_pop($ip);
        } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        // To test behaviour, it might be useful to return something else than local ip
        if (self::isLocalIp($ip)) {
            return Geocoder::config()->local_ip;
        }

        if (isset($ip) && filter_var($ip, FILTER_VALIDATE_IP) !== false) {
            return $ip;
        }

        return '0.0.0.0';
    }

    /**
     * Get a list of local ips
     * 
     * @return array
     */
    public static function getLocalIps()
    {
        return ['127.0.0.1', '1', '::1'];
    }

    /**
     * Test if given ip is local
     *
     * @param string $ip
     * @return bool
     */
    public static function isLocalIp($ip)
    {
        return in_array($ip, self::getLocalIps());
    }

    /**
     * Geocode an ip address using geoip2 database
     * 
     * @param string $ip if no ip is provided, client ip is used
     * @param string $type city|country
     * @param bool $refresh_cache
     * @return Geocoder\Model\Address
     */
    public static function geocodeIp($ip = null, $type = 'city', $refresh_cache = false)
    {
        if ($ip === null) {
            $ip = self::getRealIp();
        }

        if (self::isLocalIp($ip)) {
            return false;
        }

        $geocoder = self::getIpGeocoder($type);

        // Cache support
        if (self::config()->cache_enabled) {
            $cache = self::getCache();
            $cache_key = md5($ip . i18n::get_locale() . $type);
            if (!$refresh_cache) {
                $cache_result = $cache->load($cache_key);
                if ($cache_result) {
                    return unserialize($cache_result);
                }
            }
        }

        try {
            $result = $geocoder->geocode($ip);
            if ($result instanceof \Geocoder\Model\AddressCollection) {
                $result = $result->first();
            }
            if ($result && self::config()->cache_enabled) {
                $cache->save(serialize($result), $cache_key, array('ip'), null);
            }
            return $result;
        } catch (Exception $e) {
            SS_Log::log($e->getMessage(), SS_Log::DEBUG);
            self::$lastException = $e;
            return false;
        }
    }

    /**
     * Get an address from latitude and longitude
     * 
     * @param float $latitude
     * @param float $longitude
     * @param bool $refresh_cache
     * @return Geocoder\Model\Address
     */
    public static function reverseGeocode($latitude, $longitude, $refresh_cache = false)
    {
        // Cache support
        if (self::config()->cache_enabled) {
            $cache = self::getCache();
            $cache_key = md5($latitude . ',' . $longitude . i18n::get_locale());
            if (!$refresh_cache) {
                $cache_result = $cache->load($cache_key);
                if ($cache_result) {
                    return unserialize($cache_result);
                }
            }
        }

        try {
            $result = self::getGeocoder()->reverse($latitude, $longitude);
            if ($result instanceof \Geocoder\Model\AddressCollection) {
                $result = $result->first();
            }
            if ($result && self::config()->cache_enabled) {
                $cache->save(serialize($result), $cache_key, array('reverse'), null);
            }
            return $result;
        } catch (\Geocoder\Exception\ChainNoResultException $e) {
            SS_Log::log($e->getMessage(), SS_Log::DEBUG);
            self::$lastException = $e;
            return false;
        } catch (Exception $e) {
            SS_Log::log($e->getMessage(), SS_Log::WARN);
            self::$lastException = $e;
            return false;
        }
    }

    /**
     * Geocode an address using defined providers
     *
     * @param string $address
     * @param bool $refresh_cache
     * @return Geocoder\Model\Address
     */
    public static function geocodeAddress($address, $refresh_cache = false)
    {
        // Cache support
        if (self::config()->cache_enabled) {
            $cache = self::getCache();
            $cache_key = md5($address . i18n::get_locale());
            if ($refresh_cache) {
                $cache_result = $cache->load($cache_key);
                if ($cache_result) {
                    return unserialize($cache_result);
                }
            }
        }

        try {
            $result = self::getGeocoder()->geocode($address);
            if ($result instanceof \Geocoder\Model\AddressCollection) {
                $result = $result->first();
            }
            if ($result && self::config()->cache_enabled) {
                $cache->save(serialize($result), $cache_key, array('address'), null);
            }
            return $result;
        } catch (Exception $e) {
            $level = SS_Log::WARN;
            if ($e instanceof \Geocoder\Exception\CollectionIsEmpty) {
                $level = false;
            }
            if ($e instanceof \Geocoder\Exception\NoResult) {
                $level = false;
            }
            if ($level) {
                SS_Log::log($e->getMessage(), $level);
            }
            self::$lastException = $e;
            return false;
        }
    }

    /**
     * Format an address
     * @param \Geocoder\Model\Address $address
     * @param string $format
     * @return string
     */
    public static function formatAddress(\Geocoder\Model\Address $address, $format = null)
    {
        if ($format === null) {
            $format = self::config()->default_format;
        }
        $formatter = new \Geocoder\Formatter\StringFormatter();

        return $formatter->format($address, $format);
    }

    /**
     * Use a simple google maps request to geocode address
     * @param string $address
     * @return array|boolean
     */
    public static function simpleGeocode($address)
    {
        $url = sprintf('http://maps.google.com/maps?output=js&q=%s', rawurlencode($address));
        if ($result = file_get_contents($url)) {
            if (strpos($result, 'errortips') > 1) {
                return false;
            }
            preg_match('!center:\s*{lat:\s*(-?\d+\.\d+),lng:\s*(-?\d+\.\d+)}!U', $result, $match);
            $coords = array();
            $coords['Latitude'] = $match[1];
            $coords['Longitude'] = $match[2];
            return $coords;
        }
        return false;
    }
}

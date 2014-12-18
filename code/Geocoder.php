<?php

//this version has been slightly modified to handle null bounds
require_once 'thirdparty/get_in.php';

/**
 * Geocoder
 *
 * @author lekoala
 */
class Geocoder extends Object {

	/**
	 * @var \Geocoder\ProviderBasedGeocoder
	 */
	protected static $addressGeocoder;

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
	public static function getCache() {
		if (!self::$cache) {
			self::$cache = SS_Cache::factory('Geocoder');
		}
		return self::$cache;
	}

	public static function clearGeocoder() {
		self::$addressGeocoder = null;
	}

	public static function setGeocoder(\Geocoder\Geocoder $geocoder) {
		self::$addressGeocoder = $geocoder;
	}

	/**
	 * @return \Geocoder\Geocoder
	 * @throws RuntimeException
	 */
	public static function getGeocoder() {
		if (!self::$addressGeocoder) {
			$adapter = '\\Geocoder\\HttpAdapter\\' . self::config()->adapter;
			if (!class_exists($adapter)) {
				throw new RuntimeException("Adapter class $adapter is not defined");
			}
			$adaperOptions = self::config()->adapter_options;

			$geocoder = new \Geocoder\ProviderBasedGeocoder();

			$reflectionClass = new ReflectionClass($adapter);
			$adapterInstance = $reflectionClass->newInstanceArgs($adaperOptions);

			$providers = self::config()->providers;

			$chain = new \Geocoder\Provider\Chain();
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
				$chain->add($providerInstance);
			}

			$geocoder->registerProvider($chain);
			self::$addressGeocoder = $geocoder;
		}
		return self::$addressGeocoder;
	}

	/**
	 * @return Exception
	 */
	public static function getLastException() {
		return self::$lastException;
	}

	/**
	 * Get the ip of the client
	 * 
	 * @return string
	 */
	public static function getRealIp() {
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

			$ip = array_pop($ip);
		} else if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} else if (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
		} else if (!empty($_SERVER['REMOTE_ADDR'])) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		//to test behaviour, it might be useful to return something else than local ip
		if ($ip == '127.0.0.1') {
			return Geocoder::config()->local_ip;
		}

		if (isset($ip) && filter_var($ip, FILTER_VALIDATE_IP) !== false) {
			return $ip;
		}

		return '0.0.0.0';
	}

	/**
	 * Geocode an ip address using geoip2 database
	 * 
	 * @param string $ip if no ip is provided, client ip is used
	 * @param string $type city|country
	 * @param bool $refresh_cache
	 * @return Geocoder\Model\Address
	 * @throws InvalidArgumentException
	 */
	static public function geocodeIp($ip = null, $type = 'city', $refresh_cache = false) {
		if ($ip === null) {
			$ip = self::getRealIp();
		}

		if (!in_array($type, array('city', 'country'))) {
			throw new InvalidArgumentException('Type must either be city or country');
		}

		//cache support
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


		//could be updated to be configurable to use the webservice instead

		try {
			$reader = new \GeoIp2\Database\Reader(Director::baseFolder() . '/' . self::config()->geoip_data[$type]);

			$adapter = new \Geocoder\HttpAdapter\GeoIP2Adapter($reader, $type);
			$provider = new \Geocoder\Provider\GeoIP2($adapter);
			$geocoder = new \Geocoder\ProviderBasedGeocoder($provider);

			$result = $geocoder->geocode($ip);
			if (count($result) == 1) {
				$result = $result[0];
			}
			if ($result && self::config()->cache_enabled) {
				$cache->save(serialize($result), $cache_key, array('address'), null);
			}
			return $result;
		} catch (Exception $e) {
			SS_Log::log($e->getMessage(), SS_Log::WARN);
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
	static public function reverseGeocode($latitude, $longitude, $refresh_cache = false) {
		//cache support
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
			if (count($result) == 1) {
				$result = $result[0];
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
	 * @throws RuntimeException
	 */
	static public function geocodeAddress($address, $refresh_cache = false) {
		//cache support
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
			if (count($result) == 1) {
				$result = $result[0];
			}
			if ($result && self::config()->cache_enabled) {
				$cache->save(serialize($result), $cache_key, array('address'), null);
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
	 * Format an address
	 * @param \Geocoder\Model\Address $address
	 * @param string $format
	 * @return string
	 */
	static public function formatAddress(\Geocoder\Model\Address $address, $format = null) {
		if ($format === null) {
			$format = self::config()->default_format;
		}
		$formatter = new \Geocoder\Formatter\StringFormatter();

		return $formatter->format($address, $format);
	}

}

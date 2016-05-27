<?php

/**
 * List of utilities to deal with Belgium
 *
 * @author Koala
 */
class BelgianGeoUtils
{
    const CODE   = 'Code';
    const NAME   = 'Name';
    const REGION = 'Region';

    /**
     * An array of regions with their code as key
     *
     * @return array
     */
    public static function getRegions()
    {
        return array(
            'BE-BRU' => _t('BelgianGeoUtils.BEBRU', 'Bruxelles-Capitale'),
            'BE-VLG' => _t('BelgianGeoUtils.BEVLG', 'Vlaanderen'),
            'BE-WAL' => _t('BelgianGeoUtils.BEWAL', 'Wallonie'),
        );
    }

    /**
     * An array of provinces
     *
     * @return array
     */
    public static function getProvinces()
    {
        return array(
            array('Code' => 'BRU', 'Name' => _t('BelgianGeoUtils.BRU',
                    'Bruxelles-Capitale'), 'Region' => 'BE-BRU'),
            array('Code' => 'VAN', 'Name' => _t('BelgianGeoUtils.VAN',
                    'Antwerpen'), 'Region' => 'BE-VLG'),
            array('Code' => 'VLI', 'Name' => _t('BelgianGeoUtils.VLI', 'Limburg'),
                'Region' => 'BE-VLG'),
            array('Code' => 'VOV', 'Name' => _t('BelgianGeoUtils.VOV',
                    'Oost-Vlaanderen'), 'Region' => 'BE-VLG'),
            array('Code' => 'VBR', 'Name' => _t('BelgianGeoUtils.VBR',
                    'Vlaams-Brabant'), 'Region' => 'BE-VLG'),
            array('Code' => 'VWV', 'Name' => _t('BelgianGeoUtils.VWV',
                    'West-Vlaanderen'), 'Region' => 'BE-VLG'),
            array('Code' => 'WBR', 'Name' => _t('BelgianGeoUtils.WBR',
                    'Brabant wallon'), 'Region' => 'BE-WAL'),
            array('Code' => 'WHT', 'Name' => _t('BelgianGeoUtils.WHT', 'Hainaut'),
                'Region' => 'BE-WAL'),
            array('Code' => 'WLG', 'Name' => _t('BelgianGeoUtils.WLG', 'Liège'),
                'Region' => 'BE-WAL'),
            array('Code' => 'WLX', 'Name' => _t('BelgianGeoUtils.WLX',
                    'Luxembourg'), 'Region' => 'BE-WAL'),
            array('Code' => 'WNA', 'Name' => _t('BelgianGeoUtils.WNA', 'Namur'),
                'Region' => 'BE-WAL'),
        );
    }

    /**
     * Convert a region code into a name
     *
     * @param string $regionCode
     * @return array
     */
    public static function getRegionName($regionCode)
    {
        $regions = self::getRegions();
        if (isset($regions[$regionCode])) {
            return $regions[$regionCode];
        }
    }

    /**
     * Get the region of a province
     *
     * @param string $province
     * @return string
     */
    public static function getProvinceRegion($province)
    {
        foreach (self::getProvinces() as $pro) {
            if ($province == $pro['Code'] || $province == $pro['Name']) {
                return self::getRegionName($pro['Region']);
            }
        }
    }

    /**
     * Get provinces by region
     *
     * @param string $key Name or Code
     * @return array
     */
    public static function getProvincesByRegion($key = 'Code')
    {
        $dep = self::getProvinces();
        $reg = self::getRegions();

        $proByReg = array();
        foreach ($reg as $c => $r) {
            $proByReg[$r] = array();
        }

        foreach ($dep as $d) {
            $proByReg[$reg[$d['Region']]][$d[$key]] = $d['Name'];
        }

        return $proByReg;
    }

    /**
     * Get a list of belgian provinces
     *
     * @param string $key Name or Code
     * @return array
     */
    public static function getDepartmentsMap($key = 'Name')
    {
        $map = array();
        foreach (self::getProvinces() as $dep) {
            $map[$dep[$key]] = $dep['Name'];
        }
        asort($map);
        return $map;
    }
}

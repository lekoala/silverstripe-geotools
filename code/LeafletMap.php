<?php

/**
 * LeafletMap
 *
 * @author lekoala
 */
class LeafletMap extends ViewableData
{

    const TS_OSM_MAPNIK = 'osm_mapnik';
    const TS_OSM_BW = 'osm_bw';
    const TS_OSM_FR = 'osm_fr';
    const TS_ESRI = 'esri';
    const TS_HYDDA = 'hydda';

    protected static $instances = 0;
    protected $enableClustering = false;
    protected $useBuilder = true;
    protected $id;
    protected $width = '100%';
    protected $height = '300px';
    protected $latitude;
    protected $longitude;
    protected $icon;
    protected $zoom;
    protected $tileLayer;
    protected $tileOptions;
    protected $mapOptions;
    protected $builderOptions;
    protected $content;
    protected $itemsUrl;

    public function __construct()
    {
        parent::__construct();
        self::$instances++;

        if (defined('TILE_SERVER')) {
            $this->setTileServer(TILE_SERVER);
        } else {
            $this->setTileServer($this->config()->tile_server);
        }
    }

    public function getItemsUrl()
    {
        return $this->itemsUrl;
    }

    public function setItemsUrl($itemsUrl)
    {
        $this->itemsUrl = $itemsUrl;
    }

    public function getUseBuilder()
    {
        return $this->useBuilder;
    }

    public function setUseBuilder($useBuilder)
    {
        $this->useBuilder = $useBuilder;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function getIcon()
    {
        return $this->icon;
    }

    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
    }

    public function getZoom()
    {
        if ($this->zoom) {
            return $this->zoom;
        }
        return self::config()->default_zoom;
    }

    public function setZoom($v)
    {
        $this->zoom = $v;
        return $this;
    }

    public function getTileLayer()
    {
        if ($this->tileLayer) {
            return $this->tileLayer;
        }
        return self::config()->tilelayer;
    }

    public function setTileLayer($v)
    {
        $this->tileLayer = $v;
        return $this;
    }

    public function getMapOptions()
    {
        if ($this->mapOptions) {
            return $this->mapOptions;
        }
        return self::config()->map_options;
    }

    public function setMapOption($k, $v)
    {
        $this->mapOptions[$k] = $v;
        return $this;
    }

    public function setMapOptions($v)
    {
        $this->mapOptions = $v;
        return $this;
    }

    public function getMapOptionsJson()
    {
        return json_encode($this->getMapOptions());
    }

    public function getBuilderOptions()
    {
        if ($this->builderOptions) {
            return $this->builderOptions;
        }
        return self::config()->builder_options;
    }

    public function setBuilderOption($k, $v)
    {
        $this->builderOptions[$k] = $v;
        return $this;
    }

    public function setBuilderOptions($v)
    {
        $this->builderOptions = $v;
        return $this;
    }

    public function getBuilderOptionsJson()
    {
        return json_encode($this->getBuilderOptions());
    }

    public function getTileOptions()
    {
        if ($this->tileOptions) {
            return $this->tileOptions;
        }
        return self::config()->tile_options;
    }

    public function setTileOptions($v)
    {
        $this->tileOptions = $v;
        return $this;
    }

    public function getTileOptionsJson()
    {
        return json_encode($this->getTileOptions());
    }

    public function getEnableClustering()
    {
        return $this->enableClustering;
    }

    public function setEnableClustering($enableClustering)
    {
        $this->enableClustering = $enableClustering;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function setWidth($width)
    {
        $this->width = $width;
        return $this;
    }

    public function getID()
    {
        if ($this->id) {
            return $this->id;
        }
        return 'LeafletMap-' . self::$instances;
    }

    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setTileServer($server)
    {
        if (is_string($server)) {
            switch ($server) {
                case 'osm_mapnik':
                    $tileServer = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
                    $tileAttribution = '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>';
                    break;
                case 'osm_bw':
                    $tileServer = 'http://{s}.tiles.wmflabs.org/bw-mapnik/{z}/{x}/{y}.png';
                    $tileAttribution = '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>';
                    break;
                case 'osm_fr':
                    $tileServer = 'http://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png';
                    $tileAttribution = '&copy; Openstreetmap France | &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>';
                    break;
                case 'esri':
                    $tileServer = 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}';
                    $tileAttribution = 'Tiles &copy; Esri &mdash; Source: Esri, DeLorme, NAVTEQ, USGS, Intermap, iPC, NRCAN, Esri Japan, METI, Esri China (Hong Kong), Esri (Thailand), TomTom, 2012';
                    break;
                case 'hydda':
                    $tileServer = 'https://{s}.tile.openstreetmap.se/hydda/full/{z}/{x}/{y}.png';
                    $tileAttribution = 'Tiles courtesy of <a href="http://openstreetmap.se/" target="_blank">OpenStreetMap Sweden</a> &mdash; Map data &copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>';
                    break;
                default:
                    throw new Exception("Tileserver $server is not defined");
            }
        } else {
            $tileServer = $server['server'];
            $tileAttribution = $server['attribution'];
        }

        $this->setTileLayer($tileServer);
        $this->setTileOptions('attribution', $tileAttribution);
    }

    public function forTemplate()
    {
        Requirements::javascript('geotools/javascript/leaflet/leaflet.js');
        Requirements::css('geotools/javascript/leaflet/leaflet.css');
        if ($this->enableClustering) {
            Requirements::javascript('geotools/javascript/leaflet-prunecluster/PruneCluster.min.js');
            Requirements::css('geotools/javascript/leaflet-prunecluster/LeafletStyleSheet.css');
        }
        if ($this->useBuilder) {
            Requirements::javascript('geotools/javascript/map-builder.js');
            Requirements::customScript("jQuery(function() { buildLeafletMap('{$this->getID()}') })");
        }
        return $this->renderWith('LeafletMap');
    }
}

class LeafletMapItem
{

    public $lat;
    public $lon;
    public $popup; // Title by default or use getLeafletPopup method
    public $number; // Will show as a number on the map
    public $category_title;
    public $category_image; // Url

}

class LeafletMapItemCategory
{

    public $title;
    public $image;

}

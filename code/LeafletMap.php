<?php

/**
 * LeafletMap
 *
 * @author lekoala
 */
class LeafletMap extends ViewableData
{

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

    public function forTemplate()
    {
        Requirements::javascript('geotools/javascript/leaflet.js');
        Requirements::css('geotools/javascript/leaflet.css');
        if ($this->enableClustering) {
            Requirements::javascript('geotools/javascript/PruneCluster.min.js');
            Requirements::css('geotools/javascript/LeafletStyleSheet.css');
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

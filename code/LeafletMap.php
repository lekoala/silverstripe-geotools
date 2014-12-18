<?php

/**
 * LeafletMap
 *
 * @author lekoala
 */
class LeafletMap extends ViewableData {

	protected static $instances = 0;
	protected $enableClustering = false;
	protected $useBuilder = true;
	protected $id;
	protected $width = '100%';
	protected $height = '300px';
	protected $latitude;
	protected $longitude;
	protected $zoom;
	protected $tileLayer;
	protected $tileOptions;
	protected $mapOptions;
	protected $content;

	public function __construct() {
		parent::__construct();
		self::$instances++;
	}

	function getUseBuilder() {
		return $this->useBuilder;
	}

	function setUseBuilder($useBuilder) {
		$this->useBuilder = $useBuilder;
		return $this;
	}

	function getContent() {
		return $this->content;
	}

	function setContent($content) {
		$this->content = $content;
		return $this;
	}

	function getZoom() {
		if ($this->zoom) {
			return $this->zoom;
		}
		return self::config()->default_zoom;
	}

	function setZoom($v) {
		$this->zoom = $v;
		return $this;
	}

	function getTileLayer() {
		if ($this->tileLayer) {
			return $this->tileLayer;
		}
		return self::config()->tilelayer;
	}

	function setTileLayer($v) {
		$this->tileLayer = $v;
		return $this;
	}

	function getMapOptions() {
		if ($this->mapOptions) {
			return $this->mapOptions;
		}
		return self::config()->map_options;
	}

	function setMapOptions($v) {
		$this->mapOptions = $v;
		return $this;
	}

	function getMapOptionsJson() {
		return json_encode($this->getMapOptions());
	}

	function getTileOptions() {
		if ($this->tileOptions) {
			return $this->tileOptions;
		}
		return self::config()->tile_options;
	}

	function setTileOptions($v) {
		$this->tileOptions = $v;
		return $this;
	}

	function getTileOptionsJson() {
		return json_encode($this->getTileOptions());
	}

	function getEnableClustering() {
		return $this->enableClustering;
	}

	function setEnableClustering($enableClustering) {
		$this->enableClustering = $enableClustering;
	}

	function getLatitude() {
		return $this->latitude;
	}

	function setLatitude($latitude) {
		$this->latitude = $latitude;
		return $this;
	}

	function getLongitude() {
		return $this->longitude;
	}

	function setLongitude($longitude) {
		$this->longitude = $longitude;
		return $this;
	}

	function getHeight() {
		return $this->height;
	}

	function setHeight($height) {
		$this->height = $height;
		return $this;
	}

	function getWidth() {
		return $this->width;
	}

	function setWidth($width) {
		$this->width = $width;
		return $this;
	}

	function getID() {
		if ($this->id) {
			return $this->id;
		}
		return 'LeafletMap-' . self::$instances;
	}

	function setID($id) {
		$this->id = $id;
		return $this;
	}

	public function forTemplate() {
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

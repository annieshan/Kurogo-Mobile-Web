<?php
abstract class KurogoCache {

    static $DEFAULT_CACHE_CLASS='KurogoNoCache';
    
	public function __construct() {
	}

	public static function factory($cacheType = '', $args = array()) {
		$args = is_array($args) ? $args : array();

		includePackage("Cache");
        
        $cacheType = $cacheType ? $cacheType : self::$DEFAULT_CACHE_CLASS;
        $cacheController = LIB_DIR . "/Cache/%s.php";
        require_once(sprintf($cacheController, $cacheType));
        
        if (!class_exists($cacheType)) {
            throw new KurogoConfigurationException("Cache class $cacheType not defined");
        }
        $cacheClass = new $cacheType;
        
        if (!$cacheClass instanceOf KurogoCache) {
            throw new KurogoConfigurationException("$cacheType is not a subclass of KurogoCache");
        }

        $args = array_merge(Kurogo::getOptionalSiteSection('cache'), $args);
        $cacheClass->init($args);

        return $cacheClass;
	}

	abstract public function get($key);

	abstract public function set($key, $value, $ttl = 0);

	abstract public function delete($key);

	abstract public function clear();
}

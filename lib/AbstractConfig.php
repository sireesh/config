<?php

/**
 * Abstract Config class
 *
 * @package    Config
 * @author     Sireesh Beemineni <sireeshnaidu@gmail.com>
 * @link       https://github.com/sireesh/config
 */

abstract class AbstractConfig implements ConfigInterface {
    /**
     * Stores the configuration fixtures
     *
     * @var array|null
     */
    protected $data = null;

    /**
     * Caches the configuration fixtures
     *
     * @var array
     */
    protected $cache = [];

    /**
     * Constructor method and sets default options, if any
     *
     * @param array $data
     */
    public function __construct(array $data) {
        $this->data = array_merge($this->getDefaults(), $data);
    }

    /**
     * Override this method in your own subclass to provide an array of default
     * options and values
     *
     * @return array
     */
    protected function getDefaults() {
        return [];
    }

    /**
     * ConfigInterface Methods
     */

    /**
     * Getter Method
     */
    public function get($key, $default = null) {
        if ($this->has($key)) {
            return $this->cache[$key];
        }

        return $default;
    }

    /**
     * Setter Method
     */
    public function set($key, $value)  {
        $segs = explode('.', $key);
        $root = &$this->data;
        $cacheKey = '';

        // Look for the key, creating nested keys if needed
        while ($part = array_shift($segs)) {
            if ($cacheKey != '') {
                $cacheKey .= '.';
            }
            $cacheKey .= $part;
            if (!isset($root[$part]) && count($segs)) {
                $root[$part] = [];
            }
            $root = &$root[$part];

            //Unset all old nested cache
            if (isset($this->cache[$cacheKey])) {
                unset($this->cache[$cacheKey]);
            }

            //Unset all old nested cache in case of array
            if (count($segs) == 0) {
                foreach ($this->cache as $cacheLocalKey => $cacheValue) {
                    if (substr($cacheLocalKey, 0, strlen($cacheKey)) === $cacheKey) {
                        unset($this->cache[$cacheLocalKey]);
                    }
                }
            }
        }

        // Assign value at target node
        $this->cache[$key] = $root = $value;
    }

    /**
     * Check key exists or not - has Method
     */
    public function has($key) {
        // Check if already cached
        if (isset($this->cache[$key])) {
            return true;
        }

        $segments = explode('.', $key);
        $root = $this->data;

        // nested case
        foreach ($segments as $segment) {
            if (array_key_exists($segment, $root)) {
                $root = $root[$segment];
                continue;
            } else {
                return false;
            }
        }

        // Set cache for the given key
        $this->cache[$key] = $root;

        return true;
    }

    /**
     * Merge config from another instance
     *
     * @param ConfigInterface $config
     * @return ConfigInterface
     */
    public function merge(ConfigInterface $config) {
        $this->data = array_replace_recursive($this->data, $config->all());
        return $this;
    }

    /**
     * Get All Config Data
     */
    public function all() {
        return $this->data;
    }

}
<?php

/**
 * Abstract parser
 *
 * @package    Config
 * @author     Sireesh Beemineni <sireeshnaidu@gmail.com>
 * @link       https://github.com/sireesh/config
 */

abstract class AbstractParser implements ParserInterface {

    /**
     * String with configuration
     *
     * @var string
     */
    protected $config;

    /**
     * Sets the string with configuration
     *
     * @param string $config
     * @param string $filename
     *
     */
    public function __construct($config, $filename = null) {
        $this->config = $config;
    }
}
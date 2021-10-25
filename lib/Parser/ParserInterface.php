<?php

/**
 * Config file parser interface
 *
 * @package    Config
 * @author     Sireesh Beemineni <sireeshnaidu@gmail.com>
 * @link       https://github.com/sireesh/config
 */

interface ParserInterface {
    /**
     * Parses a configuration from file `$filename` and gets its contents as an array
     *
     * @param  string $filename
     *
     * @return array
     */
    public function parseFile($filename);

    /**
     * Returns an array of allowed file extensions for this parser
     *
     * @return array
     */
    public static function getSupportedExtensions();
}
<?php

/**
 * Configuration reader.
 *
 * @package    Config
 * @author     Sireesh Beemineni <sireeshnaidu@gmail.com>
 * @link       https://github.com/sireesh/config
 */

class Config extends AbstractConfig {
    /**
     * formats supported by Config.
     * In future, If we want to extend the config parsers with other formats like YAML, XML, INI etc...
     * Include those Parsers in this Array.
     *
     * @var array
     */
    protected $supportedParsers = [
        'Json'
    ];

    /**
     * Static method for loading a Config instance.
     *
     * @param  string|array    $values Filenames or string with configuration
     * @param  ParserInterface $parser Configuration parser
     *
     * @return Config
     */
    public static function load($values, $parser = null) {
        return new static($values, $parser);
    }

    /**
     * Loads a Config instance.
     *
     * @param  string|array    $values Filenames or string with configuration
     * @param  ParserInterface $parser Configuration parser
     *
     */
    public function __construct($values, ParserInterface $parser = null) {
        $this->loadFromFile($values, $parser);
        parent::__construct($this->data);
    }

    /**
     * Loads configuration from file.
     *
     * @param  string|array     $path   Filenames or directories with configuration
     * @param  ParserInterface  $parser Configuration parser
     *
     */
    protected function loadFromFile($path, ParserInterface $parser = null) {
        $paths      = $this->getValidPath($path);
        $this->data = [];

        if(is_array($paths) && !empty($paths)){
            foreach ($paths as $path) {
                if ($parser === null) {
                    // Get file information
                    $info      = pathinfo($path);
                    $parts     = explode('.', $info['basename']);
                    $extension = array_pop($parts);

                    // Get file parser
                    $parser = $this->getParser($extension);

                    // Try to load file
                    $this->data = array_replace_recursive($this->data, $parser->parseFile($path));

                    // Clean parser
                    $parser = null;
                } else {
                    // Try to load file using specified parser
                    $this->data = array_replace_recursive($this->data, $parser->parseFile($path));
                }
            }
        } else {
            echo "File Could Not Found to load";
        }

    }

    /**
     * Gets a parser for a given file extension.
     *
     * @param  string $extension
     *
     *
     */
    protected function getParser($extension) {
        foreach ($this->supportedParsers as $parser) {
            if (in_array($extension, $parser::getSupportedExtensions())) {
                return new $parser();
            }
        }

        // If none exist, return error
        die('Unsupported configuration format');
    }

    /**
     * Gets an array of paths
     *
     * @param  array $path
     *
     * @return array
     *
     */
    protected function getPathFromArray($path) {
        $paths = [];

        foreach ($path as $unverifiedPath) {
            try {
                // Check if `$unverifiedPath` is optional
                // If it exists, then it's added to the list
                // If it doesn't, it throws an exception which we catch
                if ($unverifiedPath[0] !== '?') {
                    $paths = array_merge($paths, $this->getValidPath($unverifiedPath));
                    continue;
                }

                $optionalPath = ltrim($unverifiedPath, '?');
                $paths = array_merge($paths, $this->getValidPath($optionalPath));
            } catch (Exception $e) {
                // If `$unverifiedPath` is optional, then skip it
                if ($unverifiedPath[0] === '?') {
                    continue;
                }
                //Error Exception
                die($e->getMessage());
            }
        }

        return $paths;
    }

    /**
     * Checks `$path` to see if it is either an array, a directory, or a file.
     *
     * @param  string|array $path
     *
     * @return array
     *
     */
    protected function getValidPath($path) {
        // If `$path` is array
        if (is_array($path)) {
            return $this->getPathFromArray($path);
        }

        // If `$path` is a directory
        if (is_dir($path)) {
            $paths = glob($path . '/*.*');
            if (empty($paths)) {
                die("Configuration directory: [$path] is empty");
            }

            return $paths;
        }

        // If `$path` is not a file, throw an exception
        if (!file_exists($path)) {
            return [];
        }

        return [$path];
    }
}

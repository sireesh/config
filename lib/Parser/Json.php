<?php

/**
 * JSON parser
 *
 * @package    Config
 * @author     Sireesh Beemineni <sireeshnaidu@gmail.com>
 * @link       https://github.com/sireesh/config
 */

class Json implements ParserInterface {
    /**
     * Parses an JSON file as an array
     *
     */
    public function parseFile($filename) {
        $data = json_decode(file_get_contents($filename), true);

        return (array)$this->parse($data, $filename);
    }

    /**
     * Completes parsing of JSON fixtures
     *
     * @param  array  $data
     * @param  string $filename
     * @return array|null
     *
     */
    protected function parse($data = null, $filename = null) {
        if (json_last_error() !== JSON_ERROR_NONE) {
            $error_message  = 'Syntax error';
            if (function_exists('json_last_error_msg')) {
                $error_message = json_last_error_msg();
            }

            $error = [
                'message' => $error_message,
                'type'    => json_last_error(),
                'file'    => $filename,
            ];

            return 'Invalid Format';
        }

        return $data;
    }

    /**
     * Gives Supported Parser Extensions
     *
     * @return array
     */
    public static function getSupportedExtensions() {
        return ['json'];
    }
}
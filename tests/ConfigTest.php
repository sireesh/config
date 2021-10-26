<?php

/**
 * PHPUNIT Config Test class
 *
 * @package    Config
 * @author     Sireesh Beemineni <sireeshnaidu@gmail.com>
 * @link       https://github.com/sireesh/config
 */

use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase {

    /**
     * Test JSON Data Parse Value
     *
     */
    public function testJSONParseValue() {
         $config = new Config(__DIR__ . '/mocks/config.json');
         $expected  = 'production';
         $actual =  $config->get('environment');
         $this->assertEquals(
            $expected,
            $actual,
            "actual value is not equals to expected"
        );
    }

    /**
     * Test No File Exists
     *
     */
    public function testNoFileExists() {
        $config = new Config(__DIR__ . '/mocks/nofile.json');
        $this->assertFalse($config->has('environment'));
    }

    /**
     * Merge Two Files
     *
     */
    public function testMergeTwoFiles() {
        $config1 = new Config(__DIR__ . '/mocks/config.json');
        $config2 = new Config(__DIR__ . '/mocks/config.local.json');

        $config1->merge($config2);
        $expected  = 'development';
        $actual =  $config1->get('environment');
        $this->assertEquals(
            $expected,
            $actual,
            "actual value is not equals to expected"
        );
    }

    /**
     * InValid Format
     *
     */
    public function testInValidFormat() {
        $config = new Config(__DIR__ . '/mocks/config.invalid.json');
        $expected  = 'Invalid Format';
        $actual =  $config->get(0);
        $this->assertEquals(
            $expected,
            $actual,
            "actual value is not equals to expected"
        );
    }
}
# Config

Config is a library for parsing JSON files and retrieve the parts of the configuration by a dot-separated path in PHP Language.

## Features
- Retrieve the key values in a JSON File.
- Merge The JSON Files and Retrieve the key values.

## Installation

Using directly fork the git repository or using [Docker](https://www.docker.com/) to install Config library.

```bash
git clone https://github.com/sireesh/config
```
OR Using Docker Approach
```bash
docker-compose up
```

## Usage
Config library is designed to be very simple and straightforward to use. All you can do with it is load JSON file and get the values by using keys.

### Loading JSON files
The Config object can be created via the factory method load(), or by direct instantiation:
```php
//Single File
$conf = Config::load('config.json');
$conf = new Config('config.json');

//Multiple Files
$conf = Config::load(['config.json','config1.json']);
$conf = new Config(['config.json','config1.json']);
```

### Getting values
Getting values can be done in three ways by using the get() method
```php
// Get value using key
$environment = $conf->get('environment');

// Get value using nested key
$host_name = $conf->get('database.host');

// Get a value with a fallback
$ttl = $conf->get('app.timeout', 30);
```
The second method is by using an array
```php
// Get value using a simple key
$environment= $conf['environment'];

// Get value using a nested key
$host_name = $conf['database.host'];

// Get nested value like you would from a nested array
$host_name = $conf['database']['host'];
```
The third method is by using the all() method:
```php
// Get all values
$data = $conf->all();
```

### Merging instances
Merging multiple Config instances:
```php
$conf1 = Config::load('conf1.json');
$conf2 = Config::load('conf2.json');
$conf1->merge($conf2);
```

## Unit Testing
```php
phpunit
```
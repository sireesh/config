<?php

spl_autoload_register ( function ($class) {

    $sources = array("lib/$class.php", "lib/Parser/$class.php" );

    foreach ($sources as $source) {
        if (file_exists($source)) {
            require_once $source;
        }
    }

});

// Load a single file
$conf = new Config('fixtures/config.json');

echo '<pre>';

echo 'CONFIG RESULTS'. "\n";
echo '---------------'. "\n";
// Get value using key
echo $conf->get('environment'). "\n";
echo $conf->get('database.host'). "\n";
print_r($conf->get('database'));


echo '<hr>MERGE RESULTS'. "\n";
echo '---------------'. "\n";
$conf1 = new Config('fixtures/config.json');
$conf2 = new Config('fixtures/config.local.json');
$conf1->merge($conf2);
echo $conf1->get('environment') . "\n";
echo $conf1->get('database.host') . "\n";

echo '<hr>MERGE RESULTS BY LOADING TWO JSON FILES IN ONE CONFIG'. "\n";
echo '--------------------------------------------------------'. "\n";
$conf1 = new Config(['fixtures/config.json','fixtures/config.local.json']);
echo $conf1->get('environment') . "\n";
echo $conf1->get('database.host') . "\n";
echo $conf1->get('app.timeout', 3000);

echo '<hr>NO FILE EXISTS'. "\n";
echo '---------------'. "\n";
$conf3 = new Config('fixtures/nofile.json');


echo '<hr>INVALID FILE FORMAT'. "\n";
echo '--------------------'. "\n";
$conf3 = new Config('fixtures/config.invalid.json');
echo $conf3->get(0);
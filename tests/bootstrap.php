<?php

$autoloader = __DIR__ . '/../vendor/autoload.php';

if (!file_exists($autoloader)) {
    exit('Please install Composer in the root folder before running tests!');
}

require $autoloader;

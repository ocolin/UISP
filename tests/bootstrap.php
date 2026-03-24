<?php

declare( strict_types = 1 );

require_once __DIR__ . '/../vendor/autoload.php';

use Ocolin\EasyEnv\Env;
use Ocolin\EasyEnv\EasyEnvFileHandleError;


try {
    Env::load(files: __DIR__ . '/../.env', append: true);
} catch ( EasyEnvFileHandleError $e ) {
    echo 'Environment setup failed: ' . $e->getMessage() . PHP_EOL;
    exit(1);
}

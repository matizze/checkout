<?php

declare(strict_types=1);

use Flasher\Prime\Configuration;

/*
 * Default PHPFlasher configuration for Laravel.
 *
 * This configuration file defines the default settings for PHPFlasher when
 * used within a Laravel application. It uses the Configuration class from
 * the core PHPFlasher library to establish type-safe configuration.
 *
 * @return array<string, mixed> PHPFlasher configuration
 */
return Configuration::from([
    'default' => 'noty',
    'plugins' => [
        'noty' => [
            'scripts' => [
                '/vendor/flasher/noty.min.js',
                '/vendor/flasher/flasher-noty.min.js',
            ],
            'styles' => [
                '/vendor/flasher/noty.css',
                '/vendor/flasher/mint.css',
            ],
            'options' => [
                //
            ],
        ],
    ],
]);

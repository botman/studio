<?php

namespace App\BotMan\Providers;

use App\Console\Kernel;
use BotMan\BotMan\Drivers\DriverManager;
use Illuminate\Support\ServiceProvider;
use TheCodingMachine\Discovery\Discovery;

class DriverServiceProvider extends ServiceProvider
{

    /**
     * @return void
     */
    public function boot()
    {
        $this->discoverDrivers();
    }

    /**
     * Auto-discover BotMan drivers and load them.
     */
    public function discoverDrivers()
    {
        $drivers = Discovery::getInstance()->get('botman/driver');

        foreach ($drivers as $driver) {
            DriverManager::loadDriver($driver);
        }
    }

    /**
     * Auto-publish BotMan driver configuration files.
     */
    public static function publishDriverConfigurations()
    {
        $stubs = Discovery::getInstance()->getAssetType('botman/driver-config');

        foreach ($stubs->getAssets() as $stub) {
            $configFile = config_path('botman/' . basename($stub->getValue()));

            if (!file_exists($configFile)) {
                copy($stub->getPackageDir() . $stub->getValue(), $configFile);
            }
        }
    }

}
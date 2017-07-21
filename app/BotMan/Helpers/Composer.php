<?php

namespace App\BotMan\Helpers;

use Illuminate\Support\Composer as BaseComposer;

class Composer extends BaseComposer
{
    /**
     * Install a composer package.
     *
     * @param $package
     * @param callable $callback
     */
    public function install($package, callable $callback)
    {
        $process = $this->getProcess();

        $process->setCommandLine(trim($this->findComposer().' require '.$package));

        $process->run($callback);
    }

}
<?php
namespace Roadbook;

/**
 * Class Module
 */
class Module
{
    const VERSION = '1.0.0';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}

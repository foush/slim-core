<?php
namespace FzySlimCore\Util;

use Slim\Slim;

class Mode {

    const MODE_PROD = 'prod';
    const MODE_DEV = 'dev';
    const MODE_TEST = 'test';
    const MODE_QA = 'qa';
    const MODE_STAGE = 'stage';

    public static function configureModes(Slim $app, array $modeConfigs)
    {
        foreach ($modeConfigs as $mode => $config) {
            $app->configureMode($mode, function() use ($app, $config) {
                $app->config($config);
            });
        }
    }
}
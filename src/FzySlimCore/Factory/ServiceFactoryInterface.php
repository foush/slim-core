<?php
namespace FzySlimCore\Factory;

use Slim\Slim;

interface ServiceFactoryInterface {

    /**
     * This function will return the service to be stored
     * @param Slim $app
     * @return mixed
     */
    public function getService(Slim $app);
}
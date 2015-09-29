<?php
namespace FzySlimCore\Util;

use FzySlimCore\Exception\Configuration\Invalid as InvalidConfigurationException;
use FzySlimCore\Exception\Container\InvalidFactory;
use FzySlimCore\Exception\Container\ServiceKeyAlreadyExists as ServiceKeyAlreadyExistsException;
use FzySlimCore\Factory\ServiceFactoryInterface;
use Slim\Slim;

/**
 * This factory class takes the service configuration array and
 * sets up the closures
 *
 * Class ServiceFactory
 * @package FzySlimCore\Util
 */
class Container {

    /**
     * Adds singleton services to the Slim application based on the configuration
     * similar to ZF2 Service Locator and Symfony2 Container
     * @param Slim $app
     * @param array $serviceConfig
     * @throws ServiceKeyAlreadyExistsException
     */
    public function registerServices(Slim $app, array $serviceConfig = [])
    {
        if (isset($serviceConfig['callables'])) {
            foreach ($serviceConfig['callables'] as $serviceKey => $callable) {
                $this->registerService($app, $serviceKey, function () use ($app, $callable) {
                    return call_user_func($callable, $app);
                });
            }
        }
        if (isset($serviceConfig['factories'])) {
            foreach ($serviceConfig['factories'] as $serviceKey => $className) {
                $this->registerService($app, $serviceKey, function () use ($app, $serviceKey, $className) {
                    $factory = new $className();
                    if ($factory instanceof ServiceFactoryInterface) {
                        return $factory->getService($app);
                    }
                    throw new InvalidFactory("Failed to create $serviceKey: $className does not implement \FzySlimCore\Factory\ServiceFactoryInterface");
                });
            }
        }
        if (isset($serviceConfig['invokables'])) {
            foreach ($serviceConfig['invokables'] as $serviceKey => $className) {
                $this->registerService($app, $serviceKey, $this->createInvokable($app, $className));
            }
        }

    }

    /**
     * @param Slim $app
     * @param $key
     * @param $callable
     * @throws ServiceKeyAlreadyExistsException
     */
    public function registerService(\Slim\Slim $app, $key, $callable)
    {
        if (isset($app->$key)) {
            throw new ServiceKeyAlreadyExistsException("The service key '$key' is already registered");
        }
        $app->container->singleton($key, $callable);
    }


    /**
     * Takes $className as string or array.
     * If $className is string, the callable simply instantiates the classname
     * If $className is an array, the callable instantiates $className['class']
     *  and passes any $className['arguments'] values to the constructor.
     *  Any $className['calls'] values are treated as an array, the first value
     *      considered the function name to call on the instance, the second value
     *      an array of arguments passed to that function call (any strings prefixed with
     *      '@' will be converted to the actual service)
     * @param Slim $app
     * @param $className
     * @return \Closure
     */
    protected function createInvokable(Slim $app, $className)
    {
        return function () use ($app, $className) {
            if (is_string($className)) {
                return new $className();
            }
            if (!is_array($className)) {
                throw new InvalidConfigurationException("Invalid configuration.");
            }
            $configuration = new Params($className);
            $className = $configuration->get('class');
            $instance = null;
            $constructorArgs = $configuration->has('arguments') ? $this->convertStringArrayToServices($app, $configuration->get('arguments')) : [];
            if (!empty($constructorArgs)) {
                $reflector = new \ReflectionClass($className);
                $instance = $reflector->newInstanceArgs($constructorArgs);
            } else {
                $instance = new $className();
            }
            foreach ($configuration->get('calls',[]) as $call) {
                $fnName = $call[0];
                $args = $this->convertStringArrayToServices($app, $call[1]);
                call_user_func_array([$instance, $fnName], $args);
            }
            return $instance;
        };
    }

    /**
     * Converts any string values prefixed with '@' into the
     * service to which it refers.
     * @param Slim $app
     * @param array $values
     * @return array
     */
    protected function convertStringArrayToServices(Slim $app, array $values)
    {
        $args = [];
        foreach ($values as $value) {
            $args[] = $this->inflateServiceParameter($app, $value);
        }
        return $args;
    }

    /**
     * Turns an @serviceKey string into the actual service
     * represented by serviceKey
     * @param Slim $app
     * @param $parameter
     * @return mixed
     * @throws InvalidConfigurationException
     */
    protected function inflateServiceParameter(Slim $app, $parameter)
    {
        if (is_string($parameter) && $parameter{0} == '@') {
            $parameter = $this->stringToService($app, substr($parameter, 1));
        }
        return $parameter;
    }

    /**
     * If the string is an actual service key, return the service
     * else throw an exception
     * @param Slim $app
     * @param $serviceKey
     * @return mixed
     * @throws InvalidConfigurationException
     */
    protected function stringToService(Slim $app, $serviceKey)
    {
        if (isset($app->$serviceKey)) {
            return $app->$serviceKey;
        }
        throw new InvalidConfigurationException("Service $serviceKey not found.");
    }
}

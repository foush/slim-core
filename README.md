# Slim Core Utils

Slim core utility classes which can be re-used across applications.

# Installation
`composer require fousheezy/slim-core`

# Documentation

## FzySlimCore\Util\Container
A factory for quickly adding services to the Slim app container

**function registerServices**

* `\Slim\Slim $app`: the slim app to which services will be added
* `array $config`: the array of services configured

This function iterates over the configuration keys, each of which should contain an array.
In each of these arrays, the string will be the name you reference on the slim app (e.g. `$app->config_key`)

* **callables**: this array of string key => callable pairs sets result of invoking the callable as the service value
* **factories**: this array of string key => string value pairs instantiates the class defined in the value- 
this class must implement the `\FzySlimCore\Factory\ServiceFactoryInterface`. 
The result of invoking `getService(\Slim\Slim $app)` becomes the service.
* **invokables**: this array of string key => string value pairs instantiates the class defined in value as a singleton. **However** if the key maps to an array, the array is used as a configuration for instantiating a class.

### Invokable Array
Instantiating a service via an array configuration in the invokables section is very convenient.
* The array must contain a `class` key, which will specify a string class name to be instantiated.
* The array can optionally contain an `arguments` key which specifies an array of constructor arguments for the class. **Note** You can specify a service by using a string `"@serviceKey"` which the container class will automatically convert into the real service for you.
* The array can optionally contain a `calls` key which specifies a list of object methods to be invoked after instantiation. This is done by setting `calls` to an array of arrays. Each sub-array should have two values; the first value is treated as a string method name, and the second value is treated as an array of arguments to be passed to that method. As with the constructor, @ prefixed strings will be converted to their representative services.

Example:
```
'invokables' => [
    'my_service' => '\MyNamespace\Service\MyService',
    'second_service' => [
        'class' => '\MyNamespace\Service\SecondService'
        'arguments' => ['@my_service', 'a regular string', 3],
        'calls' => [
            ['registerHandler', ['@my_service3', 45]]
            ['setTimeout', [1]]
        ]
    ],
    'my_service3' => '\MyNamespace\Service\ThirdService',
]
```

In the example above, the $app will have 3 services `my_service`, `second_service` and `my_service3`. The first and last services are simple string definitions so they will be instantiated and returned
```
/* @var $service \MyNamespace\Service\MyService */
$service = $app->my_service;
```

```
/* @var $service \MyNamespace\Service\ThirdService */
$service = $app->my_service3;
```

The `second_service` key will effectively run the following in order, when setting itself up:
```
    $service = new \MyNamespace\Service\SecondService($app->my_service, 'a regular string', 3);
    $service->registerHandler($app->my_service3, 45);
    $service->setTimeout(1);
    $app->second_service = $service;
```


## FzySlimCore\Util\Mode
A utility class for setting mode configurations on the slim app

**static function configureModes**

* `\Slim\Slim $app`: the slim app to which the modes will be applied
* `array $modeConfigs`: the array of modes to be configured

This function iterates over the mode configurations. Each key is treated as the mode name, 
each value is the array of configurations specific to that mode

## FzySlimCore\Util\Page

A class to contain pagination parameter information.

## FzySlimCore\Util\Params

A class to contain an iterable set of data and access it without checking for whether the key exists.

## FzySlimCore\Util\Result

A pagination result class which standardizes the format of REST responses.
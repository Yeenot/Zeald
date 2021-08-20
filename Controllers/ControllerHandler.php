<?php
use Illuminate\Support;  // https://laravel.com/docs/5.8/collections - provides the collect methods & collections class

class ControllerHandler {

    public static function state($class, $action)
    {
        $reflectionMethod = new ReflectionMethod($class, $action);
        $methodParameters = $reflectionMethod->getParameters();
        $methodParams = collect($methodParameters)->pluck('name');

        $args = collect($_REQUEST);
        $actionArgs = [];
        foreach ($methodParams as $param) {
            $actionArgs[] = $args[$param];
        }
        
        $classInstance = new $class();
        return $classInstance->{$action}(...$actionArgs);
    }
}
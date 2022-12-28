<?php

namespace MusicService\Utils;
use MusicService\Api\Requests\EmptyRequest;
use stdClass;
use ReflectionMethod;

class RequestUtils
{
    public static function getRequestPath(): string
    {
        return explode('?', $_SERVER['REQUEST_URI'])[0];
    }

    public static function getRequestData(): object
    {
        $request = new stdClass();
        $request->params = self::getRequestMethod() === 'GET' ? $_GET : $_POST;

        return $request;
    }

    public static function getRequestMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function getRequestUri(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    public static function getRequestType(callable $handler): string
    {
        $reflectionMethod = new ReflectionMethod($handler[0], $handler[1]);
        $parameters = $reflectionMethod->getParameters();

        if (count($parameters) === 0) {
            return EmptyRequest::class;
        }

        return $parameters[0]->getType()->getName();
    }
}
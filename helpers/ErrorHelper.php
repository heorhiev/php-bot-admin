<?php

namespace app\helpers;


class ErrorHelper
{
    public static function getErrorText(\Throwable $throwable): string
    {
        return join(PHP_EOL, [
            $throwable->getMessage(),
            $throwable->getFile(),
            $throwable->getLine()
        ]);
    }
}
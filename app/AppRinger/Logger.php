<?php

namespace App\AppRinger;

use Illuminate\Support\Facades\Log;
use Psr\Log\LoggerInterface;
use RuntimeException;


class Logger
{

    public static function logInfo(string $msg)
    {
        $backfiles = debug_backtrace();
        $arr = explode('/', $backfiles[0]['file']);
        $name = $arr[sizeof($arr) - 1];
        $fileName = $name . ":" . $backfiles[0]['line'] . " | " . $msg;
        self::getInfoLogger()->info($fileName);
    }

    public static function logWarning(string $msg)
    {
        $backfiles = debug_backtrace();

        $arr = explode('/', $backfiles[0]['file']);
        $name = $arr[sizeof($arr) - 1];
        $fileName = $name . ":" . $backfiles[0]['line'] . " | " . $msg;
        self::getLogger()->warning($msg);
    }

    public static function logError(string $msg)
    {
        $backfiles = debug_backtrace();
        $arr = explode('/', $backfiles[0]['file']);
        $name = $arr[sizeof($arr) - 1];
        $fileName = $name . ":" . $backfiles[0]['line'] . " | " . $msg;
        self::getApiErrorLogger()->error($msg);
    }

    public static function logDebug(string $msg)
    {
        if (env('APP_DEBUG', false)) {
            $backfiles = debug_backtrace();
            $arr = explode('/', $backfiles[0]['file']);
            $name = $arr[sizeof($arr) - 1];
            $fileName = $name . ":" . $backfiles[0]['line'] . " | " . $msg;
            self::getApiDebugLogger()->info($msg);
        }
    }

    private static function getLogger(): LoggerInterface
    {
        return Log::channel('daily');
    }

    private static function getInfoLogger(): LoggerInterface
    {
        return Log::channel('info');
    }

    private static function getApiErrorLogger(): LoggerInterface
    {
        return Log::channel('apiError');
    }

    private static function getApiDebugLogger(): LoggerInterface
    {
        return Log::channel('apiDebug');
    }

    private static function getErrorLogger(): LoggerInterface
    {
        return Log::channel('error');
    }

    public static function logErrorWithException(string $string)
    {
        self::logError($string);
        throw new RuntimeException($string);
    }
}

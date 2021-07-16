<?php
/**
 * @description
 *
 * @package
 *
 * @author kovey
 *
 * @time 2021-07-15 17:29:14
 *
 */
namespace Kovey\Cronjob\Util;

use Kovey\Library\Util\Json;

class Debug
{
    const LOGGER_FORMAT = '[%s][%s] %s';

    const DATE_FORMAT = 'Y-m-d H:i:s';

    const EXCEPTION_CODE_FORMAT = "[%s][%s] code: %s\n%s\n%s\n";

    const EXCEPTION_FORMAT = "[%s][%s] %s\n%s\n";

    private static string $nodeName = '';

    public static function setNodeName(string $nodeName) : void
    {
        self::$nodeName = $nodeName;
    }

    public static function logger(string $format, mixed ...$params) : void
    {
        if (empty($params)) {
            echo sprintf(self::LOGGER_FORMAT . PHP_EOL, self::$nodeName, date(self::DATE_FORMAT), $format);
            return;
        }

        array_walk($params, function (&$item) {
            if (!is_array($item)) {
                return;
            }

            $item = Json::encode($item);
        });
        echo sprintf(self::LOGGER_FORMAT . PHP_EOL, self::$nodeName, date(self::DATE_FORMAT), sprintf($format, ...$params));
    }

    public static function exception(\Throwable $e, int $code = 0) : void
    {
        if ($code > 0) {
            echo sprintf(self::EXCEPTION_CODE_FORMAT, self::$nodeName, date(self::DATE_FORMAT), $code, $e->getMessage(), $e->getTraceAsString());
            return;
        }

        echo sprintf(self::EXCEPTION_FORMAT, self::$nodeName, date(self::DATE_FORMAT), $e->getMessage(), $e->getTraceAsString());
    }
}

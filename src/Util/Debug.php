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
    const LOGGER_FORMAT = '[%s] %s';

    const DATE_FORMAT = 'Y-m-d H:i:s';

    public static function logger(string $format, mixed ...$params) : void
    {
        if (empty($params)) {
            echo sprintf(self::LOGGER_FORMAT . PHP_EOL, date(self::DATE_FORMAT), $format);
            return;
        }

        array_walk($params, function (&$item) {
            if (!is_array($item)) {
                return;
            }

            $item = Json::encode($item);
        });
        echo sprintf(self::LOGGER_FORMAT . PHP_EOL, date(self::DATE_FORMAT), sprintf($format, ...$params));
    }
}

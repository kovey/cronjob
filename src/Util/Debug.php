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
    public static function logger(string $format, mixed ...$params) : void
    {
        if (empty($params)) {
            echo sprintf('[%s] %s' . PHP_EOL, date('Y-m-d H:i:s'), $format);
            return;
        }

        array_walk($params, function (&$item) {
            if (!is_array($item)) {
                return;
            }

            $item = Json::encode($item);
        });
        echo sprintf('[%s] %s' . PHP_EOL, date('Y-m-d H:i:s'), sprintf($format, ...$params));
    }
}

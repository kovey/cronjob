<?php
/**
 * @description
 *
 * @package
 *
 * @author kovey
 *
 * @time 2020-02-20 21:18:12
 *
 */
namespace Kovey\Cronjob\Util;

use Kovey\Cronjob\Cli\Options;

class Util
{
    /**
     * @description parse argv
     *
     * @return Options
     */
    public static function parseArgv() : Options
    {
        global $argv, $argc;

        $options = new Options();

        for ($i = 0; $i < $argc; $i ++) {
            $val = $argv[$i];
            if (substr($val, 0, 2) !== '--') {
                continue;
            }

            $info = explode('=', $val);
            if (count($info) != 2) {
                continue;
            }

            $options->put(substr($info[0], 2), $info[1]);
        }

        return $options;
    }

    public static function getTraceId(string $class) : string
    {
        return hash('sha256', uniqid($class, true));
    }

    public static function getSpanId(string $class) : string
    {
        return md5(uniqid($class, true));
    }
}

<?php
/**
 * @description terminal input
 *
 * @package
 *
 * @author kovey
 *
 * @time 2020-02-20 21:14:15
 *
 */
namespace Kovey\Cronjob\Cli;

class Options
{
    private Array $options;

    public function __construct()
    {
        $this->options = array();
    }

    /**
     * @description get
     *
     * @param string $key
     *
     * @param string | int $default
     *
     * @return string | int
     */
    public function get(string $key, string | int $default = '') : string | int
    {
        return $this->options[$key] ?? $default;
    }

    /**
     * @description put
     *
     * @param string $key
     *
     * @param string | int $val
     *
     * @return Options
     */
    public function put(string $key, string | int $val) : Options
    {
        $this->options[$key] = $val;
        return $this;
    }
}

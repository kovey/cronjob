<?php
/**
 * @description
 *
 * @package
 *
 * @author kovey
 *
 * @time 2021-07-13 22:25:19
 *
 */
namespace Kovey\Cronjob\Util;

class Autoload
{
    private Array $locals;

    public function __construct()
    {
        $this->locals = array();
    }

    public function add(string $dir) : Autoload
    {
        $this->locals[] = $dir;
        return $this;
    }

    public function register() : Autoload
    {
        spl_autoload_register(array($this, 'load'));
        return $this;
    }

    public function load(string $class) : void
    {
        try {
            foreach ($this->locals as $local) {
                if (!is_dir($local)) {
                    continue;
                }

                $file = $local . '/' . str_replace('\\', '/', $class) . '.php';
                if (!is_file($file)) {
                    continue;
                }

                require_once $file;
                break;
            }
        } catch (\Throwable $e) {
            Debug::exception($e);
        }
    }
}

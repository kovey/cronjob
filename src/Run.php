<?php
/**
 * @description
 *
 * @package
 *
 * @author kovey
 *
 * @time 2021-07-13 22:38:21
 *
 */
namespace Kovey\Cronjob;

use Kovey\Cronjob\Util\Util;
use Kovey\Container\Container;
use Kovey\Cronjob\Cli\Options;
use Kovey\Cronjob\Cronjob\Base;
use function Swoole\Coroutine\run;

class Run
{
    private Options $options;

    private Container $container;

    private string $appPrefix;

    private string $lockDir;

    public function __construct(string $lockDir, string $appPrefix = '')
    {
        $this->options = Util::parseArgv();
        $this->container = new Container();
        $this->appPrefix = $appPrefix;
        $this->lockDir = $lockDir;
    }

    public function loop() : void
    {
        run(function () {
            try {
                $class = $this->options->get('class');
                if (empty($class)) {
                    echo 'class is not exists' . PHP_EOL;
                    return;
                }

                if (!empty($this->appPrefix)) {
                    $class = $this->appPrefix . '\\' . $class;
                }

                $obj = $this->container->get($class, Util::getTraceId($class), Util::getSpanId($class), array(), $this->options);
                if (!$obj instanceof Base) {
                    echo 'class not extends ' . Base::class . PHP_EOL;
                    return;
                }

                $obj->setLockDir($this->lockDir);
                $obj->run();
            } catch (\Exception $e) {
                echo $e->getMessage() . PHP_EOL .
                    $e->getTraceAsString() . PHP_EOL;
            } catch (\Throwable $e) {
                echo $e->getMessage() . PHP_EOL .
                    $e->getTraceAsString() . PHP_EOL;
            }
        });
    }
}

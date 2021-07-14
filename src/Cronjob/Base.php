<?php
/**
 * @description cronjob base
 *
 * @package
 *
 * @author kovey
 *
 * @time 2020-02-20 21:35:32
 *
 */
namespace Kovey\Cronjob\Cronjob;

use Kovey\Cronjob\Cli\Options;
use Kovey\Library\Exception\BusiException;

abstract class Base
{
    protected Options $options;

    protected string $lockFile;

    protected mixed $lockfp;

    protected bool $isRunning = false;

    protected string $lockDir;

    final public function __construct(Options $options)
    {
        $this->options = $options;
    }

    public function setLockDir(string $lockDir) : Base
    {
        $this->lockDir = $lockDir;
        $this->lockFile = $this->lockDir . '/' . str_replace(array('/', '\\'), '_', strtolower($this->getOption('class'))) . '.lock';
        return $this;
    }

    protected function getOption(string $key, string | int $default = '') : string | int
    {
        return $this->options->get($key, $default);
    }

    protected function echoMsg($msg)
    {
        echo $msg . PHP_EOL;
    }

    protected function lock() : bool
    {
        $this->lockfp = fopen($this->lockFile, 'w');
        $canWrite = false;
        if ($this->lockfp) {
            $canWrite = flock($this->lockfp, LOCK_EX | LOCK_NB);
        }

        return $canWrite;
    }

    protected function unlock() : bool
    {
        flock($this->lockfp, LOCK_UN);
        fclose($this->lockfp);
        unlink($this->lockFile);

        return true;
    }

    protected function begin() : void
    {
        echo $this->getOption('class') . ' running start at ' . date('Y-m-d H:i:s') . PHP_EOL;
    }

    protected function end()
    {
        echo $this->getOption('class') . ' running end at ' . date('Y-m-d H:i:s') . PHP_EOL;
    }

    public function run() : void
    {
        if (!$this->lock()) {
            return;
        }

        $this->begin();

        try {
            $this->isRunning = true;
            $this->process();
            $this->end();
        } catch (BusiException $e) {
            echo $e->getCode() . PHP_EOL .
                $e->getMessage() . PHP_EOL .
                $e->getTraceAsString() . PHP_EOL;
            $this->end();
        } catch (\Throwable $e) {
            echo $e->getMessage() . PHP_EOL .
                $e->getTraceAsString() . PHP_EOL;
            $this->end();
        } finally {
            $this->unlock();
        }
    }

    public function __destruct()
    {
        if (!$this->isRunning) {
            return;
        }

        if (is_file($this->lockFile)) {
            $this->unlock();
        }
    }

    abstract protected function process() : void;
}

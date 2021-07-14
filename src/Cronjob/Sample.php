<?php
/**
 * @description sample test
 * php start.php --class=Sample --offset=100
 *
 * @package
 *
 * @author kovey
 *
 * @time 2020-02-20 22:02:37
 *
 */
namespace Kovey\Cronjob\Cronjob;

class Sample extends Base
{
    protected function process() : void
    {
        $offset = $this->getOption('offset', 50);
        $this->echoMsg('offset: ' . $offset);
    }
}

<?php
namespace develnext\tool;

use php\lang\Process;
use php\lib\items;
use php\lib\num;

/**
 * Class Tool
 * @package develnext\tool
 */
abstract class Tool {

    /** @var Process */
    protected $lastProcess;

    /**
     * @return string
     */
    abstract public function getVersion();

    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @return string
     */
    abstract public function getBaseCommand();

    /**
     * @param $directory
     * @param array $args
     * @param bool $wait
     * @return Process
     */
    public function execute($directory, array $args = [], $wait = true) {
        $process = new Process(items::toList($this->getBaseCommand(), $args), $directory);
        return $this->lastProcess = $wait ? $process->startAndWait() : $process->start();
    }

    public function stop() {
        if ($this->lastProcess) {
            $this->lastProcess->destroy();
            $this->lastProcess = null;
        }
    }
}

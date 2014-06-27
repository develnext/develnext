<?php
namespace develnext\tool;

use php\lang\Process;

abstract class Tool {

    /**
     * @return string
     */
    abstract public function getVersion();

    /**
     * @param array $args
     * @return Process
     */
    abstract public function execute(array $args = []);
}

<?php
namespace develnext\tool;

use php\lang\Process;

class GradleTool extends Tool {

    public function getVersion() {
        return $this->execute(['-v'])->getInput()->readFully();
    }

    public function execute(array $args = []) {
        $process = new Process(['tools/gradle/bin/gradle.bat'] + $args);
        return $process->startAndWait();
    }
}

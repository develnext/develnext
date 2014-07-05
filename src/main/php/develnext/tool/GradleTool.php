<?php
namespace develnext\tool;

use php\io\File;

class GradleTool extends Tool {

    public function getVersion() {
        return $this->execute(['-v'])->getInput()->readFully();
    }

    /**
     * @return string
     */
    public function getName() {
        return 'Gradle';
    }

    /**
     * @return string
     */
    public function getBaseCommand() {
        if (\IS_WIN && (new File(\ROOT . '/tools/gradle/bin/gradle.bat'))->exists()) {
            return \ROOT . '/tools/gradle/bin/gradle.bat';
        } else
            return 'gradle';
    }
}

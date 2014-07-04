<?php
namespace develnext\tool;

use php\io\File;

class JavaTool extends Tool {

    public function getVersion() {
        return $this->execute(['-version'])->getInput()->readFully();
    }

    /**
     * @return string
     */
    public function getName() {
        return 'Java';
    }


    /**
     * @return string
     */
    public function getBaseCommand() {
        if (IS_WIN && (new File('tools/jre/bin/java.exe'))->exists()) {
            return 'tools/jre/bin/java.exe';
        } else
            return 'java';
    }
}

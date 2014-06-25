<?php

namespace develnext\filetype;

use develnext\editor\PhpEditor;
use develnext\project\Project;
use php\io\File;
use php\lib\str;
use php\swing\UIContainer;

class PhpFileType extends FileType {

    public function onDetect(File $file, Project $project = null) {
        $name = str::lower($file->getName());
        $extensions = ['.php', '.php5', '.phtml'];

        foreach($extensions as $el) {
            if (str::endsWith($name, $el))
                return true;
        }

        return false;
    }

    public function createEditor(UIContainer $container, File $file, Project $project = null) {
        return new PhpEditor($container, $file, $project);
    }

    public function getIcon() {
        return 'images/icons/filetype/php.png';
    }
}

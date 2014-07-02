<?php

namespace develnext\filetype;

use develnext\editor\PhpEditor;
use develnext\project\EditorManager;
use develnext\project\Project;
use php\io\File;
use php\lib\str;
use php\swing\UIContainer;

class PhpFileType extends FileType {

    public function onDetect(File $file, Project $project = null) {
        if (!$file->isFile())
            return false;

        $name = str::lower($file->getName());
        $extensions = ['.php', '.php5', '.phtml'];

        foreach($extensions as $el) {
            if (str::endsWith($name, $el))
                return true;
        }

        return false;
    }

    public function createEditor(File $file, EditorManager $manager = null) {
        return new PhpEditor($file, $manager);
    }

    public function getIcon() {
        return 'images/icons/filetype/php.png';
    }
}

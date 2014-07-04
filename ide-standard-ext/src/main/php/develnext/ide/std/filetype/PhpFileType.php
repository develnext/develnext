<?php

namespace develnext\ide\std\filetype;

use develnext\filetype\FileType;
use develnext\ide\std\editor\PhpEditor;
use develnext\project\EditorManager;
use develnext\project\Project;
use php\io\File;
use php\lib\str;
use php\swing\UIContainer;

class PhpFileType extends FileType {

    public function onDetect(File $file, Project $project = null) {
        return self::isFileWithExtension($file, ['.php', '.phtml', '.php5']);
    }

    public function createEditor(File $file, EditorManager $manager = null) {
        return new PhpEditor($file, $manager);
    }

    public function getIcon() {
        return 'images/icons/filetype/php.png';
    }
}

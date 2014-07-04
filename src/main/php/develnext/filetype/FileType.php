<?php

namespace develnext\filetype;

use develnext\editor\Editor;
use develnext\lang\Singleton;
use develnext\project\EditorManager;
use develnext\project\Project;
use php\io\File;
use php\lib\str;
use php\swing\UIContainer;

/**
 * Class FileType
 * @package develnext\filetype
 */
abstract class FileType {
    public function getIcon() {
        return 'images/icons/filetype/unknown.png';
    }

    abstract public function onDetect(File $file, Project $project = null);

    /**
     * @param \php\io\File $file
     * @param \develnext\project\EditorManager $manager
     * @return Editor
     */
    abstract public function createEditor(File $file, EditorManager $manager = null);

    /**
     * @param File $file
     * @param array $extensions
     * @return bool
     */
    protected static function isFileWithExtension(File $file, array $extensions) {
        if (!$file->isFile())
            return false;

        $name = str::lower($file->getName());
        foreach($extensions as $el) {
            if (str::endsWith($name, $el))
                return true;
        }

        return false;
    }
}

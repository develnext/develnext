<?php
namespace develnext\ide\std\filetype;

use develnext\editor\Editor;
use develnext\editor\TextEditor;
use develnext\filetype\FileType;
use develnext\ide\std\editor\GroovyEditor;
use develnext\project\EditorManager;
use develnext\project\Project;
use php\io\File;
use php\lib\str;

class GradleFileType extends FileType {

    public function onDetect(File $file, Project $project = null) {
        return self::isFileWithExtension($file, ['.gradle']);
    }

    /**
     * @param \php\io\File $file
     * @param \develnext\project\EditorManager $manager
     * @return Editor
     */
    public function createEditor(File $file, EditorManager $manager = null) {
        return new GroovyEditor($file, $manager);
    }

    public function getIcon() {
        return 'images/icons/gradle16.png';
    }
}

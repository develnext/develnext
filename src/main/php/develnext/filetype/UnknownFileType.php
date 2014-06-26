<?php
namespace develnext\filetype;

use develnext\editor\TextEditor;
use develnext\project\EditorManager;
use develnext\project\Project;
use php\io\File;
use php\swing\UIContainer;

class UnknownFileType extends FileType {

    public function onDetect(File $file, Project $project = null) {
        return true;
    }

    public function createEditor(File $file, EditorManager $manager = null) {
        return new TextEditor($file, $manager);
    }

}

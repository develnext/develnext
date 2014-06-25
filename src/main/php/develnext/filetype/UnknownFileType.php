<?php
namespace develnext\filetype;

use develnext\editor\TextEditor;
use develnext\project\Project;
use php\io\File;
use php\swing\UIContainer;

class UnknownFileType extends FileType {

    public function onDetect(File $file, Project $project = null) {
        return true;
    }

    public function createEditor(UIContainer $container, File $file, Project $project = null) {
        return new TextEditor($container, $file, $project);
    }
}

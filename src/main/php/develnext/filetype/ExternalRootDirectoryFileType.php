<?php
namespace develnext\filetype;

use develnext\project\EditorManager;
use develnext\project\Project;
use php\io\File;
use php\swing\UIContainer;

class ExternalRootDirectoryFileType extends DirectoryFileType {

    public function onDetect(File $file, Project $project = null) {
        return $project->isContentRoot($file);
    }

    public function getIcon() {
        return "images/icons/filetype/network_folder.png";
    }
}

<?php

namespace develnext\filetype;

use develnext\editor\Editor;
use develnext\lang\Singleton;
use develnext\project\Project;
use php\io\File;
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
     * @param UIContainer $container
     * @param File $file
     * @param Project $project
     * @return Editor
     */
    abstract public function createEditor(UIContainer $container, File $file, Project $project = null);
}

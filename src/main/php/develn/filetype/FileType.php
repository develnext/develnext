<?php

namespace develnext\filetype;

use develnext\lang\Singleton;
use develnext\project\Project;
use php\io\File;

/**
 * Class FileType
 * @package develnext\filetype
 */
abstract class FileType {
    use Singleton;

    abstract public function onDetect(File $file, Project $project = null);
    abstract public function getEditor(File $file, Project $project = null);
}

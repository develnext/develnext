<?php
namespace develnext\ide\std\project\dependency;

use develnext\project\ProjectDependency;
use php\io\File;
use php\lib\str;

class DirectoryProjectDependency extends ProjectDependency {

    /** @var File */
    protected $directory;

    function __construct(File $directory) {
        $this->directory = $directory;
    }

    function getUniqueCode() {
        $path = $this->directory->getPath();
        $path = str::replace($path, '\\', '/');

        if (File::PATH_NAME_CASE_INSENSITIVE) {
            $path = str::lower($path);
        }

        if (str::endsWith($path, '/'))
            $path = str::sub($path, str::length($path) - 1);

        return 'dir#' . $path;
    }

    function toString() {
        return $this->directory->getPath();
    }

    function fromString($string) {
        $this->directory = new File($string);
    }

    /**
     * @return File
     */
    public function getDirectory() {
        return $this->directory;
    }
}

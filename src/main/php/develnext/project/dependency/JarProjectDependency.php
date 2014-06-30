<?php
namespace develnext\project\dependency;

use develnext\project\ProjectDependency;
use php\io\File;
use php\lib\str;

class JarProjectDependency extends ProjectDependency {

    /** @var File */
    protected $jarFile;

    /**
     * @param $jarFile
     */
    function __construct(File $jarFile) {
        $this->jarFile = $jarFile;
    }

    function getUniqueCode() {
        $path = $this->jarFile->getPath();
        $path = str::replace($path, '\\', '/');

        if (File::PATH_NAME_CASE_INSENSITIVE) {
            $path = str::lower($path);
        }

        return 'jar#' . $path;
    }

    function toString() {
        return $this->jarFile->getPath();
    }

    function fromString($string) {
        $this->jarFile = new File($string);
    }
}

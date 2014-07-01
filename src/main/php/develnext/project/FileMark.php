<?php
namespace develnext\project;

use php\lib\str;

/**
 * Class FileMark
 * @package develnext\project
 */
class FileMark {
    /** @var ProjectFile */
    protected $file;

    /** @var string */
    protected $type;

    function __construct(ProjectFile $file, $type) {
        $this->file = $file;
        $this->type = str::lower($type);
    }

    function isType($type) {
        return str::lower($type) === $type;
    }

    function hashCode() {
        return $this->type . '#' . $this->file->hashCode();
    }
}

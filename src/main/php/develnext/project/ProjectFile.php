<?php
namespace develnext\project;

use develnext\filetype\FileType;
use develnext\Manager;
use php\io\File;
use php\io\Stream;
use php\lib\str;
use php\swing\Image;

/**
 * Class ProjectFile
 * @package develnext\project
 */
class ProjectFile {
    /** @var Project */
    protected $project;

    /** @var File */
    protected $file;

    /** @var FileType */
    protected $type;

    /** @var Image */
    protected $icon;

    /** @var string */
    protected $alternativeText;

    public function __construct(File $file, Project $project = null) {
        $manager = Manager::getInstance();
        $this->project = $project;

        $this->file = $file;
        $this->type = $manager->getFileTypeOf($file, $project);
        $this->icon = Image::read(Stream::of('res://' . $this->type->getIcon()));
    }

    public function __toString() {
        return $this->alternativeText ? $this->alternativeText : $this->file->getName();
    }

    public function getIcon() {
        return $this->icon;
    }

    public function getRelPath() {
        $path = str::replace($this->file->getPath(), '\\', '/');
        $root = $this->project ? str::replace($this->project->getDirectory(), '\\', '/') : '';

        if (str::startsWith($path, $root)) {
            $path = str::sub($path, str::length($root));
        }

        return $path;
    }

    public function duplicate($text, $icon = null) {
        $r = new ProjectFile($this->file, $this->project);

        if ($icon)
            $r->icon = Image::read(Stream::of('res://' . $icon));

        if ($text)
            $r->alternativeText = $text;

        return $r;
    }

    public function getType() {
        return $this->type;
    }

    public function getFile() {
        return $this->file;
    }

    public function hashCode() {
        $hash = str::replace(str::lower($this->file->getPath()), '\\', '/');
        $hash = str::replace($hash, '//', '/');

        return $hash;
    }
}

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
    /** @var File */
    protected $file;

    /** @var Project */
    protected $project;

    /** @var FileType */
    protected $type;

    /** @var Image */
    protected $icon;

    /** @var string */
    protected $alternativeText;

    /** @var bool */
    protected $external;

    /** @var File */
    protected $root;

    public function __construct(File $file, Project $project = null) {
        $this->project = $project;
        $this->external = null;

        $this->file = $file;
        if ($this->isExternal()) {
            foreach ($project->getContentRoots() as $el) {
                if (str::startsWith($file->getPath(), $el->getFile()->getPath())) {
                    $this->root = $el->getFile();
                    break;
                }
            }
        } else
            $this->root = $project == null ? null : $project->getDirectory();
    }

    public function __toString() {
        return $this->alternativeText ? $this->alternativeText : $this->file->getName();
    }

    public function getIcon() {
        if ($this->icon)
            return $this->icon;

        return $this->icon = Image::read(Stream::of('res://' . $this->getType()->getIcon()));
    }

    public function isExternal() {
        if ($this->external !== null)
            return $this->external;

        $path = str::replace($this->file->getPath(), '\\', '/');
        $root = $this->project ? str::replace($this->project->getDirectory(), '\\', '/') : '';

        return $this->external = !str::startsWith($path, $root);
    }

    public function getRelPath() {
        $path = str::replace($this->file->getPath(), '\\', '/');
        $root = $this->root ? str::replace($this->root->getPath(), '\\', '/') : '';

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
        if ($this->type)
            return $this->type;

        return $this->type = Manager::getInstance()->getFileTypeOf($this->file, $this->project);
    }

    public function getFile() {
        return $this->file;
    }

    /**
     * @return ProjectFile
     */
    public function getParent() {
        if ($this->file->getParentFile() == null)
            return null;

        return new ProjectFile($this->file->getParentFile(), $this->project);
    }

    public function hashCode() {
        $hash = str::replace(str::lower($this->file->getPath()), '\\', '/');
        $hash = str::replace($hash, '//', '/');

        return $hash;
    }

    /**
     * @return \php\io\File
     */
    public function getRoot() {
        return $this->root;
    }

    public function getProject() {
        return $this->project;
    }

    public function delete() {
        if ($this->file->isDirectory()) {
            $success = true;
            foreach ($this->file->findFiles() as $file) {
                if ($file->isDirectory())
                    $success = $success && (new ProjectFile($file, $this->project))->delete();
                else
                    $success = $success && $file->delete();
            }
            return $success && $this->file->delete();
        } else {
            return $this->file->delete();
        }
    }

    public function toArray() {
        return [
            $this->isExternal(),
            $this->isExternal() ? $this->getFile()->getPath() : $this->getRelPath()
        ];
    }

    public static function fromArray(array $array, Project $project) {
        if ($array[0])
            $projectFile = new ProjectFile(new File($array[1]), $project);
        else
            $projectFile = new ProjectFile(new File($project->getDirectory()->getPath() . $array[1]), $project);

        return $projectFile;
    }
}

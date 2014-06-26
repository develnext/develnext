<?php
namespace develnext\project;

use php\io\File;
use php\swing\UIContainer;
use php\swing\UITree;

/**
 * Class Project
 * @package develnext\project
 */
class Project {
    /** @var string */
    protected $name;

    /** @var ProjectType */
    protected $type;

    /** @var File */
    protected $directory;

    /** @var ProjectDependency */
    protected $dependencies = array();

    /** @var FileTreeManager */
    protected $fileTree;

    /** @var EditorManager */
    protected $editorManager;

    function __construct(ProjectType $type, File $directory) {
        $this->type = $type;
        $this->directory = $directory;
        $this->name = $directory->getName();
        $this->fileTree = new FileTreeManager($this);
        $this->editorManager = new EditorManager($this);
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return \php\io\File
     */
    public function getDirectory() {
        return $this->directory;
    }

    /**
     * @return \develnext\project\ProjectType
     */
    public function getType() {
        return $this->type;
    }

    public function hasDependency(ProjectDependency $dependency) {
        return isset($this->dependencies[$dependency->getUniqueCode()]);
    }

    public function addDependency(ProjectDependency $dependency) {
        $this->dependencies[$dependency->getUniqueCode()] = $dependency;
    }

    public function removeDependency(ProjectDependency $dependency) {
        unset($this->dependencies[$dependency->getUniqueCode()]);
    }

    public function getDependencies() {
        return $this->dependencies;
    }

    /**
     * @param $path
     * @return File
     */
    public function getFile($path) {
        return new File($this->getPath($path));
    }

    public function getPath($path) {
        return $this->directory->getPath() . "/$path";
    }

    public function updateTree() {
        $this->fileTree->updateAll();
    }

    public function setGuiElements(UIContainer $editorContainer, UITree $fileTree) {
        $this->fileTree->setTree($fileTree);
        $this->editorManager->setArea($editorContainer);

        $this->fileTree->setEditorManager($this->editorManager);
    }

    public function close() {
        $this->fileTree->close();
        $this->editorManager->close();
    }

    public function saveAll() {
        $this->editorManager->saveAll();
    }
}

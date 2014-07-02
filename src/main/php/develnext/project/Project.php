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

    /** @var ProjectFormat */
    protected $projectFormat;

    /** @var FileMark[] */
    protected $fileMarks;

    function __construct(ProjectType $type, File $directory) {
        $this->type = $type;
        $this->directory = $directory;
        $this->name = $directory->getName();
        $this->fileTree = new FileTreeManager($this);
        $this->editorManager = new EditorManager($this);
        $this->projectFormat = new ProjectFormat($this);
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

    public function getFileMark(ProjectFile $file, $type) {
        return $this->fileMarks[$type . '#' . $file->hashCode()];
    }

    public function setFileMark(ProjectFile $file, $type) {
        $this->fileMarks[$type . '#' . $file->hashCode()] = new FileMark($file, $type);
    }

    public function deleteFileMark(ProjectFile $file, $type) {
        unset($this->fileMarks[$type . '#' . $file->hashCode()]);
    }

    /**
     * @return FileMark[]
     */
    public function getFileMarks() {
        return $this->fileMarks;
    }


    /**
     * @return ProjectDependency[]
     */
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

    /**
     * @param $path
     * @return ProjectFile
     */
    public function getProjectFile($path) {
        return new ProjectFile($this->getFile($path), $this);
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
        $this->projectFormat->save();
    }

    public function openFile(ProjectFile $file) {
        $this->editorManager->open($file);
    }

    /**
     * @return \develnext\project\EditorManager
     */
    public function getEditorManager() {
        return $this->editorManager;
    }

    /**
     * @return \develnext\project\FileTreeManager
     */
    public function getFileTree() {
        return $this->fileTree;
    }
}

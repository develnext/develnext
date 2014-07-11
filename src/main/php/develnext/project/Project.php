<?php
namespace develnext\project;

use php\io\File;
use php\lib\str;
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

    /** @var ProjectFile[] */
    protected $contentRoots = [];

    /** @var ProjectRunner[] */
    protected $runners;

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
        $mark = new FileMark($file, $type);
        return $this->fileMarks[$mark->hashCode()];
    }

    public function addFileMark(FileMark $mark) {
        $this->fileMarks[$mark->hashCode()] = $mark;
    }

    public function setFileMark(ProjectFile $file, $type) {
        $mark = new FileMark($file, $type);
        $this->fileMarks[$mark->hashCode()] = $mark;
    }

    public function deleteFileMark(ProjectFile $file, $type) {
        $mark = new FileMark($file, $type);
        unset($this->fileMarks[$mark->hashCode()]);
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

    public function updateFile(ProjectFile $file) {
        $this->fileTree->updateFile($file);
        if (!$file->getFile()->exists())
            $this->editorManager->close($file);
    }

    public function setGuiElements(UIContainer $editorContainer, UITree $fileTree) {
        $this->fileTree->setTree($fileTree);
        $this->editorManager->setArea($editorContainer);

        $this->fileTree->setEditorManager($this->editorManager);
    }

    public function close() {
        $this->fileTree->close();
        $this->editorManager->closeAll();
    }

    public function saveAll() {
        $this->editorManager->saveAll();
        $this->projectFormat->save();
    }

    public function openFile(ProjectFile $file) {
        $this->editorManager->open($file);
        $this->fileTree->selectFile($file);
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

    public function addContentRoot(File $path) {
        $projectFile = new ProjectFile($path, $this);
        $this->contentRoots[ $projectFile->hashCode() ] = $projectFile;
    }

    public function removeContentRoot(File $path) {
        $projectFile = new ProjectFile($path, $this);
        unset($this->contentRoots[ $projectFile->hashCode() ]);
    }

    public function getContentRoots() {
        return $this->contentRoots;
    }

    public function isContentRoot(File $path) {
        $projectFile = new ProjectFile($path, $this);
        return isset($this->contentRoots[$projectFile->hashCode()]);
    }

    public function isExternalFile(File $path) {
        return (new ProjectFile($path, $this))->isExternal();
    }

    /**
     * @return \develnext\project\ProjectRunner[]
     */
    public function getRunners() {
        return $this->runners;
    }

    public function addRunner(ProjectRunner $runner) {
        $this->runners[] = $runner;
    }

    public function removeRunner($index) {
        unset($this->runners[$index]);
    }
}

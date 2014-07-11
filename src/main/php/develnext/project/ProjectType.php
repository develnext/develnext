<?php
namespace develnext\project;

use develnext\filetype\creator\Creator;
use php\lib\str;

abstract class ProjectType {

    abstract function getName();

    function getCode() {
        return str::replace(' ', '_', str::lower($this->getName()));
    }

    function getDescription() {
        return '';
    }

    protected function getIcon() {
        return 'images/icons/project';
    }

    function getBigIcon() {
        return $this->getIcon() . '32.png';
    }

    function getSmallIcon() {
        return $this->getIcon() . '16.png';
    }

    /**
     * @return ProjectDependency[]
     */
    function getDefaultDependencies() {
        return array();
    }

    function onRegister(ProjectManager $manager) {
        // nop
    }

    function onCreateProject(Project $project) {
    }

    function onUpdateProject(Project $project) {
        // nop
    }

    function onRenderFileInTree(ProjectFile $file) {
        return $file;
    }

    function isAvailableFileCreator(ProjectFile $file, Creator $creator) {
        return true;
    }
}

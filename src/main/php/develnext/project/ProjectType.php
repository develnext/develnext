<?php
namespace develnext\project;

use php\lib\str;

abstract class ProjectType {

    abstract function getName();

    function getCode() {
        return str::replace(' ', '_', str::lower($this->getName()));
    }

    function getDescription() {
        return '';
    }

    function getIcon() {
        return 'images/icons/project';
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
}

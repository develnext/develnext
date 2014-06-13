<?php
namespace develnext\project;

abstract class ProjectType {

    abstract function getName();

    function getDescription() {
        return '';
    }

    function getIcon() {
        return 'images/icons/project';
    }
}

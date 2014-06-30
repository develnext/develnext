<?php
namespace develnext\project;

abstract class ProjectDependency {

    abstract function getUniqueCode();

    abstract function toString();
    abstract function fromString($string);
}

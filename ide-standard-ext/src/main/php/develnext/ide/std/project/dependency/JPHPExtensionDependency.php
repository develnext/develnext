<?php
namespace develnext\ide\std\project\dependency;

use develnext\project\ProjectDependency;

/**
 * Class JPHPExtensionDependency
 * @package develnext\project\dependency
 */
class JPHPExtensionDependency extends ProjectDependency {

    protected $className;

    /**
     * @param string $className
     */
    function __construct($className) {
        $this->className = $className;
    }

    function getUniqueCode() {
        return 'jphp_ext#' . $this->className;
    }

    function toString() {
        return $this->className;
    }

    function fromString($string) {
        $this->className = $string;
    }

    /**
     * @return mixed
     */
    public function getClassName() {
        return $this->className;
    }
}

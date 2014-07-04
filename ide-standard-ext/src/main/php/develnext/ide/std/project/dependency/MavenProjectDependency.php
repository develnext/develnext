<?php
namespace develnext\ide\std\project\dependency;

use develnext\project\ProjectDependency;
use php\lib\str;

/**
 * Class MavenProjectDependency
 * @package develnext\project\dependency
 */
class MavenProjectDependency extends ProjectDependency {

    protected $groupId;
    protected $artifactId;
    protected $version;

    /**
     * @param string $artifactId
     * @param string $groupId
     * @param string $version
     */
    function __construct($groupId, $artifactId, $version) {
        $this->artifactId = $artifactId;
        $this->groupId = $groupId;
        $this->version = $version;
    }


    function getUniqueCode() {
        return 'maven#' . $this->groupId . ':' . $this->artifactId;
    }

    function toString() {
        return $this->groupId . ':' . $this->artifactId . ':' . $this->version;
    }

    function fromString($string) {
        $tmp = str::split($string, ':', 3);
        $this->groupId = $tmp[0];
        $this->artifactId = $tmp[1];
        $this->version = $tmp[2];
    }

    /**
     * @return string
     */
    public function getArtifactId() {
        return $this->artifactId;
    }

    /**
     * @return string
     */
    public function getGroupId() {
        return $this->groupId;
    }

    /**
     * @return string
     */
    public function getVersion() {
        return $this->version;
    }
}

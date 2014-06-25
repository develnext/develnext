<?php
namespace develnext\project\dependency;

use develnext\project\ProjectDependency;

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

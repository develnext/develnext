<?php
namespace develnext\project;

use develnext\lang\Singleton;
use php\io\File;

class ProjectManager {
    use Singleton;

    protected $types = array();

    /**
     * @param ProjectType $type
     * @param File $directory
     * @return Project
     */
    public function createProject(ProjectType $type, File $directory) {
        if (!$directory->exists())
            $directory->mkdirs();

        $project = new Project($type, $directory);
        foreach($type->getDefaultDependencies() as $dep) {
            $project->addDependency($dep);
        }

        $type->onCreateProject($project);
        return $project;
    }

    public function registerType(ProjectType $type) {
        if (isset($this->types[$type->getCode()]))
            throw new \Exception("Project type (" . $type->getCode() . ") already registered");

        $type->onRegister($this);
        $this->types[ $type->getCode() ] = $type;
    }
}

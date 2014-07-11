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
}

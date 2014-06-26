<?php

namespace develnext\project\type;

use develnext\project\dependency\MavenProjectDependency;
use develnext\project\Project;
use php\io\FileStream;
use php\util\Flow;

class GuiProjectType extends JVMProjectType {

    function getName() {
        return 'Swing GUI';
    }

    function getDefaultDependencies() {
        return Flow::of(parent::getDefaultDependencies())->append([
            new MavenProjectDependency('org.develnext', 'jphp-swing-ext', '0.4-SNAPSHOT')
        ])->toArray();
    }

    function getIcon() {
        return 'images/icons/projecttype/gui';
    }

    function onCreateProject(Project $project) {
        parent::onCreateProject($project);

        $project->getFile('resources/forms')->mkdirs();
        $project->getFile('resources/images')->mkdirs();
    }
}

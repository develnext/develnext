<?php

namespace develnext\ide\std\project\type;

use develnext\ide\std\project\dependency\JPHPExtensionDependency;
use develnext\ide\std\project\dependency\MavenProjectDependency;
use develnext\project\Project;
use php\io\FileStream;
use php\util\Flow;

class GuiProjectType extends JVMProjectType {

    function getName() {
        return 'Swing GUI';
    }

    function getDefaultDependencies() {
        return Flow::of(parent::getDefaultDependencies())->append([
            new MavenProjectDependency('org.develnext', 'jphp-swing-ext', '0.4-SNAPSHOT'),
            new JPHPExtensionDependency('org.develnext.jphp.swing.SwingExtension')
        ])->toArray();
    }

    protected function getIcon() {
        return 'images/icons/projecttype/gui';
    }

    function onCreateProject(Project $project) {
        parent::onCreateProject($project);

        $project->getFile('resources/forms')->mkdirs();
        $project->getFile('resources/images')->mkdirs();
    }
}

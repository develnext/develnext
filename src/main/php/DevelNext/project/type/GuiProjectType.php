<?php

namespace develnext\project\type;

use develnext\project\dependency\MavenProjectDependency;
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
}

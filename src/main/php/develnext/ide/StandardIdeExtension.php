<?php
namespace develnext\ide;

use develnext\filetype\DirectoryFileType;
use develnext\filetype\PhpFileType;
use develnext\filetype\SwingFormFileType;
use develnext\filetype\TextFileType;
use develnext\filetype\UnknownFileType;
use develnext\project\type\ConsoleProjectType;
use develnext\project\type\GuiProjectType;

class StandardIdeExtension extends IdeExtension {

    public function onRegister(IdeManager $manager) {
        $manager->addHeadMenuItem('res://images/icons/open16.png');
        $manager->addHeadMenuItem('res://images/icons/save16.png');
        $manager->addHeadMenuItem('res://images/icons/settings16.png');

        // file types
        $manager->registerFileType(new UnknownFileType());
        $manager->registerFileType(new DirectoryFileType());
        $manager->registerFileType(new TextFileType());
        $manager->registerFileType(new PhpFileType());
        $manager->registerFileType(new SwingFormFileType());

        // project types
        $manager->registerProjectType(new ConsoleProjectType());
        $manager->registerProjectType(new GuiProjectType());
    }
}

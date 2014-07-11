<?php

namespace develnext\project;

use develnext\ide\components\__File;
use develnext\ide\components\UIDirectoryChooser;
use develnext\ide\ImageManager;
use develnext\Manager;
use php\io\File;
use php\swing\Font;
use php\swing\tree\TreeNode;
use php\swing\UIDialog;
use php\swing\UILabel;
use php\swing\UITree;

class UIProjectChooser extends UIDirectoryChooser {

    public function __construct($forSave = false)  {
        parent::__construct('project' . ($forSave ? 'Save' : 'Load'));
        $this->setOnlyDirectories(true);

        $this->onFetchIcon(function(UIDirectoryChooser $self, File $file){
            $loader = new ProjectLoader();
            $project = $loader->load($file);
            if ($project)
                return ImageManager::get($project->getType()->getSmallIcon());
        });

        if (!$forSave) {
            $this->setOkButtonEnabled(false);

            $this->onSelected(function(UIDirectoryChooser $self, File $file){
                $loader = new ProjectLoader();
                $project = $loader->load($file);
                $self->setOkButtonEnabled(!!$project);
            });
        } else {
            $this->setOkButtonEnabled(true);

            $this->onSelected(function(UIDirectoryChooser $self, File $file){
                $loader = new ProjectLoader();
                $project = $loader->load($file);
                $self->setOkButtonEnabled(!$project);
            });
        }
    }
}

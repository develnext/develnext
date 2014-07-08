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
    public function __construct()  {
        parent::__construct();

        $this->onFetchIcon(function(UIDirectoryChooser $self, File $file){
            $loader = new ProjectLoader();
            $project = $loader->load($file);
            if ($project)
                return ImageManager::get($project->getType()->getSmallIcon());
        });
    }

    public function showForOpen() {
        $this->setOkButtonEnabled(false);

        $this->onSelected(function(UIDirectoryChooser $self, File $file){
            $loader = new ProjectLoader();
            $project = $loader->load($file);
            $self->setOkButtonEnabled(!!$project);
        });

        $this->showDialog();
    }

    public function showForSave() {
        $this->setOkButtonEnabled(true);

        $this->onSelected(function(UIDirectoryChooser $self, File $file){
            $loader = new ProjectLoader();
            $project = $loader->load($file);
            $self->setOkButtonEnabled(!$project);
        });

        $this->showDialog();
    }
}

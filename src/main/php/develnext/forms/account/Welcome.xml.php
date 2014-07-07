<?php

/** @var \develnext\IDEForm $form */

use develnext\ide\ImageManager;
use develnext\Manager;
use php\io\File;
use php\swing\event\MouseEvent;
use php\swing\UIDialog;
use php\swing\UIFileChooser;
use php\swing\UIListbox;

$form->get('btn-create-project')->on('click', function() use ($form) {
    $manager = Manager::getInstance();
    if ($manager->getSystemForm('project/NewProject.xml')->showModal()) {
        $form->hide(true);
    }
});

/** @var UIListbox $list */
$list = $form->get('list-latest-projects');
$list->on('click', function(MouseEvent $e) use ($form, $list) {
    if ($e->clickCount > 1 && $list->selectedIndex > -1) {
        $manager = Manager::getInstance();
        $manager->closeProject();

        $project = $manager->openProject(new File($list->getItem($list->selectedIndex)));
        if (!$project) {
            UIDialog::message(_('Cannot open project'), _('Error'), UIDialog::ERROR_MESSAGE);
        } else {
            $form->hide(true);
        }
    }
});

$form->get('btn-open-project')->on('click', function() use ($form) {
    $dirDialog = new UIFileChooser();
    $dirDialog->acceptAllFileFilterUsed = false;

    $dirDialog->onFileView('icon', function(File $file){
        if ((new File($file->getPath() . '/.develnext'))->exists()) {
            return ImageManager::get('images/icons/project16.png');
        }
    });

    $dirDialog->addChoosableFilter(function(File $file){
        return $file->isDirectory();
    }, 'Directories');

    if ($dirDialog->showOpenDialog()) {
        $manager = Manager::getInstance();
        $manager->closeProject();
        $project = $manager->openProject($dirDialog->selectedFile);
        if (!$project) {
            UIDialog::message(_('Cannot open project'), _('Error'), UIDialog::ERROR_MESSAGE);
        } else {
            $form->hide(true);
        }
    }
});

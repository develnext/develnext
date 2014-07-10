<?php

use develnext\ide\components\UIDirectoryChooser;
use develnext\ide\ImageManager;
use develnext\Manager;
use develnext\IDEForm;
use develnext\project\UIProjectChooser;
use php\io\File;
use php\io\Stream;
use php\swing\Border;
use php\swing\Image;
use php\swing\UIFileChooser;
use php\swing\UILabel;
use php\swing\UIListbox;

/** @var IDEForm $form */

$form->get('f-directory-btn')->on('click', function() use ($form) {
    $chooser = new UIProjectChooser(true);
    $chooser->showDialog();

    if ($chooser->getSelectedFile()) {
        $form->get('f-directory')->text = $chooser->getSelectedFile()->getPath();
        $fName = $form->get('f-name');

        if (!$fName->text)
            $fName->text = $chooser->getSelectedFile()->getName();
    }
});

/** @var UIListbox $typeList */
$typeList = $form->get('type-list');

$manager = Manager::getInstance();

$types = $manager->getProjectTypes();
foreach ($types as $type) {
    $typeList->addItem($type->getName());
}

$typeList->onCellRender(function(UIListbox $self, UILabel $template, $value, $index) use ($types) {
    $type = $types[$index];
    if ($type) {
        $template->iconTextGap = 4;
        $template->border = Border::createEmpty(3, 3, 3, 3);
        $template->setIcon(ImageManager::get($type->getBigIcon()));
    }

    return $template;
});

$typeList->on('click', function() use ($typeList, $types, $form) {
    $index = $typeList->selectedIndex;
    $type = $types[$index];

    /** @var UILabel $label */
    $label = $form->get('f-type');

    if ($type) {
        $label->text = $type->getName();
        $label->setIcon(ImageManager::get($type->getBigIcon()));
    }
});

$typeList->selectedIndex = 0;
$typeList->trigger('click');

$form->get('btn-create')->on('click', function() use ($typeList, $types, $form, $manager) {
    $index = $typeList->selectedIndex;
    $type = $types[$index];

    if ($type) {
        $manager->createProject(
            $form->get('f-name')->text,
            new File($form->get('f-directory')->text),
            $type
        );
        $form->hide(true);
    }
});

$form->get('btn-cancel')->on('click', function() use ($form){
    $form->hide();
});

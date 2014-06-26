<?php

use develnext\Manager;
use develnext\IDEForm;
use php\io\File;
use php\io\Stream;
use php\swing\Border;
use php\swing\Image;
use php\swing\UIFileChooser;
use php\swing\UILabel;
use php\swing\UIListbox;

/** @var IDEForm $form */

$form->get('f-directory-btn')->on('click', function() use ($form) {
    $dirDialog = new UIFileChooser();
    $dirDialog->acceptAllFileFilterUsed = false;

    $dirDialog->addChoosableFilter(function(File $file){
        return $file->isDirectory();
    }, 'Directories');

    if ($dirDialog->showOpenDialog()) {
        $form->get('f-directory')->text = $dirDialog->selectedFile->getPath();
        $fName = $form->get('f-name');

        if (!$fName->text)
            $fName->text = $dirDialog->selectedFile->getName();
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
        $template->setIcon(Image::read(Stream::of('res://' . $type->getIcon() . '32.png')));
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
        $label->setIcon(Image::read(Stream::of('res://' . $type->getIcon() . '32.png')));
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
        $form->hide();
    }
});

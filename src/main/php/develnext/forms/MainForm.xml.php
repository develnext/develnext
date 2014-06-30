<?php

use php\swing\docking\CControl;
use php\swing\docking\CGrid;
use php\swing\docking\SingleCDockable;
use develnext\Manager;
use php\swing\UIDialog;

$manager = Manager::getInstance();

/** @var \develnext\IDEForm $form */
$control = new CControl($form->getWindow());
$contentArea = $control->getContentArea();

$form->get('content')->add($contentArea);
//$work = $control->createWorkingArea('work');

$grid = new CGrid($control);
$grid->add(1, 1, 3, 3, $dContent = new SingleCDockable('content', 'content', $form->get('area')));
$grid->add(0, 0, 1, 4, $one = new SingleCDockable('editor', 'File Tree', $form->get('fileTreePanel')));
$grid->add(1, 3, 3, 1, new SingleCDockable('console', 'Console', $form->get('console')));


$dContent->closable = false;
$dContent->externalizable = false;
$dContent->maximizable = false;
$dContent->minimizable = false;
$dContent->titleShown = false;

$contentArea->deploy($grid);
$control->setTheme('flat');

$configFile = $manager->getConfigFile('dock.xml');
if ($configFile->exists()) {
    $control->readXml($configFile);
}

$form->loadFromFile($manager->getConfigFile('MainForm.cfg'));

/*$r = new DesignContainer();
$r->size = [100, 100];
$r->add($btn = new \php\swing\UIProgress());
$btn->value = 50;

$form->get('area')->add($r);

$cr = new ComponentResizer();
$cm = new ComponentMover();
$cr->registerComponent($r);
$cm->registerComponent($r);*/

//$r->border = new ResizableBorder(6);

/*$button = new UIButton();
$button->size = [100, 100];
$work->getComponent()->add($button);*/

/** @var \php\swing\UIMenuItem $saveAll */
/*$saveAll = $form->get('menu-save-all');
$saveAll->accelerator = 'control S';
$saveAll->on('click', function() {
    $manager = Manager::getInstance();
    $manager->currentProject->saveAll();
});*/

/*
$form->get('menu-new-project')->on('click', function(){
    $manager = Manager::getInstance();
    $manager->getSystemForm('project/NewProject.xml')->showModal();
});*/

$form->getWindow()->on('windowClosing', function() use ($control, $configFile, $form, $manager) {
    $control->writeXml($configFile);
    $form->saveToFile($manager->getConfigFile('MainForm.cfg'));
});

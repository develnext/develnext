<?php

use develnext\ide\IdeManager;
use develnext\ide\ImageManager;
use php\swing\docking\CControl;
use php\swing\docking\CGrid;
use php\swing\docking\SingleCDockable;
use develnext\Manager;
use php\swing\UIDialog;

$manager = Manager::getInstance();

/** @var \develnext\IDEForm $form */
$control = new CControl($form->getWindow());

$control->setIcon("locationmanager.maximize", ImageManager::get('images/docking/maximize.png'));
$control->setIcon("locationmanager.normalize", ImageManager::get('images/docking/normalize.png'));
$control->setIcon("locationmanager.unmaximize_externalized", ImageManager::get('images/docking/normalize.png'));
$control->setIcon("locationmanager.minimize", ImageManager::get('images/docking/minimize.png'));
$control->setIcon("locationmanager.externalize", ImageManager::get('images/docking/externalize.png'));
$control->setIcon("locationmanager.unexternalize", ImageManager::get('images/docking/unexternalize.png'));

$contentArea = $control->getContentArea();

$form->get('content')->add($contentArea);
//$work = $control->createWorkingArea('work');

$grid = new CGrid($control);
$grid->add(1, 1, 3, 3, $dContent = new SingleCDockable('content', 'content', $form->get('area')));
$grid->add(0, 0, 1, 4, $one = new SingleCDockable('editor', _('File Tree'), $form->get('fileTreePanel')));
$grid->add(1, 3, 3, 1, new SingleCDockable('console', _('Tools'), $form->get('console')));

$dContent->closable = false;
$dContent->externalizable = false;
$dContent->maximizable = false;
$dContent->minimizable = false;
$dContent->titleShown = false;

$contentArea->deploy($grid);

$configFile = $manager->getConfigFile('dock.xml');
if ($configFile->exists()) {
    $control->readXml($configFile);
}

$control->setTheme('develnext');
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

$form->getWindow()->on('windowClosing', function() use ($control, $configFile, $form, $manager) {
    IdeManager::current()->trigger('close');

    $control->writeXml($configFile);
    $form->saveToFile($manager->getConfigFile('MainForm.cfg'));
});

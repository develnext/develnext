<?php

use DevelNext\swing\ComponentMover;
use DevelNext\swing\ComponentResizer;
use DevelNext\swing\DesignContainer;
use php\swing\docking\CControl;
use php\swing\docking\CGrid;
use php\swing\docking\SingleCDockable;
use php\swing\UIButton;

/** @var \develnext\IDEForm $form */
$control = new CControl($form->getWindow());
$contentArea = $control->getContentArea();

$c['content']->add($contentArea);
//$work = $control->createWorkingArea('work');

$grid = new CGrid($control);
$grid->add(1, 1, 3, 3, $dContent = new SingleCDockable('content', 'content', $c['area']));
$grid->add(0, 0, 1, 4, $one = new SingleCDockable('editor', 'editor', $c['editor']));
$grid->add(1, 3, 3, 1, new SingleCDockable('console', 'console', $c['console']));

$control->readXml($this->getConfigFile('dock.xml'));

$dContent->closable = false;
$dContent->externalizable = false;
$dContent->maximizable = false;
$dContent->minimizable = false;
$dContent->titleShown = false;

$contentArea->deploy($grid);
$control->setTheme('flat');

$r = new DesignContainer();
$r->size = [100, 100];
$r->add($btn = new UIButton());
$btn->text = 'Я кнопка, кнопка, кнопка... я вовсе не медведь';
$c['area']->add($r);

$cr = new ComponentResizer();
$cm = new ComponentMover();
$cr->registerComponent($r);
$cm->registerComponent($r);

//$r->border = new ResizableBorder(6);

/*$button = new UIButton();
$button->size = [100, 100];
$work->getComponent()->add($button);*/

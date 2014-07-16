<?php
namespace develnext\forms;

use develnext\ide\IdeManager;
use develnext\ide\ImageManager;
use develnext\IDEForm;
use php\lang\Module;
use php\swing\docking\CControl;
use php\swing\docking\CGrid;
use php\swing\docking\SingleCDockable;
use develnext\Manager;
use php\swing\UIDialog;
use php\swing\UIWindow;

class IDEMainForm extends IDEForm {
    protected $dockTools;
    protected $dockFileTree;
    protected $dockContent;

    function __construct(UIWindow $window, Module $module = null, array $vars = array()) {
        parent::__construct($window, $module, $vars);
        $form = $this;

        $manager = Manager::getInstance();

        /** @var IDEForm $form */
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
        $grid->add(1, 1, 3, 2.7,
            $this->dockContent = $dContent = new SingleCDockable('content', 'content', $form->get('area')));

        $grid->add(0, 0, 0.6, 2.7,
            $this->dockFileTree = $one = new SingleCDockable('editor', _('File Tree'), $form->get('fileTreePanel')));

        $grid->add(1, 3, 3, 1.3,
            $this->dockTools = $two = new SingleCDockable('console', _('Tools'), $form->get('console')));

        $dContent->closable = false;
        $dContent->externalizable = false;
        $dContent->maximizable = false;
        $dContent->minimizable = false;
        $dContent->titleShown = false;

        $contentArea->deploy($grid);

        $two->setBaseLocation('bottom');

        $configFile = $manager->getConfigFile('dock.xml');
        if ($configFile->exists()) {
            $control->readXml($configFile);
        }
        $two->visible = false;

        $control->setTheme('develnext');
        $form->loadFromFile($manager->getConfigFile('MainForm.cfg'));


        $form->getWindow()->on('windowClosing', function() use ($control, $configFile, $form, $manager) {
            IdeManager::current()->trigger('close');

            $control->writeXml($configFile);
            $form->saveToFile($manager->getConfigFile('MainForm.cfg'));
        });
    }

    /**
     * @return \php\swing\docking\SingleCDockable
     */
    public function getDockContent() {
        return $this->dockContent;
    }

    /**
     * @return \php\swing\docking\SingleCDockable
     */
    public function getDockFileTree() {
        return $this->dockFileTree;
    }

    /**
     * @return \php\swing\docking\SingleCDockable
     */
    public function getDockTools() {
        return $this->dockTools;
    }
}

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

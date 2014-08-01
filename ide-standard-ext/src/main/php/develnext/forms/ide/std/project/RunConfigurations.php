<?php
namespace develnext\forms\ide\std\project;

use develnext\ide\components\UIMessages;
use develnext\ide\IdeExtension;
use develnext\ide\IdeManager;
use develnext\ide\ImageManager;
use develnext\ide\std\StandardIdeExtension;
use develnext\IDEForm;
use develnext\project\Project;
use develnext\project\ProjectFile;
use develnext\project\ProjectRunner;
use develnext\ui\decorator\UIListDecorator;
use php\lang\Module;
use php\swing\event\MouseEvent;
use php\swing\UIContainer;
use php\swing\UIDialog;
use php\swing\UIListbox;
use php\swing\UIMenuItem;
use php\swing\UIPopupMenu;
use php\swing\UIWindow;

class IDERunConfigurations extends IDEForm {
    /** @var UIListDecorator */
    protected $runList;

    /** @var UIPopupMenu */
    protected $addMenu;

    function __construct(UIWindow $window, Module $module = null, array $vars = array()) {
        parent::__construct($window, $module, $vars);

        $this->runList = new UIListDecorator($this->get('run-list'));
        $this->addMenu = $this->get('add-menu');

        $this->runList->getElement()->on('mouseRelease', function(){
            if ($this->runList->getElement()->selectedIndex >= 0) {
                $this->selectProjectRunner(
                    Project::current()->getRunners()[$this->runList->getElement()->selectedIndex]
                );
            }
        });

        $this->get('btn-add')->on('click', function(MouseEvent $e){
            $this->reloadRunnerTypes();
            $this->addMenu->show($e->target, 0, $e->target->h);
        });

        $this->get('btn-delete')->on('click', function(){
            $runner = Project::current()->getRunners()[$this->runList->getElement()->selectedIndex];

            if ($runner && UIDialog::confirm(_('Are you sure?'), _('Question')) === UIDialog::YES_OPTION) {
                Project::current()->removeRunner($runner);
                $this->reloadProjectRunners();
                $this->selectProjectRunner(Project::current()->getSelectedRunner());
            }
        });

        $this->get('btn-save-settings')->on('click', function(){
            $runner = $this->getSelectedProjectRunner();
            if ($runner) {
                $runner->setTitle($this->get('field-name')->text);
                $runner->setSingleton($this->get('field-reload')->selected);
                $runner->setConfig($runner->getType()->fetchConfig($this->get('settings')));

                $this->updateProjectRunner($runner);
            }
            Project::current()->saveAll();
        });

        $this->get('btn-cancel-settings')->on('click', function(){
            $this->hide();
        });
    }

    public function show($centered = true) {
        $this->reloadProjectRunners();
        $this->selectProjectRunner(Project::current()->getSelectedRunner());
        return parent::show($centered);
    }

    public function reloadRunnerTypes() {
        $this->addMenu->removeAll();

        $project = Project::current();
        foreach(IdeManager::current()->getRunnerTypes() as $type) {
            if ($type->isAvailable($project)) {
                $item = new UIMenuItem();
                $item->text = $type->getName();
                $item->setIcon(ImageManager::get($type->getIcon()));

                $item->on('click', function () use ($type, $project) {
                    $project->addRunner($runner = new ProjectRunner($type, 'Unnamed', []));
                    if (sizeof($project->getRunners()) === 1)
                        $project->selectRunner($runner);

                    $this->reloadProjectRunners();
                    $this->selectProjectRunner($runner);
                });

                $this->addMenu->add($item);
            }
        }
    }

    public function updateProjectRunner(ProjectRunner $runner) {
        $this->reloadProjectRunners();
        $this->selectProjectRunner($runner);
    }

    public function selectProjectRunner(ProjectRunner $runner = null) {
        $i = 0;
        foreach (Project::current()->getRunners() as $i => $el) {
            if ($runner === $el)
                break;
        }

        if ($runner) {
            $this->runList->getElement()->selectedIndex = $i;
            $this->get('field-name')->text = $runner->getTitle();
            $this->get('field-reload')->selected = $runner->isSingleton();
        } else {
            $this->runList->getElement()->selectedIndex = -1;
        }

        /** @var UIContainer $settingsPanel */
        $settingsPanel = $this->get('settings');
        $settingsPanel->removeAll();

        if ($runner) {
            $runner->getType()->createSettingsPanel($settingsPanel);
            $runner->getType()->loadConfig($runner->getConfig(), $settingsPanel);
        }
    }

    public function reloadProjectRunners() {
        $this->runList->clear();
        foreach (Project::current()->getRunners() as $runner) {
            $this->runList->add(
                $runner->getTitle(),
                $runner->getType()->getName(),
                $runner->getType()->getIcon()
            );
        }

        /** @var StandardIdeExtension $extension */
        $extension = IdeManager::current()->getExtension(StandardIdeExtension::class);
        $extension->updateRunnerList(Project::current());
    }

    /**
     * @return ProjectRunner|null
     */
    public function getSelectedProjectRunner() {
        $i = $this->runList->getElement()->selectedIndex;
        if ($i < 0)
            return null;

        return Project::current()->getRunners()[$i];
    }

    /**
     * @return UIPopupMenu
     */
    public function getAddMenu() {
        return $this->addMenu;
    }

    /**
     * @return UIListboxDecorator
     */
    public function getRunList() {
        return $this->runList;
    }
}

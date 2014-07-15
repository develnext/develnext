<?php
namespace develnext\ide;

use develnext\filetype\creator\Creator;
use develnext\filetype\FileType;
use develnext\IDEForm;
use develnext\lang\Singleton;
use develnext\Manager;
use develnext\project\ProjectType;
use develnext\tool\Tool;
use develnext\ui\UITabHead;
use php\io\File;
use php\io\IOException;
use php\io\Stream;
use php\lang\IllegalArgumentException;
use php\lang\Process;
use php\lang\Thread;
use php\lib\str;
use php\swing\Color;
use php\swing\event\SimpleEvent;
use php\swing\Image;
use php\swing\SwingUtilities;
use php\swing\SwingWorker;
use php\swing\text\Style;
use php\swing\UIButton;
use php\swing\UIElement;
use php\swing\UILabel;
use php\swing\UIMenu;
use php\swing\UIMenuBar;
use php\swing\UIMenuItem;
use php\swing\UIPanel;
use php\swing\UIPopupMenu;
use php\swing\UIRichTextArea;
use php\swing\UITabs;
use php\swing\UITextArea;
use php\util\Scanner;

/**
 * Class IdeManager
 * @package develnext\ide
 */
class IdeManager {
    /** @var Manager */
    protected $manager;

    /** @var IDEForm */
    protected $mainForm;

    /** @var UIPopupMenu */
    protected $fileTreeMenu;

    /** @var IdeExtension[] */
    protected $extensions;

    /** @var Creator */
    protected $fileTypeCreators = [];

    /** @var Creator */
    protected $fileTypeCreatorsInMenu = [];

    /** @var IdeTool[] */
    protected $tools = [];

    /** @var IdeBackgroundTask[] */
    protected $backgroundTasks;

    /** @var callable[] */
    protected $handlers = [];

    public function __construct(Manager $manager) {
        $this->manager  = $manager;
        $this->mainForm = $this->manager->getSystemForm('MainForm.xml');

        // popup
        $this->fileTreeMenu = new UIPopupMenu();
        $this->mainForm->get('fileTree')->popupMenu = $this->fileTreeMenu;

        $this->fileTreeMenu->on('open', function(){
            /** @var UIMenu $newMenu */
            $newMenu = $this->fileTreeMenu->getComponentByGroup('new');

            $projectType = Manager::getInstance()->currentProject->getType();
            $currentFile = Manager::getInstance()->currentProject->getFileTree()->getCurrentFile();
            for($i = 0; $i < $newMenu->itemCount; $i++) {
                $item = $newMenu->getItem($i);

                /** @var Creator $creator */
                if ($item && $creator = $this->fileTypeCreatorsInMenu[$item->uid]) {
                    $isAvailable = $projectType->isAvailableFileCreator($currentFile, $creator);
                    $item->visible = $isAvailable;
                }
            }
        });
    }

    public function registerExtension(IdeExtension $extension) {
        $extension->onRegister($this);

        $this->extensions[] = $extension;
    }

    public function registerFileType(FileType $fileType) {
        $this->manager->registerFileType($fileType);
    }

    public function registerProjectType(ProjectType $projectType) {
        $this->manager->registerProjectType($projectType);
    }

    public function registerFileTypeCreator(Creator $creator, $inMenu = true) {
        $this->fileTypeCreators[] = $creator;
        if ($inMenu) {
            $item = $this->addFileTreePopupItem('new', '', $creator->getDescription(), $creator->getIcon());
            $this->fileTypeCreatorsInMenu[$item->uid] = $creator;

            $item->on('click', function() use ($creator){
                $manager = Manager::getInstance();
                $creator->open($manager->currentProject->getFileTree()->getCurrentFile());
            });
        }
    }

    public function registerIdeTool($code, IdeTool $tool) {
        $this->tools[$code] = $tool;
    }

    public function unRegisterIdeTool($code) {
        unset($this->tools[$code]);
    }

    public function openTool($code) {
        /** @var IdeTool $tool */
        $tool = $this->tools[$code];
        if ($tool == null) {
            throw new IllegalArgumentException("Ide Tool '$code' is not registered'");
        }
        // copy
        $tool = clone $tool;

        $content = $tool->createGui($this);
        /** @var UITabs $toolTabs */
        $toolTabs = $this->mainForm->get('tool-tabs');

        $tab = new UIPanel();
        $tab->add($content);

        $toolTabs->add($tab);
        $toolTabs->setTabComponentAt(
            $index = $toolTabs->tabCount - 1,
            $tabHead = new UITabHead($toolTabs, $tab, $tool->getName(), ImageManager::get($tool->getIcon()))
        );

        $tabHead->on('close', function() use ($toolTabs, $index) {
             $toolTabs->removeTabAt($index);
        });

        return $tool;
    }

    /**
     * @param int $size
     * @return UIPanel
     */
    public function addHeadMenuGap($size = 4) {
        $menu = $this->mainForm->get('headMenu');
        if ($menu) {
            $gap = new UIPanel();
            $gap->align = 'left';
            $gap->w = $size;
            $menu->add($gap);
            return $gap;
        }
    }

    public function addHeadMenuSeparator($size = 5) {
        $this->addHeadMenuGap($size);

        $menu = $this->mainForm->get('headMenu');
        if ($menu) {
            $sep = new UIPanel();
            $sep->align = 'left';
            $sep->w = 1;
            $sep->background = Color::decode('#9E9E9E');
            $menu->add($sep);
        }

        $this->addHeadMenuGap($size);
    }

    /**
     * @param UIElement $element
     */
    public function addHeadMenuAny(UIElement $element) {
        $menu = $this->mainForm->get('headMenu');
        if ($menu) {
            $element->align = 'left';
            $menu->add($element);
            $this->addHeadMenuGap(2);
        }
    }

    /**
     * @param $group
     * @param $icon
     * @param string $text
     * @return UIButton
     */
    public function addHeadMenuItem($group, $icon, $text = '') {
        $menu = $this->mainForm->get('headMenu');
        if ($menu) {
            $btn = new UIButton();
            $btn->align = 'left';
            $btn->w = 27;
            $btn->cursor = 'hand';
            $btn->group = $group;

            if ($icon)
                $btn->setIcon(ImageManager::get($icon));

            $btn->text = $text;
            $menu->add($btn);

            $gap = new UIPanel();
            $gap->align = 'left';
            $gap->w = 2;
            $menu->add($btn);
            return $btn;
        }
    }

    /**
     * @param $group
     * @param $text
     * @param null $icon
     * @return NULL|\php\swing\UIElement|UIMenu
     */
    public function addMenuGroup($group, $text, $icon = null) {
        /** @var UIMenuBar $menu */
        $menu = $this->mainForm->get('mainMenu');

        /** @var UIMenu $menu */
        $test = $menu->getComponentByGroup($group);
        if (!$test) {
            $item = new UIMenu();
            $item->group = $group;
            $item->text  = $text;
            if ($icon)
                $item->setIcon(ImageManager::get($icon));

            $menu->add($item);
            return $item;
        } else
            return $test;
    }

    /**
     * @param $group
     */
    public function addMenuSeparator($group) {
        /** @var UIMenuBar $menu */
        $menu = $this->mainForm->get('mainMenu');

        /** @var UIMenu $menu */
        $menu = $menu->getComponentByGroup($group);
        if ($menu) {
            $menu->addSeparator();
        }
    }

    /**
     * @param $group
     * @param $id
     * @param $text
     * @param null $icon
     * @param null $accelerator
     * @return null|UIMenuItem
     */
    public function addMenuItem($group, $id, $text, $icon = null, $accelerator = null) {
        /** @var UIMenuBar $menu */
        $menu = $this->mainForm->get('mainMenu');

        /** @var UIMenu $menu */
        $menu = $menu->getComponentByGroup($group);
        if ($menu) {
            $item = new UIMenuItem();
            $item->text = $text;
            $item->group = $id;
            if ($icon)
                $item->setIcon(ImageManager::get($icon));

            if ($accelerator)
                $item->accelerator = $accelerator;

            $menu->add($item);
            return $item;
        }
        return null;
    }

    public function setMenuHandlers(array $handlers) {
        /** @var UIMenuBar $menu */
        $menu = $this->mainForm->get('mainMenu');

        /** @var UIPanel $headMenu */
        $headMenu = $this->mainForm->get('headMenu');

        foreach ($handlers as $code => $handler) {
            $tmp = str::split($code, ':', 2);
            $sub = $menu->getComponentByGroup($tmp[0]);
            if ($sub instanceof UIMenu) {
                $item = $sub->getComponentByGroup($tmp[1]);
                if ($item) {
                    $item->on('click', $handler);
                }
            }

            $headItem = $headMenu->getComponentByGroup($code);
            if ($headItem) {
                $headItem->on('click', $handler);
            }

            $treePopupItem = $this->fileTreeMenu->getComponentByGroup($code);
            if ($treePopupItem) {
                $treePopupItem->on('click', $handler);
            }
        }
    }

    public function addFileTreePopupGroup($group, $text) {
        $item = new UIMenu();
        $item->text = $text;
        $item->group = $group;

        $this->fileTreeMenu->add($item);
    }

    public function addFileTreePopupSeparator($group = null) {
        /** @var UIMenu $menu */
        $menu = $this->fileTreeMenu;
        if ($group)
            $menu = $this->fileTreeMenu->getComponentByGroup($group);

        $menu->addSeparator();
    }

    public function addFileTreePopupItem($group, $id, $text, $icon = null, $accelerator = null) {
        $item = new UIMenuItem();
        $item->text = $text;
        $item->group = $id;
        if ($icon)
            $item->setIcon(ImageManager::get($icon));

        if ($accelerator)
            $item->accelerator = $accelerator;

        $menu = $this->fileTreeMenu;
        if ($group)
            $menu = $this->fileTreeMenu->getComponentByGroup($group);

        $menu->add($item);
        return $item;
    }

    public function addLocalizationPath($path) {
        try {
            Manager::getInstance()->localizator->append(
                Stream::of($path . '/messages.' .  Manager::getInstance()->localizator->getLang())
            );
        } catch (IOException $e) {

        }
    }

    public function logTool(Tool $tool, File $directory, array $commands, callable $onEnd = null) {
        $this->trigger('log-tool', [$tool, $directory, $commands, $onEnd]);
    }

    public function addBackgroundTask(IdeBackgroundTask $task) {
        $this->backgroundTasks[] = $task;
        $task->ideManager = $this;
        $task->execute();
    }

    public function on($event, callable $callback) {
        $this->handlers[$event][] = $callback;
    }

    public function trigger($event, array $args = []) {
        $handlers = (array)$this->handlers[$event];
        foreach ($handlers as $handler) {
            call_user_func_array($handler, [$this] + $args);
        }
    }

    public function cleanUpBackgroundTasks() {
        $tasks = [];
        foreach ($this->backgroundTasks as $task) {
            if (!$task->isDone() && !$task->isCanceled())
                $tasks[] = $task;
        }
        $this->backgroundTasks = $tasks;
    }

    /**
     * @return \develnext\ide\IdeBackgroundTask[]
     */
    public function getBackgroundTasks() {
        return $this->backgroundTasks;
    }

    /**
     * @param $text
     * @param null|string $icon
     */
    public function setStatusBarText($text, $icon = null) {
        /** @var UIPanel $statusBar */
        $statusBar = $this->mainForm->get('status-bar');

        /** @var UILabel $status */
        $status = $statusBar->getComponentByGroup('status');

        $status->text = $text;
        $status->setIcon(ImageManager::get($icon));
    }

    /**
     * @return IdeManager
     */
    public static function current() {
        return Manager::getInstance()->ideManager;
    }
}


<?php
namespace develnext\ide;

use develnext\filetype\creator\Creator;
use develnext\filetype\FileType;
use develnext\IDEForm;
use develnext\lang\Singleton;
use develnext\Manager;
use develnext\project\ProjectType;
use develnext\tool\Tool;
use php\io\File;
use php\io\Stream;
use php\lang\Process;
use php\lang\Thread;
use php\lib\str;
use php\swing\Color;
use php\swing\Image;
use php\swing\SwingUtilities;
use php\swing\SwingWorker;
use php\swing\UIButton;
use php\swing\UIElement;
use php\swing\UIMenu;
use php\swing\UIMenuBar;
use php\swing\UIMenuItem;
use php\swing\UIPanel;
use php\swing\UIPopupMenu;
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

    public function __construct(Manager $manager) {
        $this->manager  = $manager;
        $this->mainForm = $this->manager->getSystemForm('MainForm.xml');

        // popup
        $this->fileTreeMenu = new UIPopupMenu();
        $this->mainForm->get('fileTree')->popupMenu = $this->fileTreeMenu;
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

            $item->on('click', function() use ($creator){
                $manager = Manager::getInstance();
                $creator->open($manager->currentProject->getFileTree()->getCurrentFile());
            });
        }
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
        Manager::getInstance()->localizator->append(
            Stream::of($path . '/messages.' .  Manager::getInstance()->localizator->getLang())
        );
    }

    public function logTool(Tool $tool, File $directory, array $commands, callable $onEnd = null) {
        $console = $this->mainForm->get('console-log');
        $dir = $directory->getPath();
        $console->text = '> ' . $tool->getName() . ' ' . str::join($commands, ' ') . " (for $dir) ... \n\n";

        $this->logProcess($tool->execute($directory, $commands, false), $onEnd);
    }

    public function logProcess(Process $process, callable $onEnd = null) {
        $worker = new IdeManagerLogProcessWorker($this->mainForm->get('console-log'), $process, $onEnd);
        $worker->execute();
    }
}

class IdeManagerLogProcessWorker extends SwingWorker {
    /** @var \php\swing\UIElement */
    protected $console;

    /** @var Process */
    protected $process;

    /** @var callable */
    protected $onEnd;

    public function __construct(UIElement $console, Process $process, callable $onEnd = null) {
        $this->console = $console;
        $this->process = $process;
        $this->onEnd = $onEnd;
    }

    /**
     * @return mixed
     */
    protected function doInBackground() {
        $st = $this->process->getInput();
        $scanner = new Scanner($st);
        while ($scanner->hasNextLine()) {
            $this->publish([$scanner->nextLine()]);
        }

        $err = $this->process->getError();
        $scanner2 = new Scanner($err);
        while ($scanner2->hasNextLine()) {
            $this->publish([$scanner2->nextLine()]);
        }

        $this->publish([]);
    }

    protected function process(array $values) {
        foreach ($values as $value)
            $this->console->text .= $value . "\n";

        if (!$values && $this->onEnd)
            call_user_func($this->onEnd);
    }
}

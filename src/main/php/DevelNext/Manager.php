<?php
namespace develnext;

use develnext\localization\Localizator;
use DevelNext\swing\ComponentMover;
use DevelNext\swing\ComponentResizer;
use DevelNext\swing\DesignContainer;
use develnext\util\Config;
use php\io\File;
use php\io\FileStream;
use php\io\Stream;
use php\lang\System;
use php\lib\str;
use php\swing\docking\CControl;
use php\swing\docking\CGrid;
use php\swing\docking\SingleCDockable;
use php\swing\UIButton;
use php\swing\UIDialog;
use php\swing\UIElement;
use php\swing\UIReader;
use php\swing\UIWindow;

class Manager {

    /** @var UIReader */
    protected $uiReader;

    /** @var Localizator */
    protected $localizator;

    /** @var UIWindow[] */
    protected $forms;

    /** @var File */
    protected $userDirectory;

    /** @var File */
    protected $settingsDirectory;

    /** @var Config */
    public $config;

    public function __construct() {
        $this->uiReader = new UIReader();
        $this->localizator = new Localizator();

        $this->uiReader->onTranslate($this->localizator);

        $this->userDirectory = new File(System::getProperty('user.home', './'));
        $this->settingsDirectory = new File($this->userDirectory->getPath() . '/DevelNext_A1');

        if (!$this->settingsDirectory->exists()) {
            $this->settingsDirectory->mkdirs();
        }

        $this->config = $this->getConfig('application');

        dump($this->config->getKeys());
    }

    public function __destruct() {
        $this->config->save();
    }

    public function showSplash() {
        $form = $this->getSystemForm('SplashForm.xml');
        $form->getComponent(0)->on('click', function() use ($form) {
            $form->visible = false;
        });
        $form->moveToCenter();
        $form->visible = true;
    }

    public function hideSplash() {
        $this->getSystemForm('SplashForm.xml')->visible = false;
    }

    /**
     * @param string $path
     * @param null $vars
     * @return UIWindow
     */
    public function getSystemForm($path, &$vars = NULL) {
        if ($form = $this->forms[str::lower($path)])
            return $form;

        if ($vars == null)
            $this->uiReader->onRead(null);
        else {
            $this->uiReader->onRead(function(UIElement $e, $var) use (&$vars) {
                $vars[$var] = $e;
            });
        }

        $form = $this->uiReader->read(Stream::of('res://forms/' . $path));
        $this->forms[ str::lower($path) ] = $form;
        return $form;
    }

    /**
     * @param $name
     * @return Config
     */
    public function getConfig($name) {
        $stream = new FileStream($this->settingsDirectory->getPath() . "/$name.conf", "r+");
        return new Config($stream);
    }

    public function start() {
        $c = [];
        $form = $this->getSystemForm('MainForm.xml', $c);

        $control = new CControl($form);
        $contentArea = $control->getContentArea();

        $c['content']->add($contentArea);
        //$work = $control->createWorkingArea('work');


        $grid = new CGrid($control);
        $grid->add(1, 1, 3, 3, $dContent = new SingleCDockable('content', 'content', $c['area']));
        $grid->add(0, 0, 1, 4, $one = new SingleCDockable('editor', 'editor', $c['editor']));
        $grid->add(1, 3, 3, 1, new SingleCDockable('console', 'console', $c['console']));

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

        $form->moveToCenter();
        $form->visible = true;
    }
}

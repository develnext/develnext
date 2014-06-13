<?php
namespace develnext;

use develnext\lang\Singleton;
use develnext\localization\Localizator;
use develnext\project\ProjectManager;
use develnext\project\type\GuiProjectType;
use develnext\util\Config;
use php\io\File;
use php\io\FileStream;
use php\io\IOException;
use php\io\Stream;
use php\lang\System;
use php\lib\str;
use php\swing\UIDialog;
use php\swing\UIElement;
use php\swing\UIReader;

class Manager {

    use Singleton;

    /** @var UIReader */
    protected $uiReader;

    /** @var Localizator */
    protected $localizator;

    /** @var IDEForm[] */
    protected $forms;

    /** @var File */
    protected $userDirectory;

    /** @var File */
    protected $settingsDirectory;

    /** @var Config */
    public $config;

    /** @var ProjectManager */
    public $projectManager;

    protected function __construct() {
        $this->uiReader = new UIReader();
        $this->localizator = new Localizator();

        $this->uiReader->onTranslate($this->localizator);

        $this->userDirectory = new File(System::getProperty('user.home', './'));
        $this->settingsDirectory = new File($this->userDirectory->getPath() . '/DevelNext_A1');

        if (!$this->settingsDirectory->exists()) {
            $this->settingsDirectory->mkdirs();
        }

        $this->config = $this->getConfig('application');
        $this->projectManager = ProjectManager::getInstance();
    }

    public function __destruct() {
        $this->config->save();
    }

    public function showSplash() {
        $form = $this->getSystemForm('SplashForm.xml');
        $form = $form->getWindow();

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
     * @return IDEForm
     */
    public function getSystemForm($path) {
        if ($form = $this->forms[str::lower($path)])
            return $form;

        $vars = array();
        $this->uiReader->onRead(function(UIElement $e, $var) use (&$vars) {
            $vars[$var] = $e;
        });

        $window = $this->uiReader->read(Stream::of('res://forms/' . $path));
        try {
            $form = new IDEForm($window, Stream::of('res://forms/' . $path . '.php'), $vars);
        } catch (IOException $e) {
            $form = new IDEForm($window, null, $vars);
        }

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

    /**
     * @param $fileName
     * @return File
     */
    public function getConfigFile($fileName) {
        return new File($this->settingsDirectory->getPath() . $fileName);
    }

    public function start() {
        $form = $this->getSystemForm('MainForm.xml');

        //$project = $this->projectManager->createProject(new GuiProjectType(), new File("d:/gui_project/"));

        $form->getWindow()->moveToCenter();
        $form->getWindow()->visible = true;
    }
}

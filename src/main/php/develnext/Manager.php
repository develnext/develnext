<?php
namespace develnext;

use develnext\filetype\FileType;
use develnext\i18n\Localizator;
use develnext\ide\IdeManager;
use develnext\ide\std\StandardIdeExtension;
use develnext\lang\Singleton;
use develnext\project\Project;
use develnext\project\ProjectLoader;
use develnext\project\ProjectManager;
use develnext\project\ProjectType;
use develnext\ui\decorator\UIListboxDecorator;
use develnext\util\Config;
use php\io\File;
use php\io\FileStream;
use php\io\IOException;
use php\io\ResourceStream;
use php\io\Stream;
use php\lang\Module;
use php\lang\System;
use php\lib\items;
use php\lib\str;
use php\swing\SwingUtilities;
use php\swing\Timer;
use php\swing\UIDialog;
use php\swing\UIElement;
use php\swing\UIListbox;
use php\swing\UIReader;
use php\util\Flow;
use php\util\Scanner;

class Manager {
    use Singleton;

    /** @var UIReader */
    protected $uiReader;

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

    /** @var Localizator */
    public $localizator;

    /** @var FileType[] */
    protected $fileTypes;

    /** @var ProjectType[] */
    protected $projectTypes;

    /** @var Project[] */
    protected $latestProjects;

    /** @var IdeManager */
    public $ideManager;

    /** @var Project */
    public $currentProject;

    protected function init() {
        $this->uiReader = new UIReader();

        $this->userDirectory = new File(System::getProperty('user.home', './'));
        $this->settingsDirectory = new File($this->userDirectory->getPath() . '/DevelNext_A1');

        if (!$this->settingsDirectory->exists()) {
            $this->settingsDirectory->mkdirs();
        }

        $this->config = $this->getConfig('application');

        // localization
        $this->localizator = new Localizator($this->config->get('lang', 'en'));

        $this->localizator->append(
            Stream::of(\ROOT . '/system/languages/messages.' . $this->localizator->getLang())
        );

        foreach (ResourceStream::getResources('i18n/messages.' . $this->localizator->getLang()) as $resource) {
            $this->localizator->append($resource);
        }

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

        $timer = new Timer(3000, function() use ($form) {
             $form->visible = false;
        });
        $timer->repeat = false;
        $timer->start();
    }

    public function hideSplash() {
        $this->getSystemForm('SplashForm.xml')->hide();
    }

    /**
     * @param string $path
     * @param bool $cached
     * @return IDEForm
     */
    public function getSystemForm($path, $cached = true) {
        if ($cached && $form = $this->forms[str::lower($path)])
            return $form;

        $vars = array();
        $this->uiReader->onRead(function(UIElement $e, $var) use (&$vars) {
            $vars[$var] = $e;
        });

        $this->uiReader->onTranslate(function(UIElement $e, $text) {
            return $this->localizator->translate($text);
        });

        $window = $this->uiReader->read(Stream::of('res://forms/' . $path));
        try {
            $form = new IDEForm($window, new Module(Stream::of('res://develnext/forms/' . $path . '.php')), $vars);
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
        $file = new File($this->settingsDirectory->getPath() . "/$name.conf");
        if (!$file->exists())
            $file->createNewFile();

        $stream = new FileStream($file, "r+");
        return new Config($stream);
    }

    /**
     * @param $fileName
     * @return File
     */
    public function getConfigFile($fileName) {
        return new File($this->settingsDirectory->getPath() .'/'. $fileName);
    }

    public function registerFileType(FileType $fileType) {
        $this->fileTypes[] = $fileType;
    }

    public function registerProjectType(ProjectType $projectType) {
        $this->projectTypes[] = $projectType;
    }

    /**
     * @return \develnext\project\ProjectType[]
     */
    public function getProjectTypes() {
        return $this->projectTypes;
    }


    /**
     * @param File $file
     * @param Project $project
     * @return FileType|null
     */
    public function getFileTypeOf(File $file, Project $project = null) {
        $result = null;
        foreach ($this->fileTypes as $type) {
            if ($type->onDetect($file, $project))
                $result = $type;
        }
        return $result;
    }

    public function flash($text, $max = 0.7, $delay = 500) {
        $screenSize = SwingUtilities::getScreenSize();

        $status = $this->getSystemForm('misc/StatusBar.xml');
        $status->get('message')->text = $text;

        $status->getWindow()->w = $screenSize[0];
        $status->getWindow()->position = [0, 0];
        $status->getWindow()->opacity = 0;

        $timer = null;
        $tick = (100 * 30 / $delay) / 100;

        $timer = new Timer(30, function(Timer $timer) use ($status, $tick, $max) {
            if ($status->getWindow()->opacity >= $max) {
                /** @var Timer $timer */
                $timer->stop();
            } else {
                $status->getWindow()->opacity += $tick;
            }
        });
        $timer->repeat = true;
        $timer->start();
        $status->show(false);

        $tm = new Timer(4000, function() use ($status) {
             $status->hide();
        });
        $tm->repeat = false;
        $tm->start();
    }

    protected function linkGuiToProject(Project $project) {
        $form = $this->getSystemForm('MainForm.xml');
        $project->setGuiElements($form->get('area'), $form->get('fileTree'));
    }

    public function createProject($name, File $directory, ProjectType $projectType) {
        $this->closeProject();

        $project = $this->projectManager->createProject($projectType, $directory);
        $project->setName($name);
        $this->linkGuiToProject($project);

        $project->saveAll();
        $project->updateTree();

        $this->currentProject = $project;
        $this->addToLatest($project);
        return $project;
    }

    public function openProject(File $directory) {
        $loader = new ProjectLoader();
        $project = $loader->load($directory);
        if (!$project)
            return null;

        $this->currentProject = $project;
        $this->linkGuiToProject($project);

        $project->saveAll();
        $project->updateTree();

        $this->addToLatest($project);
        return $project;
    }

    /**
     * @param $index
     * @return Project
     */
    public function getLatestProject($index) {
        return $this->latestProjects[$index];
    }

    protected function addToLatest(Project $project) {
        foreach ($this->latestProjects as $index => $file) {
            if ($file->getDirectory()->getPath() === $project->getDirectory()->getPath()) {
                break;
            }
            $index = null;
        }
        if ($index !== null) {
            unset($this->latestProjects[$index]);
        }

        $this->latestProjects = Flow::of([$project])->append($this->latestProjects)->limit(15)->toArray();
        $this->saveIdeConfigurations();
    }

    public function closeProject() {
        if ($this->currentProject)
            $this->currentProject->close();

        $this->currentProject = null;
    }

    protected function loadExtensions() {
        $st = null;
        try {
            $list = ResourceStream::getResources('DEVELNEXT-INF/extensions.list');
            foreach ($list as $st) {
                $reader = new Scanner($st);
                while ($reader->hasNextLine()) {
                    $ext = str::trim($reader->nextLine());
                    if ($ext) {
                        $class = new \ReflectionClass($ext);
                        $this->ideManager->registerExtension($class->newInstance());
                    }
                }

                $st->close();
            }
        } catch (IOException $e) {
            if ($st)
                $st->close();
            throw $e;
        }
    }

    public function showWelcome() {
        $form = $this->getSystemForm('MainForm.xml');

        if (!$this->currentProject) {
            $welcome = $this->getSystemForm('account/Welcome.xml');
            $welcome->modalResult = false;

            /** @var UIListbox $list */
            $list = $welcome->get('list-latest-projects');
            $listDecor = new UIListboxDecorator($list);
            $listDecor->clear();

            foreach($this->latestProjects as $project) {
                $listDecor->add(
                    $project->getName(), $project->getDirectory()->getPath(), $project->getType()->getBigIcon()
                );
            }

            if (!$welcome->showModal())
                System::halt(0);
        }
        $form->show();
    }

    public function start() {
        $this->ideManager = new IdeManager($this);
        $this->loadExtensions();
        $this->loadIdeConfigurations();

        $this->showWelcome();
    }

    private function loadIdeConfigurations() {
        $this->latestProjects = [];

        $loader = new ProjectLoader();
        $file = $this->getConfigFile('latest_projects.list');
        if ($file->exists()) {
            $sc = new Scanner($fs = new FileStream($file));
            while ($sc->hasNextLine()) {
                $el = new File(str::trim($sc->nextLine()));
                if ($el->exists()) {
                    $pr = $loader->load($el);
                    if ($pr)
                        $this->latestProjects[] = $pr;
                }
            }

            $fs->close();
        }
    }

    private function saveIdeConfigurations() {
        $file = $this->getConfigFile('latest_projects.list');
        $st = new FileStream($file, 'w+');

        foreach($this->latestProjects as $el) {
            $st->write($el->getDirectory()->getPath() . "\n");
        }

        $st->close();
    }
}

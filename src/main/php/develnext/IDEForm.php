<?php
namespace develnext;

use develnext\util\Config;
use php\io\File;
use php\io\FileStream;
use php\lang\Module;
use php\swing\Color;
use php\swing\UIButton;
use php\swing\UIElement;
use php\swing\UIForm;
use php\swing\UIPanel;
use php\swing\UIWindow;

/**
 * Class IDEForm
 * @package develnext
 */
class IDEForm {

    /** @var UIWindow */
    protected $window;

    /** @var Module */
    protected $module;

    /** @var UIElement */
    protected $vars;

    /** @var mixed */
    public $modalResult;

    function __construct(UIWindow $window, Module $module = null, array $vars = array()) {
        $this->window = $window;
        $this->vars = $vars;
        if ($module) {
            $this->module = $module;
            $module->call(['form' => $this]);
        }
    }

    /**
     * @return \php\lang\Module
     */
    public function getModule() {
        return $this->module;
    }

    /**
     * @return \php\swing\UIWindow
     */
    public function getWindow() {
        return $this->window;
    }

    /**
     * @param $name
     * @return UIElement|null
     */
    public function get($name) {
        return $this->vars[$name];
    }

    public function show($centered = true) {
        if ($centered)
            $this->window->moveToCenter();

        $this->window->visible = true;
        return $this->modalResult;
    }

    public function hide($modalResult = null) {
        $this->window->visible = false;
        $this->modalResult = $modalResult;
    }

    public function showModal($centered = true) {
        $this->window->modalType = 'APPLICATION_MODAL';
        $this->show($centered);
        return $this->modalResult;
    }

    public function saveToFile(File $file) {
        $config = new Config($stream = new FileStream($file, 'w+'));
        $maximized = false;
        if ($this->window instanceof UIForm)
            $maximized = $this->window->maximized;

        if (!$maximized) {
            $config->set('w', $this->window->w);
            $config->set('h', $this->window->h);
            $config->set('x', $this->window->x);
            $config->set('y', $this->window->y);
        }

        if ($this->window instanceof UIForm) {
            $config->set('maximized', $this->window->maximized);
        }

        $config->save();
        $stream->close();
    }

    public function loadFromFile(File $file) {
        if ($file->exists()) {
            $config = new Config($stream = new FileStream($file->getPath(), 'r'));

            if ($this->window instanceof UIForm) {
                $this->window->maximized = $config->get('maximized', $this->window->maximized);
            }

            $this->window->w = $config->get('w', $this->window->w);
            $this->window->h = $config->get('h', $this->window->h);
            $this->window->x = $config->get('x', $this->window->x);
            $this->window->y = $config->get('y', $this->window->y);


            $stream->close();
        }
    }
}

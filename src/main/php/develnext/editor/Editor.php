<?php
namespace develnext\editor;

use develnext\lang\Singleton;
use develnext\project\Project;
use php\io\File;
use php\swing\UIContainer;
use php\swing\UIElement;

/**
 * Class Editor
 * @package develnext\editor
 */
abstract class Editor {
    /** @var File */
    protected $file;

    /** @var Project */
    protected $project;

    public function __construct(UIContainer $container, File $file = null, Project $project = null) {
        $this->file    = $file;
        $this->project = $project;
        $this->onCreate($container);
    }

    /**
     * @return UIElement
     */
    final function doCreate() {
        return $this->onCreate();
    }

    final function doLoad() {
        $this->onLoad();
    }

    final function doSave() {
        $this->onSave();
    }

    final function doDestroy() {
        $this->onDestroy();
    }

    abstract protected function onCreate();
    protected function onDestroy() { }

    abstract protected function onLoad();
    abstract protected function onSave();

    public function canRedo() { return false; }
    public function canUndo() { return false; }

    protected function onRedo() { }
    protected function onUndo() { }
}

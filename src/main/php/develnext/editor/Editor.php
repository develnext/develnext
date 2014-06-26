<?php
namespace develnext\editor;

use develnext\lang\Singleton;
use develnext\project\EditorManager;
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

    /** @var EditorManager */
    protected $manager;

    /** @var bool */
    protected $notSaved = false;

    /** @var callable */
    private $changeCallback = null;

    public function __construct(File $file = null, EditorManager $manager = null) {
        $this->file    = $file;
        $this->manager = $manager;
    }

    /**
     * @return UIElement
     */
    final function doCreate() {
        return $this->onCreate();
    }

    final function doLoad() {
        $this->onLoad();
        $this->doChange(false);
    }

    final function doSave() {
        $this->onSave();
        $this->doChange(false);
    }

    final function doDestroy() {
        $this->onDestroy();
    }

    final public function doChange($notSaved = true) {
        $this->notSaved = $notSaved;
        if ($this->changeCallback) {
            call_user_func($this->changeCallback, $this);
        }
    }

    final public function onChange(callable $callback) {
        $this->changeCallback = $callback;
    }

    abstract protected function onCreate();
    protected function onDestroy() { }

    abstract protected function onLoad();
    abstract protected function onSave();

    public function canRedo() { return false; }
    public function canUndo() { return false; }

    protected function onRedo() { }
    protected function onUndo() { }

    /**
     * @return boolean
     */
    public function isNotSaved() {
        return $this->notSaved;
    }


}

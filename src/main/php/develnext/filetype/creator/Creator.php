<?php
namespace develnext\filetype\creator;

use develnext\IDEForm;
use develnext\Manager;
use develnext\project\Project;
use develnext\project\ProjectFile;
use php\io\File;

/**
 * Class Creator
 * @package develnext\filetype\creator
 */
abstract class Creator {
    /** @var IDEForm */
    protected $form;

    /** @var ProjectFile */
    protected $parent;

    public function __construct($formName) {
        $this->form = Manager::getInstance()->getSystemForm($formName, false);
        $this->form->get('btn-ok')->on('click', function() {
            $root = $this->parent->getFile();
            if ($root->isFile())
                $root = $root->getParentFile();

            $this->form->hide($this->onDone($root, $this->parent->getProject()));
        });
        $this->form->get('btn-cancel')->on('click', function() {
            $this->form->hide(null);
        });
    }

    public function open(ProjectFile $parent) {
        $this->parent = $parent;
        $this->onOpen($parent);

        $this->form->showModal();
        if ($this->form->modalResult instanceof ProjectFile) {
            $project = $parent->getProject();
            $project->updateFile($this->form->modalResult);
            $project->openFile($this->form->modalResult);
        }
    }

    function getIcon() {
        return null;
    }


    function onOpen(ProjectFile $parent) {

    }

    /**
     * @param \php\io\File $root
     * @param \develnext\project\Project $project
     * @return ProjectFile
     */
    abstract function onDone(File $root, Project $project);

    abstract function getDescription();
}

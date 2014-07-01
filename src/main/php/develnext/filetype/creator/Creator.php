<?php
namespace develnext\filetype\creator;

use develnext\IDEForm;
use develnext\Manager;
use develnext\project\ProjectFile;

/**
 * Class Creator
 * @package develnext\filetype\creator
 */
abstract class Creator {
    /** @var ProjectFile */
    protected $parent;

    /** @var IDEForm */
    protected $form;

    public function __construct(ProjectFile $parent, $formName) {
        $this->parent = $parent;
        $this->form   = Manager::getInstance()->getSystemForm($formName);
    }

    public function open() {
        $this->form->showModal();
        if ($this->form->modalResult instanceof ProjectFile) {
            $project = $this->parent->getProject();
            $project->updateTree();
            $project->openFile($this->form->modalResult);
        }
    }
}

<?php
namespace develnext\ide\std\dialog;

use develnext\IDEForm;
use develnext\Manager;

class RunConfigurationDialog {
    /** @var IDEForm */
    protected $form;

    function __construct() {
        $this->form = Manager::getInstance()->getSystemForm('ide/std/project/RunConfigurations.xml');
    }

    public function show() {
        $this->form->showModal();
    }
}

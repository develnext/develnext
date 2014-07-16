<?php
namespace develnext\ide;

/**
 * Class IdeSettingsManager
 * @package develnext\ide
 */
class IdeSettingsManager {
    /** @var IdeManager */
    protected $ideManager;

    function __construct(IdeManager $ideManager) {
        $this->ideManager = $ideManager;
    }

    public function openDialog() {
        static $form;
        if (!$form)
            $form = $this->ideManager->getForm('Settings.xml');

        $form->showModal();
    }
}

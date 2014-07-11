<?php
namespace develnext\project;

use php\swing\UIContainer;

abstract class RunnerType {

    abstract public function execute();

    /**
     * @return UIContainer
     */
    abstract public function createSettingsPanel();

    /**
     * @param array $config
     * @param UIContainer $settingsPanel
     * @return mixed
     */
    abstract public function loadConfig(array $config, UIContainer $settingsPanel);

    /**
     * @param UIContainer $settingsPanel
     * @return array
     */
    abstract public function fetchConfig(UIContainer $settingsPanel);

    public function getIcon() {
        return 'images/icons/play16.png';
    }
}

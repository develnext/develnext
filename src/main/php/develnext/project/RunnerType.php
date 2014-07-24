<?php
namespace develnext\project;

use php\swing\UIContainer;

/**
 * Class RunnerType
 * @package develnext\project
 */
abstract class RunnerType {

    /**
     * @return string
     */
    abstract public function getName();

    abstract public function execute(ProjectRunner $runner);

    public function stop(ProjectRunner $runner) {
        if ($runner->getTool())
            $runner->getTool()->stop();
    }

    /**
     * @param UIContainer $settingsPanel
     * @return mixed
     */
    abstract public function createSettingsPanel(UIContainer $settingsPanel);

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

    public function isAvailable(Project $project) {
        return true;
    }
}

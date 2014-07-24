<?php
namespace develnext\ide\std\project\runner;

use develnext\ide\IdeManager;
use develnext\ide\ImageManager;
use develnext\ide\std\ide\ConsoleIdeTool;
use develnext\ide\std\project\type\JVMProjectType;
use develnext\Manager;
use develnext\project\Project;
use develnext\project\ProjectRunner;
use develnext\project\RunnerType;
use develnext\tool\GradleTool;
use php\lib\items;
use php\swing\UICheckbox;
use php\swing\UICombobox;
use php\swing\UIContainer;
use php\swing\UIEdit;
use php\swing\UILabel;
use php\swing\UIListbox;
use php\swing\UIPanel;
use php\util\Flow;

class GradleLauncherRunnerType extends RunnerType {
    protected static $commands = ['run' => 'run', 'distZip' => 'distZip'];

    /**
     * @return string
     */
    public function getName() {
        return 'Gradle Launcher';
    }

    public function execute(ProjectRunner $runner) {
        $runner->setDone(false);

        $manager = Manager::getInstance();
        $gradle = new GradleTool();

        /** @var ConsoleIdeTool $console */
        $console = IdeManager::current()->openTool(
            'console', $runner->getTitle(), ImageManager::get($runner->getType()->getIcon())
        );

        $console->logTool(
            $gradle,
            $manager->currentProject->getDirectory(),
            [$runner->getConfig()['command']],
            function() use ($runner) {
                $runner->setDone(true);
            }
        );
        $runner->setTool($gradle);
    }

    /**
     * @param UIContainer $settingsPanel
     * @return mixed
     */
    public function createSettingsPanel(UIContainer $settingsPanel) {
        $label = new UILabel();
        $label->align = 'top';
        $label->h = 25;
        $label->text = __('{Command}:');

        $edit = new UICombobox();
        $edit->align = 'top';
        $edit->group = 'command';
        $edit->readOnly = false;
        $edit->setItems(self::$commands);
        $edit->h = 30;

        $settingsPanel->add($label);
        $settingsPanel->add($edit);

        $hr = new UIPanel();
        $hr->align = 'top';
        $hr->h = 10;
        $hr->background = [0,0,0,0];
        $settingsPanel->add($hr);

        $showBuildDialog = new UICheckbox();
        $showBuildDialog->align = 'top';
        $showBuildDialog->text = _('Show dialog after building');
        $showBuildDialog->h = 25;
        $showBuildDialog->group = 'show-dialog-after-building';
        $settingsPanel->add($showBuildDialog);
    }

    /**
     * @param array $config
     * @param UIContainer $settingsPanel
     * @return mixed
     */
    public function loadConfig(array $config, UIContainer $settingsPanel) {
        /** @var UIListbox $edit */
        $edit = $settingsPanel->getComponentByGroup('command');

        if (self::$commands[$config['command']]) {
            $i = 0;
            foreach (self::$commands as $key => $value) {
                if ($key === $config['command'])
                    break;
                $i++;
            }

            $edit->selectedIndex = $i;
        }

        /** @var UICheckbox $showDialog */
        $showDialog = $settingsPanel->getComponentByGroup('show-dialog-after-building');
        $showDialog->selected = $config['show_dialog_after_building'];
    }

    /**
     * @param UIContainer $settingsPanel
     * @return array
     */
    public function fetchConfig(UIContainer $settingsPanel) {
        /** @var UIListbox $edit */
        $edit = $settingsPanel->getComponentByGroup('command');

        /** @var UICheckbox $showDialog */
        $showDialog = $settingsPanel->getComponentByGroup('show-dialog-after-building');
        return [
            'command' => Flow::of(self::$commands)->skip($edit->selectedIndex)->current(),
            'show_dialog_after_building' => $showDialog->selected
        ];
    }

    public function isAvailable(Project $project) {
        return ($project->getType() instanceof JVMProjectType);
    }
}

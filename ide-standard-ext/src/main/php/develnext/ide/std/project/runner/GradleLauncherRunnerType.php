<?php
namespace develnext\ide\std\project\runner;

use develnext\ide\components\UIMessages;
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
use php\lib\str;
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
        /** @var ConsoleIdeTool $console */
        if ($runner->isSingleton()) {
            $console = $runner->getUserData();
        }
        $runner->setDone(false);

        if (!$console) {
            $manager = Manager::getInstance();
            $gradle = new GradleTool();
            $console = IdeManager::current()->openTool(
                'console', $runner->getTitle(), ImageManager::get($runner->getType()->getIcon())
            );

            if ($runner->getConfig()['command'] === 'distZip') {
                $console->getUiTabHead()->setIcon(ImageManager::get('images/icons/lorry_box16.png'));
            }

            // buttons
            $console->addButton('run', ImageManager::get('images/icons/play16.png'));
            $console->addSeparator();
            $console->addButton('stop', ImageManager::get('images/icons/stop16.png'));
            $console->addButton('restart', ImageManager::get('images/icons/arrow_refresh16.png'));

            $console->on('log', function (ConsoleIdeTool $self) {
                $self->getButton('run')->enabled = false;
                $self->getButton('stop')->enabled = true;
                $self->getButton('restart')->enabled = true;
            });
            $console->on('finish', function (ConsoleIdeTool $self) use ($runner) {
                $self->getButton('run')->enabled = true;
                $self->getButton('stop')->enabled = false;
                $self->getButton('restart')->enabled = false;
                $runner->setDone(true);
            });

            $onRun = function () use ($console, $gradle, $manager, $runner) {
                $args = items::toList(
                    $runner->getConfig()['command'],
                    str::split($runner->getConfig()['program_arguments'], ' ')
                );
                $console->logTool(
                    $gradle,
                    $manager->currentProject->getDirectory(),
                    $args
                );
            };
            $console->on('btn-run', $onRun);

            $console->on('close', function (ConsoleIdeTool $self) use ($runner) {
                $runner->setUserData(null);
                return true;
            });

            $console->on('btn-stop', function() use ($console, $gradle) {
                $console->appendText('Stopping... ', 'err-b');
                $gradle->stop();
            });

            $runner->setUserData($console);
        }

        $console->trigger('btn-run');
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

        $label = new UILabel();
        $label->align = 'top';
        $label->h = 25;
        $label->text = __('{Program arguments}:');

        $edit = new UIEdit();
        $edit->align = 'top';
        $edit->group = 'program-arguments';
        $edit->h = 30;

        $settingsPanel->add($label);
        $settingsPanel->add($edit);

        $showBuildDialog = new UICheckbox();
        $showBuildDialog->align = 'top';
        $showBuildDialog->text = _('Show dialog after building');
        $showBuildDialog->h = 35;
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

        /** @var UIEdit $programArguments */
        $programArguments = $settingsPanel->getComponentByGroup('program-arguments');
        $programArguments->text = $config['program_arguments'];

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

        /** @var UIEdit $programArguments */
        $programArguments = $settingsPanel->getComponentByGroup('program-arguments');
        return [
            'command' => Flow::of(self::$commands)->skip($edit->selectedIndex)->current(),
            'show_dialog_after_building' => $showDialog->selected,
            'program_arguments' => $programArguments->text
        ];
    }

    public function isAvailable(Project $project) {
        return ($project->getType() instanceof JVMProjectType);
    }
}

<?php
namespace develnext\ide\std;

use develnext\ide\components\UIDirectoryChooser;
use develnext\ide\std\dialog\RunConfigurationDialog;
use develnext\Manager;
use develnext\project\Project;
use develnext\project\ProjectFile;
use develnext\tool\GradleTool;
use develnext\tool\JavaTool;
use php\io\File;
use php\io\FileStream;
use php\lang\System;
use php\lib\str;
use php\swing\event\ComponentEvent;
use php\swing\UIDialog;

/**
 * Class StandardIdeMenuHandlers
 * @package develnext\ide\std
 */
class StandardIdeMenuHandlers {
    /** @var array */
    protected $handlers = [];

    public function __construct() {
        $elements = [
            'file:open-project', 'file:new-project', 'file:close-project', 'file:save-all', 'file:exit',
            'edit:undo', 'edit:redo', 'edit:delete', 'edit:copy-files',
            'build:run', 'build:run-configurations'
        ];

        foreach ($elements as $el) {
            $handler = str::replace($el, ':', '_');
            $handler = str::replace($handler, '-', '');

            $this->handlers[$el] = [$this, $handler];
        }
    }

    public function file_openProject() {

    }

    public function file_newProject() {
        $manager = Manager::getInstance();
        $manager->getSystemForm('project/NewProject.xml')->showModal();
    }

    public function file_closeProject() {
        $manager = Manager::getInstance();
        $manager->closeProject();

        $manager->getSystemForm('MainForm.xml')->hide();
        $manager->showWelcome();
    }

    public function file_saveAll() {
        $manager = Manager::getInstance();
        $manager->currentProject->saveAll();
    }

    public function file_exit() {

    }

    public function edit_undo() {

    }

    public function edit_redo() {

    }

    public function edit_copyFiles() {
        $dialog = new UIDirectoryChooser('add_copy');
        $dialog->setOnlyDirectories(false);
        $dialog->showDialog();

        if ($files = $dialog->getSelectedFiles()) {
            $folder = Manager::getInstance()->currentProject->getFileTree()->getCurrentFile();
            $folder = $folder->getFile();
            if ($folder->isFile()) {
                $folder = $folder->getParentFile();
            }

            $newFiles = [];
            foreach ($files as $file) {
                if ($file->isFile()) {
                    $fs = new FileStream($file);
                    $fs2 = new FileStream($newFiles[] = new File($folder, $file->getName()), 'w+');
                    $fs2->write($fs->readFully());

                    $fs->close();
                    $fs2->close();
                }
            }

            foreach ($newFiles as $file) {
                Manager::getInstance()->currentProject->updateFile(
                    new ProjectFile($file, Manager::getInstance()->currentProject)
                );
            }
        }
    }

    public function edit_delete() {
        $manager = Manager::getInstance();
        if (UIDialog::confirm(_('Are you sure?'), _('Question'), UIDialog::YES_NO_OPTION) == UIDialog::YES_OPTION) {
            $files = $manager->currentProject->getFileTree()->getSelectedFiles();
            $deleted = [];
            foreach ($files as $file) {
                if ($file->delete()) {
                    $deleted[] = $file;
                }
            }

            foreach ($deleted as $file) {
                $manager->currentProject->updateFile($file);
            }

        }
    }

    public function build_runConfigurations() {
        $dialog = new RunConfigurationDialog();
        $dialog->show();
    }

    public function build_run() {
        $runner = Project::current()->getSelectedRunner();
        if (!$runner->isDone()) {
            $runner->stop();
        }

        $runner->execute();
    }

    /**
     * @return array
     */
    public function getHandlers() {
        return $this->handlers;
    }
}

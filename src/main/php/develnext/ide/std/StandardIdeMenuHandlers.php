<?php
namespace develnext\ide\std;
use develnext\Manager;
use develnext\tool\GradleTool;
use develnext\tool\JavaTool;
use php\lib\str;

/**
 * Class StandardIdeMenuHandlers
 * @package develnext\ide\std
 */
class StandardIdeMenuHandlers {
    /** @var array */
    protected $handlers = [];

    public function __construct() {
        $elements = [
            'file:open-project', 'file:new-project', 'file:save-all', 'file:exit',
            'edit:undo', 'edit:redo',
            'build:run'
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

    public function build_run() {
        $manager = Manager::getInstance();

        $jre = new GradleTool();
        $manager->ideManager->logProcess($jre->execute(
            $manager->currentProject->getDirectory(), ['jar'], false
        ), function() use ($manager) {
            $pr = $manager->currentProject;
            $java = new JavaTool();
            $java->execute(null, [
                '-jar',
                $pr->getDirectory() . "/build/libs/" . $pr->getName() . "-1.0.jar"
            ]);
        });
    }

    /**
     * @return array
     */
    public function getHandlers() {
        return $this->handlers;
    }
}

<?php
namespace develnext\ide;

use develnext\filetype\FileType;
use develnext\IDEForm;
use develnext\lang\Singleton;
use develnext\Manager;
use develnext\project\ProjectType;
use php\io\Stream;
use php\swing\Image;
use php\swing\UIButton;
use php\swing\UIPanel;

class IdeManager {

    /** @var Manager */
    protected $manager;

    /** @var IDEForm */
    protected $mainForm;

    /** @var IdeExtension[] */
    protected $extensions;

    public function __construct(Manager $manager) {
        $this->manager  = $manager;
        $this->mainForm = $this->manager->getSystemForm('MainForm.xml');
    }

    public function registerExtension(IdeExtension $extension) {
        $extension->onRegister($this);

        $this->extensions[] = $extension;
    }

    public function registerFileType(FileType $fileType) {
        $this->manager->registerFileType($fileType);
    }

    public function registerProjectType(ProjectType $projectType) {
        $this->manager->registerProjectType($projectType);
    }

    /**
     * @param $icon
     * @param string $text
     * @return UIButton
     */
    public function addHeadMenuItem($icon, $text = '') {
        $menu = $this->mainForm->get('headMenu');
        if ($menu) {
            $btn = new UIButton();
            $btn->align = 'left';
            $btn->w = 27;

            if ($icon)
                $btn->setIcon(Image::read(Stream::of($icon)));

            $btn->text = $text;
            $menu->add($btn);

            $gap = new UIPanel();
            $gap->align = 'left';
            $gap->w = 2;
            $menu->add($btn);
            return $btn;
        }
    }
}

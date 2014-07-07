<?php
namespace develnext\filetype\creator;

use develnext\IDEForm;
use develnext\project\Project;
use develnext\project\ProjectFile;
use php\io\File;

/**
 * Class FileCreator
 * @package develnext\filetype\creator
 */
class FileCreator extends Creator {
    public function __construct() {
        parent::__construct('filetype/creator/FileCreator.xml');
    }

    function getDescription() {
        return _('File');
    }

    function getIcon() {
        return 'images/icons/filetype/unknown.png';
    }

    function onDone(File $root, Project $project) {
        $file = new File($root->getPath() . '/' . $this->form->get('file-name')->text);
        if (!$file->getParentFile()->exists())
            $file->getParentFile()->mkdirs();

        $file->createNewFile();
        return new ProjectFile($file, $project);
    }

    /**
     * @param ProjectFile $parent
     * @return ProjectFile
     */
    function onOpen(ProjectFile $parent) {
        $this->form->getWindow()->title = _('New File');
        $this->form->get('label')->text = _('Enter a new file name');
    }
}

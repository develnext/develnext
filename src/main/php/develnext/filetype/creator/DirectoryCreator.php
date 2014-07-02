<?php
namespace develnext\filetype\creator;

use develnext\project\Project;
use develnext\project\ProjectFile;
use php\io\File;

/**
 * Class DirectoryCreator
 * @package develnext\filetype\creator
 */
class DirectoryCreator extends Creator {

    function __construct() {
        parent::__construct('filetype/creator/FileCreator.xml');
    }

    /**
     * @param \php\io\File $root
     * @param \develnext\project\Project $project
     * @return ProjectFile
     */
    function onDone(File $root, Project $project) {
        $file = new File($root->getPath() . '/' . $this->form->get('file-name')->text);
        $file->mkdirs();
        return new ProjectFile($file, $project);
    }


    function getDescription() {
        return _('Directory');
    }

    function getIcon() {
        return 'images/icons/filetype/folder.png';
    }

    function onOpen(ProjectFile $parent) {
        $this->form->getWindow()->title = _('New Directory');
        $this->form->get('label')->text = _('Enter a new directory name');
    }
}

<?php
namespace develnext\filetype\creator;

use develnext\IDEForm;
use develnext\project\ProjectFile;

/**
 * Class FileCreator
 * @package develnext\filetype\creator
 */
class FileCreator extends Creator {
    /** @var ProjectFile */
    protected $parent;

    /** @var IDEForm */
    protected $form;

    public function __construct(ProjectFile $parent) {
        parent::__construct($parent, 'filetype/creator/FileCreator.xml');
    }
}

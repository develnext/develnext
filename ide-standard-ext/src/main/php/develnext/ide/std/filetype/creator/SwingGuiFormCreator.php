<?php
namespace develnext\ide\std\filetype\creator;

use develnext\filetype\creator\Creator;
use develnext\ide\components\UIMessages;
use develnext\project\Project;
use develnext\project\ProjectFile;
use php\io\File;
use php\io\FileStream;
use php\io\IOException;
use php\io\Stream;
use php\lib\str;
use php\swing\UICombobox;

class SwingGuiFormCreator extends Creator {

    function __construct() {
        parent::__construct('ide/std/creator/SwingGuiFormCreator.xml');
    }

    /**
     * @param \php\io\File $root
     * @param \develnext\project\Project $project
     * @return ProjectFile
     */
    function onDone(File $root, Project $project) {
        $fileName = $this->form->get('form-name')->text;
        if (!str::endsWith($fileName, '.xml'))
            $fileName .= '.xml';

        $file = new File($root, $fileName);

        /** @var UICombobox $type */
        $type = $this->form->get('form-type');

        $template = '';
        switch ($type->selectedIndex) {
            case 1:
                $template = 'dialog.xml'; break;
            case 0:
            default:
                $template = 'form.xml';
        }

        try {
            $st = Stream::of("res://develnext/ide/std/creator/templates/$template");
            $fs = new FileStream($file, 'w+');
            $fs->write($st->readFully());

            $fs->close();
            $st->close();
            return new ProjectFile($file, $project);
        } catch (IOException $e) {
            UIMessages::error(_('Unable to create the file "{0}"', $file));
            return null;
        }
    }

    function getDescription() {
        return _('GUI Form');
    }

    function getIcon() {
        return 'images/icons/filetype/swing_form.png';
    }

    function isAvailable(ProjectFile $parent) {
        return str::startsWith($parent->getRelPath(), '/resources/forms/')
            || $parent->getRelPath() === '/resources/forms';
    }
}

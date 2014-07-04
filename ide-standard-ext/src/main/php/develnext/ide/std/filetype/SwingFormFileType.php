<?php

namespace develnext\ide\std\filetype;

use develnext\filetype\FileType;
use develnext\ide\std\editor\SwingFormEditor;
use develnext\project\EditorManager;
use develnext\project\Project;
use php\io\File;
use php\io\FileStream;
use php\lib\str;
use php\swing\UIContainer;

class SwingFormFileType extends FileType {

    public function onDetect(File $file, Project $project = null) {
        if ($file->exists() && $file->isFile()) {
            $st = new FileStream($file, 'r');
            $test = str::trimLeft($st->read(20));

            $success = str::startsWith($test, '<ui-dialog') || str::startsWith($test, '<ui-form');
            $st->close();

            return $success;
        } else
            return false;
    }

    public function createEditor(File $file, EditorManager $manager = null) {
        return new SwingFormEditor($file, $manager);
    }

    public function getIcon() {
        return 'images/icons/filetype/swing_form.png';
    }
}

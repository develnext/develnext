<?php
namespace develnext\filetype;

use develnext\project\Project;
use develnext\editor\TextEditor;
use php\io\File;
use php\lib\str;

/**
 * Class TextFileType
 * @package develnext\filetype
 */
class TextFileType extends FileType {

    public function onDetect(File $file, Project $project = null) {
        $name = str::lower($file->getName());
        $extensions = ['.txt', '.log'];

        foreach($extensions as $el) {
            if (str::endsWith($name, $el))
                return true;
        }

        return false;
    }

    public function getEditor(File $file, Project $project = null) {
        return TextEditor::getInstance();
    }
}

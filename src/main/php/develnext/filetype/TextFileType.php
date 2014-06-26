<?php
namespace develnext\filetype;

use develnext\project\EditorManager;
use develnext\project\Project;
use develnext\editor\TextEditor;
use php\io\File;
use php\lib\str;
use php\swing\UIContainer;

/**
 * Class TextFileType
 * @package develnext\filetype
 */
class TextFileType extends FileType {

    public function onDetect(File $file, Project $project = null) {
        $name = str::lower($file->getName());
        $extensions = ['.txt', '.log', '.php', '.xml'];

        foreach($extensions as $el) {
            if (str::endsWith($name, $el))
                return true;
        }

        return false;
    }

    public function createEditor(File $file, EditorManager $manager = null) {
        return new TextEditor($file, $manager);
    }

    public function getIcon() {
        return 'images/icons/filetype/text.png';
    }
}

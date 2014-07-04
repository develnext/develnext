<?php
namespace develnext\ide\std\filetype;

use develnext\editor\TextEditor;
use develnext\filetype\FileType;
use develnext\project\EditorManager;
use develnext\project\Project;
use php\io\File;
use php\lib\str;
use php\swing\UIContainer;

/**
 * Class TextFileType
 * @package develnext\filetype
 */
class TextFileType extends FileType {

    public function onDetect(File $file, Project $project = null) {
        return self::isFileWithExtension($file, ['.text', '.log']);
    }

    public function createEditor(File $file, EditorManager $manager = null) {
        return new TextEditor($file, $manager);
    }

    public function getIcon() {
        return 'images/icons/filetype/text.png';
    }
}

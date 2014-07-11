<?php
namespace develnext\ide\std\filetype;

use develnext\editor\Editor;
use develnext\filetype\FileType;
use develnext\ide\std\editor\ImageEditor;
use develnext\project\EditorManager;
use develnext\project\Project;
use php\io\File;

class ImageFileType extends FileType {

    public function onDetect(File $file, Project $project = null) {
        return self::isFileWithExtension($file, ['jpg', 'jpeg', 'png', 'gif']);
    }

    /**
     * @param \php\io\File $file
     * @param \develnext\project\EditorManager $manager
     * @return Editor
     */
    public function createEditor(File $file, EditorManager $manager = null) {
        return new ImageEditor($file, $manager);
    }

    public function getIcon() {
        return 'images/icons/filetype/image.png';
    }
}

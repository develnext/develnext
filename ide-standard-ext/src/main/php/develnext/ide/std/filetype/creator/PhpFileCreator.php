<?php
namespace develnext\ide\std\filetype\creator;

use develnext\filetype\creator\Creator;
use develnext\project\Project;
use develnext\project\ProjectFile;
use php\io\File;
use php\io\FileStream;
use php\lib\str;

class PhpFileCreator extends Creator {
    function __construct() {
        parent::__construct('ide/std/creator/PhpFileCreator.xml');
    }

    /**
     * @param \php\io\File $root
     * @param \develnext\project\Project $project
     * @return ProjectFile
     */
    function onDone(File $root, Project $project) {
        $fileName = $this->form->get('file-name')->text;
        if (!str::endsWith($fileName, '.php'))
            $fileName .= '.php';

        $file = new File($root->getPath() . '/' . $fileName);

        $writer = new FileStream($file, 'w+');
        $writer->write("<?php \n");

        if ($this->form->get('with-namespace')->selected) {
            $projectFile = new ProjectFile($file, $project);
            $namespace = str::replace($projectFile->getParent()->getRelPath(), '/', '\\');
            if ($namespace[0] === '\\')
                $namespace = str::sub($namespace, 1);

            if ($namespace === 'src')
                $namespace = '';

            if (str::startsWith($namespace, 'src\\'))
                $namespace = str::sub($namespace, 4);

            if ($namespace) {
                $writer->write("namespace $namespace;\n");
            }
        }

        $writer->write("\n");

        $type = $this->form->get('file-type')->selectedIndex;

        $name = str::sub($file->getName(), 0, str::lastPos($file->getName(), '.'));
        switch ($type) {
            case 1:
                $writer->write("class $name"); break;
            case 2:
                $writer->write("interface $name"); break;
            case 3:
                $writer->write("trait $name"); break;
        }

        if ($type > 0) {
            $writer->write(" {\n\n}\n");
        }

        $writer->close();

        return new ProjectFile($file, $project);
    }

    function getDescription() {
        return _('PHP File');
    }

    function getIcon() {
        return 'images/icons/filetype/php.png';
    }
}

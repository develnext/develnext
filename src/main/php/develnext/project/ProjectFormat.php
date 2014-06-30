<?php
namespace develnext\project;
use php\format\JsonProcessor;
use php\io\File;
use php\io\FileStream;

/**
 * Class ProjectFormat
 * @package develnext\project
 */
class ProjectFormat {

    /** @var Project */
    protected $project;

    public function __construct(Project $project) {
        $this->project = $project;
    }

    public function save() {
        $processor = new JsonProcessor(JsonProcessor::SERIALIZE_PRETTY_PRINT);

        $dir = new File($this->project->getDirectory()->getPath() . '/.develnext');
        if (!$dir->isDirectory())
            $dir->mkdirs();


        $dependencies = [];
        foreach ($this->project->getDependencies() as $dep) {
            $dependencies[] = get_class($dep) . '###' . $dep->toString();
        }

        $data = array(
            'name' => $this->project->getName(),
            'type' => $this->project->getType()->getCode(),
            'dependencies' => $dependencies
        );

        $rootFile = new FileStream($dir->getPath() . '/root.json', 'w+');
        $rootFile->write($processor->format($data));
        $rootFile->close();
    }
}

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

    /** @var File */
    protected $directory;

    public function __construct(Project $project) {
        $this->project = $project;
        $this->directory = new File($this->project->getDirectory()->getPath() . '/.develnext');
    }

    public function save() {
        $processor = new JsonProcessor(JsonProcessor::SERIALIZE_PRETTY_PRINT);

        $dir = $this->directory;
        if (!$dir->isDirectory())
            $dir->mkdirs();

        $dependencies = [];
        foreach ($this->project->getDependencies() as $dep) {
            $dependencies[] = get_class($dep) . '###' . $dep->toString();
        }

        $fileMarks = [];
        foreach ($this->project->getFileMarks() as $mark) {
            $fileMarks[] = $mark->toArray();
        }

        $runners = [];
        foreach($this->project->getRunners() as $runner) {
            $el = [
                'type' => get_class($runner->getType()),
                'title' => $runner->getTitle(),
                'config'=> $runner->getConfig()
            ];
            $runners[] = $el;
        }

        $data = array(
            'name' => $this->project->getName(),
            'type' => get_class($this->project->getType()),
            'dependencies' => $dependencies,
            'file_marks' => $fileMarks,
            'runners' => $runners,
            'selected_runner_index' => $this->project->getSelectedRunnerIndex()
        );

        $data = $this->project->getType()->onSaveProject($this, $data);

        $rootFile = new FileStream($dir->getPath() . '/root.json', 'w+');
        try {
            $rootFile->write($processor->format($data));
        } finally {
            $rootFile->close();
        }
    }

    /**
     * @return File
     */
    public function getDirectory() {
        return $this->directory;
    }

    /**
     * @return Project
     */
    public function getProject() {
        return $this->project;
    }
}

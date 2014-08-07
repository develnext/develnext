<?php
namespace develnext\project;


use php\format\JsonProcessor;
use php\io\File;
use php\io\IOException;
use php\io\Stream;
use php\lib\str;

class ProjectLoader {

    /**
     * @param File $directory
     * @return Project|null
     */
    public function load(File $directory) {
        $processor = new JsonProcessor(JsonProcessor::DESERIALIZE_AS_ARRAYS);

        $dir = new File($directory->getPath() . '/.develnext');
        if (!$dir->isDirectory())
            return null;

        try {
            $st = Stream::of($dir->getPath() . '/root.json');
            try {
                $data = $processor->parse($st->readFully());
                $type = $data['type'];
            } finally {
                $st->close();
            }
        } catch (IOException $e) {
            return null;
        }

        if (!class_exists($type))
            return null;

        $project = new Project(new $type, $directory);
        $data = $project->getType()->onLoadProject($project, $dir, $data);

        $project->setName($data['name']);

        foreach($data['dependencies'] as $dep) {
            list($type, $string) = str::split($dep, '###', 2);
            if (class_exists($type)) {
                $cls = new \ReflectionClass($type);
                /** @var ProjectDependency $object */
                $object = $cls->newInstanceWithoutConstructor();
                $object->fromString($string);

                $project->addDependency($object);
            }
        }

        foreach($data['file_marks'] as $mark) {
            $project->addFileMark(FileMark::fromArray($mark, $project));
        }

        $selected = $data['selected_runner_index'];

        foreach($data['runners'] as $i => $runner) {
            $type = $runner['type'];
            if (class_exists($type)) {
                $project->addRunner($el = new ProjectRunner(new $type, $runner['title'], $runner['config']));
                if ($i == $selected) {
                    $project->selectRunner($el);
                }
            }
        }

        return $project;
    }
}

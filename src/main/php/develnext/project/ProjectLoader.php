<?php
namespace develnext\project;


use php\format\JsonProcessor;
use php\io\File;
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

        $data = $processor->parse(Stream::of($dir->getPath() . '/root.json')->readFully());
        $type = $data['type'];

        if (!class_exists($type))
            return null;

        $project = new Project(new $type, $directory);
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

        return $project;
    }
}

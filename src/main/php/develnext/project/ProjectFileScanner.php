<?php

namespace develnext\project;

use php\io\File;
use php\lib\items;

/**
 * Class ProjectFileScanner
 * @package develnext\project
 */
class ProjectFileScanner {
    /** @var Project */
    protected $project;

    public function __construct(Project $project) {
        $this->project = $project;
    }

    public function getSubTree(File $directory) {
        $files = $directory->findFiles();

        /** @var File[] $files */
        $files = items::sort($files, function(File $a, File $b){
            return $a->isDirectory() ? -1 : 0;
        });

        $result = [];
        foreach ($files as $el) {
            $item = [$el];
            if ($el->isDirectory()) {
                $item[1] = $this->getSubTree($el);
            }
            $result[] = $item;
        }

        return $result;
    }

    public function getTree() {
        return $this->getSubTree($this->project->getDirectory());
    }
}

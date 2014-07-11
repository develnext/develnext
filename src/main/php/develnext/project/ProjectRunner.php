<?php
namespace develnext\project;

/**
 * Class ProjectRunner
 * @package develnext\project
 */
class ProjectRunner {
    /** @var string */
    protected $title;

    /** @var RunnerType */
    protected $type;

    /** @var array */
    protected $config;

    function __construct(RunnerType $type, $title, array $config) {
        $this->type   = $type;
        $this->title  = $title;
        $this->config = $config;
    }

    function execute() {
        $this->type->execute($this->config);
    }
}

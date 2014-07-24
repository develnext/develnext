<?php
namespace develnext\project;
use develnext\tool\Tool;

/**
 * Class ProjectRunner
 * @package develnext\project
 */
class ProjectRunner {
    /** @var string */
    protected $title;

    /** @var bool */
    protected $singleton;

    /** @var RunnerType */
    protected $type;

    /** @var array */
    protected $config;

    /** @var Tool */
    protected $tool;

    /** @var bool */
    protected $done = true;

    function __construct(RunnerType $type, $title, array $config) {
        $this->type   = $type;
        $this->title  = $title;
        $this->config = $config;
    }

    function execute() {
        $this->type->execute($this);
    }

    function stop() {
        $this->type->stop($this);
    }

    /**
     * @return RunnerType
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param array $config
     */
    public function setConfig($config) {
        $this->config = $config;
    }

    /**
     * @return array
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * @param string $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * @return boolean
     */
    public function isSingleton() {
        return $this->singleton;
    }

    /**
     * @param boolean $singleton
     */
    public function setSingleton($singleton) {
        $this->singleton = $singleton;
    }

    /**
     * @return Tool
     */
    public function getTool() {
        return $this->tool;
    }

    /**
     * @param Tool $tool
     */
    public function setTool($tool) {
        $this->tool = $tool;
    }

    /**
     * @return boolean
     */
    public function isDone() {
        return $this->done;
    }

    /**
     * @param boolean $done
     */
    public function setDone($done) {
        $this->done = $done;
    }
}

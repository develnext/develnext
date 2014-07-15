<?php

namespace develnext\ide;

/**
 * new ClosureBackgroundTask(function($self, $process){
 *
 * }, function($self, $values){
 *
 * });
 *
 * Class ClosureBackgroundTask
 * @package develnext\ide
 */
class ClosureBackgroundTask extends IdeBackgroundTask {

    /** @var callable */
    protected $inBackground;

    /** @var callable */
    protected $process;

    /** @var callable */
    protected $done;

    function __construct(callable $inBackground, callable $process = null) {
        $this->inBackground = $inBackground;
        $this->process = $process;
    }

    /**
     * @return mixed
     */
    protected function doInBackground() {
        $callback = $this->inBackground;
        $callback($this, $this->process);
        $this->publish([null]);
    }

    /**
     * @param array $values
     */
    protected function process(array $values) {
        if ($values[0] === null) {
            if ($callback = $this->done)
                $callback($this);
        } else if ($callback = $this->process)
            $callback($this, $values);
    }

    /**
     * @param callable $process
     * @return $this
     */
    public function onProcess(callable $process) {
        $this->process = $process;
        return $this;
    }

    /**
     * @param callable $done
     * @return $this
     */
    public function onDone(callable $done) {
        $this->done = $done;
        return $this;
    }
}

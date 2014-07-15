<?php
namespace develnext\ide;

use php\swing\SwingWorker;

abstract class IdeBackgroundTask extends SwingWorker {

    public $ideManager;

    public static function of(callable $callback) {
        return new ClosureBackgroundTask($callback);
    }
}

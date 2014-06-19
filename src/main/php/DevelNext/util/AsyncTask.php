<?php
namespace develnext\util;

use php\lang\Thread;
use php\swing\SwingUtilities;

abstract class AsyncTask {

    protected $thread;

    public function execute(callable $callback = null, array $args = []) {
        $this->onPreExecute();
        $this->thread = new Thread(function() use ($args, $callback) {
            $this->doInBackground($args);
            SwingUtilities::invokeLater(function() use ($callback) {
                if ($callback)
                    $callback();

                $this->onPostExecute();
            });
        });
        $this->thread->start();
    }

    /**
     *
     */
    public function onPreExecute() {

    }

    /**
     * @param array $args
     */
    abstract public function doInBackground(array $args = []);

    /**
     *
     */
    public function onPostExecute() {

    }
}

<?php
namespace develnext\util;

use php\lang\Thread;
use php\swing\SwingUtilities;

abstract class AsyncTask {

    protected $thread;

    public function execute(array $args = []) {
        $this->onPreExecute();
        $this->thread = new Thread(function() use ($args) {
            $this->doInBackground($args);
            SwingUtilities::invokeLater(function(){
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

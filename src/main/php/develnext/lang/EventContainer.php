<?php
namespace develnext\lang;

trait EventContainer {
    /** @var array */
    protected $events = [];

    public function on($event, callable $handler, $group = 'general') {
        $this->events[$event][$group] = $handler;
    }

    public function off($event, $group = null) {
        if ($group)
            unset($this->events[$event][$group]);
        else
            unset($this->events[$event]);
    }

    public function trigger($event, array $args = [], $group = null) {
        if ($group) {
            $handler = $this->events[$event][$group];
            if ($handler)
                return call_user_func_array($handler, [$this] + $args);
            return true;
        } else {
            $result = true;
            foreach((array)$this->events[$event] as $handler) {
                $result = $result && call_user_func_array($handler, [$this] + $args);
            }
            return $result;
        }
    }
}

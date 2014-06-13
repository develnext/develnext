<?php
namespace develnext;

use php\lang\Module;
use php\swing\UIElement;
use php\swing\UIWindow;

/**
 * Class IDEForm
 * @package develnext
 */
class IDEForm {

    /** @var UIWindow */
    protected $window;

    /** @var Module */
    protected $module;

    /** @var UIElement */
    protected $vars;

    function __construct(UIWindow $window, Module $module = null, array $vars = array()) {
        $this->window = $window;
        if ($module) {
            $this->module = $module;
            $module->call(['form' => $this]);
        }

        $this->vars = $vars;
    }

    /**
     * @return \php\lang\Module
     */
    public function getModule() {
        return $this->module;
    }

    /**
     * @return \php\swing\UIWindow
     */
    public function getWindow() {
        return $this->window;
    }

    /**
     * @param $name
     * @return UIElement|null
     */
    public function get($name) {
        return $this->vars[$name];
    }
}

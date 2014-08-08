<?php
namespace develnext\jdi;

/**
 * Class JPHPVirtualMachine
 * @package develnext\jdi
 */
class JPHPVirtualMachine {
    /** @var VirtualMachine */
    protected $vm;

    /** @var ReferenceType */
    protected $launcherType;

    /** @var ObjectReference */
    protected $launcher;

    /** @var ObjectReference */
    protected $compileScope;

    /**
     * @param VirtualMachine $vm
     */
    public function __construct(VirtualMachine $vm) {
        $this->vm = $vm;
        $this->launcherType = $vm->classesByName('php.runtime.launcher.Launcher');

        $method = $this->launcherType->methodsByName('current')[0];
        $instance = $this->launcherType->instances(1)[0];

        $thread = $vm->allThreads()[0];
        $this->launcher = $instance->invokeMethod($thread, $method, [], 0);

        $this->compileScope = $this->launcher->invokeMethod(
            $thread,
            $this->launcherType->methodsByName('getCompileScope')[0],
            [], 0
        );
    }

    /**
     * @return ObjectReference
     */
    public function getLauncher() {
        return $this->launcher;
    }

    /**
     * @return ObjectReference
     */
    public function getCompileScope() {
        return $this->compileScope;
    }
}

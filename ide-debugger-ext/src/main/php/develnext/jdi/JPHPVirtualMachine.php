<?php
namespace develnext\jdi;
use php\lang\Thread;

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
        while (!$this->launcherType) {
            $this->launcherType = $vm->classesByName('php.runtime.launcher.Launcher')[0];
            Thread::sleep(100);
        }
        $vm->suspend();

        $method = $this->launcherType->methodsByName('current')[0];
        $instance = $this->launcherType->instances(1)[0];

        $thread = null;
        foreach($vm->allThreads() as $th) {
            dump($th->name());
            if ($th->name() === 'main') {
                $thread = $th; break;
            }
        }

        $thread->resume();

        $this->launcher = $instance->invokeMethod($thread, $method, [], ObjectReference::INVOKE_SINGLE_THREADED);
        $this->compileScope = $this->launcher->invokeMethod(
            $thread,
            $this->launcherType->methodsByName('getCompileScope')[0],
            [], 0
        );

        $thread->suspend();
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

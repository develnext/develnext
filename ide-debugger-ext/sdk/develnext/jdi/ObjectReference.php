<?php
namespace develnext\jdi;

/**
 * Class ObjectReference
 * @package develnext\jdi
 */
class ObjectReference extends Value {
    const INVOKE_SINGLE_THREADED = 1;
    const INVOKE_NONVIRTUAL = 2;

    /**
     * Returns a unique identifier for this ObjectReference.
     *
     * @return int
     */
    public function uniqueID() { return 0; }

    /**
     * @return int
     */
    public function hashCode() { return 0; }

    /**
     * Returns the number times this object's monitor has been entered by the current owning thread.
     *
     * @return int
     * @throws
     */
    public function entryCount() { return 0; }

    /**
     * Determines if this object has been garbage collected in the target VM.
     *
     * @return bool
     */
    public function isCollected() { return false; }

    /**
     * Prevents garbage collection for this object.
     */
    public function disableCollection() { }

    /**
     * Permits garbage collection for this object.
     */
    public function enableCollection() { }

    /**
     * Returns an ThreadReference for the thread, if any, which currently owns this object's monitor.
     *
     * @return ThreadReference
     */
    public function owningThread() { }

    /**
     * Returns a List containing a ThreadReference for each thread currently
     * waiting for this object's monitor.
     *
     * @return ThreadReference[]
     */
    public function waitingThreads() { return []; }

    /**
     * @return VirtualMachine
     */
    public function virtualMachine() { }

    /**
     * @param ThreadReference $thread
     * @param Method $method
     * @param Value[] $arguments
     * @param int $options
     * @return Value
     */
    public function invokeMethod(ThreadReference $thread, Method $method, array $arguments, $options) {
        return new Value();
    }
}

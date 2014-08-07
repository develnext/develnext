<?php
namespace develnext\jdi;

/**
 * Class ThreadReference
 * @package develnext\jdi
 */
class ThreadReference extends ObjectReference {
    const THREAD_STATUS_UNKNOWN = -1;
    const THREAD_STATUS_ZOMBIE = 0;
    const THREAD_STATUS_RUNNING = 1;
    const THREAD_STATUS_SLEEPING = 2;
    const THREAD_STATUS_MONITOR = 3;
    const THREAD_STATUS_WAIT = 4;
    const THREAD_STATUS_NOT_STARTED = 5;

    private function __construct() { }

    /**
     * @return string
     */
    public function name() { return ''; }

    /**
     * Interrupt thread
     */
    public function interrupt() { }

    /**
     * Determines whether the thread has been suspended by the the debugger.
     *
     * @return bool
     */
    public function isSuspended() { return false; }

    /**
     * Determines whether the thread is suspended at a breakpoint.
     *
     * @return bool
     */
    public function isAtBreakpoint() { return false; }

    /**
     * Suspends this thread.
     */
    public function suspend() { }

    /**
     * Returns the number of pending suspends for this thread.
     * @return int
     */
    public function suspendCount() { return 0; }

    /**
     * Resumes this thread.
     */
    public function resume() { }

    /**
     * Stops this thread with an asynchronous exception.
     *
     * @param ObjectReference $throwable
     */
    public function stop(ObjectReference $throwable) { }

    /**
     * Returns the thread's status.
     *
     * @return int THREAD_STATUS_* constants
     */
    public function status() { return 0; }
}

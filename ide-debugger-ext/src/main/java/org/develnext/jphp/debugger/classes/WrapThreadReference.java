package org.develnext.jphp.debugger.classes;

import com.sun.jdi.InvalidTypeException;
import com.sun.jdi.ObjectReference;
import com.sun.jdi.ThreadReference;
import php.runtime.Memory;
import php.runtime.env.Environment;
import php.runtime.memory.LongMemory;
import php.runtime.memory.StringMemory;
import php.runtime.reflection.ClassEntity;

import static php.runtime.annotation.Reflection.*;

@Name("develnext\\jdi\\ThreadReference")
public class WrapThreadReference extends WrapObjectReference {
    public final static int THREAD_STATUS_UNKNOWN = -1;
    public final static int THREAD_STATUS_ZOMBIE = 0;
    public final static int THREAD_STATUS_RUNNING = 1;
    public final static int THREAD_STATUS_SLEEPING = 2;
    public final static int THREAD_STATUS_MONITOR = 3;
    public final static int THREAD_STATUS_WAIT = 4;
    public final static int THREAD_STATUS_NOT_STARTED = 5;

    protected ThreadReference tr;

    public WrapThreadReference(Environment env, ThreadReference tr) {
        super(env);
        this.tr = tr;
    }

    public WrapThreadReference(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Override
    protected ObjectReference getObjectReference() {
        return tr;
    }

    @Signature
    private Memory __construct(Environment env, Memory... args) {
        return Memory.NULL;
    }

    @Signature
    public Memory name(Environment env, Memory... args) {
        return StringMemory.valueOf(tr.name());
    }

    @Signature
    public Memory interrupt(Environment env, Memory... args) {
        tr.interrupt();
        return Memory.NULL;
    }

    @Signature
    public Memory isSuspended(Environment env, Memory... args) {
        return tr.isSuspended() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature
    public Memory isAtBreakpoint(Environment env, Memory... args) {
        return tr.isAtBreakpoint() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature
    public Memory resume(Environment env, Memory... args) {
        tr.resume();
        return Memory.NULL;
    }

    @Signature
    public Memory suspend(Environment env, Memory... args) {
        tr.suspend();
        return Memory.NULL;
    }

    @Signature
    public Memory suspendCount(Environment env, Memory... args) {
        return LongMemory.valueOf(tr.suspendCount());
    }

    @Signature
    public Memory status(Environment env, Memory... args) {
        return LongMemory.valueOf(tr.status());
    }

    @Signature(@Arg(value = "object", nativeType = WrapObjectReference.class))
    public Memory stop(Environment env, Memory... args) throws InvalidTypeException {
        tr.stop(args[0].toObject(WrapObjectReference.class).getObjectReference());
        return Memory.NULL;
    }
}

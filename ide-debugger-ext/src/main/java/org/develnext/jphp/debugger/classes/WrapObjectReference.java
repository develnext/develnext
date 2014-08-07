package org.develnext.jphp.debugger.classes;

import com.sun.jdi.IncompatibleThreadStateException;
import com.sun.jdi.ObjectReference;
import com.sun.jdi.ThreadReference;
import php.runtime.Memory;
import php.runtime.env.Environment;
import php.runtime.lang.BaseObject;
import php.runtime.memory.ArrayMemory;
import php.runtime.memory.LongMemory;
import php.runtime.memory.ObjectMemory;
import php.runtime.memory.TrueMemory;
import php.runtime.reflection.ClassEntity;

import static php.runtime.annotation.Reflection.Name;
import static php.runtime.annotation.Reflection.Signature;

@Name("develnext\\jdi\\ObjectReference")
public class WrapObjectReference extends BaseObject {
    private ObjectReference obj;

    protected WrapObjectReference(Environment env) {
        super(env);
    }

    public WrapObjectReference(Environment env, ObjectReference obj) {
        super(env);
        this.obj = obj;
    }

    public WrapObjectReference(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    protected ObjectReference getObjectReference() {
        return obj;
    }

    @Signature
    public Memory uniqueID(Environment env, Memory... args) {
        return LongMemory.valueOf(getObjectReference().uniqueID());
    }

    @Signature
    public Memory hashCode(Environment env, Memory... args) {
        return LongMemory.valueOf(getObjectReference().hashCode());
    }

    @Signature
    public Memory entryCount(Environment env, Memory... args) throws IncompatibleThreadStateException {
        return LongMemory.valueOf(getObjectReference().entryCount());
    }

    @Signature
    public Memory isCollected(Environment env, Memory... args) {
        return TrueMemory.valueOf(getObjectReference().isCollected());
    }

    @Signature
    public Memory disableCollection(Environment env, Memory... args) {
        getObjectReference().disableCollection();
        return Memory.NULL;
    }

    @Signature
    public Memory enableCollection(Environment env, Memory... args) {
        getObjectReference().enableCollection();
        return Memory.NULL;
    }

    @Signature
    public Memory owningThread(Environment env, Memory... args) throws IncompatibleThreadStateException {
        if (getObjectReference().owningThread() == null)
            return Memory.NULL;

        return new ObjectMemory(new WrapThreadReference(env, getObjectReference().owningThread()));
    }

    @Signature
    public Memory virtualMachine(Environment env, Memory... args) {
        return new ObjectMemory(new WrapVirtualMachine(env, getObjectReference().virtualMachine()));
    }

    @Signature
    public Memory waitingThreads(Environment env, Memory... args) throws IncompatibleThreadStateException {
        ArrayMemory r = new ArrayMemory();
        if (getObjectReference().waitingThreads() != null)
        for (ThreadReference tr : getObjectReference().waitingThreads()) {
            r.add(new WrapThreadReference(env, tr));
        }
        return r.toConstant();
    }
}

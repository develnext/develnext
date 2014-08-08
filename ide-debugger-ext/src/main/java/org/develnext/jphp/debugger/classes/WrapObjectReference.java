package org.develnext.jphp.debugger.classes;

import com.sun.jdi.*;
import php.runtime.Memory;
import php.runtime.common.HintType;
import php.runtime.env.Environment;
import php.runtime.memory.ArrayMemory;
import php.runtime.memory.LongMemory;
import php.runtime.memory.ObjectMemory;
import php.runtime.memory.TrueMemory;
import php.runtime.reflection.ClassEntity;

import java.util.ArrayList;
import java.util.List;

import static php.runtime.annotation.Reflection.*;

@Name("develnext\\jdi\\ObjectReference")
public class WrapObjectReference extends WrapValue<ObjectReference> {

    public WrapObjectReference(Environment env, ObjectReference value) {
        super(env, value);
    }

    public WrapObjectReference(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    protected ObjectReference getObjectReference() {
        return value;
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

    @Signature({
            @Arg(value = "thread", nativeType = WrapThreadReference.class),
            @Arg(value = "method", nativeType = WrapMethod.class),
            @Arg(value = "args", type = HintType.ARRAY),
            @Arg("options")
    })
    public Memory invokeMethod(Environment env, Memory... args)
            throws InvocationException, InvalidTypeException, ClassNotLoadedException, IncompatibleThreadStateException {

        List<Value> arguments = new ArrayList<Value>();
        for(WrapValue value : args[2].toValue(ArrayMemory.class).toObjectArray(WrapValue.class)) {
            arguments.add(value.getValue());
        }

        return new ObjectMemory(new WrapValue<Value>(env, getObjectReference().invokeMethod(
                args[0].toObject(WrapThreadReference.class).tr,
                args[1].toObject(WrapMethod.class).method,
                arguments,
                args[3].toInteger()
        )));
    }
}

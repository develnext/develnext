package org.develnext.jphp.debugger.classes;

import com.sun.jdi.*;
import php.runtime.Memory;
import php.runtime.env.Environment;
import php.runtime.lang.BaseObject;
import php.runtime.memory.ArrayMemory;
import php.runtime.reflection.ClassEntity;

import static php.runtime.annotation.Reflection.Name;
import static php.runtime.annotation.Reflection.Signature;

@Name("develnext\\jdi\\Value")
public class WrapValue<T extends Value> extends BaseObject {
    protected T value;

    public WrapValue(Environment env, T value) {
        super(env);
        this.value = value;
    }

    protected WrapValue(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    public Value getValue() {
        return value;
    }

    @Signature
    public Memory getType(Environment env, Memory... args) {
        return new ArrayMemory(getValue().type().name(), getValue().type().signature());
    }
}

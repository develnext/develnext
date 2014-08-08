package org.develnext.jphp.debugger.classes;

import com.sun.jdi.Field;
import com.sun.jdi.TypeComponent;
import php.runtime.Memory;
import php.runtime.env.Environment;
import php.runtime.memory.StringMemory;
import php.runtime.reflection.ClassEntity;

import static php.runtime.annotation.Reflection.Name;
import static php.runtime.annotation.Reflection.Signature;

@Name("develnext\\jdi\\Field")
public class WrapField extends WrapTypeComponent {
    protected Field field;

    public WrapField(Environment env, Field field) {
        super(env);
        this.field = field;
    }

    public WrapField(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Override
    protected TypeComponent getTypeComponent() {
        return field;
    }

    @Signature
    public Memory typeName(Environment env, Memory... args) {
        return StringMemory.valueOf(field.typeName());
    }

    @Signature
    public Memory isEnumConstant(Environment env, Memory... args) {
        return field.isEnumConstant() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature
    public Memory isTransient(Environment env, Memory... args) {
        return field.isTransient() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature
    public Memory isVolatile(Environment env, Memory... args) {
        return field.isVolatile() ? Memory.TRUE : Memory.FALSE;
    }
}

package org.develnext.jphp.debugger.classes;

import com.sun.jdi.TypeComponent;
import php.runtime.Memory;
import php.runtime.env.Environment;
import php.runtime.lang.BaseObject;
import php.runtime.memory.LongMemory;
import php.runtime.memory.ObjectMemory;
import php.runtime.memory.StringMemory;
import php.runtime.memory.TrueMemory;
import php.runtime.reflection.ClassEntity;

import static php.runtime.annotation.Reflection.Name;
import static php.runtime.annotation.Reflection.Signature;

@Name("develnext\\jdi\\TypeComponent")
abstract public class WrapTypeComponent extends BaseObject {
    protected WrapTypeComponent(Environment env) {
        super(env);
    }

    public WrapTypeComponent(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    abstract protected TypeComponent getTypeComponent();

    @Signature
    public Memory isStatic(Environment env, Memory... args) {
        return TrueMemory.valueOf(getTypeComponent().isStatic());
    }

    @Signature
    public Memory isFinal(Environment env, Memory... args) {
        return TrueMemory.valueOf(getTypeComponent().isFinal());
    }

    @Signature
    public Memory isSynthetic(Environment env, Memory... args) {
        return TrueMemory.valueOf(getTypeComponent().isSynthetic());
    }

    @Signature
    public Memory isPackagePrivate(Environment env, Memory... args) {
        return TrueMemory.valueOf(getTypeComponent().isPackagePrivate());
    }

    @Signature
    public Memory isPrivate(Environment env, Memory... args) {
        return TrueMemory.valueOf(getTypeComponent().isPrivate());
    }

    @Signature
    public Memory isProtected(Environment env, Memory... args) {
        return TrueMemory.valueOf(getTypeComponent().isProtected());
    }

    @Signature
    public Memory isPublic(Environment env, Memory... args) {
        return TrueMemory.valueOf(getTypeComponent().isPublic());
    }

    @Signature
    public Memory signature(Environment env, Memory... args) {
        return StringMemory.valueOf(getTypeComponent().signature());
    }

    @Signature
    public Memory genericSignature(Environment env, Memory... args) {
        return StringMemory.valueOf(getTypeComponent().genericSignature());
    }

    @Signature
    public Memory name(Environment env, Memory... args) {
        return StringMemory.valueOf(getTypeComponent().name());
    }

    @Signature
    public Memory modifiers(Environment env, Memory... args) {
        return LongMemory.valueOf(getTypeComponent().modifiers());
    }

    @Signature
    public Memory declaringType(Environment env, Memory... args) {
        return ObjectMemory.valueOf(new WrapReferenceType(env, getTypeComponent().declaringType()));
    }

    @Signature
    public Memory virtualMachine(Environment env, Memory... args) {
        return ObjectMemory.valueOf(new WrapVirtualMachine(env, getTypeComponent().virtualMachine()));
    }
}

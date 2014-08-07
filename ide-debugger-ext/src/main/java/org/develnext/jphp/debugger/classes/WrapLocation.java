package org.develnext.jphp.debugger.classes;

import com.sun.jdi.AbsentInformationException;
import com.sun.jdi.Location;
import php.runtime.Memory;
import php.runtime.env.Environment;
import php.runtime.lang.BaseObject;
import php.runtime.memory.LongMemory;
import php.runtime.memory.ObjectMemory;
import php.runtime.memory.StringMemory;
import php.runtime.reflection.ClassEntity;

import static php.runtime.annotation.Reflection.Name;
import static php.runtime.annotation.Reflection.Signature;

@Name("develnext\\jdi\\Location")
public class WrapLocation extends BaseObject {
    protected Location location;

    public WrapLocation(Environment env, Location location) {
        super(env);
        this.location = location;
    }

    public WrapLocation(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    private Memory __construct(Environment env, Memory... args) {
        return Memory.NULL;
    }

    @Signature
    public Memory sourceName(Environment env, Memory... args) throws AbsentInformationException {
        return StringMemory.valueOf(location.sourceName());
    }

    @Signature
    public Memory sourcePath(Environment env, Memory... args) throws AbsentInformationException {
        return StringMemory.valueOf(location.sourcePath());
    }

    @Signature
    public Memory codeIndex(Environment env, Memory... args) {
        return LongMemory.valueOf(location.codeIndex());
    }

    @Signature
    public Memory lineNumber(Environment env, Memory... args) {
        return LongMemory.valueOf(location.lineNumber());
    }

    @Signature
    public Memory declaringType(Environment env, Memory... args) {
        return ObjectMemory.valueOf(new WrapReferenceType(env, location.declaringType()));
    }

    @Signature
    public Memory method(Environment env, Memory... args) {
        return ObjectMemory.valueOf(new WrapMethod(env, location.method()));
    }

}

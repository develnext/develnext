package org.develnext.jphp.debugger.classes;

import com.sun.jdi.AbsentInformationException;
import com.sun.jdi.Method;
import com.sun.jdi.ObjectReference;
import com.sun.jdi.ReferenceType;
import php.runtime.Memory;
import php.runtime.env.Environment;
import php.runtime.lang.BaseObject;
import php.runtime.memory.ArrayMemory;
import php.runtime.memory.StringMemory;
import php.runtime.memory.TrueMemory;
import php.runtime.reflection.ClassEntity;

import static php.runtime.annotation.Reflection.*;

@Name("develnext\\jdi\\ReferenceType")
public class WrapReferenceType extends BaseObject {
    protected ReferenceType rt;

    public WrapReferenceType(Environment env, ReferenceType rt) {
        super(env);
        this.rt = rt;
    }

    public WrapReferenceType(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public Memory signature(Environment env, Memory... args) {
        return StringMemory.valueOf(rt.signature());
    }

    @Signature
    public Memory name(Environment env, Memory... args) {
        return StringMemory.valueOf(rt.name());
    }

    @Signature
    public Memory genericSignature(Environment env, Memory... args) {
        return StringMemory.valueOf(rt.genericSignature());
    }

    @Signature
    public Memory sourceName(Environment env, Memory... args) throws AbsentInformationException {
        return StringMemory.valueOf(rt.sourceName());
    }

    @Signature
    public Memory isAbstract(Environment env, Memory... args) {
        return TrueMemory.valueOf(rt.isAbstract());
    }

    @Signature
    public Memory isFinal(Environment env, Memory... args) {
        return TrueMemory.valueOf(rt.isFinal());
    }

    @Signature
    public Memory isInitialized(Environment env, Memory... args) {
        return TrueMemory.valueOf(rt.isInitialized());
    }

    @Signature
    public Memory isPrepared(Environment env, Memory... args) {
        return TrueMemory.valueOf(rt.isPrepared());
    }

    @Signature
    public Memory isStatic(Environment env, Memory... args) {
        return TrueMemory.valueOf(rt.isStatic());
    }

    @Signature
    public Memory isVerified(Environment env, Memory... args) {
        return TrueMemory.valueOf(rt.isVerified());
    }

    @Signature(@Arg("count"))
    public Memory instances(Environment env, Memory... args) {
        ArrayMemory r = new ArrayMemory();
        for(ObjectReference o : rt.instances(args[0].toLong())) {
           r.add(new WrapObjectReference(env, o));
        }

        return r.toConstant();
    }

    @Signature
    public Memory methods(Environment env, Memory... args) {
        ArrayMemory r = new ArrayMemory();
        for(Method m : rt.methods()) {
           r.add(new WrapMethod(env, m));
        }

        return r.toConstant();
    }

    @Signature
    public Memory visibleMethods(Environment env, Memory... args) {
        ArrayMemory r = new ArrayMemory();
        for(Method m : rt.visibleMethods()) {
           r.add(new WrapMethod(env, m));
        }

        return r.toConstant();
    }

    @Signature(@Arg("name"))
    public Memory methodsByName(Environment env, Memory... args) {
        ArrayMemory r = new ArrayMemory();
        for(Method m : rt.methodsByName(args[0].toString())) {
           r.add(new WrapMethod(env, m));
        }

        return r.toConstant();
    }
}


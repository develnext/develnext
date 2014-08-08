package org.develnext.jphp.debugger.classes;

import com.sun.jdi.AbsentInformationException;
import com.sun.jdi.Location;
import com.sun.jdi.Method;
import com.sun.jdi.TypeComponent;
import php.runtime.Memory;
import php.runtime.env.Environment;
import php.runtime.lang.BaseObject;
import php.runtime.memory.ArrayMemory;
import php.runtime.memory.BinaryMemory;
import php.runtime.memory.ObjectMemory;
import php.runtime.reflection.ClassEntity;

import static php.runtime.annotation.Reflection.*;

@Name("develnext\\jdi\\Method")
public class WrapMethod extends WrapTypeComponent {
    protected Method method;

    public WrapMethod(Environment env, Method method) {
        super(env);
        this.method = method;
    }

    public WrapMethod(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Override
    protected TypeComponent getTypeComponent() {
        return method;
    }

    @Signature
    private Memory __construct(Environment env, Memory... args) {
        return Memory.NULL;
    }

    @Signature
    public Memory allLineLocations(Environment env, Memory... args) throws AbsentInformationException {
        ArrayMemory result = new ArrayMemory();
        for (Location location : method.allLineLocations()) {
            result.add(new WrapLocation(env, location));
        }
        return result.toConstant();
    }

    @Signature
    public Memory location(Environment env, Memory... args) throws AbsentInformationException {
        if (method.location() == null)
            return Memory.NULL;

        return new ObjectMemory(new WrapLocation(env, method.location()));
    }

    @Signature(@Arg("index"))
    public Memory locationOfCodeIndex(Environment env, Memory... args) throws AbsentInformationException {
        Location location;
        if ((location = method.locationOfCodeIndex(args[0].toLong())) == null)
            return Memory.NULL;

        return new ObjectMemory(new WrapLocation(env, location));
    }

    @Signature
    public Memory bytecodes(Environment env, Memory... args) {
        return new BinaryMemory(method.bytecodes());
    }

    @Signature
    public Memory isAbstract(Environment env, Memory... args) {
        return method.isAbstract() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature
    public Memory isConstructor(Environment env, Memory... args) {
        return method.isConstructor() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature
    public Memory isNative(Environment env, Memory... args) {
        return method.isNative() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature
    public Memory isBridge(Environment env, Memory... args) {
        return method.isBridge() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature
    public Memory isObsolete(Environment env, Memory... args) {
        return method.isObsolete() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature
    public Memory isStaticInitializer(Environment env, Memory... args) {
        return method.isStaticInitializer() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature
    public Memory isSynchronized(Environment env, Memory... args) {
        return method.isSynchronized() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature
    public Memory isVarArgs(Environment env, Memory... args) {
        return method.isVarArgs() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature(@Arg("line"))
    public Memory locationsOfLine(Environment env, Memory... args) throws AbsentInformationException {
        ArrayMemory r = new ArrayMemory();
        for(Location location : method.locationsOfLine(args[0].toInteger())){
            r.add(new WrapLocation(env, location));
        }

        return r.toConstant();
    }
}

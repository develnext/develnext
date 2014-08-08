package org.develnext.jphp.debugger.classes;

import com.sun.jdi.*;
import com.sun.jdi.connect.spi.Connection;
import com.sun.jdi.connect.spi.TransportService;
import org.develnext.jphp.debugger.DevelnextJDIExtension;
import php.runtime.Memory;
import php.runtime.env.Environment;
import php.runtime.ext.core.classes.WrapProcess;
import php.runtime.lang.BaseObject;
import php.runtime.memory.ArrayMemory;
import php.runtime.memory.ObjectMemory;
import php.runtime.memory.StringMemory;
import php.runtime.reflection.ClassEntity;

import java.io.IOException;

import static php.runtime.annotation.Reflection.*;

@Name("develnext\\jdi\\VirtualMachine")
public class WrapVirtualMachine extends BaseObject {
    public final static int TRACE_ALL = VirtualMachine.TRACE_ALL;
    public final static int TRACE_EVENTS = VirtualMachine.TRACE_EVENTS;
    public final static int TRACE_NONE = VirtualMachine.TRACE_NONE;
    public final static int TRACE_OBJREFS = VirtualMachine.TRACE_OBJREFS;
    public final static int TRACE_RECEIVES = VirtualMachine.TRACE_RECEIVES;
    public final static int TRACE_REFTYPES = VirtualMachine.TRACE_REFTYPES;
    public final static int TRACE_SENDS = VirtualMachine.TRACE_SENDS;

    protected VirtualMachine vm;

    public WrapVirtualMachine(Environment env, VirtualMachine vm) {
        super(env);
        this.vm = vm;
    }

    public WrapVirtualMachine(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    private Memory __construct(Environment env, Memory... args) {
        return Memory.NULL;
    }

    @Signature
    public Memory resume(Environment env, Memory... args) {
        vm.resume();
        return Memory.NULL;
    }

    @Signature
    public Memory suspend(Environment env, Memory... args) {
        vm.suspend();
        return Memory.NULL;
    }

    @Signature(@Arg(value = "code", optional = @Optional("0")))
    public Memory halt(Environment env, Memory... args) {
        vm.exit(args[0].toInteger());
        return Memory.NULL;
    }

    @Signature
    public Memory name(Environment env, Memory... args) {
        return StringMemory.valueOf(vm.name());
    }

    @Signature
    public Memory description(Environment env, Memory... args) {
        return StringMemory.valueOf(vm.description());
    }

    @Signature
    public Memory version(Environment env, Memory... args) {
        return StringMemory.valueOf(vm.version());
    }

    @Signature(@Arg("mode"))
    public Memory setDebugTraceMode(Environment env, Memory... args) {
        vm.setDebugTraceMode(args[0].toInteger());
        return Memory.NULL;
    }

    @Signature
    public Memory process(Environment env, Memory... args) {
        if (vm.process() == null)
            return Memory.NULL;

        return new ObjectMemory(new WrapProcess(env, vm.process()));
    }

    @Signature
    public Memory allThreads(Environment env, Memory... args) {
        ArrayMemory r = new ArrayMemory();

        for(ThreadReference tr : vm.allThreads()) {
            r.add(new WrapThreadReference(env, tr));
        }

        return r.toConstant();
    }

    @Signature
    public Memory allClasses(Environment env, Memory... args) {
        ArrayMemory r = new ArrayMemory();

        for(ReferenceType rt : vm.allClasses()) {
            r.add(new WrapReferenceType(env, rt));
        }

        return r.toConstant();
    }

    @Signature(@Arg("name"))
    public Memory classesByName(Environment env, Memory... args) {
        ArrayMemory r = new ArrayMemory();

        for(ReferenceType rt : vm.classesByName(args[0].toString())) {
            r.add(new WrapReferenceType(env, rt));
        }

        return r.toConstant();
    }

    @Signature({
            @Arg("address"),
            @Arg(value = "timeout", optional = @Optional("5000"))
    })
    public static Memory of(Environment env, Memory... args) throws IOException {
        TransportService ts = DevelnextJDIExtension.getTransportService();
        Connection connection = ts.attach(args[0].toString(), args[1].toLong(), args[1].toLong());

        VirtualMachine vm = Bootstrap.virtualMachineManager().createVirtualMachine(connection);
        return new ObjectMemory(new WrapVirtualMachine(env, vm));
    }

    @Signature(@Arg("arg"))
    public Memory newStringValue(Environment env, Memory... args) {
        return new ObjectMemory(new WrapValue<StringReference>(env, vm.mirrorOf(args[0].toString())));
    }

    @Signature(@Arg("arg"))
    public Memory newLongValue(Environment env, Memory... args) {
        return new ObjectMemory(new WrapValue<LongValue>(env, vm.mirrorOf(args[0].toLong())));
    }

    @Signature(@Arg("arg"))
    public Memory newDoubleValue(Environment env, Memory... args) {
        return new ObjectMemory(new WrapValue<DoubleValue>(env, vm.mirrorOf(args[0].toDouble())));
    }

    @Signature(@Arg("arg"))
    public Memory newBooleanValue(Environment env, Memory... args) {
        return new ObjectMemory(new WrapValue<BooleanValue>(env, vm.mirrorOf(args[0].toBoolean())));
    }

    @Signature(@Arg("arg"))
    public Memory newIntegerValue(Environment env, Memory... args) {
        return new ObjectMemory(new WrapValue<IntegerValue>(env, vm.mirrorOf(args[0].toInteger())));
    }

    @Signature(@Arg("arg"))
    public Memory newFloatValue(Environment env, Memory... args) {
        return new ObjectMemory(new WrapValue<FloatValue>(env, vm.mirrorOf(args[0].toFloat())));
    }

    @Signature(@Arg("arg"))
    public Memory newCharValue(Environment env, Memory... args) {
        return new ObjectMemory(new WrapValue<CharValue>(env, vm.mirrorOf(args[0].toChar())));
    }

    @Signature(@Arg("arg"))
    public Memory newShortValue(Environment env, Memory... args) {
        return new ObjectMemory(new WrapValue<ShortValue>(env, vm.mirrorOf((short)args[0].toInteger())));
    }

    @Signature(@Arg("arg"))
    public Memory newByteValue(Environment env, Memory... args) {
        return new ObjectMemory(new WrapValue<ByteValue>(env, vm.mirrorOf((byte)args[0].toInteger())));
    }

    @Signature
    public Memory newVoidValue(Environment env, Memory... args) {
        return new ObjectMemory(new WrapValue<VoidValue>(env, vm.mirrorOfVoid()));
    }
}

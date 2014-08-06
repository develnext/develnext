package org.develnext.jphp.debugger.classes;

import com.sun.jdi.Bootstrap;
import com.sun.jdi.VirtualMachine;
import com.sun.jdi.connect.spi.Connection;
import com.sun.jdi.connect.spi.TransportService;
import org.develnext.jphp.debugger.DevelnextJDIExtension;
import php.runtime.Memory;
import php.runtime.env.Environment;
import php.runtime.lang.BaseObject;
import php.runtime.memory.ObjectMemory;
import php.runtime.reflection.ClassEntity;

import java.io.IOException;

import static php.runtime.annotation.Reflection.*;

@Name("develnext\\jdi\\VirtualMachine")
public class WrapVirtualMachine extends BaseObject {
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
}

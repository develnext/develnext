package org.develnext.jphp.debugger;

import com.sun.jdi.connect.spi.TransportService;
import org.develnext.jphp.debugger.classes.WrapVirtualMachine;
import php.runtime.env.CompileScope;
import php.runtime.ext.support.Extension;

public class DevelnextJDIExtension extends Extension {
    protected static TransportService ts;

    @Override
    public String getVersion() {
        return "~";
    }

    @Override
    synchronized public void onRegister(CompileScope scope) {
        registerNativeClass(scope, WrapVirtualMachine.class);
        if (ts == null) {
            try {
                Class c = Class.forName("com.sun.tools.jdi.SocketTransportService");
                ts = (TransportService) c.newInstance();
            } catch (Exception x) {
                throw new Error(x);
            }
        }
    }

    public static TransportService getTransportService() {
        return ts;
    }
}

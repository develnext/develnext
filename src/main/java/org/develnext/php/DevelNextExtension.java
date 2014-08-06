package org.develnext.php;

import org.develnext.jphp.debugger.DevelnextJDIExtension;
import org.develnext.jphp.http.HttpExtension;
import org.develnext.jphp.json.JsonExtension;
import org.develnext.jphp.swing.SwingExtension;
import org.develnext.php.ext.UIDockingExtension;
import org.develnext.php.ext.UISyntaxExtension;
import org.develnext.php.ext.UIWeblafExtension;
import org.develnext.swing.DesignContainer;
import php.runtime.env.CompileScope;

public class DevelNextExtension extends SwingExtension {
    public final static String NAMESPACE = "develnext\\";

    @Override
    public String getName() {
        return "DevelNext";
    }

    @Override
    public String getVersion() {
        return "4.0";
    }

    @Override
    public String[] getRequiredExtensions() {
        return new String[]{
                DevelnextJDIExtension.class.getName(),
                SwingExtension.class.getName(),
                HttpExtension.class.getName(),
                JsonExtension.class.getName(),
                UIDockingExtension.class.getName(),
                UISyntaxExtension.class.getName(),
                UIWeblafExtension.class.getName()
        };
    }

    @Override
    public void onRegister(CompileScope scope) {
        registerNativeClass(scope, WrapResizableBorder.class);
        registerNativeClass(scope, WrapResizableContainer.class);
        registerNativeClass(scope, WrapDesignContainer.class, DesignContainer.class);

        registerNativeClass(scope, WrapComponentResizer.class);
        registerNativeClass(scope, WrapComponentMover.class);
    }
}

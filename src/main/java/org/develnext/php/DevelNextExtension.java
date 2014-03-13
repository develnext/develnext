package org.develnext.php;

import org.develnext.php.ext.UIDockingExtension;
import org.develnext.php.ext.UISyntaxExtension;
import org.develnext.swing.DesignContainer;
import org.develnext.swing.ResizableContainer;
import php.runtime.env.CompileScope;
import php.runtime.ext.swing.SwingExtension;

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
                SwingExtension.class.getName(),
                LocalizationExtension.class.getName(),
                UIDockingExtension.class.getName(),
                UISyntaxExtension.class.getName()
        };
    }

    @Override
    public void onRegister(CompileScope scope) {
        registerNativeClass(scope, WrapResizableBorder.class);
        registerNativeClass(scope, WrapResizableContainer.class, ResizableContainer.class);
        registerNativeClass(scope, WrapDesignContainer.class, DesignContainer.class);

        registerNativeClass(scope, WrapComponentResizer.class);
        registerNativeClass(scope, WrapComponentMover.class);
    }
}

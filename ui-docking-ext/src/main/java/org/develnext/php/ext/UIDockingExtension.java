package org.develnext.php.ext;

import org.develnext.jphp.swing.SwingExtension;
import org.develnext.php.ext.classes.*;
import php.runtime.env.CompileScope;

public class UIDockingExtension extends SwingExtension {

    public final static String NAMESPACE = "php\\swing\\docking\\";

    @Override
    public String getName() {
        return "UI-Docking";
    }

    @Override
    public String getVersion() {
        return "1.0";
    }

    @Override
    public void onRegister(CompileScope scope) {
        registerNativeClass(scope, WrapCControl.class);
        registerNativeClass(scope, WrapCGrid.class);

        registerNativeClass(scope, WrapCDockable.class);
        registerNativeClass(scope, WrapSingleCDockable.class);
        registerNativeClass(scope, WrapCContentArea.class);
        registerNativeClass(scope, WrapCGridArea.class);
    }
}

package org.develnext.php.ext;

import bibliothek.gui.dock.common.CContentArea;
import org.develnext.php.ext.classes.*;
import php.runtime.env.CompileScope;
import php.runtime.ext.swing.SwingExtension;

public class UIDockingExtension extends SwingExtension {

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
        registerNativeClass(scope, WrapCContentArea.class, CContentArea.class);
        registerNativeClass(scope, WrapCGridArea.class);
    }
}

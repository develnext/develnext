package org.develnext.php.ext;

import com.alee.extended.dock.WebDockableFrame;
import org.develnext.jphp.swing.SwingExtension;
import org.develnext.php.ext.classes.UIWebDockablePanelTag;
import org.develnext.php.ext.classes.WrapUIWebDockablePanel;
import php.runtime.env.CompileScope;
import php.runtime.env.Environment;

public class UIWeblafExtension extends SwingExtension {
    @Override
    public void onLoad(Environment env) {
    }

    @Override
    public void onRegister(CompileScope scope) {
        registerNativeClass(scope, WrapUIWebDockablePanel.class, WebDockableFrame.class);
        registerReaderTag(new UIWebDockablePanelTag());
    }
}

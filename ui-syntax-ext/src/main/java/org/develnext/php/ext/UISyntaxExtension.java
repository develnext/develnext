package org.develnext.php.ext;

import org.develnext.jphp.swing.SwingExtension;
import org.develnext.php.ext.classes.RSyntaxTextAreaExEventProvider;
import org.develnext.php.ext.classes.RSyntaxTextAreaReaders;
import org.develnext.php.ext.classes.UISyntaxTextArea;
import org.develnext.php.ext.classes.UISyntaxTextAreaTag;
import php.runtime.env.CompileScope;

public class UISyntaxExtension extends SwingExtension {
    @Override
    public String getName() {
        return "UI-Syntax";
    }

    @Override
    public String getVersion() {
        return "1.0";
    }

    @Override
    public void onRegister(CompileScope scope) {
        registerNativeClass(scope, UISyntaxTextArea.class);

        registerReaderTag(new UISyntaxTextAreaTag());
        registerPropertyReaders(new RSyntaxTextAreaReaders());
        registerEventProvider(new RSyntaxTextAreaExEventProvider());
    }
}

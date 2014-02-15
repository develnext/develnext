package org.develnext.php.ext;

import org.develnext.php.ext.classes.RSyntaxTextAreaReaders;
import org.develnext.php.ext.classes.UISyntaxTextArea;
import org.develnext.php.ext.classes.UISyntaxTextAreaTag;
import org.fife.ui.rsyntaxtextarea.RSyntaxTextArea;
import php.runtime.env.CompileScope;
import php.runtime.ext.swing.SwingExtension;

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
        registerNativeClass(scope, UISyntaxTextArea.class, RSyntaxTextArea.class);

        registerReaderTag(new UISyntaxTextAreaTag());
        registerPropertyReaders(new RSyntaxTextAreaReaders());
    }
}

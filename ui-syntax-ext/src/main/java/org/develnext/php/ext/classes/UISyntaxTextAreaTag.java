package org.develnext.php.ext.classes;

import org.fife.ui.rsyntaxtextarea.RSyntaxTextArea;
import php.runtime.ext.swing.loader.support.BaseTag;
import php.runtime.ext.swing.loader.support.ElementItem;
import php.runtime.ext.swing.loader.support.Tag;

@Tag("ui-syntax-area")
public class UISyntaxTextAreaTag extends BaseTag<RSyntaxTextArea> {
    @Override
    public RSyntaxTextArea create() {
        return new RSyntaxTextArea();
    }

    @Override
    public void read(ElementItem elementItem, RSyntaxTextArea rSyntaxTextArea) {

    }
}

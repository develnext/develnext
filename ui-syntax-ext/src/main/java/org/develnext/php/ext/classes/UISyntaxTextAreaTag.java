package org.develnext.php.ext.classes;

import org.develnext.jphp.swing.loader.UIReader;
import org.develnext.jphp.swing.loader.support.BaseTag;
import org.develnext.jphp.swing.loader.support.ElementItem;
import org.develnext.jphp.swing.loader.support.Tag;
import org.fife.ui.rsyntaxtextarea.RSyntaxTextArea;
import org.w3c.dom.Node;

@Tag("ui-syntax-area")
public class UISyntaxTextAreaTag extends BaseTag<RSyntaxTextArea> {
    @Override
    public RSyntaxTextArea create(ElementItem elementItem, UIReader xmlUIReader) {
        return new RSyntaxTextArea();
    }

    @Override
    public void read(ElementItem elementItem, RSyntaxTextArea rSyntaxTextArea, Node node, UIReader uiReader) {

    }
}

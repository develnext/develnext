package org.develnext.php.ext.classes;

import org.fife.ui.rsyntaxtextarea.RSyntaxTextArea;
import org.fife.ui.rsyntaxtextarea.SyntaxConstants;
import php.runtime.Memory;
import php.runtime.env.Environment;
import php.runtime.ext.swing.SwingExtension;
import php.runtime.ext.swing.classes.components.UITextElement;
import php.runtime.memory.StringMemory;
import php.runtime.reflection.ClassEntity;

import javax.swing.text.JTextComponent;
import java.awt.*;

import static php.runtime.annotation.Reflection.*;

@Name(SwingExtension.NAMESPACE + "UISyntaxTextArea")
public class UISyntaxTextArea extends UITextElement {
    protected RSyntaxTextArea component;

    public UISyntaxTextArea(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Override
    public JTextComponent getTextComponent() {
        component.setSyntaxEditingStyle(SyntaxConstants.SYNTAX_STYLE_PHP);
        return component;
    }

    @Override
    public void setComponent(Component component) {
        this.component = (RSyntaxTextArea) component;
    }

    @Override
    protected void onInit(Environment environment, Memory... memories) {
        component = new RSyntaxTextArea();
    }

    @Signature
    protected Memory __getSyntaxStyle(Environment env, Memory... args) {
        return new StringMemory(component.getSyntaxEditingStyle());
    }

    @Signature(@Arg("value"))
    protected Memory __setSyntaxStyle(Environment env, Memory... args) {
        component.setSyntaxEditingStyle(args[0].toString());
        return Memory.NULL;
    }
}

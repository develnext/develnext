package org.develnext.php.ext.classes;

import php.runtime.Memory;
import php.runtime.env.Environment;
import php.runtime.ext.swing.SwingExtension;
import php.runtime.ext.swing.classes.components.UITextElement;
import php.runtime.ext.swing.support.RootTextElement;
import php.runtime.memory.StringMemory;
import php.runtime.reflection.ClassEntity;

import java.awt.*;

import static php.runtime.annotation.Reflection.*;

@Name(SwingExtension.NAMESPACE + "UISyntaxTextArea")
public class UISyntaxTextArea extends UITextElement {
    protected RSyntaxTextAreaEx component;

    public UISyntaxTextArea(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Override
    public Container getContainer() {
        return component;
    }

    @Override
    public RootTextElement getTextComponent() {
        return component;
    }

    @Override
    public void setComponent(Component component) {
        this.component = (RSyntaxTextAreaEx) component;
    }

    @Override
    protected void onInit(Environment environment, Memory... memories) {
        component = new RSyntaxTextAreaEx();
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

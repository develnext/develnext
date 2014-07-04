package org.develnext.php.ext.classes;

import org.develnext.jphp.swing.SwingExtension;
import org.develnext.jphp.swing.classes.components.UITextElement;
import org.develnext.jphp.swing.support.RootTextElement;
import php.runtime.Memory;
import php.runtime.env.Environment;
import php.runtime.memory.LongMemory;
import php.runtime.memory.StringMemory;
import php.runtime.memory.TrueMemory;
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

    @Signature
    protected Memory __getTabSize(Environment env, Memory... args) {
        return LongMemory.valueOf(component.getContent().getTabSize());
    }

    @Signature(@Arg("value"))
    protected Memory __setTabSize(Environment env, Memory... args) {
        component.getContent().setTabSize(args[0].toInteger());
        return Memory.NULL;
    }

    @Signature
    protected Memory __getLineNumbersEnabled(Environment env, Memory... args) {
        return TrueMemory.valueOf(component.getLineNumbersEnabled());
    }

    @Signature(@Arg("value"))
    protected Memory __setLineNumbersEnabled(Environment env, Memory... args) {
        component.setLineNumbersEnabled(args[0].toBoolean());
        return Memory.NULL;
    }

    @Signature
    protected Memory __getIconRowHeaderEnabled(Environment env, Memory... args) {
        return TrueMemory.valueOf(component.isIconRowHeaderEnabled());
    }

    @Signature(@Arg("value"))
    protected Memory __setIconRowHeaderEnabled(Environment env, Memory... args) {
        component.setLineNumbersEnabled(args[0].toBoolean());
        return Memory.NULL;
    }

    @Signature
    protected Memory __getCodeFolding(Environment env, Memory... args) {
        return TrueMemory.valueOf(component.getContent().isCodeFoldingEnabled());
    }

    @Signature(@Arg("value"))
    protected Memory __setCodeFolding(Environment env, Memory... args) {
        component.getContent().setCodeFoldingEnabled(args[0].toBoolean());
        return Memory.NULL;
    }

    @Signature
    protected Memory __getAntiAliasing(Environment env, Memory... args) {
        return TrueMemory.valueOf(component.getContent().getAntiAliasingEnabled());
    }

    @Signature(@Arg("value"))
    protected Memory __setAntiAliasing(Environment env, Memory... args) {
        component.getContent().setAntiAliasingEnabled(args[0].toBoolean());
        return Memory.NULL;
    }
}

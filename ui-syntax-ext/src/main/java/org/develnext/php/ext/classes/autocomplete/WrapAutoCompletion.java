package org.develnext.php.ext.classes.autocomplete;

import org.develnext.jphp.swing.classes.components.UILabel;
import org.develnext.jphp.swing.classes.components.UITextElement;
import org.develnext.jphp.swing.classes.components.support.RootObject;
import org.develnext.jphp.swing.classes.components.support.UIElement;
import org.fife.ui.autocomplete.AutoCompletion;
import php.runtime.Memory;
import php.runtime.common.HintType;
import php.runtime.env.Environment;
import php.runtime.invoke.Invoker;
import php.runtime.memory.LongMemory;
import php.runtime.memory.ObjectMemory;
import php.runtime.memory.StringMemory;
import php.runtime.memory.TrueMemory;
import php.runtime.memory.support.MemoryUtils;
import php.runtime.reflection.ClassEntity;

import javax.swing.*;

import java.awt.*;

import static php.runtime.annotation.Reflection.*;

@Name("develnext\\syntax\\AutoCompletion")
public class WrapAutoCompletion extends RootObject {
    protected AutoCompletion completion;

    public WrapAutoCompletion(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature(@Arg(value = "completionProvider", nativeType = WrapCompletionProvider.class))
    public Memory __construct(Environment env, Memory... args) {
        completion = new AutoCompletion(args[0].toObject(WrapCompletionProvider.class).completionProvider);
        return Memory.NULL;
    }

    @Signature(@Arg(value = "textComponent", nativeType = UITextElement.class))
    public Memory install(Environment env, Memory... args) {
        completion.install(args[0].toObject(UITextElement.class).getTextComponent().getTextComponent());
        return Memory.NULL;
    }

    @Signature
    public Memory uninstall(Environment env, Memory... args) {
        completion.uninstall();
        return Memory.NULL;
    }

    @Signature
    public Memory doCompletion(Environment env, Memory... args) {
        completion.doCompletion();
        return Memory.NULL;
    }

    @Signature(@Arg("value"))
    protected Memory __setActivationDelay(Environment env, Memory... args) {
        completion.setAutoActivationDelay(args[0].toInteger());
        return Memory.NULL;
    }

    @Signature
    protected Memory __getActivationDelay(Environment env, Memory... args) {
        return LongMemory.valueOf(completion.getAutoActivationDelay());
    }

    @Signature(@Arg("value"))
    protected Memory __setAutoActivation(Environment env, Memory... args) {
        completion.setAutoActivationEnabled(args[0].toBoolean());
        return Memory.NULL;
    }

    @Signature
    protected Memory __getAutoActivation(Environment env, Memory... args) {
        return TrueMemory.valueOf(completion.isAutoActivationEnabled());
    }

    @Signature(@Arg("value"))
    protected Memory __setAutoComplete(Environment env, Memory... args) {
        completion.setAutoCompleteEnabled(args[0].toBoolean());
        return Memory.NULL;
    }

    @Signature
    protected Memory __getAutoComplete(Environment env, Memory... args) {
        return TrueMemory.valueOf(completion.isAutoCompleteEnabled());
    }

    @Signature(@Arg("value"))
    protected Memory __setParameterAssistance(Environment env, Memory... args) {
        completion.setParameterAssistanceEnabled(args[0].toBoolean());
        return Memory.NULL;
    }

    @Signature
    protected Memory __getParameterAssistance(Environment env, Memory... args) {
        return TrueMemory.valueOf(completion.isParameterAssistanceEnabled());
    }

    @Signature(@Arg("value"))
    protected Memory __setShowDescWindow(Environment env, Memory... args) {
        completion.setShowDescWindow(args[0].toBoolean());
        return Memory.NULL;
    }

    @Signature
    protected Memory __getShowDescWindow(Environment env, Memory... args) {
        return TrueMemory.valueOf(completion.getShowDescWindow());
    }

    @Signature
    protected Memory __getTriggerKey(Environment env, Memory... args) {
        return StringMemory.valueOf(completion.getTriggerKey().toString());
    }

    @Signature(@Arg("value"))
    protected Memory __setTriggerKey(Environment env, Memory... args) {
        completion.setTriggerKey(KeyStroke.getKeyStroke(args[0].toString()));
        return Memory.NULL;
    }

    protected static DefaultListCellRenderer defaultRenderer = new DefaultListCellRenderer();

    @Signature(@Arg(value = "handler", type = HintType.CALLABLE, optional = @Optional("null")))
    public Memory onCellRender(final Environment env, Memory... args) {
        if (args[0].isNull())
            completion.setListCellRenderer(null);
        else {
            final ObjectMemory self = new ObjectMemory(this);
            final Invoker invoker = Invoker.valueOf(env, null, args[0]);

            completion.setListCellRenderer(new ListCellRenderer() {
                @Override
                public Component getListCellRendererComponent(JList list, Object value, int index, boolean isSelected,
                                                              boolean cellHasFocus) {

                    JLabel template = (JLabel) defaultRenderer.getListCellRendererComponent(list, value, index,
                            isSelected, cellHasFocus);

                    Memory _value = MemoryUtils.valueOf(value);
                    Memory _index = LongMemory.valueOf(index);
                    Memory _isSelected = TrueMemory.valueOf(isSelected);
                    Memory _cellHasFocus = TrueMemory.valueOf(cellHasFocus);

                    Memory r = invoker.callNoThrow(
                            self,
                            new ObjectMemory(new UILabel(env, template)),
                            _value, _index, _isSelected, _cellHasFocus
                    );

                    if (r.isObject() && r.instanceOf(UIElement.class)) {
                        return r.toObject(UIElement.class).getComponent();
                    }

                    return template;
                }
            });
        }

        return Memory.NULL;
    }
}

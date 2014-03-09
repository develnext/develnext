package org.develnext.php;

import org.develnext.swing.ComponentMover;
import php.runtime.Memory;
import php.runtime.env.Environment;
import php.runtime.ext.swing.SwingExtension;
import php.runtime.ext.swing.classes.components.support.RootObject;
import php.runtime.ext.swing.classes.components.support.UIElement;
import php.runtime.reflection.ClassEntity;

import java.awt.*;

import static php.runtime.annotation.Reflection.*;

@Name(DevelNextExtension.NAMESPACE + "swing\\ComponentMover")
public class WrapComponentMover extends RootObject {
    protected ComponentMover mover;

    public WrapComponentMover(Environment env, ComponentMover mover) {
        super(env);
        this.mover = mover;
    }

    public WrapComponentMover(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    public Memory __construct(Environment env, Memory... args) {
        mover = new ComponentMover();
        mover.setSnapSize(new Dimension(8, 8));
        return Memory.NULL;
    }

    @Signature(@Arg(value = "component", typeClass = SwingExtension.NAMESPACE + "UIElement"))
    public Memory registerComponent(Environment env, Memory... args) {
        mover.registerComponent(args[0].toObject(UIElement.class).getComponent());
        return Memory.NULL;
    }
}

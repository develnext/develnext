package org.develnext.php.ext.classes;

import bibliothek.gui.dock.common.DefaultSingleCDockable;
import bibliothek.gui.dock.common.intern.CDockable;
import org.develnext.php.ext.UIDockingExtension;
import php.runtime.Memory;
import php.runtime.env.Environment;
import php.runtime.ext.swing.SwingExtension;
import php.runtime.ext.swing.classes.components.support.UIElement;
import php.runtime.reflection.ClassEntity;

import static php.runtime.annotation.Reflection.*;

@Name(UIDockingExtension.NAMESPACE + "SingleCDockable")
public class WrapSingleCDockable extends WrapCDockable {
    protected DefaultSingleCDockable dockable;

    public WrapSingleCDockable(Environment env, DefaultSingleCDockable dockable) {
        super(env);
        this.dockable = dockable;
    }

    public WrapSingleCDockable(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Override
    public CDockable getCDockable() {
        return dockable;
    }

    @Signature({
            @Arg("id"),
            @Arg("title"),
            @Arg(value = "component", typeClass = SwingExtension.NAMESPACE + "UIElement")
    })
    public Memory __construct(Environment env, Memory... args) {
        dockable = new DefaultSingleCDockable(
                args[0].toString(),
                args[1].toString(),
                args[2].toObject(UIElement.class).getComponent()
        );
        return Memory.NULL;
    }

    @Signature(@Arg("value"))
    public Memory __setClosable(Environment env, Memory... args) {
        dockable.setCloseable(args[0].toBoolean());
        return Memory.NULL;
    }
}

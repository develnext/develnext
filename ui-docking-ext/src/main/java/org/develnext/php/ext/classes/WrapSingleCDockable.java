package org.develnext.php.ext.classes;

import bibliothek.gui.dock.common.DefaultSingleCDockable;
import bibliothek.gui.dock.common.intern.CDockable;
import org.develnext.jphp.swing.SwingExtension;
import org.develnext.jphp.swing.classes.components.support.UIElement;
import org.develnext.php.ext.UIDockingExtension;
import php.runtime.Memory;
import php.runtime.env.Environment;
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

    @Signature(@Arg("value"))
    public Memory __setExternalizable(Environment env, Memory... args) {
        dockable.setExternalizable(args[0].toBoolean());
        return Memory.NULL;
    }

    @Signature(@Arg("value"))
    public Memory __setMaximizable(Environment env, Memory... args) {
        dockable.setMaximizable(args[0].toBoolean());
        return Memory.NULL;
    }

    @Signature(@Arg("value"))
    public Memory __setMinimizable(Environment env, Memory... args) {
        dockable.setMinimizable(args[0].toBoolean());
        return Memory.NULL;
    }

    @Signature(@Arg("value"))
    public Memory __setTitleShown(Environment env, Memory... args) {
        dockable.setTitleShown(args[0].toBoolean());
        return Memory.NULL;
    }
}

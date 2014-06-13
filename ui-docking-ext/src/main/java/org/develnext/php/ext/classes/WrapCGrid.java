package org.develnext.php.ext.classes;

import bibliothek.gui.dock.common.CGrid;
import org.develnext.jphp.swing.SwingExtension;
import org.develnext.jphp.swing.classes.components.support.RootObject;
import org.develnext.php.ext.UIDockingExtension;
import php.runtime.Memory;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

import static php.runtime.annotation.Reflection.*;

@Name(UIDockingExtension.NAMESPACE + "CGrid")
public class WrapCGrid extends RootObject {
    protected CGrid grid;

    public WrapCGrid(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    public WrapCGrid(Environment env, CGrid grid) {
        super(env);
        this.grid = grid;
    }

    @Signature({
            @Arg(value = "control", typeClass = SwingExtension.NAMESPACE + "docking\\CControl")
    })
    public Memory __construct(Environment env, Memory... args) {
        grid = new CGrid(args[0].toObject(WrapCControl.class).control);
        return Memory.NULL;
    }

    @Signature({
            @Arg("x"), @Arg("y"), @Arg("w"), @Arg("h"),
            @Arg(value = "dockable", typeClass = SwingExtension.NAMESPACE + "docking\\CDockable")
    })
    public Memory add(Environment env, Memory... args) {
        grid.add(args[0].toInteger(), args[1].toInteger(), args[2].toInteger(), args[3].toInteger(),
                args[4].toObject(WrapCDockable.class).getCDockable());
        return Memory.NULL;
    }
}

package org.develnext.php.ext.classes;

import bibliothek.gui.dock.common.CGrid;
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
            @Arg(value = "control", nativeType = WrapCControl.class)
    })
    public Memory __construct(Environment env, Memory... args) {
        grid = new CGrid(args[0].toObject(WrapCControl.class).control);
        return Memory.NULL;
    }

    @Signature({
            @Arg("x"), @Arg("y"), @Arg("w"), @Arg("h"),
            @Arg(value = "dockable", nativeType = WrapCDockable.class)
    })
    public Memory add(Environment env, Memory... args) {
        grid.add(args[0].toFloat(), args[1].toFloat(), args[2].toFloat(), args[3].toFloat(),
                args[4].toObject(WrapCDockable.class).getCDockable());
        return Memory.NULL;
    }

    @Signature({
            @Arg("x1"), @Arg("x2"), @Arg("y")
    })
    public Memory addHorizontalDivider(Environment env, Memory... args) {
        grid.addHorizontalDivider(args[0].toDouble(), args[1].toDouble(), args[2].toDouble());
        return Memory.NULL;
    }


    @Signature({
            @Arg("x"), @Arg("y1"), @Arg("y2")
    })
    public Memory addVerticalDivider(Environment env, Memory... args) {
        grid.addVerticalDivider(args[0].toDouble(), args[1].toDouble(), args[2].toDouble());
        return Memory.NULL;
    }
}

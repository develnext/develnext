package org.develnext.php.ext.classes;

import bibliothek.gui.dock.common.CContentArea;
import org.develnext.php.ext.UIDockingExtension;
import php.runtime.Memory;
import php.runtime.env.Environment;
import php.runtime.ext.swing.SwingExtension;
import php.runtime.ext.swing.classes.components.UIPanel;
import php.runtime.reflection.ClassEntity;

import static php.runtime.annotation.Reflection.*;

@Name(UIDockingExtension.NAMESPACE + "CContentArea")
public class WrapCContentArea extends UIPanel {
    protected CContentArea component;

    public WrapCContentArea(Environment env, CContentArea component) {
        super(env, component);
        this.component = component;
    }

    public WrapCContentArea(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Override
    protected void onInit(Environment env, Memory... args) {

    }

    @Override
    @Signature({
            @Arg(value = "control", typeClass = SwingExtension.NAMESPACE + "docking\\CControl"),
            @Arg("uniqueId")
    })
    public Memory __construct(Environment env, Memory... args) {
        component = new CContentArea(args[0].toObject(WrapCControl.class).control, args[1].toString());
        super.component = component;

        onAfterInit(env, args);
        return Memory.NULL;
    }

    @Signature({
            @Arg(value = "grid", typeClass = SwingExtension.NAMESPACE + "docking\\CGrid")
    })
    public Memory deploy(Environment env, Memory... args) {
        component.deploy(args[0].toObject(WrapCGrid.class).grid);
        return Memory.NULL;
    }
}

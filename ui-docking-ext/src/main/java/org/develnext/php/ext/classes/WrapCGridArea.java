package org.develnext.php.ext.classes;

import bibliothek.gui.dock.common.CGridArea;
import bibliothek.gui.dock.common.intern.CDockable;
import org.develnext.php.ext.UIDockingExtension;
import php.runtime.Memory;
import php.runtime.env.Environment;
import php.runtime.ext.swing.classes.components.support.UIElement;
import php.runtime.memory.ObjectMemory;
import php.runtime.reflection.ClassEntity;

import static php.runtime.annotation.Reflection.Name;
import static php.runtime.annotation.Reflection.Signature;

@Name(UIDockingExtension.NAMESPACE + "CGridArea")
public class WrapCGridArea extends WrapCDockable {
    protected CGridArea area;

    public WrapCGridArea(Environment env, CGridArea area) {
        super(env);
        this.area = area;
    }

    public WrapCGridArea(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Override
    public CDockable getCDockable() {
        return area;
    }

    @Signature
    private Memory __construct(Environment env, Memory... args) {
        return Memory.NULL;
    }

    @Signature
    public Memory getComponent(Environment env, Memory... args) {
        return new ObjectMemory(UIElement.of(env, area.getComponent()));
    }
}

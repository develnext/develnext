package org.develnext.php.ext.classes;

import bibliothek.gui.dock.common.intern.CDockable;
import org.develnext.php.ext.UIDockingExtension;
import php.runtime.Memory;
import php.runtime.env.Environment;
import php.runtime.ext.swing.classes.components.support.RootObject;
import php.runtime.reflection.ClassEntity;

import static php.runtime.annotation.Reflection.*;

@Name(UIDockingExtension.NAMESPACE + "CDockable")
abstract public class WrapCDockable extends RootObject {

    public WrapCDockable(Environment env) {
        super(env);
    }

    public WrapCDockable(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    abstract public CDockable getCDockable();

    @Signature
    public Memory __getVisible(Environment env, Memory... args) {
        return getCDockable().isVisible() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature(@Arg("value"))
    public Memory __setVisible(Environment env, Memory... args) {
        getCDockable().setVisible(args[0].toBoolean());
        return Memory.NULL;
    }

    @Signature
    public Memory __getSticky(Environment env, Memory... args) {
        return getCDockable().isSticky() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature(@Arg("value"))
    public Memory __setSticky(Environment env, Memory... args) {
        getCDockable().setSticky(args[0].toBoolean());
        return Memory.NULL;
    }

    @Signature
    public Memory __getStickySwitchable(Environment env, Memory... args) {
        return getCDockable().isStickySwitchable() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature(@Arg("value"))
    public Memory __setStickySwitchable(Environment env, Memory... args) {
        getCDockable().setStickySwitchable(args[0].toBoolean());
        return Memory.NULL;
    }

    @Signature
    public Memory __getCloseable(Environment env, Memory... args) {
        return getCDockable().isCloseable() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature
    public Memory __getMaximizable(Environment env, Memory... args) {
        return getCDockable().isMaximizable() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature
    public Memory __getMinimizable(Environment env, Memory... args) {
        return getCDockable().isMinimizable() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature
    public Memory __getExternalizable(Environment env, Memory... args) {
        return getCDockable().isExternalizable() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature
    public Memory __getNormalizeable(Environment env, Memory... args) {
        return getCDockable().isNormalizeable() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature
    public Memory __getTitleShown(Environment env, Memory... args) {
        return getCDockable().isTitleShown() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature
    public Memory __getStackable(Environment env, Memory... args) {
        return getCDockable().isStackable() ? Memory.TRUE : Memory.FALSE;
    }
}

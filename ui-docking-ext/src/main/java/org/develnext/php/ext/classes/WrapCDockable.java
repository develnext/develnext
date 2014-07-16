package org.develnext.php.ext.classes;

import bibliothek.gui.dock.common.CLocation;
import bibliothek.gui.dock.common.intern.CDockable;
import bibliothek.gui.dock.common.mode.ExtendedMode;
import org.develnext.jphp.swing.classes.components.support.RootObject;
import org.develnext.php.ext.UIDockingExtension;
import php.runtime.Memory;
import php.runtime.env.Environment;
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
    protected Memory __getVisible(Environment env, Memory... args) {
        return getCDockable().isVisible() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature(@Arg("value"))
    protected Memory __setVisible(Environment env, Memory... args) {
        getCDockable().setVisible(args[0].toBoolean());
        return Memory.NULL;
    }

    @Signature
    protected Memory __getSticky(Environment env, Memory... args) {
        return getCDockable().isSticky() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature(@Arg("value"))
    protected Memory __setSticky(Environment env, Memory... args) {
        getCDockable().setSticky(args[0].toBoolean());
        return Memory.NULL;
    }

    @Signature
    protected Memory __getStickySwitchable(Environment env, Memory... args) {
        return getCDockable().isStickySwitchable() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature(@Arg("value"))
    protected Memory __setStickySwitchable(Environment env, Memory... args) {
        getCDockable().setStickySwitchable(args[0].toBoolean());
        return Memory.NULL;
    }

    @Signature
    protected Memory __getCloseable(Environment env, Memory... args) {
        return getCDockable().isCloseable() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature
    protected Memory __getMaximizable(Environment env, Memory... args) {
        return getCDockable().isMaximizable() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature
    protected Memory __getMinimizable(Environment env, Memory... args) {
        return getCDockable().isMinimizable() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature
    protected Memory __getExternalizable(Environment env, Memory... args) {
        return getCDockable().isExternalizable() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature
    protected Memory __getNormalizeable(Environment env, Memory... args) {
        return getCDockable().isNormalizeable() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature
    protected Memory __getTitleShown(Environment env, Memory... args) {
        return getCDockable().isTitleShown() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature
    protected Memory __getStackable(Environment env, Memory... args) {
        return getCDockable().isStackable() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature
    public Memory isShowing(Environment env, Memory... args) {
        return getCDockable().isShowing() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature
    public Memory hasParent(Environment env, Memory... args) {
        return getCDockable().hasParent() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature(@Arg("mode"))
    public Memory setExtendedMode(Environment env, Memory... args) {
        try {
            getCDockable().setExtendedMode(
                    (ExtendedMode) ExtendedMode.class.getField(args[0].toString().toUpperCase()).get(null)
            );
        } catch (IllegalAccessException e) {
            throw new IllegalArgumentException(args[0].toString());
        } catch (NoSuchFieldException e) {
            throw new IllegalArgumentException(args[0].toString());
        } catch (ClassCastException e) {
            throw new IllegalArgumentException(args[0].toString());
        }
        return Memory.NULL;
    }

    @Signature(@Arg(value = "dockable", nativeType = WrapCDockable.class))
    public Memory setLocationsAside(Environment env, Memory... args) {
        getCDockable().setLocationsAside(args[0].toObject(WrapCDockable.class).getCDockable());
        return Memory.NULL;
    }

    @Signature({
            @Arg("pos")
    })
    public Memory setBaseLocation(Environment env, Memory... args) {
        String pos = args[0].toString().toLowerCase();

        if (pos.equals("left"))
            getCDockable().setLocation(CLocation.base().minimalWest());
        else if (pos.equals("right"))
            getCDockable().setLocation(CLocation.base().minimalEast());
        else if (pos.equals("top"))
            getCDockable().setLocation(CLocation.base().minimalNorth());
        else if (pos.equals("bottom"))
            getCDockable().setLocation(CLocation.base().minimalSouth());
        else
            throw new IllegalArgumentException(args[0].toString());

        return Memory.NULL;
    }
}

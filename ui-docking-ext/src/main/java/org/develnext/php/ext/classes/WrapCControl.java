package org.develnext.php.ext.classes;

import bibliothek.gui.DockTheme;
import bibliothek.gui.dock.common.CContentArea;
import bibliothek.gui.dock.common.CControl;
import bibliothek.gui.dock.common.MultipleCDockable;
import bibliothek.gui.dock.common.SingleCDockable;
import bibliothek.gui.dock.common.intern.CDockable;
import bibliothek.gui.dock.common.theme.CDockThemeFactory;
import bibliothek.gui.dock.themes.ThemePropertyFactory;
import bibliothek.util.xml.XElement;
import bibliothek.util.xml.XIO;
import org.develnext.php.ext.UIDockingExtension;
import php.runtime.Memory;
import php.runtime.env.Environment;
import php.runtime.ext.core.stream.Stream;
import php.runtime.ext.swing.SwingExtension;
import php.runtime.ext.swing.classes.components.UIForm;
import php.runtime.ext.swing.classes.components.support.RootObject;
import php.runtime.ext.swing.support.JFrameX;
import php.runtime.memory.ObjectMemory;
import php.runtime.reflection.ClassEntity;

import java.io.*;

import static php.runtime.annotation.Reflection.*;

@Name(UIDockingExtension.NAMESPACE + "CControl")
public class WrapCControl extends RootObject {
    protected CControl control;

    public WrapCControl(Environment env, CControl control) {
        super(env);
        this.control = control;
    }

    public WrapCControl(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature({
            @Arg(value = "form", typeClass = SwingExtension.NAMESPACE + "UIForm", optional = @Optional("NULL"))
    })
    public Memory __construct(Environment env, Memory... args) {
        if (args[0].isNull()) {
            control = new CControl();
        } else {
            JFrameX frameX = (JFrameX)args[0].toObject(UIForm.class).getWindow();
            control = new CControl(frameX);
        }
        control.getThemes().put("develnext", new CDockThemeFactory<DevelNextTheme>(
                new ThemePropertyFactory<DevelNextTheme>(DevelNextTheme.class), control) {
            @Override
            public DockTheme create(CControl control) {
                return new DevelNextTheme();
            }
        });
        return Memory.NULL;
    }

    @Signature(@Arg("theme"))
    public Memory setTheme(Environment env, Memory... args) {
        control.setTheme(args[0].toString());
        return Memory.NULL;
    }

    @Signature
    public Memory getContentArea(Environment env, Memory... args) {
        CContentArea area = control.getContentArea();
        if (area == null)
            return Memory.NULL;
        SwingExtension.registerComponent(area);
        return new ObjectMemory(new WrapCContentArea(env, area));
    }

    @Signature(@Arg("id"))
    public Memory createWorkingArea(Environment env, Memory... args) {
        return new ObjectMemory(new WrapCGridArea(env,
                control.createWorkingArea(args[0].toString())
        ));
    }

    @Signature(@Arg("stream"))
    public Memory write(Environment env, Memory... args) throws IOException {
        control.write(new DataOutputStream(Stream.getOutputStream(env, args[0])));
        return Memory.NULL;
    }

    @Signature(@Arg("stream"))
    public Memory read(Environment env, Memory... args) throws IOException {
        control.read(new DataInputStream(Stream.getInputStream(env, args[0])));
        return Memory.NULL;
    }


    @Signature(@Arg("stream"))
    public Memory writeXml(Environment env, Memory... args) throws IOException {
        OutputStream out = Stream.getOutputStream(env, args[0]);
        try {
            XElement root = new XElement( "root" );
            control.getResources().writeXML( root );
            XIO.writeUTF( root, out );
        } finally {
            Stream.closeStream(env, out);
        }
        return Memory.NULL;
    }

    @Signature(@Arg("stream"))
    public Memory readXml(Environment env, Memory... args) throws IOException {
        InputStream in = Stream.getInputStream(env, args[0]);
        try {
            XElement element = XIO.readUTF(in);
            control.readXML( element );
        } finally {
            Stream.closeStream(env, in);
        }
        return Memory.NULL;
    }

    @Signature({
            @Arg(value = "dockable", typeClass = SwingExtension.NAMESPACE + "docking\\CDockable"),
            @Arg(value = "uniqueId", optional = @Optional("NULL"))
    })
    public Memory addDockable(Environment env, Memory... args) {
        CDockable dockable = args[0].toObject(WrapCDockable.class).getCDockable();

        if (dockable instanceof SingleCDockable)
            control.addDockable((SingleCDockable) dockable);
        else if (dockable instanceof MultipleCDockable) {
            if (args[1].isNull())
                control.addDockable((MultipleCDockable) dockable);
            else
                control.addDockable(args[1].toString(), (MultipleCDockable) dockable);
        }

        return Memory.NULL;
    }
}

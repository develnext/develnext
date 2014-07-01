package org.develnext.php.ext.classes;

import com.alee.extended.dock.WebDockableFrame;
import org.develnext.jphp.swing.SwingExtension;
import org.develnext.jphp.swing.XYLayout;
import org.develnext.jphp.swing.classes.components.UIPanel;
import php.runtime.Memory;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

import java.awt.*;

import static php.runtime.annotation.Reflection.Name;

@Name(SwingExtension.NAMESPACE + "UIWebDockablePanel")
public class WrapUIWebDockablePanel extends UIPanel {
    public WrapUIWebDockablePanel(Environment env, WebDockableFrame component) {
        super(env, component);
    }

    public WrapUIWebDockablePanel(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Override
    public void setComponent(Component component) {
        this.component = (WebDockableFrame)component;
    }

    @Override
    protected void onInit(Environment env, Memory... args) {
        component = new WebDockableFrame();
        component.setLayout(new XYLayout());
    }
}

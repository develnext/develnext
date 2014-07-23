package org.develnext.php;

import org.develnext.jphp.swing.classes.components.support.UIContainer;
import org.develnext.swing.DesignContainer;
import php.runtime.Memory;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

import java.awt.*;

import static php.runtime.annotation.Reflection.*;

@Name(DevelNextExtension.NAMESPACE + "swing\\DesignContainer")
public class WrapDesignContainer extends UIContainer {
    protected DesignContainer component;

    public WrapDesignContainer(Environment env, DesignContainer component) {
        super(env);
        this.component = component;
    }

    public WrapDesignContainer(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Override
    public Container getContainer() {
        return component;
    }

    @Override
    public void setComponent(Component component) {
        this.component = (DesignContainer) component;
    }

    @Override
    protected void onInit(Environment environment, Memory... memories) {
        component = new DesignContainer();
    }

    @Signature
    protected Memory __getSelected(Environment env, Memory... args) {
        return component.isSelected() ? Memory.TRUE : Memory.FALSE;
    }

    @Signature(@Arg("value"))
    protected Memory __setSelected(Environment env, Memory... args) {
        component.setSelected(args[0].toBoolean());
        return Memory.NULL;
    }
}

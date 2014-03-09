package org.develnext.php;

import org.develnext.swing.DesignContainer;
import php.runtime.Memory;
import php.runtime.env.Environment;
import php.runtime.ext.swing.classes.components.support.UIContainer;
import php.runtime.reflection.ClassEntity;

import java.awt.*;

import static php.runtime.annotation.Reflection.Name;

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
}

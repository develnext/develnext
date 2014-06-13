package org.develnext.php;

import org.develnext.jphp.swing.classes.components.support.UIContainer;
import org.develnext.swing.ResizableContainer;
import php.runtime.Memory;
import php.runtime.env.Environment;
import php.runtime.reflection.ClassEntity;

import java.awt.*;

import static php.runtime.annotation.Reflection.Name;

@Name(DevelNextExtension.NAMESPACE + "swing\\ResizableContainer")
public class WrapResizableContainer extends UIContainer {
    protected ResizableContainer component;

    public WrapResizableContainer(Environment env, ResizableContainer component) {
        super(env);
        this.component = component;
    }

    public WrapResizableContainer(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Override
    public Container getContainer() {
        return component;
    }

    @Override
    public void setComponent(Component component) {
        this.component = (ResizableContainer) component;
    }

    @Override
    protected void onInit(Environment environment, Memory... memories) {
        component = new ResizableContainer();
    }
}

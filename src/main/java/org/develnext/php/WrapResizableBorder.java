package org.develnext.php;

import org.develnext.swing.ResizableBorder;
import php.runtime.Memory;
import php.runtime.env.Environment;
import php.runtime.ext.swing.classes.WrapBorder;
import php.runtime.reflection.ClassEntity;

import static php.runtime.annotation.Reflection.*;

@Name(DevelNextExtension.NAMESPACE + "swing\\ResizableBorder")
public class WrapResizableBorder extends WrapBorder {
    public WrapResizableBorder(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature(@Arg("size"))
    public Memory __construct(Environment env, Memory... args) {
        border = new ResizableBorder(args[0].toInteger());
        return Memory.NULL;
    }
}

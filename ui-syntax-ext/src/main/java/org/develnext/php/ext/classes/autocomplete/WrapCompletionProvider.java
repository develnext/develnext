package org.develnext.php.ext.classes.autocomplete;

import org.develnext.jphp.swing.classes.components.support.RootObject;
import org.fife.ui.autocomplete.*;
import php.runtime.Memory;
import php.runtime.env.Environment;
import php.runtime.ext.core.classes.stream.Stream;
import php.runtime.reflection.ClassEntity;

import java.io.IOException;
import java.io.InputStream;

import static php.runtime.annotation.Reflection.*;

@Name("develnext\\syntax\\CompletionProvider")
public class WrapCompletionProvider extends RootObject {
    protected CompletionProvider completionProvider;

    public WrapCompletionProvider(Environment env, ClassEntity clazz) {
        super(env, clazz);
        completionProvider = new DefaultCompletionProvider();
    }

    private DefaultCompletionProvider getProvider() {
        return (DefaultCompletionProvider) completionProvider;
    }

    @Signature({
            @Arg("replacementText"),
            @Arg(value = "shortDesc", optional = @Optional("null")),
            @Arg(value = "summary", optional = @Optional("null"))
    })
    public Memory addCompilation(Environment env, Memory... args) {
        String shortDesc = args[1].isNull() ? null : args[1].toString();
        String summary   = args[2].isNull() ? null : args[2].toString();

        getProvider().addCompletion(new BasicCompletion(completionProvider, args[0].toString(), shortDesc, summary));
        return Memory.NULL;
    }

    @Signature({
            @Arg("inputText"),
            @Arg("definitionString"),
            @Arg("template")
    })
    public Memory addTemplateCompletion(Environment env, Memory... args) {
        getProvider().addCompletion(new TemplateCompletion(
                completionProvider, args[0].toString(), args[1].toString(), args[2].toString()
        ));
        return Memory.NULL;
    }

    @Signature({
            @Arg("name"),
            @Arg("type")
    })
    public Memory addVariableCompletion(Environment env, Memory... args) {
        getProvider().addCompletion(new VariableCompletion(completionProvider, args[0].toString(), args[1].toString()));
        return Memory.NULL;
    }

    @Signature(@Arg("source"))
    public Memory loadFromXml(Environment env, Memory... args) throws IOException {
        InputStream stream = Stream.getInputStream(env, args[0]);
        try {
            getProvider().loadFromXML(stream);
        } finally {
            Stream.closeStream(env, stream);
        }
        return Memory.NULL;
    }
}

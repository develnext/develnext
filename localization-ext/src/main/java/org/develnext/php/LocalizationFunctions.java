package org.develnext.php;

import php.runtime.Memory;
import php.runtime.ext.support.compile.FunctionsContainer;

import java.text.MessageFormat;

public class LocalizationFunctions extends FunctionsContainer {

    public static String i18n_format(String name, Memory... args) {
        return MessageFormat.format(name, args);
    }
}

package org.develnext.php;

import php.runtime.env.CompileScope;
import php.runtime.ext.support.Extension;

public class LocalizationExtension extends Extension {
    @Override
    public String getName() {
        return "Develnext.Localization";
    }

    @Override
    public String getVersion() {
        return "~";
    }

    @Override
    public void onRegister(CompileScope scope) {
        registerFunctions(new LocalizationFunctions());
    }
}

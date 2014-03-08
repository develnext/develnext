package org.develnext.php;

import php.runtime.ext.support.Extension;

public class DevelNextExtension extends Extension {
    @Override
    public String getName() {
        return "DevelNext";
    }

    @Override
    public String getVersion() {
        return "4.0";
    }
}

package org.develnext.php.ext.classes;

import bibliothek.extension.gui.dock.theme.flat.FlatColorScheme;

import java.awt.*;

public class DevelNextColorScheme extends FlatColorScheme {

    @Override
    protected void updateUI() {
        super.updateUI();
        setColor("title.active.left", new Color(176, 173, 174));
        setColor("title.inactive.left", new Color(175, 172, 173));
        setColor("title.active.right", new Color(231, 231, 231) );
        setColor("title.inactive.right", new Color(238, 238, 238) );
        setColor("title.active.text", Color.BLACK );
        setColor("title.inactive.text", Color.DARK_GRAY );

        setColor("title.flap.active", new Color(225, 225, 225) );
        setColor("title.flap.active.text", Color.DARK_GRAY );
        setColor("title.flap.inactive", new Color(221, 221, 221) );
        setColor("title.flap.inactive.text", Color.GRAY );
    }
}

package org.develnext.php.ext.classes;

import bibliothek.extension.gui.dock.theme.EclipseTheme;
import bibliothek.gui.dock.themes.ThemeProperties;

@ThemeProperties(
        nameBundle = "theme.develnext",
        descriptionBundle = "theme.develnext.description",
        authors = { "Dmitriy Zayceff" }, webpages = {}
)
public class DevelNextTheme extends EclipseTheme {

    public DevelNextTheme() {
        super();
        setColorScheme(new DevelNextColorScheme());
    }
}

package org.develnext.php.ext.classes;

import com.alee.extended.dock.WebDockableFrame;
import org.develnext.jphp.swing.XYLayout;
import org.develnext.jphp.swing.loader.UIReader;
import org.develnext.jphp.swing.loader.support.BaseTag;
import org.develnext.jphp.swing.loader.support.ElementItem;
import org.develnext.jphp.swing.loader.support.Tag;

@Tag("ui-web-dockable-panel")
public class UIWebDockablePanelTag extends BaseTag<WebDockableFrame> {
    @Override
    public WebDockableFrame create(ElementItem element, UIReader uiReader) {
        WebDockableFrame r = new WebDockableFrame();
        r.setLayout(new XYLayout());
        return r;
    }
}

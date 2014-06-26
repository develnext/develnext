package org.develnext.php.ext.classes;

import org.develnext.jphp.swing.ComponentProperties;
import org.develnext.jphp.swing.event.EventProvider;
import php.runtime.env.Environment;

import java.awt.*;
import java.awt.event.InputMethodEvent;
import java.awt.event.InputMethodListener;
import java.awt.event.KeyEvent;
import java.awt.event.KeyListener;

public class RSyntaxTextAreaExEventProvider extends EventProvider<RSyntaxTextAreaEx> {
    @Override
    public Class<RSyntaxTextAreaEx> getComponentClass() {
        return RSyntaxTextAreaEx.class;
    }

    @Override
    public void register(final Environment env, RSyntaxTextAreaEx component, final ComponentProperties properties) {
        component.getContent().addKeyListener(new KeyListener() {
            @Override
            public void keyTyped(KeyEvent e) {
            }

            @Override
            public void keyPressed(KeyEvent e) {
                triggerKey(env, properties, "keypress", e);
            }

            @Override
            public void keyReleased(KeyEvent e) {
            }
        });

        component.getContent().addInputMethodListener(new InputMethodListener() {
            @Override
            public void inputMethodTextChanged(InputMethodEvent event) {
                triggerSimple(env, properties, "change", event);
            }

            @Override
            public void caretPositionChanged(InputMethodEvent event) {

            }
        });
    }

    @Override
    public boolean isAllowedEventType(Component component, String code) {
        return "keypress".equalsIgnoreCase(code) || "change".equalsIgnoreCase(code);
    }
}

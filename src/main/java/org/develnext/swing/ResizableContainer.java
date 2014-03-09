package org.develnext.swing;

import javax.swing.*;
import javax.swing.event.MouseInputAdapter;
import javax.swing.event.MouseInputListener;
import java.awt.*;
import java.awt.event.MouseEvent;

public class ResizableContainer extends JComponent {

    public ResizableContainer() {
        setLayout(new BorderLayout());
        addMouseListener(resizeListener);
        addMouseMotionListener(resizeListener);
        setBorder(new ResizableBorder(6));
    }

    private void resize() {
        if (getParent() != null) {
            ((JComponent)getParent()).revalidate();
        }
    }

    MouseInputListener resizeListener = new MouseInputAdapter() {
        public void mouseMoved(MouseEvent me) {
            if (hasFocus()) {
                ResizableBorder border = (ResizableBorder)getBorder();
                setCursor(Cursor.getPredefinedCursor(border.getCursor(me)));
            }
        }

        public void mouseExited(MouseEvent mouseEvent) {
            setCursor(Cursor.getDefaultCursor());
        }

        private int cursor;
        private Point startPos = null;

        public void mousePressed(MouseEvent me) {
            ResizableBorder border = (ResizableBorder)getBorder();
            cursor = border.getCursor(me);
            startPos = me.getPoint();
            requestFocus();
            repaint();
        }

        public void mouseDragged(MouseEvent me) {
            if (startPos != null) {

                int x = getX();
                int y = getY();
                int w = getWidth();
                int h = getHeight();

                int dx = me.getX() - startPos.x;
                int dy = me.getY() - startPos.y;

                switch (cursor) {
                    case Cursor.N_RESIZE_CURSOR:
                        if (!(h - dy < 50)) {
                            setBounds(x, y + dy, w, h - dy);
                            resize();
                        }
                        break;

                    case Cursor.S_RESIZE_CURSOR:
                        if (!(h + dy < 50)) {
                            setBounds(x, y, w, h + dy);
                            startPos = me.getPoint();
                            resize();
                        }
                        break;

                    case Cursor.W_RESIZE_CURSOR:
                        if (!(w - dx < 50)) {
                            setBounds(x + dx, y, w - dx, h);
                            resize();
                        }
                        break;

                    case Cursor.E_RESIZE_CURSOR:
                        if (!(w + dx < 50)) {
                            setBounds(x, y, w + dx, h);
                            startPos = me.getPoint();
                            resize();
                        }
                        break;

                    case Cursor.NW_RESIZE_CURSOR:
                        if (!(w - dx < 50) && !(h - dy < 50)) {
                            setBounds(x + dx, y + dy, w - dx, h - dy);
                            resize();
                        }
                        break;

                    case Cursor.NE_RESIZE_CURSOR:
                        if (!(w + dx < 50) && !(h - dy < 50)) {
                            setBounds(x, y + dy, w + dx, h - dy);
                            startPos = new Point(me.getX(), startPos.y);
                            resize();
                        }
                        break;

                    case Cursor.SW_RESIZE_CURSOR:
                        if (!(w - dx < 50) && !(h + dy < 50)) {
                            setBounds(x + dx, y, w - dx, h + dy);
                            startPos = new Point(startPos.x, me.getY());
                            resize();
                        }
                        break;

                    case Cursor.SE_RESIZE_CURSOR:
                        if (!(w + dx < 50) && !(h + dy < 50)) {
                            setBounds(x, y, w + dx, h + dy);
                            startPos = me.getPoint();
                            resize();
                        }
                        break;

                    case Cursor.MOVE_CURSOR:
                        Rectangle bounds = getBounds();
                        bounds.translate(dx, dy);
                        setBounds(bounds);
                        resize();
                }

                setCursor(Cursor.getPredefinedCursor(cursor));
            }
        }

        public void mouseReleased(MouseEvent mouseEvent) {
            startPos = null;
        }
    };
}

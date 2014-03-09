package org.develnext.swing;

import javax.swing.*;
import javax.swing.border.Border;
import java.awt.*;
import java.awt.event.MouseEvent;
import java.awt.geom.Rectangle2D;

public class ResizableBorder implements Border {
    private int dist = 6;

    int locations[] =
    {
            SwingConstants.NORTH, SwingConstants.SOUTH, SwingConstants.WEST,
            SwingConstants.EAST, SwingConstants.NORTH_WEST,
            SwingConstants.NORTH_EAST, SwingConstants.SOUTH_WEST,
            SwingConstants.SOUTH_EAST
    };

    int cursors[] =
    {
            Cursor.N_RESIZE_CURSOR, Cursor.S_RESIZE_CURSOR, Cursor.W_RESIZE_CURSOR,
            Cursor.E_RESIZE_CURSOR, Cursor.NW_RESIZE_CURSOR, Cursor.NE_RESIZE_CURSOR,
            Cursor.SW_RESIZE_CURSOR, Cursor.SE_RESIZE_CURSOR
    };

    public ResizableBorder(int dist) {
        this.dist = dist;
    }

    public Insets getBorderInsets(Component component) {
        return new Insets(dist, dist, dist, dist);
    }

    public boolean isBorderOpaque() {
        return false;
    }

    public void paintBorder(Component component, Graphics og, int x, int y,
                            int w, int h) {
        //if (component.hasFocus()) {
            og.setColor(Color.GRAY);

            Graphics2D g = (Graphics2D)og;
            Rectangle2D rect = new Rectangle2D.Float(x + dist / 2, y + dist / 2, w - dist, h - dist);
            float[] dash = { 5F, 5F };
            Stroke dashedStroke = new BasicStroke( 1F, BasicStroke.CAP_SQUARE,
                    BasicStroke.JOIN_MITER, 1F, dash, 0F );
            g.fill( dashedStroke.createStrokedShape( rect ) );

            for (int i = 0; i < locations.length; i++) {
                Rectangle rect1 = getRectangle(x, y, w, h, locations[i]);
                g.setColor(Color.WHITE);
                g.fillRect(rect1.x, rect1.y, rect1.width - 1, rect1.height - 1);
                g.setColor(Color.BLACK);
                g.drawRect(rect1.x, rect1.y, rect1.width - 1, rect1.height - 1);
            }
        //}
    }

    private Rectangle getRectangle(int x, int y, int w, int h, int location) {
        switch (location) {
            case SwingConstants.NORTH:
                return new Rectangle(x + w / 2 - dist / 2, y, dist, dist);
            case SwingConstants.SOUTH:
                return new Rectangle(x + w / 2 - dist / 2, y + h - dist, dist,
                        dist);
            case SwingConstants.WEST:
                return new Rectangle(x, y + h / 2 - dist / 2, dist, dist);
            case SwingConstants.EAST:
                return new Rectangle(x + w - dist, y + h / 2 - dist / 2, dist,
                        dist);
            case SwingConstants.NORTH_WEST:
                return new Rectangle(x, y, dist, dist);
            case SwingConstants.NORTH_EAST:
                return new Rectangle(x + w - dist, y, dist, dist);
            case SwingConstants.SOUTH_WEST:
                return new Rectangle(x, y + h - dist, dist, dist);
            case SwingConstants.SOUTH_EAST:
                return new Rectangle(x + w - dist, y + h - dist, dist, dist);
        }
        return null;
    }

    public int getCursor(MouseEvent me) {
        Component c = me.getComponent();
        int w = c.getWidth();
        int h = c.getHeight();

        for (int i = 0; i < locations.length; i++) {
            Rectangle rect = getRectangle(0, 0, w, h, locations[i]);
            if (rect.contains(me.getPoint()))
                return cursors[i];
        }

        return Cursor.MOVE_CURSOR;
    }
}

package org.develnext.swing;

import javax.swing.*;
import javax.swing.border.EmptyBorder;
import java.awt.*;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import java.awt.image.BufferedImage;

public class DesignContainer extends JComponent {

    protected boolean mouse;
    protected BufferedImage lastScreen;

    public DesignContainer() {
        setLayout(new GridLayout());
        setOpaque(false);
        setBorder(new EmptyBorder(6, 6, 6, 6));
    }

    @Override
    protected void paintComponent(Graphics g) {
        super.paintComponent(g);
    }

    public static BufferedImage componentToImage(Component component, Rectangle region) {
        BufferedImage img = new BufferedImage(component.getWidth(), component.getHeight(), BufferedImage.TYPE_INT_ARGB_PRE);
        Graphics g = img.getGraphics();
        g.setColor(component.getForeground());
        g.setFont(component.getFont());
        component.paintAll(g);
        g.dispose();
        if (region == null) {
            return img;
        }
        return img.getSubimage(region.x, region.y, region.width, region.height);
    }

    @Override
    protected void addImpl(final Component comp, Object constraints, int index) {
        super.addImpl(comp, constraints, index);

        comp.addMouseMotionListener(new MouseAdapter() {
            @Override
            public void mouseMoved(MouseEvent e) {
                lastScreen = componentToImage(comp, new Rectangle(0, 0, comp.getWidth(), comp.getHeight()));
            }
        });
        comp.addMouseListener(new MouseAdapter() {
            @Override
            public void mouseClicked(MouseEvent e) {
                setBorder(new ResizableBorder(6));
            }

            @Override
            public void mousePressed(MouseEvent e) {
                mouse = true;
            }

            @Override
            public void mouseReleased(MouseEvent e) {
                mouse = false;
            }
        });
    }

    @Override
    protected void paintChildren(Graphics g) {
        if (!mouse)
            super.paintChildren(g);
        else {
            g.drawImage(lastScreen, getInsets().left, getInsets().top, lastScreen.getWidth(), lastScreen.getHeight(), null);
        }
    }

    @Override
    public Component add(Component comp) {
        comp.setFocusable(false);
        return super.add(comp);
    }
}

package org.develnext.php.ext.classes;

import org.fife.ui.rsyntaxtextarea.RSyntaxTextArea;
import php.runtime.ext.swing.support.JScrollableComponent;
import php.runtime.ext.swing.support.RootTextElement;

import java.awt.*;
import java.awt.print.PrinterException;

public class RSyntaxTextAreaEx extends JScrollableComponent<RSyntaxTextArea> implements RootTextElement {
    private String syntaxEditingStyle;

    @Override
    protected RSyntaxTextArea newComponent() {
        return new RSyntaxTextArea();
    }

    @Override
    public void setText(String s) {
        component.setText(s);
    }

    @Override
    public String getText() {
        return component.getText();
    }

    @Override
    public int getCaretPosition() {
        return component.getCaretPosition();
    }

    @Override
    public void setCaretPosition(int i) {
        component.setCaretPosition(i);
    }

    @Override
    public Color getCaretColor() {
        return component.getCaretColor();
    }

    @Override
    public void setCaretColor(Color color) {
        component.setCaretColor(color);
    }

    @Override
    public boolean isEditable() {
        return component.isEditable();
    }

    @Override
    public void setEditable(boolean b) {
        component.setEditable(b);
    }

    @Override
    public int getSelectionStart() {
        return component.getSelectionStart();
    }

    @Override
    public void setSelectionStart(int i) {
        component.setSelectionStart(i);
    }

    @Override
    public int getSelectionEnd() {
        return component.getSelectionEnd();
    }

    @Override
    public void setSelectionEnd(int i) {
        component.setSelectionEnd(i);
    }

    @Override
    public String getSelectedText() {
        return component.getSelectedText();
    }

    @Override
    public Color getSelectionColor() {
        return component.getSelectionColor();
    }

    @Override
    public void setSelectionColor(Color color) {
        component.setSelectionColor(color);
    }

    @Override
    public Color getSelectedTextColor() {
        return component.getSelectedTextColor();
    }

    @Override
    public void setSelectedTextColor(Color color) {
        component.setSelectedTextColor(color);
    }

    @Override
    public Color getDisabledTextColor() {
        return component.getDisabledTextColor();
    }

    @Override
    public void setDisabledTextColor(Color color) {
        component.setDisabledTextColor(color);
    }

    @Override
    public void copy() {
        component.copy();
    }

    @Override
    public void cut() {
        component.cut();
    }

    @Override
    public void paste() {
        component.paste();
    }

    @Override
    public void select(int i, int i2) {
        component.select(i, i2);
    }

    @Override
    public void selectAll() {
        component.selectAll();
    }

    @Override
    public void replaceSelection(String s) {
        component.replaceSelection(s);
    }

    @Override
    public boolean print() throws PrinterException {
        return component.print();
    }

    @Override
    public void setMargin(Insets insets) {
        component.setMargin(insets);
    }

    @Override
    public Insets getMargin() {
        return component.getMargin();
    }

    public String getSyntaxEditingStyle() {
        return component.getSyntaxEditingStyle();
    }

    public void setSyntaxEditingStyle(String syntaxEditingStyle) {
        component.setSyntaxEditingStyle(syntaxEditingStyle);
    }
}

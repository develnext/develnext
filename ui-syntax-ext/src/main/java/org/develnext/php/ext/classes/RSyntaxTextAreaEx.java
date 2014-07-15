package org.develnext.php.ext.classes;

import org.develnext.jphp.swing.support.RootTextElement;
import org.fife.ui.rsyntaxtextarea.RSyntaxTextArea;
import org.fife.ui.rtextarea.RTextScrollPane;

import javax.swing.text.JTextComponent;
import java.awt.*;
import java.awt.print.PrinterException;

public class RSyntaxTextAreaEx extends RTextScrollPane
        implements RootTextElement {

    protected RSyntaxTextArea component;

    public RSyntaxTextAreaEx() {
        super();
        this.setViewportView(new RSyntaxTextArea());
        //super(new RSyntaxTextArea());
        component = (RSyntaxTextArea) this.getTextArea();
    }

    public RSyntaxTextArea getContent() {
        return component;
    }

    @Override
    public JTextComponent getTextComponent() {
        return component;
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

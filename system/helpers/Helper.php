<?php

namespace System\Helpers;

/**
 * Helper class provides utility methods for creating HTML forms, form elements, and other helper functions like base_url.
 */
class Helper {

    /**
     * Get the base URL for the application.
     *
     * @param string $uri The URI to append to the base URL.
     * @return string The full URL.
     */
    public static function base_url($uri = '')
    {
        // Get the base URL from the configuration
        $config = require ROOT_DIR . '/config.php';
        $baseUrl = $config['base_url'];

        // Append the URI to the base URL if provided
        return rtrim($baseUrl, '/') . '/' . ltrim($uri, '/');
    }

    /**
     * Open a form tag with specified action, method, and additional attributes.
     *
     * @param string $action The action URL the form will submit to.
     * @param string $method The HTTP method for form submission (default is POST).
     * @param array $attributes Additional attributes for the form tag.
     * @return string The form opening tag.
     */
    public static function open($action = '', $method = 'POST', $attributes = [])
    {
        $attr = self::arrayToAttributes($attributes);
        return "<form action=\"$action\" method=\"$method\"$attr>";
    }

    /**
     * Close a form tag.
     *
     * @return string The form closing tag.
     */
    public static function close()
    {
        return "</form>";
    }

    /**
     * Create an input field.
     *
     * @param array $attributes Attributes for the input element (e.g., type, name, class).
     * @return string The HTML input element.
     */
    public static function input($attributes = [])
    {
        $attr = self::arrayToAttributes($attributes);
        return "<input$attr>";
    }

    /**
     * Create a textarea field.
     *
     * @param string $name The name attribute of the textarea.
     * @param string $value The default value to be displayed inside the textarea.
     * @param array $attributes Additional attributes for the textarea.
     * @return string The HTML textarea element.
     */
    public static function textarea($name, $value = '', $attributes = [])
    {
        $attr = self::arrayToAttributes($attributes);
        return "<textarea name=\"$name\"$attr>$value</textarea>";
    }

    /**
     * Create a select dropdown field.
     *
     * @param string $name The name attribute of the select element.
     * @param array $options The options for the select dropdown as an associative array (value => label).
     * @param string|null $selected The selected value (optional).
     * @param array $attributes Additional attributes for the select element.
     * @return string The HTML select dropdown element.
     */
    public static function select($name, $options = [], $selected = null, $attributes = [])
    {
        $attr = self::arrayToAttributes($attributes);
        $optionsHtml = '';

        foreach ($options as $value => $label) {
            $isSelected = ($value == $selected) ? ' selected' : '';
            $optionsHtml .= "<option value=\"$value\"$isSelected>$label</option>";
        }

        return "<select name=\"$name\"$attr>$optionsHtml</select>";
    }

    /**
     * Create a label for a form element.
     *
     * @param string $for The id of the element the label is for.
     * @param string $text The text for the label.
     * @return string The HTML label element.
     */
    public static function label($for, $text)
    {
        return "<label for=\"$for\">$text</label>";
    }

    /**
     * Create a button element.
     *
     * @param string $text The text to display on the button.
     * @param string $type The type of button (default is 'button').
     * @param array $attributes Additional attributes for the button element.
     * @return string The HTML button element.
     */
    public static function button($text, $type = 'button', $attributes = [])
    {
        $attr = self::arrayToAttributes($attributes);
        return "<button type=\"$type\"$attr>$text</button>";
    }

    /**
     * Create a radio button.
     *
     * @param string $name The name attribute of the radio button.
     * @param string $value The value of the radio button.
     * @param bool $checked Whether the radio button should be checked (default is false).
     * @param array $attributes Additional attributes for the radio button.
     * @return string The HTML radio button element.
     */
    public static function radio($name, $value, $checked = false, $attributes = [])
    {
        $checkedAttr = $checked ? ' checked' : '';
        $attr = self::arrayToAttributes($attributes);
        return "<input type=\"radio\" name=\"$name\" value=\"$value\"$checkedAttr$attr>";
    }

    /**
     * Create a checkbox.
     *
     * @param string $name The name attribute of the checkbox.
     * @param string $value The value of the checkbox.
     * @param bool $checked Whether the checkbox should be checked (default is false).
     * @param array $attributes Additional attributes for the checkbox.
     * @return string The HTML checkbox element.
     */
    public static function checkbox($name, $value, $checked = false, $attributes = [])
    {
        $checkedAttr = $checked ? ' checked' : '';
        $attr = self::arrayToAttributes($attributes);
        return "<input type=\"checkbox\" name=\"$name\" value=\"$value\"$checkedAttr$attr>";
    }

    /**
     * Create a file input field.
     *
     * @param string $name The name attribute of the file input.
     * @param array $attributes Additional attributes for the file input.
     * @return string The HTML file input element.
     */
    public static function file($name, $attributes = [])
    {
        $attr = self::arrayToAttributes($attributes);
        return "<input type=\"file\" name=\"$name\"$attr>";
    }

    /**
     * Create a hidden input field.
     *
     * @param string $name The name attribute of the hidden field.
     * @param string $value The value of the hidden field.
     * @return string The HTML hidden input element.
     */
    public static function hidden($name, $value)
    {
        return "<input type=\"hidden\" name=\"$name\" value=\"$value\">";
    }

    /**
     * Convert an associative array of attributes into HTML attributes.
     *
     * @param array $attributes The attributes as an associative array (key => value).
     * @return string A string of HTML attributes.
     */
    private static function arrayToAttributes($attributes)
    {
        $attrs = '';
        foreach ($attributes as $key => $value) {
            $attrs .= " $key=\"$value\"";
        }
        return $attrs;
    }

    /**
     * Generate a CSRF token input field (if needed for security).
     *
     * @param string $token The CSRF token value.
     * @return string The HTML hidden input field for the CSRF token.
     */
    public static function csrf_token($token)
    {
        return "<input type=\"hidden\" name=\"csrf_token\" value=\"$token\">";
    }
}

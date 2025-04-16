<?php

if (!function_exists('view')) {
    function view(string $view, array $data = []): void
    {
        $viewFile = "app/views/{$view}.php";

        if (file_exists($viewFile)) {
            extract($data);
            require $viewFile;
        } else {
            echo "View file '{$view}.php' not found.";
        }
    }
}

// --------------------
// 🟡 URL Helpers
// --------------------

function base_url($path = '') {
    $config = include __DIR__ . '/../../config/config.php';
    $base = rtrim($config['base_url'], '/');
    return $base . '/' . ltrim($path, '/');
}

function current_url() {
    return (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}


// --------------------
// 🟡 Form Helpers
// --------------------

function form_open($action = '', $method = 'post', $attributes = []) {
    $attrs = '';
    foreach ($attributes as $key => $val) {
        $attrs .= "$key=\"$val\" ";
    }

    $csrf = '<input type="hidden" name="_token" value="' . csrf_token() . '">';
    return "<form action=\"$action\" method=\"$method\" $attrs>$csrf";
}

function form_close() {
    return "</form>";
}

function form_input($name, $value = '', $attributes = []) {
    $attrs = '';
    foreach ($attributes as $key => $val) {
        $attrs .= "$key=\"$val\" ";
    }
    return "<input type=\"text\" name=\"$name\" value=\"$value\" $attrs />";
}

function form_label($value)
{
    return "<label>". $value ."</label>";
}

function form_password($name, $attributes = []) {
    $attrs = '';
    foreach ($attributes as $key => $val) {
        $attrs .= "$key=\"$val\" ";
    }
    return "<input type=\"password\" name=\"$name\" $attrs />";
}

function form_textarea($name, $value = '', $attributes = []) {
    $attrs = '';
    foreach ($attributes as $key => $val) {
        $attrs .= "$key=\"$val\" ";
    }
    return "<textarea name=\"$name\" $attrs>$value</textarea>";
}

function form_hidden($name, $value) {
    return "<input type=\"hidden\" name=\"$name\" value=\"$value\" />";
}

function form_submit($name, $value = 'Submit', $attributes = []) {
    $attrs = '';
    foreach ($attributes as $key => $val) {
        $attrs .= "$key=\"$val\" ";
    }
    return "<input type=\"submit\" name=\"$name\" value=\"$value\" $attrs />";
}


// --------------------
// 🟡 Session Helpers
// --------------------

function set_session($key, $value) {
    $_SESSION[$key] = $value;
}

function get_session($key) {
    return $_SESSION[$key] ?? null;
}

function unset_session($key) {
    unset($_SESSION[$key]);
}


// --------------------
// 🟡 Flash Message Helpers
// --------------------

function set_flash($key, $message) {
    $_SESSION['flash'][$key] = $message;
}

function get_flash($key) {
    if (isset($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
    return null;
}


// --------------------
// 🟡 Array Helpers
// --------------------

function array_get($array, $key, $default = null) {
    return $array[$key] ?? $default;
}


// --------------------
// 🟡 Debug Helpers
// --------------------

function dd($data) {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    die;
}


// --------------------
// 🟡 Redirect Helper
// --------------------

function redirect($url) {
    header("Location: " . base_url($url));
    exit;
}


function csrf_token() {
    if (!isset($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf_token'];
}


function verify_csrf_token() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['_token'] ?? '';
        if (!$token || $token !== ($_SESSION['_csrf_token'] ?? '')) {
            die("CSRF token mismatch.");
        }
    }
}
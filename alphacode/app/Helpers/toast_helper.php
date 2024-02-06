<?php

function set_toast($type, $title, $message)
{
    helper('cookie');
    set_cookie('toast_type', $type, 60);
    set_cookie('toast_title', $title, 60);
    set_cookie('toast_message', $message, 60);
}

function get_toast()
{
    helper('cookie');
    if (get_cookie('toast_message')) {
        $type = get_cookie('toast_type');
        $title = get_cookie('toast_title');
        $message = get_cookie('toast_message');
        delete_cookie('toast_type');
        delete_cookie('toast_title');
        delete_cookie('toast_message');
        return "showToast('$type', '$title', '$message');";
    }
    return '';
}
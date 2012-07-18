<?php
use miranda\config\Config;
use miranda\plugins\SecureHash;


function format($location)
{
    return Config::get('site', 'base') . $location;
}
function link_to($location, $display)
{
    return '<a href="' . Config::get('site', 'base') . $location . '">' . $display . '</a>';
}
function check_admin($password, $unique = '')
{
    return Config::get('admin', 'ip') === $_SERVER['REMOTE_ADDR'] && Config::get('admin', 'hash') === SecureHash::hash(Config::get('admin', 'hash_type'), $password, $unique, false);
}
?>
<?php
function link_to($location, $display)
{
    return '<a href="' . SITE_BASE . $location . '">' . $display . '</a>';
}
function render_partial($partial)
{
    require(VIEWS_BASE . 'partials/' . $partial .'.php');
}
?>
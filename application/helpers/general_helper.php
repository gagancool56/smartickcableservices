<?php
if (!function_exists('load_file')) {
    function load_file($maincontent = '', $data = '')
    {
        $CI = &get_instance();
        $CI->load->view('inc/topbar');
        $CI->load->view('inc/header');
        $CI->load->view($maincontent, $data);
        $CI->load->view('inc/footer');
    }
}

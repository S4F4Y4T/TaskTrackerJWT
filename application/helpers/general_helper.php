<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('filter')) {

    function filter($data)
    {
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $data = strip_tags($data);
        $data = htmlentities($data);
        return $data;
    }

}



?>

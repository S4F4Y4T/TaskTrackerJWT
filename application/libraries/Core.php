<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Core
{
    public function outputResponse($data, $status_code = 200)
    {
        http_response_code($status_code);
        echo json_encode($data);
        exit();
    }
}

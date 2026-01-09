<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$hook['post_controller'] = array(
    'class'    => 'Root',
    'function' => 'check_access',
    'filename' => 'Root.php',
    'filepath' => 'hooks'
);
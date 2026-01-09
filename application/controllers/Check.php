<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Check extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }
    function index(){
        echo "OK";
    }
}
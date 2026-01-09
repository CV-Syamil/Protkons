<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['login'] = "auth/index";
$route['authenticate']['post'] = "auth/submit";
$route['pelayanan/file/(:any)/(:any)'] = "Pelayanan_file/file/$1/$2";
$route['pelayanan/file/(:any)/(:any)/read'] = "Pelayanan_file/file/$1/$2/read";
$route['pelayanan/file/(:any)/(:any)/(:any)/(:any)'] = "Pelayanan_file/file/$1/$2/$3/$4";
$route['pelayanan/test-template/(:any)/(:any)'] = "Pelayanan_file/test_file/$1/$2";
$route['pelayanan/file-kwitansi/(:any)/(:any)'] = "Pelayanan_file/file_kwitansi/$1/$2";
$route['pelayanan/test-template-kwitansi/(:any)/(:any)'] = "Pelayanan_file/test_file_kwitansi/$1/$2";
$route['pelayanan/file-bukti/(:any)/(:any)'] = "Pelayanan_file/file_bukti/$1/$2";
$route['pelayanan/test-template-bukti/(:any)/(:any)'] = "Pelayanan_file/test_file_bukti/$1/$2";
$route['logout'] = "auth/logout";

$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;

<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Route::root ('main');
Route::get ('/login', 'platform@login');
Route::get ('/platform/index', 'platform@login');
Route::get ('/platform', 'platform@login');

Route::group ('admin', function () {
  Route::get ('/', 'main');
  Route::resourcePagination (array ('users'), 'users');
  Route::resourcePagination (array ('product_tags'), 'product_tags');
});
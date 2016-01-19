<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Route::root ('main');
Route::get ('/abouts', 'main@abouts');
Route::get ('/contacts', 'main@contacts');
Route::get ('/works', 'works@index');
Route::get ('/works/(:id)', 'works@index($1)');
Route::get ('/works/(:id)-', 'works@index($1)');
Route::get ('/works/(:id)-(:any)', 'works@index($1)');
Route::get ('/work/(:id)', 'works@show($1)');
Route::get ('/work/(:id)-', 'works@show($1)');
Route::get ('/work/(:id)-(:any)', 'works@show($1)');

Route::get ('/login', 'platform@login');
Route::get ('/platform/index', 'platform@login');
Route::get ('/platform', 'platform@login');

Route::group ('admin', function () {
  Route::get ('/', 'main');
  Route::resourcePagination (array ('users'), 'users');
  Route::resourcePagination (array ('works'), 'works');
  Route::resourcePagination (array ('work_tags'), 'work_tags');
  Route::resourcePagination (array ('work_tags', 'tags'), 'work_tag_tags');
  Route::resourcePagination (array ('work_tags', 'works'), 'work_tag_works');
  
  Route::resourcePagination (array ('banners'), 'banners');
  Route::resourcePagination (array ('promos'), 'promos');
  
  Route::resourcePagination (array ('invoice_tags'), 'invoice_tags');
  Route::resourcePagination (array ('invoices'), 'invoices');
});

<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Route::root ('main');
Route::get ('/abouts', 'main@abouts');
Route::resource (array ('contacts'), 'contacts');

Route::get ('/login', 'platform@login');
Route::get ('/platform/index', 'platform@login');
Route::get ('/platform', 'platform@login');

Route::resourcePagination (array ('works'), 'works');
Route::get ('/works/(:id)-', 'works@show($1)');
Route::get ('/works/(:id)-(:any)', 'works@show($1)');
Route::get ('/work/', 'works@index(0)');
Route::get ('/work/(:id)', 'works@show($1)');
Route::get ('/work/(:id)-', 'works@show($1)');
Route::get ('/work/(:id)-(:any)', 'works@show($1)');

Route::resourcePagination (array ('work-tag', 'works'), 'tag_works');
Route::get ('work-tag/(:any)/works/', 'tag_works@index($1, 0)');
Route::get ('work-tag/(:any)/works/(:num)/', 'tag_works@index($1, $2)');

Route::resourcePagination (array ('articles'), 'articles');
Route::get ('/articles/(:id)-', 'articles@show($1)');
Route::get ('/articles/(:id)-(:any)', 'articles@show($1)');
Route::get ('/article/', 'articles@index(0)');
Route::get ('/article/(:id)', 'articles@show($1)');
Route::get ('/article/(:id)-', 'articles@show($1)');
Route::get ('/article/(:id)-(:any)', 'articles@show($1)');

Route::resourcePagination (array ('article-tag', 'articles'), 'tag_articles');
Route::get ('article-tag/(:any)/articles/', 'tag_articles@index($1, 0)');
Route::get ('article-tag/(:any)/articles/(:num)/', 'tag_articles@index($1, $2)');

Route::group ('admin', function () {
  Route::get ('/', 'main');
  Route::resourcePagination (array ('users'), 'users');
  Route::resourcePagination (array ('works'), 'works');
  Route::resourcePagination (array ('work_tags'), 'work_tags');
  Route::resourcePagination (array ('work_tags', 'tags'), 'work_tag_tags');
  // Route::resourcePagination (array ('work_tags', 'works'), 'work_tag_works');
  
  Route::resourcePagination (array ('banners'), 'banners');
  Route::resourcePagination (array ('promos'), 'promos');
  Route::resourcePagination (array ('contacts'), 'contacts');
  
  Route::resourcePagination (array ('article_tags'), 'article_tags');
  Route::resourcePagination (array ('articles'), 'articles');
  
  Route::resourcePagination (array ('invoice_tags'), 'invoice_tags');
  Route::resourcePagination (array ('invoices'), 'invoices');
});
// echo '<meta http-equiv="Content-type" content="text/html; charset=utf-8" /><pre>';
// var_dump (Route::getRoute ());
// exit ();
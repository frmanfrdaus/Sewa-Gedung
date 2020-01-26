<?php

$router->get('/', function () use ($router) {
    return $router->app->version();
});

/*// Gedung
$router->get('/gedung', 'GedungController@index');
$router->post('/gedung', 'GedungController@store');
$router->get('/gedung/{id}', 'GedungController@show');
$router->put('/gedung/{id}', 'GedungController@update');
$router->delete('/gedung/{id}', 'GedungController@destroy');
// pembatalan
$router->get('/pembatalan', 'PembatalanController@index');
$router->post('/pembatalan', 'PembatalanController@store');
$router->get('/pembatalan/{id}', 'PembatalanController@show');
$router->put('/pembatalan/{id}', 'PembatalanController@update');
$router->delete('/pembatalan/{id}', 'PembatalanController@destroy');
// Pembayaran
$router->get('/pembayaran', 'PembayaranController@index');
$router->post('/pembayaran', 'PembayaranController@store');
$router->get('/pembayaran/{id}', 'PembayaranController@show');
$router->put('/pembayaran/{id}', 'PembayaranController@update');
$router->delete('/pembayaran/{id}', 'PembayaranController@destroy');
// pembukuan
$router->get('/pembukuan', 'PembukuanController@index');
$router->post('/pembukuan', 'PembukuanController@store');
$router->get('/pembukuan/{id}', 'PembukuanController@show');
$router->put('/pembukuan/{id}', 'PembukuanController@update');
$router->delete('/pembukuan/{id}', 'PembukuanController@destroy');
// pemesanan
$router->get('/pemesanan', 'PemesananController@index');
$router->post('/pemesanan', 'PemesananController@store');
$router->get('/pemesanan/{id}', 'PemesananController@show');
$router->put('/pemesanan/{id}', 'PemesananController@update');
$router->delete('/pemesanan/{id}', 'PemesananController@destroy');*/

// public route
$router->get('/public/gedung', 'PublicController\GedungController@index');
$router->get('/public/gedung/{id}', 'PublicController\GedungController@show');

$router->group(['prefix' => 'auth'], function () use ($router) {
	$router->post('/register','AuthController@register');
	$router->post('/login','AuthController@login');
});

$router->group(['middleware' => 'auth'], function () use ($router) {
	// pemesanan
	$router->get('/pemesanan', 'PemesananController@index');
	$router->post('/pemesanan', 'PemesananController@store');
	$router->get('/pemesanan/{id}', 'PemesananController@show');
	$router->put('/pemesanan/{id}', 'PemesananController@update');
	$router->delete('/pemesanan/{id}', 'PemesananController@destroy');
	// user
	$router->post('/user', 'UsersController@store');
	$router->get('/users', 'UsersController@index');
	$router->get('/user/{id}', 'UsersController@show');
	$router->put('/user/{id}', 'UsersController@update');
	$router->delete('/user/{id}', 'UsersController@destroy');
	// Gedung
	$router->get('/gedung', 'GedungController@index');
	$router->post('/gedung', 'GedungController@store');
	$router->get('/gedung/{id}', 'GedungController@show');
	$router->put('/gedung/{id}', 'GedungController@update');
	$router->delete('/gedung/{id}', 'GedungController@destroy');
	// pembatalan
	$router->get('/pembatalan', 'PembatalanController@index');
	$router->post('/pembatalan', 'PembatalanController@store');
	$router->get('/pembatalan/{id}', 'PembatalanController@show');
	$router->put('/pembatalan/{id}', 'PembatalanController@update');
	$router->delete('/pembatalan/{id}', 'PembatalanController@destroy');
	// Pembayaran
	$router->get('/pembayaran', 'PembayaranController@index');
	$router->post('/pembayaran', 'PembayaranController@store');
	$router->get('/pembayaran/{id}', 'PembayaranController@show');
	$router->put('/pembayaran/{id}', 'PembayaranController@update');
	$router->delete('/pembayaran/{id}', 'PembayaranController@destroy');
	// pembukuan
	$router->get('/pembukuan', 'PembukuanController@index');
	$router->post('/pembukuan', 'PembukuanController@store');
	$router->get('/pembukuan/{id}', 'PembukuanController@show');
	$router->put('/pembukuan/{id}', 'PembukuanController@update');
	$router->delete('/pembukuan/{id}', 'PembukuanController@destroy');
	
	
});


	
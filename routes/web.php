<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\FamilyTree;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/homepage-birthdays', [FamilyTree::class, 'getBirthdays']);

Route::view('/family-tree', 'main')->middleware('auth');

Route::view('/add-member', 'addMember')->middleware('auth');

Route::view('/update-member', 'updateMember')->middleware('auth');
Route::post('/update-a-member',[FamilyTree::class, 'updateMember']);

\Illuminate\Support\Facades\Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');

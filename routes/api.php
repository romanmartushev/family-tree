<?php

use App\Http\Controllers\FamilyTree;
use Illuminate\Support\Facades\Route;

Route::get('/members', [FamilyTree::class, 'viewTree']);

Route::post('/add-new-member',[FamilyTree::class,'createMember']);

Route::get('/members/all', [FamilyTree::class,'getMembers']);

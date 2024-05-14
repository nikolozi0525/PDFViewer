<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ViewerController extends Controller
{
    //
    public function index() {
        $user = [
            'firstName' => 'Nikolozi',
            'lastName' => 'Svanadze',
            'gmail' => 'nikolozi0622@gmail.com'
        ];

        return view('viewer', compact('user'));
    }
}
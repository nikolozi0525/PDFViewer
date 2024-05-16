<?php

namespace App\Http\Controllers;
use App\Models\Book;


use Illuminate\Http\Request;

class ViewerController extends Controller
{
    //
    public function booklist() {
        
        $allBooks = Book::all();

        return view('viewer.booklist', compact('allBooks'));
    }

    public function view($id) {
        $user = [
            'firstName' => 'Nikolozi',
            'lastName' => 'Svanadze',
            'gmail' => 'nikolozi0622@gmail.com'
        ];
        $book = Book::find($id);
        $book->file = url('storage/' . $book->filepath);
        return view('viewer.viewer', compact('user','book'));
    }
}
<?php

namespace App\Http\Controllers;
use App\Models\Book;
use App\Models\Annotation;


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
            'userId' => 2,
            'username' => 'Nikolozi Svanadze',
            'gmail' => 'nikolozi0622@gmail.com'
        ];
        $book = Book::find($id);
        $book->file = url('storage/' . $book->filepath);
        $annotations = Annotation::where('bookId', $id)->where('userId', $user['userId'])->first();
        if ($annotations) {
            # code...
            $annotations = $annotations->annotations;
        } else {
            $annotations = '[]';
        }
        // dd($annotations);
        return view('viewer.viewer', compact('user','book','annotations'));
    }
}
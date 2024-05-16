<?php

namespace App\Http\Controllers\Api;

use App\Models\Book;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AnnotationController extends Controller
{
    //
    public function store(Request $request) {
        $data = $request->all();
        $id = $data['id'];
        $book = Book::find($id);
        $book->annotations = $data['annotations'];
        $book->save();
        
        return response()->json(['message' => 'saved!'], 200);
    }
}
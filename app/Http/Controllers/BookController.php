<?php

namespace App\Http\Controllers;
use App\Models\Book;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{

    // public function __construct() {
    //     $this->middleware('auth');
    // }
    
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
		$allBooks = Book::all();
		return view('books.index', compact('allBooks')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        $user = [
            'name' => 'Nikolozi',
            'email' => 'nikolozi0626@gmail.com'
        ];
		return view('books.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) : RedirectResponse
    {
        //
        $this->validate($request,[
            'name' => 'required|unique:books',
            'description' => 'string',
            'file' => 'required',
        ]);
        $input = $request->all();   


        if ($bookFile = $request->file('file')) {
            $destinationPath = 'bookstore/';
            $input['extension'] = $bookFile->getClientOriginalExtension();
            $fileName = date('YmdHis') . "." . $input['extension'];
            $bookFile->storeAs($destinationPath, $fileName, 'public');
            $input['filepath'] = $destinationPath . $fileName;
            $input['type'] = $bookFile->getClientMimeType();
            $input['bookId'] = Str::random(40);
        }

  
        Book::create($input);
   
        return redirect()->route('books.index')
                        ->with('success','Book created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): View
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book): View
    {
        return view("books.edit", compact("book"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book) : RedirectResponse
    {

        $input = $request->all();

        if ($bookFile = $request->file('file')) {
            $input['extension'] = $bookFile->getClientOriginalExtension();
            $destinationPath = 'books/';
            $fileName = date('YmdHis') . "." . $input['extension'];
            $bookFile->move($destinationPath, $fileName);
            $input['filepath'] = $destinationPath . $fileName;
            $input['type'] = $bookFile->type;
            $input['bookId'] = Str::random(40);
        }
  
        $book->update($input);
   
        return redirect()->route('books.index')
                        ->with('success','Book updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) : RedirectResponse
    {
        //
        $book = Book::find($id);
        Storage::disk('public')->delete('storage' . $book->filepath);
        $book->delete();
        return redirect()->route("books.index");
    }
}
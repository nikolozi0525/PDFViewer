@extends('layouts.app')

@section('page_title')
    BookList
@endsection

@section('content')
    <h1 class="text-center"> <strong>All Books in Store</strong> </h1>
    <a href="{{ route('books.create') }}" class="btn btn-warning float-right">Add New Book</a>
    <table class="table table-dark text-center mt-5">
        <thead>
            <tr>
                <th>Title</th>
                <th>extensions</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>


            @forelse ($allBooks as $book)
                <tr>
                    <td width="50%"><a href="{{ url('storage/' . $book->filepath) }}" target="_blank"> {{ $book->name }}
                        </a></td>
                    <td>{{ $book->extension }}</td>
                    <td class="d-flex justify-content-center">
                        <a href="{{ route('books.edit', $book->id) }}" class="btn btn-success me-1">Edit</a>
                        @include('partials.delete_button')
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">There are no results</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection

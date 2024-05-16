@extends('layouts.app')

@section('page_title')
    BookList
@endsection

@section('content')
    <h1 class="text-center"> <strong>All Books in Store</strong> </h1>
    <table class="table table-dark text-center mt-5">
        <thead>
            <tr>
                <th>Title</th>
                <th>extensions</th>
            </tr>
        </thead>
        <tbody>


            @forelse ($allBooks as $book)
                <tr>
                    <td width="50%">
                        <a href="/viewer/{{ $book->id }}"> {{ $book->name }}
                        </a>
                    </td>
                    <td>{{ $book->extension }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">There are no results</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection

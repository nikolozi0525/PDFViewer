@extends('layouts.app')

@section('content')
    <h1 class="text-center"> <strong>Edit Book</strong> </h1>
    @include('partials.error_banner')

    <form method="post" class="form form-horizontal" action="{{ route('books.update', $book->id) }}" role="form"
        enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="form-group">
            <label for="name">Book Name</label>
            <input id="name" name="name" value="{{ $book->name }}" type="text" class="form-control"
                placeholder="Enter Book Name">
            @if ($errors->first('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <label for="author">Author</label>
            <input id="author" name="author" value="{{ $book->author }}" type="text" class="form-control"
                placeholder="Enter Author name">
            @if ($errors->first('author'))
                <span class="help-block">
                    <strong>{{ $errors->first('author') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <label for="description">Book Description</label>
            <textarea class="form-control" name="description" id="description" placeholder="Enter description">{{ $book->description }}</textarea>
            @if ($errors->first('description'))
                <span class="help-block">
                    <strong>{{ $errors->first('description') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            <label for="file">File</label>
            <input id="file" name="file" type="file" class="form-control" accept=".pdf,.epub"
                placeholder="Enter Book Sale Price" value="{{ url('storage/' . $book->filepath) }}">
            <span>Accepted formats are : PDF, ePub</span>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection

@section('page_title')
    Edit Book in Store
@endsection

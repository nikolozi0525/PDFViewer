@extends('layouts.app')

@section('content')
    <h1 class="text-center"> <strong>Add New Book</strong> </h1>
    @include('partials.error_banner')

    <form method="post" class="form form-horizontal" action="{{ route('books.store') }}" role="form"
        enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="name">Book Name</label>
            <input id="name" name="name" value="{{ old('name') }}" type="text" class="form-control"
                placeholder="Enter Book Name">
            @if ($errors->first('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group">
            <label for="description">Book Description</label>
            <textarea class="form-control" name="description" id="description" value=" {{ old('description') }}"
                placeholder="Enter description"></textarea>
            @if ($errors->first('description'))
                <span class="help-block">
                    <strong>{{ $errors->first('description') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group">
            <label for="file">File</label>
            <input id="file" name="file" type="file" class="form-control" accept=".pdf,.epub"
                placeholder="Enter Book Sale Price">
            <span>Accepted formats are : PDF, ePub</span>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection

@section('page_title')
    New Books in Store
@endsection

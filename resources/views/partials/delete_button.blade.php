<form class="delete_books" action="{{ route('books.destroy', $book->id) }}" method="post">
    @csrf
    @method('delete')
    <input type="submit" class="btn btn-danger" value="Delete">
</form>

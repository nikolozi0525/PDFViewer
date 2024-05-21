<form class="delete_books" action="{{ route('books.destroy', $book->id) }}" method="post">
    @csrf
    @method('delete')
    
    <button type="submit" class="btn btn-outline-primary" value="Delete"><i class="fas fa-trash-alt"></i></button>
</form>

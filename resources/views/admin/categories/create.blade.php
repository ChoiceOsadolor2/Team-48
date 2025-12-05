<h1>Create Category</h1>

<form method="POST" action="{{ route('categories.store') }}">
    @csrf
    <input type="text" name="name" placeholder="Category Name" required>
    <button type="submit">Save</button>
</form>

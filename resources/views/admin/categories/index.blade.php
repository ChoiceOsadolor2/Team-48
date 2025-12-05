<h1>Categories</h1>

<a href="{{ route('categories.create') }}">+ Add Category</a>

<ul>
@foreach($categories as $c)
    <li>
        {{ $c->name }}

        <a href="{{ route('categories.edit', $c->id) }}">Edit</a>

        <form action="{{ route('categories.destroy', $c->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit">Delete</button>
        </form>
    </li>
@endforeach
</ul>

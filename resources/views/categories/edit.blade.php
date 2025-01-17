@extends('layouts.appcompany')

@section('content')
<div style="margin-top: 150px;">
    <h1>Edit Category</h1>

    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Name:</label>
        <input type="text" name="name" value="{{ $category->name }}" required>

        <label>Code:</label>
        <input type="text" name="code" value="{{ $category->code }}" required>

        <button type="submit">Update Category</button>
    </form>
</div>
@endsection

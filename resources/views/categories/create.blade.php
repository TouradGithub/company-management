@extends('layouts.appcompany')

@section('content')
<div style="margin-top: 150px;">
    <h1>Add New Category</h1>

    @if($errors->any())
        <div style="color: red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <label>Name:</label>
        <input type="text" name="name" required>

        <label>Code:</label>
        <input type="text" name="code" required>

        <button type="submit">Add Category</button>
    </form>
</div>
@endsection

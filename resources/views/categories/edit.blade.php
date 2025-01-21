@extends('layouts.overtime')

@section('content')
<div style="margin-top: 150px;">
    <h1>تعديل الفئة </h1>

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

        <label>الاسم:</label>
        <input type="text" name="name" value="{{ $category->name }}" required>

        <label>الكود:</label>
        <input type="text" name="code" value="{{ $category->code }}" required>

        <button  class="btn btn-primary mb-3" type="submit">تحديث</button>
    </form>
</div>
@endsection

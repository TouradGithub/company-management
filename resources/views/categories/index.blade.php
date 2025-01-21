@extends('layouts.overtime')

@section('content')
@if(session('success'))
<div style="color: green;">{{ session('success') }}</div>
@endif
<section id="overtime-form" class="content-section active">
    <h1>الفئات</h1>



    {{-- <a href="{{ route('categories.create') }}">Add New --}}
    <a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">إضافة فئة  </a>
    {{-- Category</a> --}}
    <div id="entriesContainer">

        <table class="records-table">
        <thead>
            <tr>
                <th>الاسم </th>
                <th>الكود</th>
                <th>الاجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td>{{ $category->code }}</td>
                <td>
                    <a class="btn-primary btn-edit" href="{{ route('categories.edit', $category->id) }}">تعديل</a>
                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="btn-primary btn-delete" type="submit" onclick="return confirm('Are you sure?')">حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
@endsection

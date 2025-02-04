@extends('layouts.mastercomany')

@section('content')
@if(session('success'))
<div style="color: green;">{{ session('success') }}</div>
@endif
<div class="section-header">
    <h2>الفئات</h2>
    <button class="add-deduction-btn">
        <a href="{{route('categories.create')}}">
            <i class="fas fa-plus"></i>

            إضافة فئة
        </a>
    </button>
</div>
  <div class="deductions-table">
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
                    <a  href="{{ route('categories.edit', $category->id) }}" class="action-btn edit-btn">
                        <i class="fas fa-edit"></i>تعديل</a>
                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button class="action-btn delete-btn" type="submit" onclick="return confirm('Are you sure?')">حذف</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
@endsection

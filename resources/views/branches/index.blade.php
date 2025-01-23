@extends('layouts.overtime')

@section('content')
<section id="overtime-form" class="content-section active">
<div >
    <h1>الفروع</h1>
    <a href="{{ route('branches.create') }}" class="btn btn-primary">إضافة فرع </a>
    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif
    <div id="entriesContainer">

        <table class="records-table">
        <thead>
            <tr>
                <th>الاسم</th>
                <th>اسم المدير</th>
                <th>الايميل</th>
                <th>الاجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($companies as $company)
            <tr>
                <td>{{ $company->name }}</td>
                <td>{{ $company->name_admin_company }}</td>
                <td>{{ $company->email }}</td>
                <td>
                    <a class="btn-primary btn-edit" href="{{ route('branches.edit', $company->id) }}">تحديث</a>
                    <form action="{{ route('branches.destroy', $company->id) }}" method="POST" style="display: inline-block;">
                        @csrf
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

@extends('layouts.branch')

@section('content')
<section id="overtime-form" class="content-section active">
<div class="container">
    <h1>المستخدمين</h1>
    <a href="{{ route('users.create') }}" class="btn btn-primary">إضافة مستخدم</a>
    <div id="entriesContainer">

        <table class="records-table">
        <thead>
            <tr>
                <th>الاسم</th>
                <th>الايميل</th>
                <th>الاجراءات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <a class="btn-primary btn-edit" href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm" >Edit</a>
                    <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-primary btn-delete" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
</section>
@endsection

@extends('layouts.appcompany')

@section('content')
<div style="margin-top: 150px;">
    <h1>Edit Branch</h1>

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <form action="{{ route('branches.update', $company->id) }}" method="POST">
        @csrf
        <label>Name:</label>
        <input type="text" name="name" value="{{ $company->name }}" required>

        <label>Name of Admin Branch:</label>
        <input type="text" name="name_admin_company" value="{{ $company->name_admin_company }}" required>

        <label>Email:</label>
        <input type="email" name="email" value="{{ $company->email }}" required>

        <label>Password (leave blank if not changing):</label>
        <input type="password" name="password">

        <button type="submit">Update</button>
    </form>
</div>
@endsection

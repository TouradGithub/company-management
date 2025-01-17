@extends('layouts.appcompany')

@section('content')
<div style="margin-top: 150px;">
    <h1>Branches List</h1>

    @if(session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Admin Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($companies as $company)
            <tr>
                <td>{{ $company->name }}</td>
                <td>{{ $company->name_admin_company }}</td>
                <td>{{ $company->email }}</td>
                <td>
                    <a href="{{ route('branches.edit', $company->id) }}">Edit</a>
                    <form action="{{ route('branches.destroy', $company->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@
@extends('layouts.app')

@section('content')
<div style="margin-top:150px; " class="employees-container" id="employees-container">
    <h2>List Of Company</h2>
    <div class="search-export-container">


    </div>
    <table id="employeesTable">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>

        @foreach($companies as $company)
        <tr>
            <td>{{ $company->name }}</td>
            <td>{{ $company->email ?? 'N/A' }}</td>
            <td>{{ $company->start_date ?? 'N/A' }}</td>
            <td>{{ $company->end_date ?? 'N/A' }}</td>
            <td>{{ $company->status=="active" ?'Active': 'Inactive' }}</td>

        </tr>

        @endforeach
      </tbody>
    </table>
  </div>
  @endsection

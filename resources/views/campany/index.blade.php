@
@extends('layouts.admin')

@section('content')
<section id="overtime-form" class="content-section active">
    <div class="container">
    <h1>الشركات</h1>


    @if(session('success'))
    <div style="color: green; margin-bottom: 20px;">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
        <div style="color: red; margin-bottom: 20px;">
            {{ session('error') }}
        </div>
    @endif
    <div id="entriesContainer">
        <a  class="btn btn-primary" href="{{ route('company.create') }}">إضافة شركة</a>

        <table class="records-table">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>

        @foreach($companies as $company)
        <tr>
            <td>{{ $company->name }}</td>
            <td>{{ $company->email ?? 'N/A' }}</td>
            <td>{{ $company->start_date ?? 'N/A' }}</td>
            <td>{{ $company->end_date ?? 'N/A' }}</td>
            <td>
                @if ($company->status === 'active')
                    <span style="color: green;">Active</span>
                @elseif ($company->status === 'inactive')
                    <span style="color: orange;">Inactive</span>
                @elseif ($company->status === 'cancelled')
                    <span style="color: red;">Cancelled</span>
                @endif
            </td>
            <td>
                @if ($company->status != 'active')
                <form action="{{ route('companies.updateStatus', $company->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" name="status" value="active" style="background: green; color: white; border: none; padding: 5px; cursor: pointer;">
                        Activate
                    </button>
                </form>
                @endif
                @if ($company->status != 'inactive')
                <form action="{{ route('companies.updateStatus', $company->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" name="status" value="inactive" style="background: orange; color: white; border: none; padding: 5px; cursor: pointer;">
                        Deactivate
                    </button>
                </form>
                @endif
                @if ($company->status != 'cancelled')
                <form action="{{ route('companies.updateStatus', $company->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" name="status" value="cancelled" style="background: red; color: white; border: none; padding: 5px; cursor: pointer;">
                        Cancel
                    </button>
                </form>
                @endif
            </td>

        </tr>

        @endforeach
      </tbody>
    </table>
    </div>
  </div>
  @endsection

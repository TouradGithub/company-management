@extends('layouts.appcompany')

@section('content')




<div class="employees-container" id="employees-container">
    <h2>;&#x642;&#x627;&#x626;&#x645;&#x629; &#x627;&#x644;&#x645;&#x648;&#x638;&#x641;&#x64a;&#x646;</h2>
    <div class="search-export-container">
      <div class="search-container">
        <input type="text" id="employeeSearch" placeholder=";&#x628;&#x62d;&#x62b; &#x639;&#x646; &#x645;&#x648;&#x638;&#x641;...">
      </div>
      <div class="export-buttons">
        <button onclick="printReport()" class="print-btn">&#x637;&#x628;&#x627;&#x639;&#x629; &#x627;&#x644;&#x62a;&#x642;&#x631;&#x64a;&#x631;</button>
        <button onclick="exportToExcel()" class="excel-btn">&#x62a;&#x635;&#x62f;&#x64a;&#x631; &#x625;&#x644;&#x649; Excel</button>
      </div>
    </div>
    <table id="employeesTable">
      <thead>
        <tr>
          <th>;&#x627;&#x644;&#x627;&#x633;&#x645;</th>
          <th>;&#x627;&#x644;&#x645;&#x647;&#x646;&#x629;</th>
          <th>;&#x627;&#x644;&#x631;&#x627;&#x62a;&#x628; &#x627;&#x644;&#x623;&#x633;&#x627;&#x633;&#x64a;</th>
          <th>;&#x628;&#x62f;&#x644; &#x627;&#x644;&#x633;&#x643;&#x646;</th>
          <th>;&#x628;&#x62f;&#x644; &#x627;&#x644;&#x645;&#x648;&#x627;&#x635;&#x644;&#x627;&#x62a;</th>
          <th>;&#x628;&#x62f;&#x644; &#x627;&#x644;&#x625;&#x639;&#x627;&#x634;&#x629;</th>
          <th>;&#x627;&#x644;&#x625;&#x636;&#x627;&#x641;&#x64a;</th>
          <th>;&#x627;&#x644;&#x62e;&#x635;&#x648;&#x645;&#x627;&#x62a;</th>
          <th>;&#x625;&#x62c;&#x645;&#x627;&#x644;&#x64a; &#x627;&#x644;&#x631;&#x627;&#x62a;&#x628;</th>
          <th>;&#x627;&#x644;&#x62c;&#x627;&#x631;&#x627;&#x62a;&#x631;</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>

  @endsection

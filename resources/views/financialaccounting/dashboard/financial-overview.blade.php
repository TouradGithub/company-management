@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">لوحة المعلومات المالية</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- إجمالي المبيعات -->
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3 id="totalSales">{{ number_format($totalSales, 2) }}</h3>
                                    <p>إجمالي المبيعات</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                            </div>
                        </div>

                        <!-- رصيد العملاء -->
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3 id="customersBalance">{{ number_format($customersBalance, 2) }}</h3>
                                    <p>رصيد العملاء</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>

                        <!-- رصيد الصندوق -->
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3 id="cashBalance">{{ number_format($cashBalance, 2) }}</h3>
                                    <p>رصيد الصندوق</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                            </div>
                        </div>

                        <!-- الضرائب المستحقة -->
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3 id="taxesPayable">{{ number_format($taxesPayable, 2) }}</h3>
                                    <p>الضرائب المستحقة</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-percent"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- جدول آخر المعاملات -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">آخر القيود اليومية</h3>
                                </div>
                                <div class="card-body table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>رقم القيد</th>
                                                <th>التاريخ</th>
                                                <th>إجمالي المدين</th>
                                                <th>إجمالي الدائن</th>
                                                <th>الحالة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentEntries as $entry)
                                            <tr>
                                                <td>{{ $entry->entry_number }}</td>
                                                <td>{{ $entry->entry_date }}</td>
                                                <td>{{ number_format($entry->total_debit, 2) }}</td>
                                                <td>{{ number_format($entry->total_credit, 2) }}</td>
                                                <td>
                                                    @if(abs($entry->total_debit - $entry->total_credit) < 0.01)
                                                        <span class="badge badge-success">متوازن</span>
                                                    @else
                                                        <span class="badge badge-danger">غير متوازن</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

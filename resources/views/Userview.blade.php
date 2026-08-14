@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row g-3 mb-3">
    @if (session('message'))
        <div id="alert" class="alert alert-success">{{ session('message') }}</div>
    @endif
    
    <div class="col-md-12 col-xxl-3">
        <div class="card h-md-100 ecommerce-card-min-width">
            <div class="card-header pb-0">
                <h6 class="mb-0 mt-2 d-flex align-items-center">Users</h6>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ URL::previous() }}" class="btn btn-sm btn-primary ms-auto">Back </a>
                  </div>
                
            </div>
            <div class="card-body d-flex flex-column justify-content-end">
                <div class="row">
                    <table class="table table-bordered table-striped fs--1 text-center">
                        <thead class="bg-200 text-900">
                            <tr>
                                <th>Total Earn</th>
                                <th>Total Spending</th>
                            </tr>
                        </thead>
                        <tbody >
                            <tr>
                                <td class="text-success">{{ $data['TotalEarn'] ?? '0.00' }}</td>
                                <td class="text-danger">{{ $data['Totalspending'] ?? '0.00' }}</td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <h1>Upcoming Shifts</h1>
                    <table class="table table-bordered table-striped fs--1">
                        <thead class="bg-200 text-900">
                            <tr>
                                <th>Date</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Job Title</th>
                                <th>Pay Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['rota'] as $shift)
                                <tr>
                                    <td>{{ $shift->Date }}</td>
                                    <td>{{ $shift->sTime }}</td>
                                    <td>{{ $shift->eTime }}</td>
                                    <td>{{ $shift->Job_title }}</td>
                                    <td>£{{ $shift->pay_rate }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <h1>Saving Goals</h1>
                    <table class="table table-bordered table-striped fs--1">
                        <thead class="bg-200 text-900">
                            <tr>
                                <th>Goal Name</th>
                                <th>Target Amount</th>
                                <th>Saved Amount</th>
                                <th>Deadline</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['goals'] as $goal)
                                <tr>
                                    <td>{{ $goal->name }}</td>
                                    <td>£{{ $goal->target_amount }}</td>
                                    <td>£{{ $goal->saved_amount }}</td>
                                    <td>{{ $goal->deadline }}</td>
                                    <td>{{ ucfirst($goal->status) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    setTimeout(function() {
        document.getElementById('alert')?.remove();
    }, 3000);
</script>
@endsection
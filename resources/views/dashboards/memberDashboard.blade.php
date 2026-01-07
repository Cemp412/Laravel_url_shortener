@extends('layouts.app')
@section('title', "Client Member Dashboard")
@section('content')
<div class="container">
    <!-- URLs list -->
    <div class="card mb-3">
        <div class=" card-header d-flex justify-content-between align-items-center p-3 border">
            <div>
                <h3 class="mb-0">
                Generated Short URLs
                <button class="btn btn-primary m-2" data-bs-toggle="modal" data-bs-target="#generateUrl" >Generate</button>
            </h3>
            </div>
            
            
            <!-- Filter dropdown -->
            <div>
                <select id="dateFilter" class="form-select d-inline w-auto">
                    <option value="">This Month</option>
                    <option value="today">Today</option>
                    <option value="last_week">Last Week</option>
                    <option value="last_month">Last Month</option>
                </select>

                <!-- Download button -->
                <a href="#" id="downloadCSV" class="btn btn-primary ms-2">Download</a>
            </div>
        </div>

        <!-- Table -->
        <div class="card-body">
            <table class="table table-bodered" id="urlsTable">
                <thead>
                    <tr>                        
                        <th>Short URL</th>
                        <th>Long URL</th>
                        <th>Hits</th>
                        <th>Created On</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/app/member/memberTable.js')
@endpush
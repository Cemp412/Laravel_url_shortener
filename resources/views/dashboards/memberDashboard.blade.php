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
                <button class="btn btn-primary m-2" data-bs-toggle="modal" data-bs-target="#generateUrlModal" >Generate</button>
            </h3>
            </div>
            
            
            <!-- Filter dropdown -->
            <div>
                <select id="dateFilter" class="form-select d-inline w-auto">
                    <option value="">Filter</option>
                    <option value="this_month">This Month</option>
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

    <!-- Generate URL  -->
    <div class="modal fade" id="generateUrlModal" tabindex="-1" aria-labelledby="generateUrlModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('api.short-urls.store') }}" method="POST" id="generateUrlModalForm">
                @csrf
                
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Generate URL</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row justify-content-center">
                            <div class="col-12 mb-3">
                                <label for="original_url" class="form-label">Long Url</label>
                                <input type="text" name="original_url" required class="form-control" id="original-url" placeholder="https://google.com" />
                            </div>
                        </div>
                        
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary justify-content-start"  type="submit" id="generateUrl-btn">Generate</button>
                    </div>
                </div>
                
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @vite('resources/js/app/member/memberTable.js')
    @vite('resources/js/app/shortUrl.js')
@endpush
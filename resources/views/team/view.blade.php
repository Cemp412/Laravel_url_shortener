@extends('layouts.app')
@section('title', "Team Members List")
@section('content')
<div class="container">
    
    <!-- Team Members list-->
    <div class="card mb-3">
        <div class=" card-header d-flex justify-content-between align-items-center p-3 border">
            <h3 class="mb-0">
                Team Members
            </h3>

            <!-- Add new button -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inviteModal" >Invite</button>

        </div>

        <!-- Table -->
        <div class="card-body">
            <table class="table table-bodered" id="teamTable">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Total Generated URLs</th>
                        <th>Total URL Hits</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Invite modal -->
     <div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="inviteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('api.invitations.store') }}" method="POST" id="inviteModalForm">
                @csrf
                
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Invite New Client</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row justify-content-center">
                            <div class="col-4 mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" required class="form-control" id="name" placeholder="@role('admin') User Name @endrole" />
                            </div>

                            <div class="col-4 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" required class="form-control" id="email" placeholder="ex: sample@example.com" />
                            </div>


                            <div class="col-4 mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select name="role" id="role-select" class="form-select">
                                    <option >Select Role</option>
                                </select>
                            </div>
                        </div>
                        
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary justify-content-start"  type="submit" id="sendInvite-btn">Send Invitation</button>
                    </div>
                </div>
                
            </form>
        </div>
     </div>   
     
</div>
@endsection

@push('scripts')
    @vite('resources/js/app/clients/view.js')
    @vite('resources/js/app/team/teamTable.js')
@endpush
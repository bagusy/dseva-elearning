@extends('layouts.app')

@section('content')
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                                <li class="breadcrumb-item" aria-current="page">Users</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">User List</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="card table-card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover" id="pc-dt-simple">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Company</th>
                                        <th>Employees</th>
                                        <th>Status</th>
                                        <th>Subscription</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>
                                            <div class="row">
                                                <div class="col-auto pe-0">
                                                    <img src="{{ $user['avatar'] }}" alt="user-image"
                                                         class="wid-40 rounded-circle">
                                                </div>
                                                <div class="col">
                                                    <h6 class="mb-0">{{ $user['name'] }}</h6>
                                                    <p class="text-muted f-12 mb-0">{{ $user['email'] }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {!! $user['company']['name'] ?? '<span class="badge bg-light-danger rounded-pill f-12">Not Registered</span>' !!}<br>
                                            <small>{{ $user['company']['industry'] ?? '' }}</small>
                                        </td>
                                        <td>{{ count($user['company']['employees']) }}</td>
                                        <td>
                                            @if($user->hasVerifiedEmail())
                                                <span class="badge bg-light-success rounded-pill f-12">Verified</span>
                                            @else
                                                <span class="badge bg-light-secondary rounded-pill f-12">Not Verified</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light-secondary rounded-pill f-12">No Subscription</span>
                                        </td>
                                        <td class="text-center">
                                            <ul class="list-inline me-auto mb-0">
                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
                                                    <a href="#" class="avtar avtar-xs btn-link-success btn-pc-default" data-bs-toggle="modal"
                                                       data-bs-target="#customer-edit_add-modal">
                                                        <i class="ti ti-edit-circle f-18"></i>
                                                    </a>
                                                </li>
                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">
                                                    <a href="#" class="avtar avtar-xs btn-link-danger btn-pc-default">
                                                        <i class="ti ti-trash f-18"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {!! $users->links('vendor.pagination.default') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- [ sample-page ] end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    <div class="modal fade" id="customer-edit_add-modal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="mb-0">Edit Customer</h5>
                    <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-bs-dismiss="modal">
                        <i class="ti ti-x f-20"></i>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-3 text-center">
                            <div class="chat-avtar d-inline-flex mx-auto">
                                <img class="rounded-circle img-fluid wid-70" src="/dashboard/assets/images/user/avatar-5.jpg"
                                     alt="User image">
                            </div>
                        </div>
                        <div class="col-sm-9">
                            <div class="form-group">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" placeholder="Name">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="text" class="form-control" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select class="form-select">
                                    <option>Select Status</option>
                                    <option>Complicated</option>
                                    <option>Single</option>
                                    <option>Relationship</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label" >Location</label>
                                <textarea class="form-control" rows="3" placeholder="Enter Location"></textarea>
                            </div>
                            <div class="form-check form-switch d-flex align-items-center justify-content-between p-0">
                                <label class="form-check-label h5 pe-3 mb-0" for="customSwitchemlnot1">Make Contact Info Public
                                    <span class="text-muted w-75 d-block text-sm f-w-400 mt-2">Means that anyone viewing your profile will be able to see your contacts details</span>
                                </label>
                                <input class="form-check-input h4 m-0 position-relative flex-shrink-0" type="checkbox" id="customSwitchemlnot1" checked="">
                            </div>
                            <hr class="my-3 border border-secondary-subtle">
                            <div class="form-check form-switch d-flex align-items-center justify-content-between p-0">
                                <label class="form-check-label h5 pe-3 mb-0" for="customSwitchemlnot2">Available to hire
                                    <span class="text-muted w-75 d-block text-sm f-w-400 mt-2">Toggling this will let your teammates know that you are available for acquiring new projects</span>
                                </label>
                                <input class="form-check-input h4 m-0 position-relative flex-shrink-0" type="checkbox" id="customSwitchemlnot2" checked="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <ul class="list-inline me-auto mb-0">
                        <li class="list-inline-item align-bottom">
                            <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default w-sm-auto" data-bs-toggle="tooltip" title="Delete">
                                <i class="ti ti-trash f-18"></i>
                            </a>
                        </li>
                    </ul>
                    <div class="flex-grow-1 text-end">
                        <button type="button" class="btn btn-link-danger btn-pc-default" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="/dashboard/assets/js/plugins/simple-datatables.js"></script>
    <script>
        const dataTable = new simpleDatatables.DataTable('#pc-dt-simple', {
            sortable: false,
            perPage: 10
        });
    </script>
@endpush

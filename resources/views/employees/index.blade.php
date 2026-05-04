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
                                <li class="breadcrumb-item" aria-current="page">Employee</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Employee List</h2>
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
                            <div class="text-end p-4 pb-0">
                                <a href="#" class="btn btn-primary d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#employee-add-modal">
                                    <i class="ti ti-plus f-18"></i> Add Employee
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover" id="pc-dt-simple">
                                    <thead>
                                    <tr>
                                        <th>Email</th>
                                        <th>Task</th>
                                        <th>Department</th>
                                        <th>Last Login</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($employees as $employee)
                                        <tr>
                                            <td>
                                                @if(!is_null($employee['user']))
                                                    <div class="row">
                                                        <div class="col-auto pe-0">
                                                            <img src="{{ $employee['user']['avatar'] }}" alt="user-image"
                                                                 class="wid-40 rounded-circle">
                                                        </div>
                                                        <div class="col">
                                                            <h6 class="mb-0">{{ $employee['user']['name'] }}</h6>
                                                            <p class="text-muted f-12 mb-0">{{ $employee['user']['email'] }}</p>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="row">
                                                        <div class="col">
                                                            <h6 class="mb-0">{{ $employee['email'] }}</h6>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!is_null($employee['user']) && count($employee['user']['courseEnrollments']) > 0)
                                                    {{ count($employee['user']['courseEnrollments']) }}
                                                @else
                                                    <span class="badge bg-light-secondary rounded-pill f-12">No Task</span>
                                                @endif
                                            </td>
                                            <td>{{ $employee['department']['name'] ?? 'No Department' }}</td>
                                            <td>{{ now()->format('j F Y, H:i') }}</td>
                                            <td class="text-center">
                                                <ul class="list-inline me-auto mb-0">
                                                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
                                                        <a href="#" class="avtar avtar-xs btn-link-success btn-pc-default" data-bs-toggle="modal"
                                                           data-bs-target="#customer-edit_add-modal">
                                                            <i class="ti ti-edit-circle f-18"></i>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">
                                                        <a href="#" class="avtar avtar-xs btn-link-danger btn-pc-default"
                                                           onclick="$('#delete-employee').attr('action','/company/employees/{{ $employee['id'] }}').submit()">
                                                            <i class="ti ti-trash f-18"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- [ sample-page ] end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>

    <form id="delete-employee" method="POST" action="#" style="display: none">
        @csrf
        <input type="hidden" name="_method" value="DELETE">
    </form>

    <div class="modal fade" id="employee-add-modal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="mb-0">Add Employee</h5>
                    <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-bs-dismiss="modal">
                        <i class="ti ti-x f-20"></i>
                    </a>
                </div>
                <form action="/company/employees" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" placeholder="Email" name="email" required value="{{ old('email') }}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Department</label>
                                    <select class="form-select" name="department_id" required>
                                        @foreach($departments as $department)
                                            <option value="{{ $department['id'] }}" {{ old('department_id') == $department['id']?'selected':'' }}>{{ $department['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if(auth()->user()->can('manage user'))
                                    <div class="form-group">
                                        <label class="form-label">Role</label>
                                        <select class="form-select" name="role" required>
                                            @foreach(\App\Models\User::ROLE_LIST as $role)
                                                <option value="{{ $role }}" {{ old('role') == $role?'selected':'' }}>{{ \App\Models\User::roleString($role) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <hr class="my-3 border border-secondary-subtle">
                                <div class="form-check form-switch d-flex align-items-center justify-content-between p-0">
                                    @if(auth()->user()->can('manage user'))
                                        <label class="form-check-label h5 pe-3 mb-0" for="customSwitchemlnot1">Has User
                                            <span class="text-muted w-75 d-block text-sm f-w-400 mt-2">Means that this employee has access this platform as role that you've been defined above by invitation link</span>
                                        </label>
                                    @else
                                        <label class="form-check-label h5 pe-3 mb-0" for="customSwitchemlnot1">Has Training
                                            <span class="text-muted w-75 d-block text-sm f-w-400 mt-2">Means that this employee can learn in this platform by invitation link</span>
                                        </label>
                                    @endif
                                    <input class="form-check-input h4 m-0 position-relative flex-shrink-0" type="checkbox" {{ old('with_user') || auth()->user()->can('manage user') ?'checked':'' }} name="with_user" id="customSwitchemlnot1" onchange="showFormUser()">
                                </div>
                                <div id="form-user" style="display: {{ old('with_user') || auth()->user()->can('manage user')?'':'none' }}">
                                    <hr class="my-3 border border-secondary-subtle">
                                    <div class="form-group">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" class="form-control" placeholder="Full Name" name="name" value="{{ old('name') }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Select Avatar</label>
                                        <br>
                                        @for($i = 1; $i <= 10; $i ++)
                                            <label>
                                                <input type="radio" name="avatar" value="{{ url('/dashboard/assets/images/user/avatar-' . $i . '.jpg') }}" {{ $i == 1 || old('avatar') == $i?'checked':'' }}>
                                                <img src="/dashboard/assets/images/user/avatar-{{ $i }}.jpg" alt="Avatar {{ $i }}">
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <div class="flex-grow-1 text-end">
                            <button type="button" class="btn btn-link-danger btn-pc-default" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="employee-edit-modal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
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
    <script>
        function showFormUser(){
            var status = document.getElementById('customSwitchemlnot1').checked;
            document.getElementById('form-user').style.display = status === true ? '' : 'none';
        }
    </script>
@endpush

@push('head')
    <style>
        [type=radio] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* IMAGE STYLES */
        [type=radio] + img {
            cursor: pointer;
            border-radius: 50%;
            width: 50px;
            height: auto;
            margin: 3px;
        }

        /* CHECKED STYLES */
        [type=radio]:checked + img {
            outline: 2px solid #c02e2e;
            border-radius: 50%;
        }
    </style>
@endpush

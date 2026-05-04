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
                                <li class="breadcrumb-item" aria-current="page">Departments</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Departments List</h2>
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
                                <a href="#" class="btn btn-primary d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#customer-add-modal">
                                    <i class="ti ti-plus f-18"></i> Add Department
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover" id="pc-dt-simple">
                                    <thead>
                                    <tr>
                                        <th>Department Name</th>
                                        <th>Report to</th>
                                        <th>User Assigned</th>
                                        <th>All Task Done</th>
                                        <th>Task In Progress</th>
                                        <th>Not Started</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($departments as $department)
                                    <tr>
                                        <td><h6 class="mb-0">{{ $department['name'] }}</h6></td>
                                        <td>
                                            @if(is_null($department['report_email']))
                                                <a href="#"  data-bs-toggle="modal" data-bs-target="#customer-edit-modal" onclick="editDepartment('{{ $department['id'] }}', '{{ $department['name'] }}', '{{ $department['report_email'] }}')">Add Email</a>
                                            @else
                                                {{ $department['report_email'] }}
                                            @endif
                                        </td>
                                        <td style="text-align: center">{{ $department->employees()->count() }}</td>
                                        <td style="text-align: center">{{ $department->courseAssignments()->where('is_expired',1)->count() }}</td>
                                        <td style="text-align: center">{{ $department->courseAssignments()->where('is_expired',0)->where('start_date','<=',now())->count() }}</td>
                                        <td style="text-align: center">{{ $department->courseAssignments()->where('is_expired',0)->where('start_date','>',now())->count() }}</td>
                                        <td class="text-center">
                                            <ul class="list-inline me-auto mb-0">
                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Edit">
                                                    <a href="#" class="avtar avtar-xs btn-link-success btn-pc-default" data-bs-toggle="modal"
                                                       data-bs-target="#customer-edit-modal" onclick="editDepartment('{{ $department['id'] }}', '{{ $department['name'] }}', '{{ $department['report_email'] }}')">
                                                        <i class="ti ti-edit-circle f-18"></i>
                                                    </a>
                                                </li>
                                                @if($department->employees()->count() == 0)
                                                <li class="list-inline-item align-bottom" data-bs-toggle="tooltip" title="Delete">
                                                    <a href="#" class="avtar avtar-xs btn-link-danger btn-pc-default" onclick="$('#delete-department').attr('action','/company/departments/{{ $department['id'] }}').submit()">
                                                        <i class="ti ti-trash f-18"></i>
                                                    </a>
                                                </li>
                                                @endif
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
    <form id="delete-department" method="POST" action="#" style="display: none">
        @csrf
        <input type="hidden" name="_method" value="DELETE">
    </form>
    <div class="modal fade" id="customer-add-modal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="mb-0">Add Department</h5>
                    <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-bs-dismiss="modal">
                        <i class="ti ti-x f-20"></i>
                    </a>
                </div>
                <form action="/company/departments" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">Department Name</label>
                                    <input type="text" class="form-control" name="name" required placeholder="Department Name">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Report Email</label>
                                    <input type="text" class="form-control" name="report_email" placeholder="Report Email">
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
    <div class="modal fade" id="customer-edit-modal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="mb-0">Edit Department</h5>
                    <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-bs-dismiss="modal">
                        <i class="ti ti-x f-20"></i>
                    </a>
                </div>
                <form action="#" method="POST" id="form-department-update">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">Department Name</label>
                                    <input type="text" id="name-department" class="form-control" name="name" required placeholder="Department Name">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Report Email</label>
                                    <input type="text" id="report-email-department" class="form-control" name="report_email" placeholder="Report Email">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <div class="flex-grow-1 text-end">
                            <button type="button" class="btn btn-link-danger btn-pc-default" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Update</button>
                        </div>
                    </div>
                </form>
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
        function editDepartment(id, name, report_email) {
            $('#form-department-update').attr('action', "/company/departments/"+id);
            $('#name-department').val(name);
            $('#report-email-department').val(report_email);
        }
    </script>
@endpush

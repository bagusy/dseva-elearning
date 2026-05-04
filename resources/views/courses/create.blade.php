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
                                <li class="breadcrumb-item"><a href="javascript: void(0)">Manage Course</a></li>
                                <li class="breadcrumb-item" aria-current="page">Create</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Add New Course</h2>
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
                    <div class="card">
                        <div class="card-body">
                            <form action="/courses" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12" style="text-align: right">
                                        Status : <span class="badge bg-warning">Draft</span>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Title</label>
                                            <input type="text" class="form-control" name="title" required
                                                   placeholder="Course Title">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control" name="description"
                                                      placeholder="Course Description" rows="4" required></textarea>
                                        </div>
                                    </div>
                                    @if(!auth()->user()->hasRole(\App\Models\User::ROLE_USER_ADMIN))
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Category</label>
                                                <select class="form-select" name="category" required>
                                                    @foreach(\App\Models\Course::CATEGORY_LIST as $category)
                                                        <option value="{{ $category }}">{{ $category }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
{{--                                            <div class="form-group">--}}
{{--                                                <label class="form-label">Price in USD</label>--}}
{{--                                                <input type="number" min="1" name="price_in_usd" class="form-control"--}}
{{--                                                       required--}}
{{--                                                       placeholder="Price in USD">--}}
{{--                                            </div>--}}
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Level</label>
                                                <select class="form-select" name="level" required>
                                                    @foreach(\App\Models\Course::LEVEL_LIST as $level)
                                                        <option value="{{ $level }}">{{ $level }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{--                                        <div class="form-group">--}}
                                            {{--                                            <p id="img-name"><span class="text-danger">*</span> Recommended resolution is 640*320 with file size</p>--}}
                                            {{--                                            <label class="btn btn-outline-secondary" for="flupld"><i--}}
                                            {{--                                                    class="ti ti-upload me-2"></i> Click to Upload</label>--}}
                                            {{--                                            <input type="file" id="flupld" name="images" class="d-none" onchange="showPreview()">--}}
                                            {{--                                        </div>--}}
                                        </div>
                                    @endif
                                    <div class="col-md-12">
                                        <div class="text-end btn-page mb-0 mt-4">
                                            <button type="submit" class="btn btn-primary">Create</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- [ sample-page ] end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
@endsection

@push('script')
    <script>
        function showPreview() {
            var img = document.getElementById('flupld').value.toString();
            document.getElementById('img-name').innerHTML = img.replace("C:\\fakepath\\", "") + ' <a href="javascript:void(0)" style="color: red" onclick="removePreview()">x</a>'
        }

        function removePreview() {
            document.getElementById('flupld').value = ''
            document.getElementById('img-name').innerHTML = '<span class="text-danger">*</span> Recommended resolution is 640*320 with file size'
        }
    </script>
@endpush

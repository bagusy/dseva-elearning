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
                                <li class="breadcrumb-item" aria-current="page">Edit</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Course Edit</h2>
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
                            <form action="/courses/{{ $course['id'] }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="_method" value="PUT">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Title</label>
                                            <input type="text" class="form-control" name="title" required
                                                   value="{{ $course['title'] }}"
                                                   placeholder="Course Title">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control" name="description"
                                                      placeholder="Course Description" rows="4"
                                                      required>{{ $course['description'] }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Category</label>
                                            <select class="form-select" name="category" required>
                                                @foreach(\App\Models\Course::CATEGORY_LIST as $category)
                                                    <option
                                                        value="{{ $category }}" {{ $course['category'] === $category ?'selected':'' }}>{{ $category }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Level</label>
                                            <select class="form-select" name="level" required>
                                                @foreach(\App\Models\Course::LEVEL_LIST as $level)
                                                    <option
                                                        value="{{ $level }}" {{ $course['level'] === $level ?'selected':'' }}>{{ $level }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Price in USD</label>
                                            <input type="number" min="1" name="price_in_usd" class="form-control"
                                                   required value="{{ $course['price_in_usd'] }}"
                                                   placeholder="Price in USD">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="text-end btn-page mb-0 mt-4">
                                            <button type="submit" class="btn btn-primary">Update</button>
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

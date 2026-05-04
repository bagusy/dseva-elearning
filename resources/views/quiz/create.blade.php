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
                                <li class="breadcrumb-item"><a href="javascript: void(0)">Manage Quiz</a></li>
                                <li class="breadcrumb-item" aria-current="page">Create</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Add New Quiz</h2>
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
                            <form action="/quiz" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12" style="text-align: right">
                                        Status : <span class="badge bg-warning">Draft</span>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Quiz Title</label>
                                            <input type="text" class="form-control" name="title" required
                                                   placeholder="Title">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Minimum Score to Pass (0 ~ 100 %)</label>
                                            <input type="number" class="form-control" name="min_score" required
                                                   placeholder="Minimum Score" min="0" max="100">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Total Question Show</label>
                                            <input type="number" class="form-control" name="show_question" required
                                                   placeholder="Total Question Show" min="1" max="20">
                                        </div>
                                    </div>
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

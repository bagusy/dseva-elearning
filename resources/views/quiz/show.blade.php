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
                                <li class="breadcrumb-item" aria-current="page">Detail</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                <h2 class="mb-0">Quiz Detail</h2>
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
                            <div class="row">
                                <div class="col-md-12" style="text-align: right">
                                    Status : <span class="badge bg-warning">Draft</span>
                                </div>
                                <div class="col-md-12">
                                    <table>
                                        <tr>
                                            <td><h4>Title</h4></td>
                                            <td><h4>:</h4></td>
                                            <td><h4>{{ $quiz['title'] }}</h4></td>
                                        </tr>
                                        <tr>
                                            <td><h4>Minimum Score to Pass </h4></td>
                                            <td><h4>:</h4></td>
                                            <td><h4>{{ $quiz['min_score'] }}</h4></td>
                                        </tr>
                                        <tr>
                                            <td><h4>Total Question Show </h4></td>
                                            <td><h4>:</h4></td>
                                            <td><h4>{{ $quiz['show_question'] }}</h4></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card table-card">
                        <div class="card-body">
                            <div class="text-end p-4 pb-0">
                                <a href="#" class="btn btn-primary d-inline-flex align-items-center"
                                   data-bs-toggle="modal" data-bs-target="#add-question-modal">
                                    <i class="ti ti-plus f-18"></i> Add Question
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover" id="pc-dt-simple">
                                    <thead>
                                    <tr>
                                        <th>Question</th>
                                        <th>Answers</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($quiz['items'] as $quizItem)
                                        <tr>
                                            <td>
                                                {{ $quizItem['question'] }}
                                            </td>
                                            <td>
                                                @foreach(json_decode($quizItem['answer'], true) as $item)
                                                    <span class="badge bg-light-{{ $item['status'] == 1?'success':'secondary' }} rounded-pill f-12">{{ $item['answer'] }}</span>
                                                @endforeach
                                            </td>
                                            <td class="text-center">
                                                <ul class="list-inline me-auto mb-0">
                                                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                        title="View">
                                                        <a href="/quiz/{{ $quizItem['id'] }}"
                                                           class="avtar avtar-xs btn-link-secondary btn-pc-default">
                                                            <i class="ti ti-eye f-18"></i>
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
    <div class="modal fade" id="add-question-modal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="mb-0">Upload Video</h5>
                    <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-bs-dismiss="modal">
                        <i class="ti ti-x f-20"></i>
                    </a>
                </div>
                <form action="/quiz/{{ $quiz['id'] }}/items" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">Question</label>
                                    <textarea name="question" class="form-control" placeholder="Question" required
                                              rows="4"></textarea>
                                </div>
                                @for($i = 0; $i < 7; $i ++)
                                    <div class="row mb-2">
                                        <div class="col-md-8">
                                            <input type="text" name="answer[{{ $i }}]" class="form-control"
                                                   placeholder="Answer {{ $i+1 }}">
                                        </div>
                                        <div class="col-md-2">
                                            <input type="radio" class="mt-3" name="status[{{ $i }}]" value="1"> True
                                        </div>
                                        <div class="col-md-2">
                                            <input type="radio" class="mt-3" name="status[{{ $i }}]" value="0" checked>
                                            False
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                        <div class="flex-grow-1 text-end">
                            <button type="button" class="btn btn-link-danger btn-pc-default" data-bs-dismiss="modal">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

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
                                <li class="breadcrumb-item" aria-current="page">Videos</li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <div class="page-header-title">
                                @if(auth()->user()->hasRole(\App\Models\User::ROLE_USER_ADMIN))
                                    <h2 class="mb-0">Private Video List</h2>
                                @else
                                    <h2 class="mb-0">Video List</h2>
                                @endif
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
                                <a href="#" class="btn btn-primary d-inline-flex align-items-center"
                                   data-bs-toggle="modal" data-bs-target="#add-video-modal">
                                    <i class="ti ti-plus f-18"></i> Upload Video
                                </a>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover" id="pc-dt-simple">
                                    <thead>
                                    <tr>
                                        <th>Preview</th>
                                        <th>Creator</th>
                                        <th>Title</th>
                                        <th>Categories</th>
                                        <th>Tags</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($videos as $video)
                                        <tr>
                                            <td><img src="{{ $video['thumbnail_img'] }}"
                                                     style="height: 60px; width: auto"></td>
                                            <td>
                                                <div class="row">
                                                    <div class="col-auto pe-0">
                                                        <img src="{{ $video['user']['avatar'] }}" alt="user-image"
                                                             class="wid-40 rounded-circle">
                                                    </div>
                                                    <div class="col">
                                                        <h6 class="mb-0">{{ $video['user']['name'] }}</h6>
                                                        <p class="text-muted f-12 mb-0">{{ $video['user']['email'] }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $video['title'] }}<br>
                                                <img src="/dashboard/assets/images/icons/{{ $video['source'] }}.png"
                                                     style="height: 15px; width: auto">
                                                <small>{{ $video['created_at']->format('j F Y, H:i') }}</small>
                                            </td>
                                            <td>
                                                <div class="row">
                                                    @foreach($video['categories'] as $category)
                                                        <div class="col-6 m-1">
                                                            <span
                                                                class="badge bg-light-secondary rounded-pill f-12">{{ $category }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td>
                                                <div class="row">
                                                    @foreach($video['tags'] as $tag)
                                                        <div class="col-6 m-1">
                                                            <span
                                                                class="badge bg-light-secondary rounded-pill f-12">{{ $tag }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <ul class="list-inline me-auto mb-0">
                                                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                        title="View">
                                                        <a href="#"
                                                           class="avtar avtar-xs btn-link-secondary btn-pc-default"
                                                           data-bs-toggle="modal" data-bs-target="#videoModal{{ $video['id'] }}">
                                                            <i class="ti ti-eye f-18"></i>
                                                        </a>
                                                    </li>
                                                    @if(auth()->user()->hasRole(\App\Models\User::ROLE_ADMIN) || auth()->user()['id'] === $video['user_id'])
                                                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                        title="Edit">
                                                        <a href="#"
                                                           class="avtar avtar-xs btn-link-success btn-pc-default"
                                                           data-bs-toggle="modal"
                                                           data-bs-target="#edit-video-modal"
                                                           onclick="editVideo('{{ $video['id'] }}', '{{ $video['title'] }}', '{{ $video['link'] }}', '{{ $video['source'] }}', '{{ $video['category'] }}', '{{ $video['tag'] }}', '{{ $video['thumbnail_img'] }}')">
                                                            <i class="ti ti-edit-circle f-18"></i>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item align-bottom" data-bs-toggle="tooltip"
                                                        title="Delete">
                                                        <a href="#"
                                                           class="avtar avtar-xs btn-link-danger btn-pc-default"
                                                           onclick="$('#delete-video').attr('action','/videos/list/{{ $video['id'] }}').submit()">
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
                                {!! $videos->links('vendor.pagination.default') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- [ sample-page ] end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>

    @if(count($videos) > 0)
        @include('layouts.video-modal', ['videos' => $videos])
    @endif

    <form id="delete-video" method="POST" action="#" style="display: none">
        @csrf
        <input type="hidden" name="_method" value="DELETE">
    </form>

    <!-- Modal -->
    <div class="modal fade" id="add-video-modal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="mb-0">Upload Video</h5>
                    <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-bs-dismiss="modal">
                        <i class="ti ti-x f-20"></i>
                    </a>
                </div>
                <form action="/videos/list" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="title" class="form-control" placeholder="Title">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Link <a href="https://youtube.com">Youtube</a> or <a href="https://wistia.com">Wistia</a></label>
                                    <input type="text" name="link" class="form-control" placeholder="Link">
                                </div>
                                @if(!auth()->user()->hasRole(\App\Models\User::ROLE_USER_ADMIN))
                                    <div class="form-group">
                                        <label class="form-label">Categories</label>
                                        @foreach (\app\Models\Video::CATEGORY_LIST as $category)
                                            <br>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="category[]"
                                                                         value="{{ $category }}"> {{ $category }}
                                        @endforeach
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Tags</label>
                                        <select id="select-tag" name="tag[]" multiple="multiple">
                                            @foreach (\app\Models\Video::getAllTag() as $tag)
                                                <option value="{{ $tag }}">{{ $tag }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
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

    <div class="modal fade" id="edit-video-modal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="mb-0">Edit Video</h5>
                    <a href="#" class="avtar avtar-s btn-link-danger btn-pc-default" data-bs-dismiss="modal">
                        <i class="ti ti-x f-20"></i>
                    </a>
                </div>

                <form action="#" method="POST" id="form-video-update">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <img src="#" id="thumbnail-video" style="width: 300px; height: auto">
                            </div>
                            @if(!auth()->user()->hasRole(\App\Models\User::ROLE_USER_ADMIN))
                                <div class="col-sm-6">
                                    <div class="form-group" id="edit-category">
                                        <label class="form-label">Categories</label>
                                    </div>
                                </div>
                            @endif
                            <p>&nbsp;</p>
                            <hr>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="title" id="title-video" class="form-control"
                                           placeholder="Title">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Link <a href="https://youtube.com">Youtube</a> or <a href="https://wistia.com">Wistia</a></label>
                                    <input type="text" name="link" id="link-video" class="form-control" placeholder="Link">
                                </div>
                                @if(!auth()->user()->hasRole(\App\Models\User::ROLE_USER_ADMIN))
                                    <div class="form-group">
                                        <label class="form-label">Tags</label>
                                        <select id="select-tag-2" name="tag[]" multiple="multiple">
                                            @foreach (\app\Models\Video::getAllTag() as $tag)
                                                <option value="{{ $tag }}">{{ $tag }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <div class="flex-grow-1 text-end">
                            <button type="button" class="btn btn-link-danger btn-pc-default" data-bs-dismiss="modal">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('head')
    <link rel="stylesheet" href="/dashboard/assets/css/select/selectize.default.min.css"/>
@endpush

@push('script')
    <script src="https://fast.wistia.net/assets/external/E-v1.js" async></script>
    @if(count($videos) > 0)
        @foreach($videos as $video)
            @if($video['source'] === \App\Models\Video::SOURCE_YOUTUBE)
                <script>
                    $("#videoModal{{ $video['id'] }}").on('hidden.bs.modal', function (e) {
                        $("#videoModal{{ $video['id'] }} iframe").attr("src", $("#videoModal{{ $video['id'] }} iframe").attr("src"));
                    });
                </script>
            @endif
        @endforeach
    @endif
    <script src="/dashboard/assets/js/select/selectize.min.js"></script>
    <script src="/dashboard/assets/js/plugins/simple-datatables.js"></script>
    <script>
        const dataTable = new simpleDatatables.DataTable('#pc-dt-simple', {
            sortable: false,
            perPage: 10
        });
    </script>
    <script>
        function editVideo(id, title, link, source, category, tag, thumbnail) {
            $('#form-video-update').attr('action', "/videos/list/" + id);
            $('#title-video').val(title);
            $('#link-video').val(link);
            $('#thumbnail-video').attr('src', thumbnail);

            @if(!auth()->user()->hasRole(\App\Models\User::ROLE_USER_ADMIN))
                var tagElement = $("#select-tag-2").selectize({
                    delimiter: ",",
                    persist: false,
                    maxItems: null,
                    create: function (input) {
                        return {
                            value: input,
                            text: input,
                        };
                    }
                });
                var control = tagElement[0].selectize
                control.setValue(tag.split(','));

                var categories = category.split(',')
                var categoryElement = document.getElementById('edit-category')
                categoryElement.innerHTML = '';
                @foreach (\App\Models\Video::CATEGORY_LIST as $category)
                    var checked = inArray('{{ $category }}', categories) ? 'checked' : '';
                    categoryElement.insertAdjacentHTML('beforeend', `
                      <br><input type="checkbox" name="category[]" value="{{ $category }}" ${checked}> {{ $category }}
                    `)
                @endforeach
            @endif
        }
        function inArray(needle, haystack) {
            var length = haystack.length;
            for(var i = 0; i < length; i++) {
                if(haystack[i] === needle) return true;
            }
            return false;
        }
    </script>
    <script>
        $("#select-tag").selectize({
            delimiter: ",",
            persist: false,
            maxItems: null,
            create: function (input) {
                return {
                    value: input,
                    text: input,
                };
            }
        });
    </script>
@endpush

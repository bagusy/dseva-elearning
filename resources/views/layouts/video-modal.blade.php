@foreach($videos as $video)
<div class="modal fade" id="videoModal{{ $video['id'] }}" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-body" style="padding: 0px">
                @include('layouts.video-player', ['video' => $video])
            </div>
        </div>
    </div>
</div>
@endforeach

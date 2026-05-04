@if($video['source'] === \App\Models\Video::SOURCE_YOUTUBE)
    <iframe id="ytplayer" type="text/html" width="100%" height="450px" src="{{ $video['link'] }}" frameborder="0"></iframe>
@elseif($video['source'] === \App\Models\Video::SOURCE_WISTIA)
    <iframe src="{{ $video['link'] }}" allowtransparency="true" frameborder="0" scrolling="no" class="wistia_embed" name="wistia_embed" allowfullscreen mozallowfullscreen webkitallowfullscreen oallowfullscreen msallowfullscreen width="100%" height="450px"></iframe>
@endif

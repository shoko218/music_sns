<div class="music_box">
    <div class="music_infos">
        <p><img src="{{ $artwork_url }}" alt="" class="music_artwork"></p>
        <div class="music_text_infos">
            <p class="music_title">{{ $title }}</p>
            <p class="music_artist">{{ $artist }}</p>
            <p class="music_itunes_url"><a href="{{ $itunes_url }}">iTunesでダウンロード</a></p>
        </div>
    </div>
    <audio src="{{ $music_url }}" controls controlslist="nodownload" preload="auto"></audio>
</div>

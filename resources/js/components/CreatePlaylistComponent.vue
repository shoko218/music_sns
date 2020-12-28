<template>
    <div id="create_playlist">
        <div id="create_playlist_info">
            <form action="/playlist/create_process" id="create_playlist_form" method="post">
                <input type="hidden" name="_token" :value="csrf"><!--csrfトークン-->
                <label for="title">プレイリスト名</label>
                <input name="title" id="title" type="text">
                <label for="description">プレイリストの説明</label>
                <textarea name="description" id="description" cols="30" rows="5"></textarea>
            </form>
            <div class="btns">
                <button form="create_playlist_form">登録する</button>
            </div>
        </div>
        <search-music-component ref="search_music" @selected-music="setMusic" @stop-audios="stopAudio"></search-music-component>
        <draggable tag="ul" class="result_lists">
            <li class="music_box search_music_box result_list"  v-for="(r,i) in musics" :key="i">
                <p class="play_btn" @click="audioBtn(i)" v-html="btnInners[i]"></p>
                <div class="music_infos">
                    <p><img :src="r.artwork_url" alt="" class="music_artwork"></p>
                    <div class="music_text_infos">
                        <p class="music_title">{{ r.title }}</p>
                        <p class="music_artist">{{ r.artist }}</p>
                    </div>
                </div>
                <input type="hidden" name="track_ids[]" :value="r.track_id" form="create_playlist_form">
            </li>
        </draggable>
    </div>
</template>

<script>
    import SearchMusicComponent from './SearchMusicComponent';
    import draggable from 'vuedraggable';
    const playBtn='<i class="far fa-play-circle"></i>';
    const stopBtn='<i class="far fa-pause-circle"></i>';

    export default {
        components:{
            SearchMusicComponent,
            'draggable': draggable,
        },
        props: {
            csrf: {//csrfトークン
                type: String,
                required: true,
            },
        },
        data(){
            return {
                musics:[],
                audios:[],
                btnInners:[],
                playingIndex:null,
                music_ids:'',
            }
        },
        mounted() {
        },
        methods:{
            setMusic(track_id){
                axios.get('/api/get_music?track_id='+track_id).then(res => {
                    this.musics.push(res.data.musicInfo);
                    this.audios.push(new Audio(res.data.musicInfo.music_url));
                    this.btnInners.push(playBtn);
                    this.$refs.search_music.reset();//検索結果をリセットする
                });
            },
            audioBtn(id){//曲を再生/停止する
                if(this.audios[id].paused){//止まっている場合
                    this.$refs.search_music.stopAudio();
                    this.stopAudio();
                    this.audios[id].play();
                    this.btnInners.splice(id, 1, stopBtn)
                    this.audios[id].addEventListener('ended',function(){
                        this.btnInners.splice(id, 1, playBtn)
                    }.bind(this));
                    this.playingIndex=id;
                }else{//再生中の場合
                    this.stopAudio();
                }
            },
            stopAudio(){//再生を止める
                if(this.playingIndex!=null){
                    this.audios[this.playingIndex].pause();
                    this.btnInners.splice(this.playingIndex, 1, playBtn)
                    this.playingIndex=null;
                }
            },
        }
    }
</script>


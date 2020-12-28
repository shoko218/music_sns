<template>
    <div id="my_music_parts">
        <p class="my_music_label">イチオシ曲</p>
        <div class="music_box my_music_box" v-if="myMusic!=null"><!--イチオシ曲を登録している場合-->
            <p class="play_btn" @click="audioBtn()" v-html="btnInner"></p>
            <div class="music_infos">
                <p><img :src="myMusic.artwork_url" alt="" class="music_artwork"></p>
                <div class="music_text_infos">
                    <p class="music_title">{{ myMusic.title }}</p>
                    <p class="music_artist">{{ myMusic.artist }}</p>
                    <p class="music_itunes_url"><a :href="myMusic.itunes_url">iTunesでダウンロード</a></p>
                </div>
            </div>
            <div class="my_music_remove_btn" @click="removeMyMusic()">
                <p>×</p>
            </div>
        </div>
        <p v-else-if="dataMusicId!=null">…</p><!--イチオシ曲を登録しているが、曲情報を読み込めていない場合-->
        <p class="my_music_not_set" v-else>まだ設定されていません。</p><!--イチオシ曲を登録していない場合-->
        <div class="btns" v-if="!showed">
            <div class="button reverse_btn" @click="showSearchBar()">＋曲を探す</div>
        </div>
        <search-music-component v-if="showed" ref="search_music" @selected-music="setMusic" @stop-audios="stopAudio"></search-music-component>
    </div>
</template>

<script>
    import Vue from 'vue';
    import Paginate from 'vuejs-paginate';
    import SearchMusicComponent from './SearchMusicComponent';
    Vue.component('paginate', Paginate);
    const playBtn='<i class="far fa-play-circle"></i>';
    const stopBtn='<i class="far fa-pause-circle"></i>';

    export default {
        components:{
            SearchMusicComponent
        },
        props: {
            musicId: {
                type: Number,
            },
        },
        data(){
            return {
                btnInner:playBtn,
                dataMusicId:this.musicId,
                showed:false,
                myMusic:null,
                myAudio:null,
            }
        },
        mounted() {
            if(this.dataMusicId!=null){
                this.getMusicInfo(this.dataMusicId);
            }
        },
        watch:{
            myMusic: function(){//イチオシ音楽が新しく定義された際にAudioオブジェクトを作る
                if(this.myMusic!=null&&this.myMusic.music_url!=null){
                    this.myAudio = new Audio(this.myMusic.music_url);
                }
            },
        },
        methods:{
            getMusicInfo(id){//楽曲情報を取得する
                axios.get('/api/get_music?track_id='+id).then(res => {
                    this.myMusic=res.data.musicInfo;
                });
            },
            setMusic(track_id){//イチオシ音楽を登録する
                var post_data = {
                    'track_id': track_id,
                };
                axios.post('/api/set_my_music',post_data).then(res => {
                    this.dataMusicId=res.data.track_id;
                    this.getMusicInfo(res.data.track_id);
                });
                this.$refs.search_music.reset();//検索結果をリセットする
            },
            audioBtn(){//曲を再生/停止する
                if(this.myAudio.paused){//止まっている場合
                    if(this.showed){
                        this.$refs.search_music.stopAudio();
                    }
                    this.myAudio.play();
                    this.btnInner=stopBtn;
                    this.myAudio.addEventListener('ended',function(){
                        this.btnInner=playBtn;
                    }.bind(this));
                }else{//再生中の場合
                    this.stopAudio();
                }
            },
            stopAudio(){//再生を止める
                this.myAudio.pause();
                this.btnInner=playBtn;
            },
            removeMyMusic(){//イチオシ曲を削除する
                this.myAudio.pause();
                this.btnInner=playBtn;
                axios.post('/api/remove_my_music');
                this.myMusic=null;
                this.dataMusicId=null;
            },
            showSearchBar(){
                if(!this.showed){
                    this.showed=true;
                }
            }
        }
    }
</script>


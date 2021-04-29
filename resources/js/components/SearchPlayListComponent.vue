<template>
    <div id="search_inner">
        <section id="search_data_form" v-if="searchType==0">
            <div class="input_parts">
                <input type="text" v-model="keyword" placeholder="キーワードを入力" id="search_datas_input">
            </div>
            <div class="btns">
                <button v-on:click="searchPlaylist(0)">検索</button>
            </div>
        </section>
        <section id="search_data_form" v-if="searchType==1">
            <div class="music_box select_music_box" v-if="keyMusic!=null"><!--添付する曲を登録している場合-->
                <p class="play_btn" @click="audioBtn()" v-html="btnInner"></p>
                <div class="music_infos">
                    <p><img :src="keyMusic.artwork_url" alt="" class="music_artwork"></p>
                    <div class="music_text_infos">
                        <p class="music_title">{{ keyMusic.title }}</p>
                        <p class="music_artist">{{ keyMusic.artist }}</p>
                        <p class="music_itunes_url"><a :href="keyMusic.itunes_url">iTunesでダウンロード</a></p>
                    </div>
                </div>
                <div class="music_remove_btn" @click="removeKeyMusic()"><!--投稿を削除(☆ワンクッション欲しい)-->
                    <p>×</p>
                </div>
            </div>
            <p v-else-if="keyMusicId!=null"><i class="fas fa-spinner faa-spin animated"></i></p><!--添付する曲を登録しているが、曲情報を読み込めていない場合-->
            <div id="post_search_music_parts" v-if="keyMusic==null">
                <search-music-component ref="search_music" @selected-music="setMusic" @stop-audios="stopAllAudios"></search-music-component>
            </div>
            <div class="btns" v-if="keyMusic!=null">
                <button v-on:click="searchPlaylist(1)">検索</button>
            </div>
        </section>
        <div class="radiobtns">
            <label class="radio" for="word"><input class="radiobtn" type="radio" name="search_type" id="word" v-on:click="changeSearchType(0)" checked>キーワードで検索</label>
            <label class="radio" for="music"><input class="radiobtn" type="radio" name="search_type" id="music" v-on:click="changeSearchType(1)">曲で検索</label>
        </div>
        <show-playlists-component :playlists='playlists' :user-id='userId' v-if="playlists.length"></show-playlists-component>
        <h2 v-else-if="searched">検索結果がありませんでした。</h2>
    </div>
</template>

<script>
    import ShowPlaylistsComponent from './ShowPlaylistsComponent'
    import SearchMusicComponent from './SearchMusicComponent';
    const playBtn='<i class="far fa-play-circle"></i>';
    const stopBtn='<i class="far fa-pause-circle"></i>';

    export default {
        components:{
            ShowPlaylistsComponent,
            SearchMusicComponent
        },
        props:{
            userId:Number,
        },
        data(){
            return {
                keyword:null,
                keyMusicId:null,
                keyMusic:null,
                keyMusicAudio:null,
                playlists:[],
                btnInner:playBtn,
                searchType:0,
                searched:false,
            }
        },
        watch:{
            keyMusic: function(){//投稿する音楽が新しく定義された際にAudioオブジェクトを作る
                if(this.keyMusic!=null&&this.keyMusic.music_url!=null){
                    this.keyMusicAudio = new Audio(this.keyMusic.music_url);
                }
            },
        },
        methods:{
            searchPlaylist(type){
                switch (type) {
                    case 0://ワード検索
                        var post_data = {
                            'keyword': this.keyword,
                        };
                        break;
                    case 1://曲検索
                        var post_data = {
                            'key_music_id': this.keyMusicId,
                        };
                        break;
                }
                if(this.playlists!=null){
                    this.playlists.splice(0, this.playlists.length)
                }
                axios.post('/api/search_playlist',post_data).then(res=>{
                    this.playlists.push(...res.data.results);
                    this.searched=true;
                });
            },
            showImg(imgPath){
                this.showedImgPath=imgPath;
            },
            disappearedImg(){
                this.showedImgPath=null;
            },
            setMusic(track_id){//添付する曲を決定する
                axios.get('/api/get_music?track_id='+track_id).then(res => {
                    this.keyMusic=res.data.musicInfo;
                });
                this.keyMusicId=track_id;
                this.$refs.search_music.reset();//検索結果をリセットする
            },
            audioBtn(){//曲を再生/停止する
                if(this.keyMusicAudio.paused){//止まっている場合
                    this.keyMusicAudio.play();
                    this.btnInner=stopBtn;
                    this.keyMusicAudio.addEventListener('ended',function(){
                        this.btnInner=playBtn;
                    }.bind(this));
                }else{//再生中の場合
                    stopAudio();
                }
            },
            stopAudio(){//再生を止める
                if(this.keyMusicAudio!=null){
                    this.keyMusicAudio.pause();
                    this.btnInner=playBtn;
                }
            },
            stopAllAudios(){//再生を止める
                this.stopAudio();
                if(this.searchType==1&&this.keyMusic==null){
                    this.$refs.search_music.stopAudio();
                }
            },
            removeKeyMusic(){
                this.keyMusicAudio.pause();
                this.btnInner=playBtn;
                this.keyMusicId=null;
                this.keyMusic=null;
            },
            changeSearchType(num){
                if(this.searchType!=num){
                    this.searchType=num;
                    switch (num) {
                        case 0://キーワード検索に変更
                            if(this.keyMusicAudio!=null){
                                this.keyMusicAudio.pause();
                            }
                            this.keyMusicId=null;
                            this.keyMusic=null;
                            this.keyMusicAudio=null;
                            this.searched=false;
                            break;
                        case 1:
                            this.keyword=null;
                            this.searched=false;
                            break;
                    }
                }
            }
        }
    }
</script>


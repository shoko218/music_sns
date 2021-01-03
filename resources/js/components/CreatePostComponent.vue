<template>
    <section id="create_post">
        <form id="create_post_form" action="/home/send_post_process" method="post" enctype='multipart/form-data'><!--投稿フォーム-->
            <input type="hidden" name="_token" :value="csrf"><!--csrfトークン-->
            <textarea name="contents" id="" cols="30" rows="6" placeholder="投稿してみましょう。"></textarea>
            <input id="music_track_id" type="hidden" name="music_track_id" :value="selectedMusicId">
            <div id="post_media_btns">
                <div @click="showSearchBar()"><!--音楽追加ボタン-->
                    <p v-bind:class="{ 'active_media' : selectedMusicId!=null }"><i class="fas fa-music"></i></p>
                </div>
                <label><!--画像追加ボタン-->
                    <p v-bind:class="{ 'active_media' : selectedImg!=null }"><i alt="画像を追加" class="fas fa-camera"></i></p>
                    <input id="post_file_input" type="file" name="image" accept="image/jpeg, image/png" class="file_input" @change="onFileChange($event)">
                </label>
            </div>
        </form>
        <div id="post_medias">
            <div class="music_box select_music_box" id="post_music_preview_parts" v-if="selectedMusic!=null"><!--添付する曲を登録している場合-->
                <p class="play_btn" @click="audioBtn()" v-html="btnInner"></p>
                <div class="music_infos">
                    <p><img :src="selectedMusic.artwork_url" alt="" class="music_artwork"></p>
                    <div class="music_text_infos">
                        <p class="music_title">{{ selectedMusic.title }}</p>
                        <p class="music_artist">{{ selectedMusic.artist }}</p>
                        <p class="music_itunes_url"><a :href="selectedMusic.itunes_url">iTunesでダウンロード</a></p>
                    </div>
                </div>
                <div class="music_remove_btn" @click="removePostMusic()"><!--投稿を削除(☆ワンクッション欲しい)-->
                    <p>×</p>
                </div>
            </div>
            <p v-else-if="selectedMusicId!=null">…</p><!--添付する曲を登録しているが、曲情報を読み込めていない場合-->
            <div id="post_search_music_parts">
                <search-music-component v-if="showed" ref="search_music" @selected-music="setMusic"></search-music-component>
            </div>
            <div id="post_img_preview_parts" v-if="selectedImg!=null"><!--選択した画像がある場合はプレビューを表示-->
                <div id="post_img_preview">
                    <img :src="selectedImg" alt="プレビュー画像">
                </div>
            </div>
        </div>
        <div class="btns">
            <button form="create_post_form">送信</button>
        </div>
    </section>
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
            csrf: {//csrfトークン
                type: String,
                required: true,
            },
        },
        data(){
            return {
                selectedMusicId:null,
                selectedMusic:null,
                selectedAudio:null,
                selectedImg:null,
                showed:false,
                btnInner:playBtn,
            }
        },
        watch:{
            selectedMusic: function(){//投稿する音楽が新しく定義された際にAudioオブジェクトを作る
                if(this.selectedMusic!=null&&this.selectedMusic.music_url!=null){
                    this.selectedAudio = new Audio(this.selectedMusic.music_url);
                }
            },
        },
        methods:{
            setMusic(track_id){//添付する曲を決定する
                axios.get('/api/get_music?track_id='+track_id).then(res => {
                    this.selectedMusic=res.data.musicInfo;
                });
                this.selectedMusicId=track_id;
                this.word="";//検索ワードを削除
                this.$refs.search_music.reset();//検索結果をリセットする
                this.showed=false;
            },
            audioBtn(){//曲を再生/停止する
                if(this.selectedAudio.paused){//止まっている場合
                    this.$emit('stop-show-posts-music');//投稿されている曲もとめる
                    this.selectedAudio.play();
                    this.btnInner=stopBtn;
                    this.selectedAudio.addEventListener('ended',function(){
                        this.btnInner=playBtn;
                    }.bind(this));
                }else{//再生中の場合
                    stopAudio();
                }
            },
            removePostMusic(){//投稿する曲を削除する
                this.selectedAudio.pause();
                this.btnInner=playBtn;
                this.selectedMusicId=null;
                this.selectedMusic=null;
            },
            stopAudio(){//再生を止める
                if(this.selectedAudio!=null){
                    this.selectedAudio.pause();
                    this.btnInner=playBtn;
                }
            },
            showSearchBar(){
                if(this.showed){
                    this.showed=false;
                }else{
                    if(this.selectedMusicId==null){
                        this.showed=true;
                    }
                }
            },
            onFileChange(e){//画像選択
                const files = e.target.files;
                if(files.length > 0) {
                    const file = files[0];
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.selectedImg = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            },
        }
    }
</script>


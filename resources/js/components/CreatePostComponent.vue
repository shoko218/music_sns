<template>
    <div id="create_post">
        <form id="create_post_form" action="/send_post_process" method="post" enctype='multipart/form-data'><!--投稿フォーム-->
            <p>投稿する</p>
            <input type="hidden" name="_token" :value="csrf"><!--csrfトークン-->
            <textarea name="contents" id="" cols="30" rows="6" placeholder=""></textarea>
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
                <div class="search_parts" v-if="showed">
                    <div class="search_inputs">
                        <label for="post_music_search_input">曲を探す</label>
                        <div class="search_input_parts">
                            <input class="search_input" type="text" v-model="word" id="post_music_search_input" placeholder="曲名、アーティスト名で検索">
                            <div class="search_input_btns" v-on:click="searchMusic(word)"><p><i class="fas fa-search"></i></p></div>
                        </div>
                    </div>
                    <div v-if="result.length!=0&&result[0]['track_id']!=-1"><!--検索結果が1つ以上ある場合検索結果を表示-->
                        <p class="result_label">検索結果</p>
                        <ul class="result_lists">
                            <li class="search_music_box music_box result_list"  v-for="(r,i) in result" :key="r.track_id">
                                <p class="play_btn" @click="audioBtn(i)" v-html="btnInners[i]"></p>
                                <div class="music_infos" @click="setPostMusic(r.track_id);">
                                    <p><img :src="r.artwork_url" alt="" class="music_artwork"></p>
                                    <div class="music_text_infos">
                                        <p class="music_title">{{ r.title }}</p>
                                        <p class="music_artist">{{ r.artist }}</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <paginate
                        v-model="currentPage"
                        :page-count="pageCount"
                        :page-range="3"
                        :click-handler="pageChange"
                        :prev-text="'<'"
                        :next-text="'>'"
                        :container-class="'pagination'"
                        :page-class="'page-item'"
                        :page-link-class="'page-link'"
                        :prev-class="'page-arrow-item'"
                        :prev-link-class="'page-link'"
                        :next-class="'page-arrow-item'"
                        :next-link-class="'page-link'">
                    </paginate>
                    </div>
                    <p v-else-if="results.length!=0&&result[0]['track_id']==-1">見つかりませんでした。</p><!--検索結果が1つもない場はメッセージを表示-->
                </div>
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
    </div>
</template>

<script>
    import Vue from 'vue';
    import Paginate from 'vuejs-paginate';
    Vue.component('paginate', Paginate);
    const playBtn='<i class="far fa-play-circle"></i>';
    const stopBtn='<i class="far fa-pause-circle"></i>';

    export default {
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
                word:"",
                btnInner:playBtn,
                btnInners:[],
                results:[],
                result:[],
                audios:[],
                currentPage: 1,
                numOfPage:5,
                pageCount:0,
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
            searchMusic(word){//楽曲情報を検索する
                if(word!=""){
                    axios.get('/api/search_music?word='+word).then(res => {
                        this.results=res.data.results;
                        this.pageCount=Math.ceil(res.data.results.length/this.numOfPage);
                        this.currentPage=1;
                        this.pageChange();
                    });
                }
            },
            getPostMusic(id){//曲の楽曲情報を取得する
                axios.get('/api/get_music?track_id='+id).then(res => {
                    this.selectedMusic=res.data.musicInfo;
                });
            },
            setPostMusic(track_id){//添付する曲を決定する
                this.getPostMusic(track_id);
                this.selectedMusicId=track_id;
                this.word="";//検索ワードを削除
                this.stopAllAudios();//再生している全ての音楽を止める
                this.results.splice(0, this.results.length);//検索結果にまつわる全ての情報を削除する
                this.results.splice(0, this.results.length);
                this.result.splice(0, this.result.length);
                this.audios.splice(0, this.audios.length);
                this.showed=false;
            },
            audioBtn(id){//曲を再生/停止する
                if(id!=null){//対象が検索結果の曲
                    if(this.audios[id].paused){//止まっている場合
                        this.$emit('stop-show-posts-music');//投稿されている曲もとめる
                        this.stopAllAudios();
                        this.audios[id].play();
                        this.btnInners.splice(id, 1, stopBtn)
                        this.audios[id].addEventListener('ended',function(){
                            this.btnInners.splice(id, 1, playBtn)
                        }.bind(this));
                    }else{//再生中の場合
                        this.audios[id].pause();
                        this.btnInners.splice(id, 1, playBtn)
                    }
                }else{//対象が添付する曲
                    if(this.selectedAudio.paused){//止まっている場合
                        this.$emit('stop-show-posts-music');//投稿されている曲もとめる
                        this.stopAllAudios();
                        this.selectedAudio.play();
                        this.btnInner=stopBtn;
                        this.selectedAudio.addEventListener('ended',function(){
                            this.btnInner=playBtn;
                        }.bind(this));
                    }else{//再生中の場合
                        this.selectedAudio.pause();
                        this.btnInner=playBtn;
                    }
                }
            },
            removePostMusic(){//投稿する曲を削除する
                this.selectedAudio.pause();
                this.btnInner=playBtn;
                this.selectedMusicId=null;
                this.selectedMusic=null;
                this.selectedMusicId=null;
            },
            pageChange(){//検索ページの切り替え
                this.result=this.results.slice(this.numOfPage*(this.currentPage-1),this.numOfPage*this.currentPage);
                this.stopAllAudios(false);
                this.audios=[];
                for (let i = 0; i < this.result.length; i++) {
                    this.audios[i]=new Audio(this.result[i].music_url);
                    this.btnInners[i]=playBtn;
                }
            },
            stopAllAudios(DoIncludeMyAudio=true){//全ての音楽を止める(☆効率化したい)
                if(this.selectedAudio!=null&&DoIncludeMyAudio){//イチオシ音楽を止める
                    this.selectedAudio.pause();
                    this.btnInner=playBtn;
                }
                if(this.audios.length!=0){//検索結果の音楽を止める
                    this.btnInner=playBtn;
                    for (let i = 0; i < this.audios.length; i++) {
                        this.btnInners.splice(i, 1, playBtn);
                        this.audios[i].pause();
                    }
                }
            },
            showSearchBar(){
                if(this.showed){
                    this.showed=false;
                }else{
                    this.showed=true;
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


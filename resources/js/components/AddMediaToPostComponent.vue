<template>
    <div id="post_medias">
        <div id="post_media_btns">
            <label>
                <p v-bind:class="{ 'active_media' : selectedImg!=null }"><i alt="画像を追加" class="fas fa-camera"></i></p>
                <input id="post_file_input" type="file" name="image" accept="image/jpeg, image/png" class="file_input" @change="onFileChange($event)">
            </label>
            <div @click="showSearchBar()">
                <p v-bind:class="{ 'active_media' : selectedMusicId!=null }"><i class="fas fa-music"></i></p>
            </div>
        </div>
        <div id="post_search_music_parts">
            <div id="music_search_parts" v-if="showed">
                <div class="music_search_inputs">
                    <label for="post_music_search_input">曲を探す</label>
                    <div class="music_search_input_parts">
                        <input class="music_search_input" type="text" v-model="word" id="post_music_search_input" placeholder="曲名、アーティスト名で検索">
                        <div class="music_search_input_btns" v-on:click="searchMusic(word)"><p><i class="fas fa-search"></i></p></div>
                    </div>
                </div>
                <div v-if="result.length!=0&&result[0]['track_id']!=-1"><!--検索結果が1つ以上ある場合-->
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
                <p v-else-if="results.length!=0&&result[0]['track_id']==-1">見つかりませんでした。</p><!--検索結果が1つもない場合-->
            </div>
            <input id="track_id" type="hidden" name="track_id" :value="selectedMusicId">
        </div>
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
            <div class="music_remove_btn" @click="removePostMusic()">
                <p>×</p>
            </div>
        </div>
        <p v-else-if="selectedMusicId!=null">…</p><!--添付する曲を登録しているが、曲情報を読み込めていない場合-->
        <div id="post_img_preview_parts" v-if="selectedImg!=null"><!--新しく選択した画像がある場合-->
            <div id="post_img_preview">
                <img :src="selectedImg" alt="プレビュー画像">
            </div>
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
            selectedMusic: function(){//イチオシ音楽が新しく定義された際にAudioオブジェクトを作る
                if(this.selectedMusic!=null&&this.selectedMusic.music_url!=null){
                    this.selectedAudio = new Audio(this.selectedMusic.music_url);
                    this.selectedAudio.addEventListener('ended',function(){
                        this.btnInner=playBtn;
                    }.bind(this));
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
            getPostMusic(id){//添付する曲の楽曲情報を取得する
                axios.get('/api/get_music?track_id='+id).then(res => {
                    this.selectedMusic=res.data.musicInfo;
                });
            },
            setPostMusic(track_id){//添付する曲を決定する
                var post_data = {
                    'track_id': track_id,
                };
                axios.post('/api/set_my_music',post_data).then(res => {
                    this.selectedMusicId=res.data.track_id;
                    this.getPostMusic(res.data.track_id);
                });
                this.word="";//検索ワードを削除
                this.stopAllAudios();//再生している全ての音楽を止める
                this.results.splice(0, this.results.length);//検索結果にまつわる全ての情報を削除する
                this.results.splice(0, this.results.length);
                this.result.splice(0, this.result.length);
                this.audios.splice(0, this.audios.length);
                this.showed=false;
            },
            audioBtn(id){//曲を再生/停止する
                if(id!=null){//検索結果の曲
                    if(this.audios[id].paused){//止まっている場合
                        this.stopAllAudios();
                        this.audios[id].play();
                        this.btnInners.splice(id, 1, stopBtn)
                    }else{//再生中の場合
                        this.audios[id].pause();
                        this.btnInners.splice(id, 1, playBtn)
                    }
                }else{//添付する
                    if(this.selectedAudio.paused){//止まっている場合
                        this.stopAllAudios();
                        this.selectedAudio.play();
                        this.btnInner=stopBtn;
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
            stopAllAudios(DoIncludeMyAudio=true){//全ての音楽を止める
                if(this.selectedAudio!=null&&DoIncludeMyAudio)this.selectedAudio.pause();//イチオシ音楽を止める
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
            }
        }
    }
</script>


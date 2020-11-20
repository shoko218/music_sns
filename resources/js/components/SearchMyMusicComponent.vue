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
        <div class="my_music_search_parts" v-if="showed">
            <div class="music_search_inputs">
                <p>曲を探す</p>
                <div class="music_search_input_parts">
                    <input class="music_search_input" type="text" v-model="word" placeholder="曲名、アーティスト名で検索">
                    <div class="music_search_input_btns" v-on:click="searchMusic(word)"><p><i class="fas fa-search"></i></p></div>
                </div>
            </div>
            <div v-if="result.length!=0&&result[0]['track_id']!=-1"><!--検索結果が1つ以上ある場合-->
                <p class="result_label">検索結果</p>
                <ul class="result_lists">
                    <li class="music_box search_music_box result_list"  v-for="(r,i) in result" :key="r.track_id">
                        <p class="play_btn" @click="audioBtn(i)" v-html="btnInners[i]"></p>
                        <div class="music_infos" @click="setMyMusic(r.track_id);">
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
            musicId: {
                type: Number,
            },
        },
        data(){
            return {
                dataMusicId:this.musicId,
                showed:false,
                myMusic:null,
                myAudio:null,
                word:"",
                btnInner:playBtn,
                btnInners:[],
                results:[],
                result:[],
                audios:[],
                currentPage: 1,
                numOfPage:10,
                pageCount:0,
            }
        },
        mounted() {
            if(this.dataMusicId!=null){
                this.getMyMusic(this.dataMusicId);
            }
        },
        watch:{
            myMusic: function(){//イチオシ音楽が新しく定義された際にAudioオブジェクトを作る
                if(this.myMusic!=null&&this.myMusic.music_url!=null){
                    this.myAudio = new Audio(this.myMusic.music_url);
                    this.myAudio.addEventListener('ended',function(){
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
            getMyMusic(id){//イチオシ音楽にする曲の楽曲情報を取得する
                axios.get('/api/get_music?track_id='+id).then(res => {
                    this.myMusic=res.data.musicInfo;
                });
            },
            setMyMusic(track_id){//イチオシ音楽を登録する
                var post_data = {
                    'track_id': track_id,
                };
                axios.post('/api/set_my_music',post_data).then(res => {
                    this.dataMusicId=res.data.track_id;
                    this.getMyMusic(res.data.track_id);
                });
                this.word="";//検索ワードを削除
                this.stopAllAudios();//再生している全ての音楽を止める
                this.results.splice(0, this.results.length);//検索結果にまつわる全ての情報を削除する
                this.results.splice(0, this.results.length);
                this.result.splice(0, this.result.length);
                this.audios.splice(0, this.audios.length);
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
                }else{//イチオシ曲
                    if(this.myAudio.paused){//止まっている場合
                        this.stopAllAudios();
                        this.myAudio.play();
                        this.btnInner=stopBtn;
                    }else{//再生中の場合
                        this.myAudio.pause();
                        this.btnInner=playBtn;
                    }
                }
            },
            removeMyMusic(){//イチオシ曲を削除する
                this.myAudio.pause();
                this.btnInner=playBtn;
                axios.post('/api/remove_my_music');
                this.myMusic=null;
                this.dataMusicId=null;
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
                if(this.myAudio!=null&&DoIncludeMyAudio)this.myAudio.pause();//イチオシ音楽を止める
                if(this.audios.length!=0){//検索結果の音楽を止める
                    this.btnInner=playBtn;
                    for (let i = 0; i < this.audios.length; i++) {
                        this.btnInners.splice(i, 1, playBtn);
                        this.audios[i].pause();
                    }
                }
            },
            showSearchBar(){
                if(!this.showed){
                    this.showed=true;
                }
            }
        }
    }
</script>


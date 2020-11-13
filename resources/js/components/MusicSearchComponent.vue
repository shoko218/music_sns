<template>
    <div id="my_music_parts">
        <p class="my_music_label">イチオシ曲</p>
        <div class="my_music_box" v-if="myMusic!=null">
            <p class="play_btn" @click="playAudio()" v-html="btnInner"><i class="far fa-play-circle"></i></p>
            <div class="my_music_infos">
                <p><img :src="myMusic.artwork_url" alt="" class="my_music_artwork"></p>
                <div class="my_music_text_infos">
                    <p class="my_music_title">{{ myMusic.title }}</p>
                    <p class="my_music_artist">{{ myMusic.artist }}</p>
                    <p class="my_music_itunes_url"><a :href="myMusic.itunes_url">iTunesでダウンロード</a></p>
                </div>
            </div>
            <div class="my_music_remove_btn" @click="removeMyMusic()">
                <p>×</p>
            </div>
        </div>
        <p v-else-if="dataMusicId!=null">…</p>
        <p class="my_music_not_set" v-else>まだ設定されていません。</p>
        <div class="my_music_search_parts">
            <div class="my_music_search_inputs">
                <p>曲を探す</p>
                <div class="my_music_search_input_parts">
                    <input class="my_music_search_input" type="text" v-model="word" placeholder="曲名、アーティスト名で検索">
                    <div class="search_input_btns" v-on:click="searchMusic(word)"><p><i class="fas fa-search"></i></p></div>
                </div>
            </div>
            <div v-if="result.length!=0&&result[0]['track_id']!=-1">
                <p class="result_label">検索結果</p>
                <ul class="result_lists">
                    <li class="search_music_box result_list"  v-for="(r,i) in result" :key="r.track_id">
                        <p class="play_btn" @click="playAudio(i)" v-html="btnInners[i]"><i class="far fa-play-circle"></i></p>
                        <div class="search_music_infos" @click="setMyMusic(r.track_id);">
                            <p><img :src="r.artwork_url" alt="" class="search_music_artwork"></p>
                            <div class="search_music_text_infos">
                                <p class="search_music_title">{{ r.title }}</p>
                                <p class="search_music_artist">{{ r.artist }}</p>
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
            <p v-else-if="results.length!=0&&result[0]['track_id']==-1">見つかりませんでした。</p>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    import Paginate from 'vuejs-paginate';
    Vue.component('paginate', Paginate);
    export default {
        props: {
            musicId: {
                type: Number,
            },
        },
        data(){
            return {
                dataMusicId:this.musicId,
                myMusic:null,
                myAudio:null,
                word:"",
                btnInner:'<i class="far fa-play-circle"></i>',
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
                this.getMusic(this.dataMusicId);
            }
        },
        watch:{
            myMusic: function(){
                if(this.myMusic!=null&&this.myMusic.music_url!=null){
                    this.myAudio = new Audio(this.myMusic.music_url);
                    this.myAudio.addEventListener('ended',function(){
                        this.btnInner='<i class="far fa-play-circle"></i>';
                    }.bind(this));
                }
            },
        },
        methods:{
            searchMusic(word){
                if(word!=""){
                    axios.get('/search_music?word='+word).then(res => {
                        this.results=res.data.results;
                        this.pageCount=Math.ceil(res.data.results.length/this.numOfPage);
                        this.currentPage=1;
                        this.pageChange();
                    });
                }
            },
            getMusic(id){
                axios.get('/get_music?track_id='+id).then(res => {
                    this.myMusic=res.data.musicInfo;
                });
            },
            setMyMusic(track_id){
                var post_data = {
                    'track_id': track_id,
                };
                axios.post('/set_my_music',post_data).then(res => {
                    this.dataMusicId=res.data.track_id;
                    this.getMusic(this.dataMusicId);
                    this.word="";
                    this.results.splice(0, this.results.length);
                });
                this.stopAllAudios();
                this.results.splice(0, this.results.length);
                this.result.splice(0, this.result.length);
                this.audios.splice(0, this.audios.length);
            },
            playAudio(id){
                if(id!=null){
                    if(this.audios[id].paused){
                        this.stopAllAudios();
                        this.audios[id].play();
                        this.btnInners.splice(id, 1, '<i class="far fa-pause-circle"></i>')
                    }else{
                        this.audios[id].pause();
                        this.btnInners.splice(id, 1, '<i class="far fa-play-circle"></i>')
                    }
                }else{
                    if(this.myAudio.paused){
                        this.stopAllAudios();
                        this.myAudio.play();
                        this.btnInner='<i class="far fa-pause-circle"></i>';
                    }else{
                        this.myAudio.pause();
                        this.btnInner='<i class="far fa-play-circle"></i>';
                    }
                }
            },
            removeMyMusic(){
                this.myAudio.pause();
                this.btnInner='<i class="far fa-play-circle"></i>';
                axios.post('/remove_my_music');
                this.myMusic=null;
                this.dataMusicId=null;
            },
            pageChange(){
                this.result=this.results.slice(this.numOfPage*(this.currentPage-1),this.numOfPage*this.currentPage);
                this.stopAllAudios(false);
                this.audios=[];
                for (let i = 0; i < this.result.length; i++) {
                    this.audios[i]=new Audio(this.result[i].music_url);
                    this.btnInners[i]='<i class="far fa-play-circle"></i>';
                }
            },
            stopAllAudios(DoIncludeMyAudio=true){
                if(this.myAudio!=null&&DoIncludeMyAudio)this.myAudio.pause();
                if(this.audios.length!=0){
                    this.btnInner='<i class="far fa-play-circle"></i>';
                    for (let i = 0; i < this.audios.length; i++) {
                        this.btnInners.splice(i, 1, '<i class="far fa-play-circle"></i>');
                        this.audios[i].pause();
                    }
                }
            }
        }
    }
</script>


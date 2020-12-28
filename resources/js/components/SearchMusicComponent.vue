<template>
    <div class="search_parts">
        <div class="search_inputs">
            <p>曲を探す</p>
            <div class="search_input_parts">
                <input class="search_input" type="text" v-model="word" placeholder="曲名、アーティスト名で検索">
                <div class="search_input_btns" v-on:click="searchMusic(word)"><p><i class="fas fa-search"></i></p></div>
            </div>
        </div>
        <div v-if="result.length!=0&&result[0]['track_id']!=-1"><!--検索結果が1つ以上ある場合-->
            <p class="result_label">検索結果</p>
            <ul class="result_lists">
                <li class="music_box search_music_box result_list"  v-for="(r,i) in result" :key="r.track_id">
                    <p class="play_btn" @click="audioBtn(i)" v-html="btnInners[i]"></p>
                    <div class="music_infos" @click="selectMusic(r.track_id);">
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
</template>

<script>
    import Vue from 'vue';
    import Paginate from 'vuejs-paginate';
    Vue.component('paginate', Paginate);
    const playBtn='<i class="far fa-play-circle"></i>';
    const stopBtn='<i class="far fa-pause-circle"></i>';
    export default {
        props: {
        },
        data(){
            return {
                word:"",
                btnInners:[],
                results:[],//結果全体
                result:[],//現在表示している結果
                audios:[],
                currentPage: 1,
                numOfPage:10,
                pageCount:0,
                playingIndex:null,
            }
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
            audioBtn(id){//曲を再生/停止する
                if(this.audios[id].paused){//止まっている場合
                    this.$emit('stop-audios');
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
            selectMusic(track_id){//曲を選択
                this.$emit('selected-music',track_id);
            },
            pageChange(){//検索ページの切り替え
                this.result=this.results.slice(this.numOfPage*(this.currentPage-1),this.numOfPage*this.currentPage);
                this.stopAudio();
                this.audios=[];
                for (let i = 0; i < this.result.length; i++) {
                    this.audios[i]=new Audio(this.result[i].music_url);
                    this.btnInners[i]=playBtn;
                }
            },
            stopAudio(){//今流れている検索結果の音楽を止める
                if(this.playingIndex!=null){
                    this.audios[this.playingIndex].pause();
                    this.btnInners.splice(this.playingIndex, 1, playBtn)
                    this.playingIndex=null;
                }
            },
            reset(){
                this.stopAudio();
                this.word="";//検索ワードを削除
                this.results.splice(0, this.results.length);//検索結果にまつわる全ての情報を削除する
                this.results.splice(0, this.results.length);
                this.result.splice(0, this.result.length);
                this.audios.splice(0, this.audios.length);
            }
        }
    }
</script>


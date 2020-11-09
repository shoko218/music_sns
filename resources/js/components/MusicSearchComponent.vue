<template>
    <div id="my_music_parts">
        <p class="my_music_label">イチオシ曲</p>
        <div class="my_music_box" v-if="title!=null&&artist!=null">
            <p class="play_btn" @click="playAudio()" v-html="btn_inner"><i class="far fa-play-circle"></i></p>
            <div class="my_music_infos">
                <p><img :src="artworkUrl" alt="" class="my_music_artwork"></p>
                <div class="my_music_text_infos">
                    <p class="my_music_title">{{ title }}</p>
                    <p class="my_music_artist">{{ artist }}</p>
                    <p class="my_music_itunes_url"><a :href="itunesUrl">iTunesでダウンロード</a></p>
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
            <div v-if="results!=null&&results[0]['trackId']!=-1">
                <p class="result_label">検索結果</p>
                <ul class="result_lists">
                    <li class="result_list" v-for="r in results" :key="r.trackId" @click="setMyMusic(r.trackId);"><span class="result_list_title">{{r.title}}</span><br><span class="result_list_artist">{{r.artist}}</span></li>
                </ul>
            </div>
            <p v-else-if="results!=null&&results[0]['trackId']==-1">見つかりませんでした。</p>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            musicId: {
                type: Number,
            },
        },
        data(){
            return {
                dataMusicId:this.musicId,
                title:null,
                artist:null,
                word:null,
                results:null,
                artWorkUrl:null,
                musicUrl:null,
                itunesUrl:null,
                doSearch:false,
                audio:false,
                btn_inner:'<i class="far fa-play-circle"></i>',
            }
        },
        mounted() {
            if(this.dataMusicId!=null){
                this.getMusic(this.dataMusicId);
            }
        },
        watch:{
            musicUrl: function(){
                if(this.musicUrl!=null){
                    this.audio = new Audio(this.musicUrl);
                }
            },
        },
        methods:{
            searchMusic(word){
                if(word!=null){
                    axios.get('/search_music?word='+word).then(res => {
                        this.results=res.data.results;
                    });
                }
            },
            getMusic(id){
                axios.get('/get_music?track_id='+id).then(res => {
                    this.title=res.data.music_info.title;
                    this.artist=res.data.music_info.artist;
                    this.dataMusicId=res.data.music_info.track_id;
                    this.artworkUrl=res.data.music_info.artwork_url;
                    this.musicUrl=res.data.music_info.music_url;
                    this.itunesUrl=res.data.music_info.itunes_url;
                });
            },
            setMyMusic(track_id){
                var post_data = {
                    'track_id': track_id,
                };
                axios.post('/set_my_music',post_data).then(res => {
                    this.dataMusicId=res.data.track_id;
                    this.getMusic(this.dataMusicId);
                    this.word=null;
                    this.results=null;
                });
            },
            playAudio(){
                if(this.audio.paused){
                    this.audio.play();
                    this.btn_inner='<i class="far fa-pause-circle"></i>';
                }else{
                    this.audio.pause();
                    this.btn_inner='<i class="far fa-play-circle"></i>';
                }
            },
            removeMyMusic(){
                axios.post('/remove_my_music');
                this.dataMusicId=null;
                this.title=null;
                this.artist=null;
                this.dataMusicId=null;
                this.artworkUrl=null;
                this.musicUrl=null;
                this.itunesUrl=null;
                this.audio=null;
            },
        }
    }
</script>


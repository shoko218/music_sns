<template>
    <div id="show_playlist">
        <p class="playlist_msg" v-if="repostUserName!=null"><i class="fas fa-retweet"></i> {{repostUserName}}さんが拡散しました</p>
        <section id="show_playlist_info">
            <div id="show_playlist_info_img">
                <img :src="'/storage/playlist_imgs/'+dataPlaylist['img_path']" v-if="dataPlaylist['img_path']!=null">
                <img src="/storage/playlist_imgs/noimage.png" v-else>
            </div>
            <div id="show_playlist_info_detail">
                <h1>{{dataPlaylist.title}}</h1>
                <p>{{dataPlaylist.description}}</p>
                <p>作成者: <a :href="'/user/'+dataPlaylist.user.user_name"><img :src="'/storage/user_icons/'+dataPlaylist.user.icon_path"> {{dataPlaylist.user.name}}@{{dataPlaylist.user.user_name}}</a></p>
            </div>
        </section>
        <section class="action_btns">
                <p><i class="fas fa-comment"></i></p><!--リプライ(☆未実装)-->
                <p @click="repostBtn()" v-bind:class="{ 'reposted' : dataPlaylist.reposted }"><i class="fas fa-retweet"></i></p><!--リツイート-->
                <p @click="likeBtn()" v-bind:class="{ 'liked' : dataPlaylist.like_playlist_logs.length }"><i class="fas fa-heart"></i></p><!--お気に入り-->
                <p @click="deletePost()" v-if="userId==dataPlaylist.user_id">
                    <i class="fas fa-trash-alt"></i>
                </p><!--投稿削除-->
                <p v-else>
                    <i>&emsp;</i>
                </p>
        </section>
        <section id="show_playlist_musics">
            <ul>
                <li class="music_box"  v-for="(r,i) in dataPlaylist.playlist_logs" :key="i">
                    <p class="play_btn" @click="audioBtn(i)" v-html="btnInners[i]"></p>
                    <div class="music_infos">
                        <p><img :src="r.music_artwork" alt="" class="music_artwork"></p>
                        <div class="music_text_infos">
                            <p class="music_title">{{ r.music_title }}</p>
                            <p class="music_artist">{{ r.music_artist }}</p>
                            <p class="music_itunes_url"><a :href="r.music_itunes_url">iTunesでダウンロード</a></p>
                        </div>
                    </div>
                    <input type="hidden" name="track_ids[]" :value="r.music_track_id" form="create_playlist_form">
                </li>
            </ul>
        </section>
    </div>
</template>

<script>
    const playBtn='<i class="far fa-play-circle"></i>';
    const stopBtn='<i class="far fa-pause-circle"></i>';

    export default {
        props: {
            playlist:Object,
            userId:Number,
            repostUserName: String
        },
        data(){
            return {
                audios:[],
                btnInners:[],
                playingIndex:null,
                music_ids:'',
                dataPlaylist:this.playlist,
            }
        },
        mounted() {
            this.dataPlaylist.playlist_logs.forEach(m => {
                this.audios.push(new Audio(m.music_url));
                this.btnInners.push(playBtn);
            });
        },
        methods:{
            audioBtn(id){//曲を再生/停止する
                if(this.audios[id].paused){//止まっている場合
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
            likeBtn(){
                var post_data = {
                    'playlist_id': this.dataPlaylist.id,
                };
                axios.post('/playlist/like_playlist_process',post_data).then(res=>{
                    this.dataPlaylist.like_playlist_logs=res.data.like_playlist_logs;
                });
            },
            repostBtn(){
                var post_data = {
                    'playlist_id': this.dataPlaylist.id,
                    'reposted': this.dataPlaylist.reposted
                };
                axios.post('/playlist/repost_playlist_process',post_data).then(res=>{
                    if(res.data.action == 1){
                        this.dataPlaylist.reposted=true;
                    }else{
                        this.dataPlaylist.reposted=false;
                    }
                });
            },
            deletePost(){
                var post_data = {
                    'playlist_id': this.dataPlaylist.id,
                    'from_detail':true
                };
                axios.post('/playlist/delete_playlist_process',post_data).then(res=>{
                    location.href = '/playlist';
                });
            },
        }
    }
</script>


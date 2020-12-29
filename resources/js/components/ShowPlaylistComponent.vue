<template>
    <div id="show_playlist">
        <section id="show_playlist_info">
            <div id="show_playlist_info_img">
                <img :src="'/storage/playlist_imgs/'+playlist['img_path']" v-if="playlist['img_path']!=null">
                <img src="/storage/playlist_imgs/noimage.png" v-else>
            </div>
            <div id="show_playlist_info_detail">
                <h1>{{playlist.title}}</h1>
                <p>{{playlist.description}}</p>
                <p>作成者:<a :href="'/user/'+playlist.user.user_name"><img :src="'/storage/user_icons/'+playlist.user.icon_path"> {{playlist.user.name}}@{{playlist.user.user_name}}</a></p>
            </div>
        </section>
        <section id="show_playlist_musics">
            <ul>
                <li class="music_box"  v-for="(r,i) in playlist.playlist_logs" :key="i">
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
        },
        data(){
            return {
                audios:[],
                btnInners:[],
                playingIndex:null,
                music_ids:'',
            }
        },
        mounted() {
            this.playlist.playlist_logs.forEach(m => {
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
        }
    }
</script>


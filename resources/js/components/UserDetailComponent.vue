<template>
    <section id="user_detail">
        <div id='showed_img_bg' @click="disappearedImg()" v-if="showedImgPath!=null">
            <img :src="'/storage/post_imgs/'+showedImgPath" alt="" id="showed_img">
        </div>
        <div class="profile_infos">
            <p v-if="user.icon_path!=null"><img :src="'/storage/user_icons/'+user.icon_path" class="user_detail_icon"></p>
            <p v-else><img src="/image/somethings/noimage_user.jpg" class="user_detail_icon"></p>
            <div class="profile_text_infos">
                <h1>{{ user.name }}</h1>
                <h2>@{{ user.user_name }}</h2>
                <h3>{{ user.self_introduction }}</h3>
            </div>
            <div class="music_box my_music_box" v-if="user.my_music_track_id!=null">
                <p class="play_btn" @click="audioBtn()" v-html="btnInner"></p>
                <div class="music_infos">
                    <p><img :src="user.my_music_artwork" alt="" class="music_artwork"></p>
                    <div class="music_text_infos">
                        <p class="music_title">{{ user.my_music_title }}</p>
                        <p class="music_artist">{{ user.my_music_artist }}</p>
                        <p class="music_itunes_url"><a :href="user.my_music_itunes_url">iTunesでダウンロード</a></p>
                    </div>
                </div>
            </div>
            <p class="not_set_my_music_text" v-else>まだイチオシ曲が設定されていません。<br><a href="http://music_sns.com/mypage/edit#my_music_parts">イチオシ曲を設定する</a></p>
            <div class="btns">
                <button class="reverse_btn" onclick="location.href='/mypage/edit'">プロフィールを編集する</button>
            </div>
        </div>
        <show-posts-component :posts="posts" ref="show_posts" @stop-my-music="stopMyMusic" v-if="posts!=null"></show-posts-component>
    </section>
</template>

<script>
    import ShowPostsComponent from './ShowPostsComponent'
    const playBtn='<i class="far fa-play-circle"></i>';
    const stopBtn='<i class="far fa-pause-circle"></i>';

    export default {
        components:{
            ShowPostsComponent
        },
        props: {
            user:{
                type:Object,
            },
            posts:{
                type:Array,
            }
        },
        data(){
            return {
                showedImgPath:null,
                myMusic:null,
                btnInner:playBtn,
            }
        },
        mounted(){
            if(this.user.my_music_track_id!=null){
                this.myMusic=new Audio(this.user.my_music_url);
            }
        },
        methods:{
            showImg(imgPath){
                this.showedImgPath=imgPath;
            },
            disappearedImg(){
                this.showedImgPath=null;
            },
            audioBtn(){
                if(this.myMusic.paused){//止まっている場合
                    this.$refs.show_posts.stopAllAudios();
                    this.myMusic.play();
                    this.btnInner=stopBtn;
                    this.myMusic.addEventListener('ended',function(){
                        this.btnInner=playBtn;
                    }.bind(this));
                }else{//再生中の場合
                    this.stopMyMusic();
                }
            },
            stopMyMusic(){
                this.myMusic.pause();
                this.btnInner=playBtn;
            }
        }
    }
</script>


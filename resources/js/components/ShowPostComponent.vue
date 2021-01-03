<template>
    <div class="post_detail_inner">
        <p class="post_msg" v-if="repostUserName!=null"><i class="fas fa-retweet"></i> {{repostUserName}}さんが拡散しました</p>
        <div class="post_detail_profile">
            <div class="post_icon" ><!--アイコン--->
                <a :href="'/user/'+dataPost.user.user_name">
                    <img :src="'/storage/user_icons/'+dataPost.user.icon_path" alt="">
                </a>
            </div>
            <p class="post_user_name"><b>{{ dataPost.user.name }}</b><br>{{ " "+"@"+dataPost.user.user_name }}</p><!--ユーザー名-->
        </div>
        <div class="post_detail_datas" ><!--投稿データ-->
            <p class="post_detail_contents">{{ dataPost.contents }}</p><!--投稿内容-->
            <div class="music_box" v-if="audio!=null"><!--音楽がついていれば音楽-->
                <p class="play_btn" @click="audioBtn()" v-html="btnInner"></p>
                <div class="music_infos">
                    <p><img :src="dataPost.music_artwork" alt="" class="music_artwork"></p>
                    <div class="music_text_infos">
                        <p class="music_title">{{ dataPost.music_title }}</p>
                        <p class="music_artist">{{ dataPost.music_artist }}</p>
                        <p class="music_itunes_url"><a :href="dataPost.music_itunes_url">iTunesでダウンロード</a></p>
                    </div>
                </div>
            </div>
            <p v-else-if="dataPost.music_track_id!=null" class="post_music_spinner"><i class="fas fa-spinner faa-spin animated"></i></p><!--音楽がついているが読み込み中ならこれを表示-->
            <div v-if="dataPost.img_path!=null" class="post_img_box" @click="showImg(dataPost.img_path)"><!--画像がついていれば画像を表示-->
                <img class="post_img" :src="'/storage/post_imgs/'+dataPost.img_path" alt="" v-if="dataPost.img_path!=null" >
            </div>
        </div>
        <div class="action_btns">
                <p><i class="fas fa-reply"></i></p><!--リプライ(☆未実装)-->
                <p @click="repostBtn()" v-bind:class="{ 'reposted' : dataPost.reposted }"><i class="fas fa-retweet"></i></p><!--リツイート-->
                <p @click="likeBtn()" v-bind:class="{ 'liked' : dataPost.like_post_logs.length }"><i class="fas fa-heart"></i></p><!--お気に入り-->
                <p @click="deletePost()" v-if="userId==dataPost.user_id">
                    <i class="fas fa-trash-alt"></i>
                </p><!--投稿削除-->
                <p v-else>
                    <i>&emsp;</i>
                </p>
        </div>
    </div>
</template>

<script>
    const playBtn='<i class="far fa-play-circle"></i>';
    const stopBtn='<i class="far fa-pause-circle"></i>';

    export default {
        props: {
            post:{
                type: Object,
            },
            repostUserName:{
                type: String
            },
            userId:{
                type: Number,
            }
        },
        data(){
            return {
                btnInner:null,
                audio:null,
                dataPost:this.post,
            }
        },
        mounted() {
            if(this.dataPost.music_url!=null){
                this.btnInner=playBtn;
                this.audio=new Audio(this.dataPost['music_url']);
            }
        },
        methods:{
            audioBtn(id){//曲を再生/停止する
                if(this.audio.paused){//止まっている場合
                    this.audio.play();
                    this.btnInner=stopBtn
                }else{//再生中の場合
                    this.audio.pause();
                    this.btnInner=playBtn
                }
            },
            deletePost(){
                var post_data = {
                    'post_id': this.dataPost.id,
                    'from_detail':true
                };
                axios.post('/home/delete_post_process',post_data).then(res=>{
                    location.href = '/home';
                });
            },
            likeBtn(){
                var post_data = {
                    'post_id': this.dataPost.id,
                };
                axios.post('/home/like_post_process',post_data).then(res=>{
                    this.dataPost.like_post_logs=res.data.like_post_logs;
                });
            },
            repostBtn(){
                var post_data = {
                    'post_id': this.dataPost.id,
                    'reposted': this.dataPost.reposted
                };
                axios.post('/home/repost_process',post_data).then(res=>{
                    if(res.data.action == 1){
                        this.dataPost.reposted=true;
                    }else{
                        this.dataPost.reposted=false;
                    }
                });
            },
        }
    }
</script>


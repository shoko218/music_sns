<template>
    <div id="post_detail">
        <div id='showed_img_bg' @click="disappearedImg()" v-if="showedImgPath!=null">
            <img :src="'/storage/post_imgs/'+showedImgPath" alt="" id="showed_img">
        </div>
        <show-before-posts-component :posts="beforePosts" :user-id="userId" ref="show_before_posts" @stop-all-music="stopAllMusic" @show-img="showImg" v-if="beforePosts.length"></show-before-posts-component>
        <div class="post_detail_inner" id='main_post'>
            <div class="post_detail_datas">
                <p class="post_msg" v-if="repostUserName!=null"><i class="fas fa-retweet"></i> {{repostUserName}}さんが拡散しました</p>
                <p class="post_msg" v-if="post['reply_post']!=null"><i class="fas fa-reply"></i> {{post['reply_post']['user']['name']}}さんへの返信</p>
                <div class="post_detail_profile">
                    <div class="post_icon" ><!--アイコン--->
                        <a :href="'/user/'+dataPost.user.user_name">
                            <img :src="'/storage/user_icons/'+dataPost.user.icon_path" alt="">
                        </a>
                    </div>
                    <p class="post_user_name"><b>{{ dataPost.user.name }}</b><br>{{ " "+"@"+dataPost.user.user_name }}</p><!--ユーザー名-->
                </div>
                <div class="post_detail_texts" ><!--投稿データ-->
                    <p class="post_detail_contents">{{ dataPost.contents }}</p><!--投稿内容-->
                    <div class="music_box show_music_box" v-if="audio!=null"><!--音楽がついていれば音楽-->
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
            </div>
            <div class="action_btns">
                    <p @click="showCreatePostForm()"><i class="fas fa-reply"></i></p><!--リプライ-->
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
        <create-reply-component ref="create_reply" @stop-all-music="stopAllMusic" :csrf="csrf" :placeholderText="'@'+post['user']['user_name']+':'+post['contents']+' に返信する'" :reply-id="post['id']" :replies-chain='true' v-if="dataCreateReply"></create-reply-component>
        <show-replies-component :posts="replys" :userId="userId" ref="show_posts" @stop-all-music="stopAllMusic" @show-img="showImg"  :replies-chain='true' v-if="replys.length"></show-replies-component>
    </div>
</template>

<script>
    import CreateReplyComponent from './CreateReplyComponent'
    import ShowRepliesComponent from './ShowPostsComponent'
    import ShowBeforePostsComponent from './ShowPostsComponent'

    const playBtn='<i class="far fa-play-circle"></i>';
    const stopBtn='<i class="far fa-pause-circle"></i>';

    export default {
        components:{
            CreateReplyComponent,
            ShowBeforePostsComponent,
            ShowRepliesComponent
        },
        props: {
            post:{
                type: Object,
            },
            repostUserName:{
                type: String
            },
            userId:{
                type: Number,
            },
            csrf: {//csrfトークン
                type: String,
                required: true,
            },
            replys:{
                type: Array,
            },
            beforePosts:{
                type: Array,
            },
            createReply:{
                type: Boolean,
            }
        },
        data(){
            return {
                btnInner:null,
                audio:null,
                dataPost:this.post,
                dataCreateReply:this.createReply,
                showedImgPath:null,
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
                    this.stopAllMusic()
                    this.audio.play();
                    this.btnInner=stopBtn
                }else{//再生中の場合
                    this.stopAudio();
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
            showCreatePostForm(){
                if(!this.dataCreateReply){//フォームが表示されていなければ
                    this.dataCreateReply=true;
                }else{//フォームが表示されていれば
                    this.dataCreateReply=false;
                }
            },
            stopShowPostMusic(){
                this.stopAudio();
            },
            stopAudio(){
                this.audio.pause();
                this.btnInner=playBtn
            },
            stopAllMusic(){
                if(this.$refs.show_before_posts!=null){
                    this.$refs.show_before_posts.stopAudio();
                }
                if(this.$refs.show_posts!=null){
                    this.$refs.show_posts.stopAudio();
                }
                if(this.$refs.create_reply!=null){
                    this.$refs.create_reply.stopAudio();
                    this.$refs.create_reply.stopSearchAudio();
                }
                this.stopAudio();
            },
            showImg(imgPath){
                this.showedImgPath=imgPath;
            },
            disappearedImg(){
                this.showedImgPath=null;
            }
        }
    }
</script>


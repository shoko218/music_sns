<template><!--投稿+ホーム画面-->
    <div id="home">
        <div id='showed_img_bg' @click="disappearedImg()" v-if="showedImgPath!=null">
            <img :src="'/storage/post_imgs/'+showedImgPath" alt="" id="showed_img">
        </div>
        <create-post-component ref="create_post" @stop-show-posts-music="stopShowPostsMusic" :csrf="csrf"></create-post-component>
        <show-posts-component :posts="posts" :user-id="userId" ref="show_posts" @stop-create-post-music="stopCreatePostMusic" @show-img="showImg" ></show-posts-component>
    </div>
</template>

<script>
    import CreatePostComponent from './CreatePostComponent'
    import ShowPostsComponent from './ShowPostsComponent'
    export default {
        components:{
            CreatePostComponent,
            ShowPostsComponent
        },
        props: {
            posts:{
                type:Array,
            },
            csrf: {//csrfトークン
                type: String,
                required: true,
            },
            userId:{
                type: String,
            }
        },
        data(){
            return {
                showedImgPath:null,
            }
        },
        methods:{
            stopShowPostsMusic(){//投稿側の音楽を止める
                this.$refs.show_posts.stopAudio();
            },
            stopCreatePostMusic(){//投稿作成側の音楽を止める
                this.$refs.create_post.stopAudio();
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


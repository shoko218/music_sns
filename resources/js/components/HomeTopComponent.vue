<template><!--投稿+ホーム画面-->
    <div>
        <create-post-component ref="create_post" @stop-show-posts-music="stopShowPostsMusic" :csrf="csrf"></create-post-component>
        <show-posts-component :posts="posts" ref="show_posts" @stop-create-post-music="stopCreatePostMusic"></show-posts-component>
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
        },
        methods:{
            stopShowPostsMusic(){//投稿側の音楽を止める
                this.$refs.show_posts.stopAllAudios();
            },
            stopCreatePostMusic(){//投稿作成側の音楽を止める
                this.$refs.create_post.stopAllAudios();
            },
        }
    }
</script>


<template>
    <section class="playlists">
        <div class="playlist" v-for="(p,i) in dataPlaylists" :key="p['id']">
            <div class="playlist_icon"><!--アイコン--->
                <a :href="'/user/'+p['user']['user_name']">
                    <img :src="'/storage/user_icons/'+p['user']['icon_path']" alt="">
                </a>
            </div>
            <div class="playlist_texts"><!--投稿データ-->
                <p class="playlist_user_name"><b>{{ p['user']['name'] }}</b> {{ "@"+p['user']['user_name'] }}</p><!--ユーザー名-->
                <div class="playlist_info" :onclick="'location.href=\'/playlist/detail/'+p['id']+'\''" >
                    <div class="playlist_info_img">
                        <img src="/storage/playlist_imgs/noimage.png">
                    </div>
                    <div class="playlist_info_detail">
                        <h2>{{ p['title'] }}</h2>
                        <p>{{ p['description'] }}</p>
                    </div>
                </div>
                <div class="playlist_action_btns">
                    <p><a><i class="fas fa-reply"></i></a></p><!--リプライ(☆未実装)-->
                    <p><a><i class="fas fa-retweet"></i></a></p><!--リツイート(☆未実装)-->
                    <p><a><i class="fas fa-star"></i></a></p><!--お気に入り(☆未実装)-->
                    <p>
                        <a @click="deletePost(p['id'],i)" v-if="userId==p['user_id']"><i class="fas fa-trash-alt"></i></a>
                        <i v-else>&emsp;</i>
                    </p><!--投稿削除-->
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        props: {
            playlists:Array,
            userId:String,
        },
        data(){
            return {
                dataPlaylists:this.playlists,
            }
        },
        methods:{
            deletePost(id,i){
                var post_data = {
                    'playlist_id': id,
                };
                axios.post('/playlist/delete_playlist_process',post_data).then(res=>{
                    if(res.data.deleted_id!=-1){
                        this.dataPlaylists.splice(i, 1);
                    }
                });
            },
        }
    }
</script>


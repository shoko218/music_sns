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
                        <img :src="'/storage/playlist_imgs/'+p['img_path']" v-if="p['img_path']!=null">
                        <img src="/storage/playlist_imgs/noimage.png" v-else>
                    </div>
                    <div class="playlist_info_detail">
                        <h2>{{ p['title'] }}</h2>
                        <p>{{ p['description'] }}</p>
                    </div>
                </div>
                <div class="playlist_action_btns">
                    <p><i class="fas fa-reply"></i></p><!--リプライ(☆未実装)-->
                    <p><i class="fas fa-retweet"></i></p><!--リツイート(☆未実装)-->
                    <p @click="favBtn(p['id'],i)" v-bind:class="{ 'liked' : p['like_playlist_logs'].length }"><i class="fas fa-heart"></i></p><!--お気に入り-->
                    <p @click="deletePlaylist(p['id'],i)" v-if="userId==p['user_id']">
                        <i class="fas fa-trash-alt"></i>
                    </p><!--投稿削除-->
                    <p v-else>
                        <i>&emsp;</i>
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        props: {
            playlists:{
                type:Array,
            },
            userId:{
                type:String,
            },
        },
        data(){
            return {
                dataPlaylists:this.playlists,
            }
        },
        methods:{
            deletePlaylist(id,i){
                var post_data = {
                    'playlist_id': id,
                };
                axios.post('/playlist/delete_playlist_process',post_data).then(res=>{
                    if(res.data.deleted_id!=-1){
                        this.dataPlaylists.splice(i, 1);
                    }
                });
            },
            favBtn(id,i){
                var post_data = {
                    'playlist_id': id,
                };
                axios.post('/playlist/like_playlist_process',post_data).then(res=>{
                    this.$set(this.dataPlaylists[i],`like_playlist_logs`,res.data.like_playlist_logs);
                });
            }
        }
    }
</script>


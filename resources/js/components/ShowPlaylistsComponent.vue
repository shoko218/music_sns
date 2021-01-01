<template>
    <section class="playlists">
        <div class="playlist" v-for="(playlist,idx) in dataPlaylists" :key="playlist['id']">
            <div class="playlist_icon"><!--アイコン--->
                <a :href="'/user/'+playlist['user']['user_name']">
                    <img :src="'/storage/user_icons/'+playlist['user']['icon_path']" alt="">
                </a>
            </div>
            <div class="playlist_texts"><!--投稿データ-->
                <p class="playlist_user_name"><b>{{ playlist['user']['name'] }}</b> {{ "@"+playlist['user']['user_name'] }}</p><!--ユーザー名-->
                <div class="playlist_info" :onclick="'location.href=\'/playlist/detail/'+playlist['id']+'\''" >
                    <div class="playlist_info_img">
                        <img :src="'/storage/playlist_imgs/'+playlist['img_path']" v-if="playlist['img_path']!=null">
                        <img src="/storage/playlist_imgs/noimage.png" v-else>
                    </div>
                    <div class="playlist_info_detail">
                        <h2>{{ playlist['title'] }}</h2>
                        <p>{{ playlist['description'] }}</p>
                    </div>
                </div>
                <div class="playlist_action_btns">
                    <p><i class="fas fa-reply"></i></p><!--リプライ(☆未実装)-->
                    <p><i class="fas fa-retweet"></i></p><!--リツイート(☆未実装)-->
                    <p @click="favBtn(playlist['id'],idx)" v-bind:class="{ 'liked' : playlist['like_playlist_logs'].length }"><i class="fas fa-heart"></i></p><!--お気に入り-->
                    <p @click="deletePlaylist(playlist['id'],idx)" v-if="userId==playlist['user_id']">
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
                type:Number,
            },
        },
        data(){
            return {
                dataPlaylists:this.playlists,
            }
        },
        methods:{
            deletePlaylist(id,idx){
                var post_data = {
                    'playlist_id': id,
                };
                axios.post('/playlist/delete_playlist_process',post_data).then(res=>{
                    if(res.data.deleted_id!=-1){
                        this.hidePlaylist(idx);
                    }
                });
            },
            favBtn(id,idx){
                var post_data = {
                    'playlist_id': id,
                };
                axios.post('/playlist/like_playlist_process',post_data).then(res=>{
                    this.$set(this.dataPlaylists[idx],`like_playlist_logs`,res.data.like_playlist_logs);
                    if(res.data.action == 0){
                        this.$emit('unfav',idx);
                    }
                });
            },
            hidePlaylist(idx){
                this.dataPlaylists.splice(idx, 1);
            }
        }
    }
</script>


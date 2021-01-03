<template>
    <section class="playlists">
        <div class="playlist" v-for="(playlist,idx) in dataPlaylists" :key="playlist['id']">
            <a :href="'/playlist/detail/'+playlist['id']" class="playlist_inner" v-if="playlist['repost']==null">
                <div class="playlist_icon"><!--アイコン--->
                    <a :href="'/user/'+playlist['user']['user_name']">
                        <img :src="'/storage/user_icons/'+playlist['user']['icon_path']" alt="">
                    </a>
                </div>
                <div class="playlist_texts"><!--投稿データ-->
                    <p class="playlist_user_name"><b>{{ playlist['user']['name'] }}</b> {{ "@"+playlist['user']['user_name'] }}</p><!--ユーザー名-->
                    <div class="playlist_info">
                        <div class="playlist_info_img">
                            <img :src="'/storage/playlist_imgs/'+playlist['img_path']" v-if="playlist['img_path']!=null">
                            <img src="/storage/playlist_imgs/noimage.png" v-else>
                        </div>
                        <div class="playlist_info_detail">
                            <h2>{{ playlist['title'] }}</h2>
                            <p>{{ playlist['description'] }}</p>
                        </div>
                    </div>
                    <div class="action_btns">
                        <p><i class="fas fa-reply faa-float animated-hover"></i></p><!--リプライ(☆未実装)-->
                        <p @click.prevent.stop="repostBtn(playlist['id'],idx)" v-bind:class="{ 'reposted' : playlist['reposted'] }"><i class="fas fa-retweet faa-float animated-hover"></i></p><!--リツイート(☆未実装)-->
                        <p @click.prevent.stop="likeBtn(playlist['id'],idx)" v-bind:class="{ 'liked' : playlist['like_playlist_logs'].length }"><i class="fas fa-heart faa-float animated-hover"></i></p><!--お気に入り-->
                        <p @click.prevent.stop="deletePlaylist(playlist['id'],idx)" v-if="userId==playlist['user_id']">
                            <i class="fas fa-trash-alt faa-float animated-hover"></i>
                        </p><!--投稿削除-->
                        <p v-else>
                            <i>&emsp;</i>
                        </p>
                    </div>
                </div>
            </a>
            <a :href="'/playlist/detail/'+playlist['id']" v-else>
                <p class="playlist_msg"><i class="fas fa-retweet"></i> {{playlist['user']['name']}}さんが拡散しました</p>
                <div class="playlist_inner">
                    <div class="playlist_icon"><!--アイコン--->
                        <a :href="'/user/'+playlist['repost']['user']['user_name']">
                            <img :src="'/storage/user_icons/'+playlist['repost']['user']['icon_path']" alt="">
                        </a>
                    </div>
                    <div class="playlist_texts"><!--投稿データ-->
                        <p class="playlist_user_name"><b>{{ playlist['repost']['user']['name'] }}</b> {{ "@"+playlist['repost']['user']['user_name'] }}</p><!--ユーザー名-->
                        <div class="playlist_info" :onclick="'location.href=\'/playlist/detail/'+playlist['repost']['id']+'\''" >
                            <div class="playlist_info_img">
                                <img :src="'/storage/playlist_imgs/'+playlist['repost']['img_path']" v-if="playlist['repost']['img_path']!=null">
                                <img src="/storage/playlist_imgs/noimage.png" v-else>
                            </div>
                            <div class="playlist_info_detail">
                                <h2>{{ playlist['repost']['title'] }}</h2>
                                <p>{{ playlist['repost']['description'] }}</p>
                            </div>
                        </div>
                        <div class="action_btns">
                            <p><i class="fas fa-reply faa-float animated-hover"></i></p><!--リプライ(☆未実装)-->
                            <p @click.prevent.stop="repostBtn(playlist['repost']['id'],idx)" v-bind:class="{ 'reposted' : playlist['repost']['reposted'] }"><i class="fas fa-retweet faa-float animated-hover"></i></p><!--リツイート(☆未実装)-->
                            <p @click.prevent.stop="likeBtn(playlist['repost']['id'],idx)" v-bind:class="{ 'liked' : playlist['repost']['like_playlist_logs'].length }"><i class="fas fa-heart faa-float animated-hover"></i></p><!--お気に入り-->
                            <p @click.prevent.stop="deletePlaylist(playlist['repost']['id'],idx)" v-if="userId==playlist['repost']['user_id']">
                                <i class="fas fa-trash-alt faa-float animated-hover"></i>
                            </p><!--投稿削除-->
                            <p v-else>
                                <i>&emsp;</i>
                            </p>
                        </div>
                    </div>
                </div>
            </a>
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
            likeBtn(id,idx){
                var post_data = {
                    'playlist_id': id,
                };
                var playlist;
                if(this.dataPlaylists[idx]['repost']==null){//普通の投稿なら
                    playlist=this.dataPlaylists[idx];
                }else{//拡散投稿なら
                    playlist=this.dataPlaylists[idx]['repost'];
                }
                axios.post('/playlist/like_playlist_process',post_data).then(res=>{
                    this.$set(playlist,`like_playlist_logs`,res.data.like_playlist_logs);
                    if(res.data.action == 0){
                        this.$emit('unfav',idx);
                    }
                });
            },
            repostBtn(id,idx){
                var playlist;
                if(this.dataPlaylists[idx]['repost']==null){//普通の投稿なら
                    playlist=this.dataPlaylists[idx];
                }else{
                    playlist=this.dataPlaylists[idx]['repost'];
                }
                var post_data = {
                    'playlist_id': id,
                    'reposted': playlist['reposted']
                };
                axios.post('/playlist/repost_playlist_process',post_data).then(res=>{
                    if(res.data.action == 1){
                        this.$set(playlist,`reposted`,true);
                    }else{
                        this.$set(playlist,`reposted`,false);
                    }
                });
            },
            hidePlaylist(idx){
                this.dataPlaylists.splice(idx, 1);
            }
        }
    }
</script>


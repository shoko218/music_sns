<template><!--投稿を表示-->
    <div class="posts">
        <div class="post" v-for="(post,idx) in dataPosts" :key="post['id']">
            <div v-if="post['repost']==null">
                <p class="post_msg" v-if="post['reply_post']!=null"><i class="fas fa-reply"></i> {{post['reply_post']['user']['name']}}さんへの返信</p>
                <a :href="'/home/detail/'+post['id']+'#main_post'" class="post_inner" v-if="post['repost']==null"><!--自分の投稿-->
                    <div class="post_icon" ><!--アイコン--->
                        <a :href="'/user/'+post['user']['user_name']">
                            <img :src="'/storage/user_icons/'+post['user']['icon_path']" alt="">
                        </a>
                    </div>
                    <div class="post_texts"><!--投稿データ-->
                        <p class="post_user_name"><b>{{ post['user']['name'] }}</b>{{ " "+"@"+post['user']['user_name'] }}</p><!--ユーザー名-->
                        <p class="post_contents">{{ post['contents'] }}</p><!--投稿内容-->
                        <div class="music_box" v-if="audios[post['id']]!=null"><!--音楽がついていれば音楽-->
                            <p class="play_btn" @click.prevent.stop="audioBtn(post['id'])" v-html="btnInners[post['id']]"></p>
                            <div class="music_infos">
                                <p><img :src="post['music_artwork']" alt="" class="music_artwork"></p>
                                <div class="music_text_infos">
                                    <p class="music_title">{{ post['music_title'] }}</p>
                                    <p class="music_artist">{{ post['music_artist'] }}</p>
                                    <p class="music_itunes_url"><a :href="post['music_itunes_url']">iTunesでダウンロード</a></p>
                                </div>
                            </div>
                        </div>
                        <p v-else-if="post['music_track_id']!=null" class="post_music_spinner"><i class="fas fa-spinner faa-spin animated"></i></p><!--音楽がついているが読み込み中ならこれを表示-->
                        <div v-if="post['img_path']!=null" class="post_img_box" @click.prevent.stop="showImg(post['img_path'])"><!--画像がついていれば画像を表示-->
                            <img class="post_img" :src="'/storage/post_imgs/'+post['img_path']" alt="" v-if="post['img_path']!=null" >
                        </div>
                        <div class="action_btns">
                            <p @click.prevent.stop="createReply(post['id'])"><i class="fas fa-reply faa-float animated-hover"></i></p><!--リプライ-->
                            <p @click.prevent.stop="repostBtn(post['id'],idx)" v-bind:class="{ 'reposted' : post['reposted'] }"><i class="fas fa-retweet faa-float animated-hover"></i></p><!--リツイート-->
                            <p @click.prevent.stop="likeBtn(post['id'],idx)" v-bind:class="{ 'liked' : post['like_post_logs'].length }"><i class="fas fa-heart faa-float animated-hover"></i></p><!--お気に入り-->
                            <p @click.prevent.stop="deletePost(post['id'],idx)" v-if="userId==post['user_id']">
                                <i class="fas fa-trash-alt faa-float animated-hover"></i>
                            </p><!--投稿削除-->
                            <p v-else>
                                <i>&emsp;</i>
                            </p>
                        </div>
                    </div>
                </a>
                <div v-if="repliesChain==true">
                    <p @click.prevent.stop="getRepliesChain(post['id'])" v-if="post['has_replies_chain']==true && repliesChains[post['id']]==null" class="post_show_replies_btn">+返信を表示</p>
                    <show-replies-chain-component :posts="repliesChains[post['id']]" :userId="userId" v-if="repliesChains[post['id']]!=null"></show-replies-chain-component>
                </div>
            </div>
            <a :href="'/home/detail/'+post['id']+'#main_post'" v-else><!-- 拡散された投稿 -->
                <p class="post_msg"><i class="fas fa-retweet"></i> {{post['user']['name']}}さんが拡散しました</p>
                <p class="post_msg" v-if="post['repost']['reply_post']!=null"><i class="fas fa-reply"></i> {{post['repost']['reply_post']['user']['name']}}さんへの返信</p>
                <div class="post_inner">
                    <div class="post_icon" ><!--アイコン--->
                        <a :href="'/user/'+post['repost']['user']['user_name']">
                            <img :src="'/storage/user_icons/'+post['repost']['user']['icon_path']" alt="">
                        </a>
                    </div>
                    <div class="post_texts"><!--投稿データ-->
                        <p class="post_user_name"><b>{{ post['repost']['user']['name'] }}</b>{{ " "+"@"+post['repost']['user']['user_name'] }}</p><!--ユーザー名-->
                        <p class="post_contents">{{ post['repost']['contents'] }}</p><!--投稿内容-->
                        <div class="music_box" v-if="audios[post['repost']['id']]!=null"><!--音楽がついていれば音楽-->
                            <p class="play_btn" @click.prevent.stop="audioBtn(post['repost']['id'])" v-html="btnInners[post['repost']['id']]"></p>
                            <div class="music_infos">
                                <p><img :src="post['repost']['music_artwork']" alt="" class="music_artwork"></p>
                                <div class="music_text_infos">
                                    <p class="music_title">{{ post['repost']['music_title'] }}</p>
                                    <p class="music_artist">{{ post['repost']['music_artist'] }}</p>
                                    <p class="music_itunes_url"><a :href="post['repost']['music_itunes_url']">iTunesでダウンロード</a></p>
                                </div>
                            </div>
                        </div>
                        <p v-else-if="post['repost']['music_track_id']!=null" class="post_music_spinner"><i class="fas fa-spinner faa-spin animated"></i></p><!--音楽がついているが読み込み中ならこれを表示-->
                        <div v-if="post['repost']['img_path']!=null" class="post_img_box" @click.prevent.stop="showImg(post['repost']['img_path'])"><!--画像がついていれば画像を表示-->
                            <img class="post_img" :src="'/storage/post_imgs/'+post['repost']['img_path']" alt="" v-if="post['repost']['img_path']!=null" >
                        </div>
                        <div class="action_btns">
                            <p @click.prevent.stop="createReply(post['repost']['id'])"><i class="fas fa-reply faa-float animated-hover"></i></p><!--リプライ-->
                            <p @click.prevent.stop="repostBtn(post['repost']['id'],idx)" v-bind:class="{ 'reposted' : post['repost']['reposted'] }"><i class="fas fa-retweet faa-float animated-hover"></i></p><!--リツイート-->
                            <p @click.prevent.stop="likeBtn(post['repost']['id'],idx)" v-bind:class="{ 'liked' : post['repost']['like_post_logs'].length }"><i class="fas fa-heart faa-float animated-hover"></i></p><!--お気に入り-->
                            <p @click.prevent.stop="deletePost(post['repost']['id'],idx)" v-if="userId==post['repost']['user_id']">
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

    </div>
</template>

<script>
    import Vue from 'vue';
    import ShowRepliesChainComponent from './ShowRepliesChainComponent'
    const playBtn='<i class="far fa-play-circle"></i>';
    const stopBtn='<i class="far fa-pause-circle"></i>';

    export default {
        components:{
            ShowRepliesChainComponent
        },
        props: {
            posts:{
                type: Array,
            },
            userId:{
                type: Number,
            },
            repliesChain:{
                type: Boolean,
            }
        },
        data(){
            return {
                postMusics:{},
                btnInners:{},
                audios:{},
                dataPosts:this.posts,
                playingIndex:null,
                repliesChains:{},
            }
        },
        mounted() {
            this.getPostMusics();
        },
        watch:{
            dataPosts: function(){//イチオシ音楽が新しく定義された際にAudioオブジェクトを作る
                this.stopAllAudios();
                this.getPostMusics();
            },
        },
        methods:{
            audioBtn(id){//曲を再生/停止する
                if(this.audios[id].paused){//止まっている場合
                    this.stopAllAudios();
                    this.audios[id].play();
                    this.$set(this.btnInners,id,stopBtn);
                    this.audios[id].addEventListener('ended',function(){
                        this.$set(this.btnInners,id,playBtn);
                    }.bind(this));
                    this.playingIndex=id;
                }else{//再生中の場合
                    this.stopAudio();
                }
            },
            stopAllAudios(){//全ての音楽を止める
                this.$emit('stop-create-post-music');
                this.$emit('stop-my-music');
                this.$emit('stop-all-music');
                this.$emit('stop-search-all-music');
                // this.stopAudio();
            },
            stopAudio(){
                if(this.playingIndex!=null){
                    this.audios[this.playingIndex].pause();
                    this.$set(this.btnInners,this.playingIndex,playBtn);
                    this.playingIndex!=null;
                }
            },
            deletePost(id,idx){
                var post_data = {
                    'post_id': id,
                };
                axios.post('/home/delete_post_process',post_data).then(res=>{
                    if(res.data.deleted_id!=-1&&res.data.deleted_id!=null){
                        this.hidePost(idx)
                        this.$delete(this.postMusics,res.data.deleted_id);
                        this.$delete(this.btnInners,res.data.deleted_id);
                        this.$delete(this.audios,res.data.deleted_id);
                    }
                });
            },
            getPostMusics(){
                this.dataPosts.forEach(post => {
                    if(post['music_track_id']!=null){
                        this.$set(this.btnInners,post['id'],playBtn);
                        this.$set(this.audios,post['id'],new Audio(post['music_url']));
                    }else if(post['repost']!=null && post['repost']['music_track_id']!=null){
                        this.$set(this.btnInners,post['repost']['id'],playBtn);
                        this.$set(this.audios,post['repost']['id'],new Audio(post['repost']['music_url']));
                    }
                });
            },
            showImg(imgPath){
                this.$emit('show-img',imgPath);
            },
            likeBtn(id,idx){
                var post_data = {
                    'post_id': id,
                };
                var post;
                if(this.dataPosts[idx]['repost']==null){//普通の投稿なら
                    post=this.dataPosts[idx];
                }else{//拡散投稿なら
                    post=this.dataPosts[idx]['repost'];
                }
                axios.post('/home/like_post_process',post_data).then(res=>{
                    this.$set(post,`like_post_logs`,res.data.like_post_logs);
                    if(res.data.action == 0){
                        this.$emit('unfav',idx);
                    }
                });
            },
            repostBtn(id,idx){
                var post;
                if(this.dataPosts[idx]['repost']==null){//普通の投稿なら
                    post=this.dataPosts[idx];
                }else{
                    post=this.dataPosts[idx]['repost'];
                }
                var post_data = {
                    'post_id': id,
                    'reposted': post['reposted']
                };
                axios.post('/home/repost_process',post_data).then(res=>{
                    if(res.data.action == 1){
                        this.$set(post,`reposted`,true);
                    }else{
                        this.$set(post,`reposted`,false);
                    }
                });
            },
            hidePost(idx){
                this.dataPosts.splice(idx, 1);
            },
            getRepliesChain(id){
                var post_data = {
                    'post_id': id,
                };
                axios.post('/home/detail/get_replies_chain',post_data).then(res=>{
                    this.$set(this.repliesChains,id,res.data.replies_chain);
                });
            },
            createReply(id){
                location.href = '/home/detail/'+id+'?status=create_reply'+'#main_post';
            }
        }
    }
</script>


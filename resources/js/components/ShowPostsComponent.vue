<template><!--投稿を表示-->
    <section class="posts">
        <div class="post" v-for="(post,i) in dataPosts" :key="post['id']">
            <div class="post_icon"><!--アイコン--->
                <a :href="'/user/'+post['user']['user_name']">
                    <img :src="'/storage/user_icons/'+post['user']['icon_path']" alt="">
                </a>
            </div>
            <div class="post_texts"><!--投稿データ-->
                <p class="post_user_name"><b>{{ post['user']['name'] }}</b>{{ " "+"@"+post['user']['user_name'] }}</p><!--ユーザー名-->
                <p class="post_contents">{{ post['contents'] }}</p><!--投稿内容-->
                <div class="music_box" v-if="audios[post['id']]!=null"><!--音楽がついていれば音楽-->
                    <p class="play_btn" @click="audioBtn(post['id'])" v-html="btnInners[post['id']]"></p>
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
                <div v-if="post['img_path']!=null" class="post_img_box" @click="showImg(post['img_path'])"><!--画像がついていれば画像を表示-->
                    <img class="post_img" :src="'/storage/post_imgs/'+post['img_path']" alt="" v-if="post['img_path']!=null" >
                </div>
                <div class="post_action_btns">
                    <p><i class="fas fa-reply"></i></p><!--リプライ(☆未実装)-->
                    <p><i class="fas fa-retweet"></i></p><!--リツイート(☆未実装)-->
                    <p @click="favBtn(post['id'],i)" v-bind:class="{ 'liked' : post['like_post_logs'].length }"><i class="fas fa-heart"></i></p><!--お気に入り-->
                    <p @click="deletePost(post['id'],i)" v-if="userId==post['user_id']">
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
    import Vue from 'vue';
    const playBtn='<i class="far fa-play-circle"></i>';
    const stopBtn='<i class="far fa-pause-circle"></i>';

    export default {
        props: {
            posts:{
                type: Array,
            },
            userId:{
                type: Number,
            }
        },
        data(){
            return {
                postMusics:{},
                btnInners:{},
                audios:{},
                dataPosts:this.posts,
                playingIndex:null,
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
                this.stopAudio();
            },
            stopAudio(){
                if(this.playingIndex!=null){
                    this.audios[this.playingIndex].pause();
                    this.$set(this.btnInners,this.playingIndex,playBtn);
                    this.playingIndex!=null;
                }
            },
            deletePost(id,i){
                var post_data = {
                    'post_id': id,
                };
                axios.post('/delete_post_process',post_data).then(res=>{
                    if(res.data.deleted_id!=-1&&res.data.deleted_id!=null){
                        this.dataPosts.splice(i, 1);
                        this.$delete(this.postMusics,res.data.deleted_id);
                        this.$delete(this.btnInners,res.data.deleted_id);
                        this.$delete(this.audios,res.data.deleted_id);
                    }
                });
            },
            getPostMusics(){
                this.dataPosts.forEach(p => {
                    if(p['music_track_id']!=null){
                        this.$set(this.btnInners,p['id'],playBtn);
                        this.$set(this.audios,p['id'],new Audio(p['music_url']));
                    }
                });
            },
            showImg(imgPath){
                this.$emit('show-img',imgPath);
            },
            favBtn(id,i){
                var post_data = {
                    'post_id': id,
                };
                axios.post('/like_post_process',post_data).then(res=>{
                    this.$set(this.dataPosts[i],`like_post_logs`,res.data.like_post_logs);
                });
            }
        }
    }
</script>


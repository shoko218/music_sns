<template><!--投稿を表示-->
    <div id="home">
        <div id="posts">
            <div class="post" v-for="(post,i) in dataPosts" :key="post['post_id']">
                <div class="post_icon"><!--アイコン--->
                    <img :src="'/storage/user_icons/'+post['icon_path']" alt="">
                </div>
                <div class="post_texts"><!--投稿データ-->
                    <p class="post_user_name"><b>{{ post['name'] }}</b>{{ " "+"@"+post['user_name'] }}</p><!--ユーザー名-->
                    <p class="post_contents">{{ post['contents'] }}</p><!--投稿内容-->
                    <div class="music_box" v-if="postMusics[post['post_id']]!=null"><!--音楽がついていれば音楽-->
                        <p class="play_btn" @click="audioBtn(post['post_id'])" v-html="btnInners[post['post_id']]"></p>
                        <div class="music_infos">
                            <p><img :src="postMusics[post['post_id']]['artwork_url']" alt="" class="music_artwork"></p>
                            <div class="music_text_infos">
                                <p class="music_title">{{ postMusics[post['post_id']]['title'] }}</p>
                                <p class="music_artist">{{ postMusics[post['post_id']]['artist'] }}</p>
                                <p class="music_itunes_url"><a :href="postMusics[post['post_id']]['itunes_url']">iTunesでダウンロード</a></p>
                            </div>
                        </div>
                    </div>
                    <p v-else-if="post['track_id']!=null"><i class="fas fa-spinner"></i></p><!--音楽がついているが読み込み中ならこれを表示-->
                    <div v-if="post['img_path']!=null" class="post_img_box" ><!--画像がついていれば画像を表示-->
                        <img class="post_img" :src="'/storage/post_imgs/'+post['img_path']" alt="" v-if="post['img_path']!=null" >
                    </div>
                    <div class="post_action_btns">
                        <p><a><i class="fas fa-reply"></i></a></p><!--リプライ-->(☆未実装)
                        <p><a><i class="fas fa-retweet"></i></a></p><!--リツイート-->(☆未実装)
                        <p><a><i class="fas fa-star"></i></a></p><!--お気に入り-->(☆未実装)
                        <p><a @click="deletePost(post['post_id'],i)"><i class="fas fa-trash-alt"></i></a></p><!--投稿削除-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue';
    const playBtn='<i class="far fa-play-circle"></i>';
    const stopBtn='<i class="far fa-pause-circle"></i>';

    export default {
        props: {
            posts:{
                type:Array,
            },
        },
        data(){
            return {
                postMusics:{},
                btnInners:{},
                audios:{},
                dataPosts:this.posts,
            }
        },
        mounted() {
            this.getPostMusics();
        },
        methods:{
            audioBtn(id){//曲を再生/停止する
                if(id!=null){//検索結果の曲
                    if(this.audios[id].paused){//止まっている場合
                        this.stopAllAudios();
                        this.audios[id].play();
                        this.$set(this.btnInners,id,stopBtn);
                    }else{//再生中の場合
                        this.audios[id].pause();
                        this.$set(this.btnInners,id,playBtn);
                    }
                }
            },
            stopAllAudios(){//全ての音楽を止める
                this.$emit('stop-create-post-music');
                if(this.audios.length!=0){
                    this.btnInner=playBtn;
                    Object.keys(this.audios).forEach(key => {
                        this.$set(this.btnInners,key,playBtn);
                        this.audios[key].pause();
                    });
                }
            },
            deletePost(id,i){
                var post_data = {
                    'post_id': id,
                };
                axios.post('/delete_post_process',post_data).then(res=>{
                    this.dataPosts.splice(i, 1);
                    this.$delete(this.postMusics,res.data.deleted_id);
                    this.$delete(this.btnInners,res.data.deleted_id);
                    this.$delete(this.audios,res.data.deleted_id);
                });
            },
            getPostMusics(){
                this.dataPosts.forEach(p => {
                    if(p['track_id']!=null){
                        axios.get('/api/get_music?track_id='+p['track_id']).then(res => {
                            this.$set(this.postMusics,p['post_id'],res.data.musicInfo);
                            this.$set(this.btnInners,p['post_id'],playBtn);
                            this.$set(this.audios,p['post_id'],new Audio(res.data.musicInfo.music_url));
                        });
                    }
                });
            },
        }
    }
</script>


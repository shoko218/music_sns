<template>
    <div id="user_detail">
        <div id='showed_img_bg' @click="disappearedImg()" v-if="showedImgPath!=null">
            <img :src="'/storage/post_imgs/'+showedImgPath" alt="" id="showed_img">
        </div>
        <section id="profile_infos">
            <p v-if="user.icon_path!=null"><img :src="'/storage/user_icons/'+user.icon_path" class="user_detail_icon"></p>
            <p v-else><img src="/image/somethings/noimage_user.jpg" class="user_detail_icon"></p>
            <div class="profile_text_infos">
                <h1>{{ user.name }}</h1>
                <h2>@{{ user.user_name }}</h2>
                <p>{{ user.self_introduction }}</p>
                <p><a :href="'/user/'+user.user_name+'/follow'"><b>{{followNum}}</b>フォロー</a>　<a :href="'/user/'+user.user_name+'/follower'"><b>{{followerNum}}</b>フォロワー</a></p>
            </div>
            <div class="music_box my_music_box" v-if="user.my_music_track_id!=null">
                <p class="play_btn" @click="audioBtn()" v-html="btnInner"></p>
                <div class="music_infos">
                    <p><img :src="user.my_music_artwork" alt="" class="music_artwork"></p>
                    <div class="music_text_infos">
                        <p class="music_title">{{ user.my_music_title }}</p>
                        <p class="music_artist">{{ user.my_music_artist }}</p>
                        <p class="music_itunes_url"><a :href="user.my_music_itunes_url">iTunesでダウンロード</a></p>
                    </div>
                </div>
            </div>
            <p class="not_set_my_music_text" v-else-if="myId==user['id']">まだイチオシ曲が設定されていません。<br><a href="http://music_sns.com/mypage/edit#my_music_parts">イチオシ曲を設定する</a></p>
            <div class="btns">
                <button class="reverse_btn" onclick="location.href='/mypage/edit'" v-if="myId==user['id']">プロフィールを編集する</button>
                <button @click="followBtn()" v-else-if="dataIsFollow">フォロー中</button>
                <button class="reverse_btn" @click="followBtn()" v-else>フォローする</button>
            </div>
        </section>
        <section id="user_posts">
            <div class="tabs"><!--ステータス別タブ-->
                <button v-bind:class="[currentId==0 ? 'active_tab' : 'inactive_tab']" @click="tab(0)" class="tab">投稿</button>
                <button v-bind:class="[currentId==1 ? 'active_tab' : 'inactive_tab']" @click="tab(1)" class="tab">プレイリスト</button>
                <button v-bind:class="[currentId==2 ? 'active_tab' : 'inactive_tab']" @click="tab(2)" class="tab">お気に入り<br>投稿</button>
                <button v-bind:class="[currentId==3 ? 'active_tab' : 'inactive_tab']" @click="tab(3)" class="tab">お気に入り<br>プレイリスト</button>
            </div>
            <show-posts-component :posts="posts" :user-id="myId" ref="show_posts" @show-img="showImg" @stop-my-music="stopMyMusic" v-if="currentId==0&&posts.length"></show-posts-component>
            <h2 v-else-if="currentId==0">投稿はありません。</h2>
            <show-playlists-component :playlists="playlists" :user-id="myId" v-else-if="currentId==1&&playlists.length"></show-playlists-component>
            <h2 v-else-if="currentId==1">投稿はありません。</h2>
            <show-liked-posts-component :posts="likedPosts" :user-id="myId" @unfav="unfavPost" ref="show_liked_posts" @show-img="showImg" @stop-my-music="stopMyMusic" v-else-if="currentId==2&&likedPosts.length"></show-liked-posts-component>
            <h2 v-else-if="currentId==2">投稿はありません。</h2>
            <show-liked-playlists-component :playlists="likedPlaylists" :user-id="myId" @unfav="unfavPlaylist" ref="show_liked_playlists" v-else-if="currentId==3&&likedPlaylists.length"></show-liked-playlists-component>
            <h2 v-else-if="currentId==3">投稿はありません。</h2>
        </section>
    </div>
</template>

<script>
    import ShowPostsComponent from './ShowPostsComponent'
    import ShowPlaylistsComponent from './ShowPlaylistsComponent'
    import ShowLikedPostsComponent from './ShowPostsComponent'
    import ShowLikedPlaylistsComponent from './ShowPlaylistsComponent'
    const playBtn='<i class="far fa-play-circle"></i>';
    const stopBtn='<i class="far fa-pause-circle"></i>';

    export default {
        components:{
            ShowPostsComponent,ShowPlaylistsComponent,ShowLikedPostsComponent,ShowLikedPlaylistsComponent
        },
        props: {
            user:{
                type:Object,
            },
            posts:{
                type:Array,
            },
            likedPosts:{
                type:Array,
            },
            playlists:{
                type:Array,
            },
            likedPlaylists:{
                type:Array,
            },
            isFollow:{
                type:Boolean,
            },
            myId:{
                type:Number,
            },
        },
        data(){
            return {
                showedImgPath:null,
                myMusic:null,
                btnInner:playBtn,
                dataIsFollow:this.isFollow,
                followNum:null,
                followerNum:null,
                currentId:0,
                dataPosts:this.posts,
                dataLikedPosts:this.likedPosts,
                dataPlaylists:this.playlists,
                dataLikedPlaylists:this.likedPlaylists,
            }
        },
        mounted(){
            this.getFollowRelation()
            if(this.user.my_music_track_id!=null){
                this.myMusic=new Audio(this.user.my_music_url);
            }
        },
        methods:{
            showImg(imgPath){
                this.showedImgPath=imgPath;
            },
            disappearedImg(){
                this.showedImgPath=null;
            },
            audioBtn(){
                if(this.myMusic.paused){//止まっている場合
                    switch (this.currentId) {
                        case 0:
                            this.$refs.show_posts.stopAudio();
                            break;
                        case 2:
                            this.$refs.show_liked_posts.stopAudio();
                            break;
                    }
                    this.myMusic.play();
                    this.btnInner=stopBtn;
                    this.myMusic.addEventListener('ended',function(){
                        this.btnInner=playBtn;
                    }.bind(this));
                }else{//再生中の場合
                    this.stopMyMusic();
                }
            },
            stopMyMusic(){
                if(this.myMusic!=null){
                    this.myMusic.pause();
                    this.btnInner=playBtn;
                }
            },
            getFollowRelation(){
                var post_data = {
                    'user_id': this.user['id'],
                };
                axios.post('/api/get_follow_relations',post_data).then(res=>{
                    this.followNum=res.data.follow_num;
                    this.followerNum=res.data.follower_num;
                });
            },
            followBtn(){
                var post_data = {
                    'to_user_id': this.user['id'],
                };
                axios.post('/api/change_follow',post_data).then(res=>{
                    this.dataIsFollow=res.data.is_follow;
                    this.getFollowRelation()
                });
            },
            tab(idx){
                switch (this.currentId) {
                    case 0:
                        if(this.posts.length){
                            this.$refs.show_posts.stopAudio();
                        }
                        break;
                    case 2:
                        if(this.likedPosts.length){
                            this.$refs.show_liked_posts.stopAudio();
                        }
                        break;
                }
                switch (idx) {
                    case 0:
                        axios.get('/api/get_user_posts?user_id='+this.user.id).then(res=>{
                            this.dataPosts.splice(0, this.dataPosts.length)
                            this.dataPosts.push(... res.data.posts);
                        });
                        break;
                    case 1:
                        axios.get('/api/get_user_playlists?user_id='+this.user.id).then(res=>{
                            this.dataPlaylists.splice(0, this.dataPlaylists.length)
                            this.dataPlaylists.push(... res.data.playlists);
                        });
                        break;
                    case 2:
                        axios.get('/api/get_user_liked_posts?user_id='+this.user.id).then(res=>{
                            this.dataLikedPosts.splice(0, this.dataLikedPosts.length)
                            this.dataLikedPosts.push(... res.data.posts);
                        });
                        break;
                    case 3:
                        axios.get('/api/get_user_liked_playlists?user_id='+this.user.id).then(res=>{
                            this.dataLikedPlaylists.splice(0, this.dataLikedPlaylists.length)
                            this.dataLikedPlaylists.push(... res.data.playlists);
                        });
                        break;
                }
                this.currentId=idx;
            },
            unfavPost (idx) {
                this.$refs.show_liked_posts.hidePost(idx);
            },
            unfavPlaylist (idx) {
                this.$refs.show_liked_playlists.hidePlaylist(idx);
            },
        }
    }
</script>

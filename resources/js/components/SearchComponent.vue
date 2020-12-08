<template>
    <div id="search">
        <div id='showed_img_bg' @click="disappearedImg()" v-if="showedImgPath!=null">
            <img :src="'/storage/post_imgs/'+showedImgPath" alt="" id="showed_img">
        </div>
        <section class="search_inputs">
            <label for="search_datas_input">投稿を検索</label>
            <div class="search_input_parts">
                <input class="search_input" type="text" v-model="keyword" placeholder="キーワードを入力" id="search_datas_input">
                <div class="search_input_btns" v-on:click="search()">
                    <p><i class="fas fa-search"></i></p>
                </div>
            </div>
        </section>
        <show-posts-component :posts="posts" ref="show_posts" @show-img="showImg" v-if="posts.length"></show-posts-component>
    </div>
</template>

<script>
    import ShowPostsComponent from './ShowPostsComponent'
    export default {
        components:{
            ShowPostsComponent
        },
        data(){
            return {
                keyword:null,
                posts:[],
                showedImgPath:null,
            }
        },
        methods:{
            search(){
                var post_data = {
                    'keyword': this.keyword,
                };
                if(this.posts!=null){
                    this.posts.splice(0, this.posts.length)
                }
                axios.post('/api/search_data',post_data).then(res=>{
                    this.posts.push(...res.data.results);
                });
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


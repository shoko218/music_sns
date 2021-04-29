<template>
    <div id="search">
        <div class="tabs"><!--ステータス別タブ-->
            <button v-bind:class="[searchFor==0 ? 'active_tab' : 'inactive_tab']" @click="tab(0)" class="tab">投稿</button>
            <button v-bind:class="[searchFor==1 ? 'active_tab' : 'inactive_tab']" @click="tab(1)" class="tab">プレイリスト</button>
        </div>
        <search-post-component ref="search_post" v-if="searchFor==0"></search-post-component>
        <search-playlist-component ref="search_playlist" :user-id="userId" v-if="searchFor==1"></search-playlist-component>
    </div>
</template>

<script>
    import SearchPostComponent from './SearchPostComponent'
    import SearchPlaylistComponent from './SearchPlaylistComponent';
    export default {
        components:{
            SearchPostComponent,SearchPlaylistComponent
        },
        props: {
            userId:Number
        },
        data(){
            return {
                searchFor:0,
            }
        },
        mounted() {
        },
        watch:{
        },
        methods:{
            tab(idx){
                switch (this.searchFor) {
                    case 0:
                        this.$refs.search_post.stopAllAudios();
                        break;
                    case 1:
                        this.$refs.search_playlist.stopAllAudios();
                        break;
                }
                this.searchFor=idx;
            }
        }
    }
</script>

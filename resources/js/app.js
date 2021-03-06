/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import Vue from 'vue'
import IconComponent from './components/IconComponent'
import SelectMyMusicComponent from './components/SelectMyMusicComponent'
import CreatePostComponent from './components/CreatePostComponent'
import CreateReplyComponent from './components/CreateReplyComponent'
import ShowPostComponent from './components/ShowPostComponent'
import ShowPostsComponent from './components/ShowPostsComponent'
import ShowRepliesChainComponent from './components/ShowRepliesChainComponent'
import HomeTopComponent from './components/HomeTopComponent'
import SearchPostComponent from './components/SearchPostComponent'
import SearchPlaylistComponent from './components/SearchPlaylistComponent'
import SearchComponent from './components/SearchComponent'
import UserDetailComponent from './components/UserDetailComponent'
import CreatePlaylistComponent from './components/CreatePlaylistComponent'
import SearchMusicComponent from './components/SearchMusicComponent'
import ShowPlaylistComponent from './components/ShowPlaylistComponent'
import ShowPlaylistsComponent from './components/ShowPlaylistsComponent'
import draggable from 'vuedraggable'

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


const app = new Vue({
    el: '#app',
    components: {
        IconComponent,SelectMyMusicComponent,CreatePostComponent,ShowPostsComponent,HomeTopComponent,SearchPostComponent,SearchPlaylistComponent,UserDetailComponent,CreatePlaylistComponent,SearchMusicComponent,ShowPlaylistComponent,ShowPlaylistsComponent,ShowPostComponent,CreateReplyComponent,ShowRepliesChainComponent,SearchComponent
    },
});

<template>
    <li class="input_parts">
        <label for="icon">写真(jpeg,png形式、10MB以下)</label>
        <input id="image" type="file" name="icon" accept="image/jpeg, image/png" class="image" @change="onFileChange($event)">
        <p v-for="errMsg in errMsgs" :key="errMsg.id" class="form_alert">{{ errMsg }}</p>
        <div class="preview_user_icon" v-if="selectedImg!=null"><!--このセッションで新たに選択した画像がある場合-->
            <div class="user_icon">
                <img :src="selectedImg" alt="プレビュー画像">
            </div>
        </div>
        <div class="preview_user_icon" v-else-if="s3Url!=null"><!--新たに選択した画像はないが、以前に登録した画像がある場合(本番環境)-->
            <div class="user_icon">
                <img :src="s3Url" alt="プレビュー画像">
            </div>
        </div>
        <div class="preview_user_icon" v-else-if="imgPath!=null"><!--新たに選択した画像はないが、以前に登録した画像がある場合(開発環境)-->
            <div class="user_icon">
                <img :src="'/storage/user_icons/'+imgPath" alt="プレビュー画像">
            </div>
        </div>
        <div class="preview_user_icon" v-else><!--選択している画像も保存している画像もない場合-->
            <div class="user_icon">
                <img :src="'/image/somethings/user_icon_preview.jpg'" alt="プレビュー画像">
            </div>
        </div>
    </li>
</template>

<script>
    export default {
        props: {
            errMsgs: {
                type: Array,
            },
            imgPath: {
                type: String,
            },
            s3Url: {
                type: String,
            },
        },
        data(){
            return {
                dataErrMsgs: this.errMsgs,
                selectedImg: null
            }
        },
        methods:{
            onFileChange(e){//画像が選択された時に、選択した画像のプレビューを表示する
                const files = e.target.files;
                if(files.length > 0) {
                    const file = files[0];
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.selectedImg = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            }
        }
    }
</script>

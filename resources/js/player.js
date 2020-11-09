window.onload = function onload(){
    var playButtons = document.getElementsByClassName("play_btn")
    var audios = document.getElementsByClassName("audio")

    for (let i = 0; i < playButtons.length; i++) {
        playButtons[i].addEventListener('click',()=>{
            if(audios[i].paused){
                for (let j = 0; j < playButtons.length; j++) {
                    if(!audios[j].paused){
                        audios[j].pause();
                        playButtons[j].innerHTML='<i class="far fa-play-circle"></i>';
                    }
                }
                audios[i].play();
                playButtons[i].innerHTML='<i class="far fa-pause-circle"></i>';
            }else{
                audios[i].pause();
                playButtons[i].innerHTML='<i class="far fa-play-circle"></i>';
            }
        },false)
    }
}

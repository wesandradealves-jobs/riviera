function mobileNavigation(){
    $(".tcon").toggleClass("tcon-transform");
    if($(".tcon").is(".tcon-transform")){
        $(".navigation.-mobile").addClass("-active");
    } else {
        $(".navigation.-mobile").removeClass("-active");
    }
} 

function closeMenu(){
    $(".navigation.-mobile").removeClass("-active");
    if($(".tcon").is(".tcon-transform")){
        $(".tcon").removeClass("tcon-transform")
    }
}

$(document).ready(function () {
    $('.owl-carousel.owl-gallery').owlCarousel({
        center:false,
        items:1,
        dots:false,
        nav:true,
        loop:false,
        margin:0,
        navText:["<i class='owl-prev-arrow'><img src='assets/imgs/arrow.png' /></i>","<i class='owl-next-arrow'><img src='assets/imgs/arrow.png' /></i>"]
    });
    $(window).scroll(function(event){
        closeMenu()
    });
});
      
      
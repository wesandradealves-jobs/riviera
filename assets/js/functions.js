function mobileNavigation(){
    $(".tcon").toggleClass("tcon-transform")
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
        center:true,
        items:1,
        dots:false,
        nav:true,
        loop:true,
        margin:0,
        navText:["<i class='owl-prev-arrow'><img src='assets/imgs/arrow.png' /></i>","<i class='owl-next-arrow'><img src='assets/imgs/arrow.png' /></i>"]
    });
    $('.owl-carousel.owl-item-carousel').owlCarousel({
        loop:true,
        margin:10,
        nav:true,
        dots:false,
        navText:["<i class='owl-prev-arrow'><img src='assets/imgs/arrow.png' /></i>","<i class='owl-next-arrow'><img src='assets/imgs/arrow.png' /></i>"],
        responsive:{
            0:{
                items:1
            },
            568:{
                items:2
            },
            737:{
                items:3
            },
            768:{
                items:4
            }
        }
    });
    $(window).scroll(function(event){
        closeMenu()
    });
});
      
      
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
    $(".navigation.-mobile .-opened").removeClass("-opened");
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
        loop:true,
        margin:0,
        navText:["<i class='owl-prev-arrow'><img src='assets/imgs/arrow.png' /></i>","<i class='owl-next-arrow'><img src='assets/imgs/arrow.png' /></i>"]
    });
    $('.owl-carousel.owl-item-carousel').owlCarousel({
        autoplay:true,
        autoplayTimeout:6000,        
        loop:true,
        margin:10,
        nav:false,
        dots:false,
        navText:["<i class='owl-prev-arrow'><img src='assets/imgs/arrow.png' /></i>","<i class='owl-next-arrow'><img src='assets/imgs/arrow.png' /></i>"],
        responsive:{
            768:{
                items:4
            }
        }
    });
    $(window).scroll(function(event){
        closeMenu()
    });
    $(window).resize(function() {
        closeMenu();
    });    
    $( ".navigation > ul > li" ).each(function() {
        if($(this).find("ul").length){
            $(this).addClass("has-submenu"),
            $(this).children("a").append("<i class='fas fa-angle-down'></i>")
        }
    });    
});
      
      
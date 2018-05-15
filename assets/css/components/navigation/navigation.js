$(document).ready(function () {
    $(".navigation li")
    .find("a")
    .click(function() {
        var id = "#" + $(this).text().toLowerCase().replace(/\s/g, '-'),
            bottom = $("#footer").offset();
            goTo = $(id).offset(); 

            if($(id).length == 0){
                window.location = "./"+id;
            } else {
                if(!$(this).parent().is(".has-submenu")){
                    setTimeout(function(){
                        $("html, body").animate({scrollTop: goTo.top - $("header").outerHeight()}, 500); 
                    });            
                } else {
                    $(this).parent().toggleClass("-opened")
                }
            }
    }); 
    $(window).scroll(function(event){
        var st = $(this).scrollTop();

        $( '.pg-home section' ).each(function() {
            if($(this).offset().top >= st + ($(this).prev().outerHeight() + $("header").outerHeight()*2)){
                $(".pg-home .navigation ul li:nth-child("+$(this).index()+")").removeClass("-active");
                $(this).removeClass('-animated');
            } else {
                $(".pg-home .navigation ul li:not(:nth-child("+$(this).index()+"))").removeClass("-active");
                $(".pg-home .navigation ul li:nth-child("+$(this).index()+")").addClass("-active");
                $(this).addClass('-animated');
            }
        });
    });  
    if(location.hash){
        setTimeout(function(){
            $('html, body').animate({scrollTop: $(location.hash).offset().top - $("header").outerHeight()}, 500); 
        });
    }       
});
$(document).ready(function () {
    $(".navigation li")
    .find("a")
    .click(function() {
        var id = "#" + $(this).text().toLowerCase().replace(/\s/g, '-'),
            bottom = $("#footer").offset();
            goTo = $(id).offset(); 

            setTimeout(function(){
                if(id == "#home"){
                    $("html, body").animate({scrollTop: 0}, 500);    
                } else if(id == "#entre-em-contato"){
                    $("html, body").animate({scrollTop: bottom.top}, 500); 
                } else {
                    $("html, body").animate({scrollTop: goTo.top - 55}, 500); 
                }
            });            
    }); 
    $(window).scroll(function(event){
        var st = $(this).scrollTop();

        $( 'section' ).each(function() {
            if($(this).offset().top >= st + $('.header').outerHeight() * 2){
                $(this).removeClass('-animated');
            } else {
                $(this).addClass('-animated');
            }
        });
    });     
});
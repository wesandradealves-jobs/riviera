$(document).ready(function () {
    $(".navigation li")
    .find("a")
    .click(function() {
        var id = "#" + $(this).text().toLowerCase().replace(/\s/g, '-'),
            bottom = $("#footer").offset();
            goTo = $(id).offset(); 

            setTimeout(function(){
                if(id == "#banner"){
                    $("html, body").animate({scrollTop: 0}, 500);    
                } else if(id == "#entre-em-contato"){
                    $("html, body").animate({scrollTop: bottom.top}, 500); 
                } else {
                    $("html, body").animate({scrollTop: goTo.top - 55}, 500); 
                }
            });            
    }); 
});
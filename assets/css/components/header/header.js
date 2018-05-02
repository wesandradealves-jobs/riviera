$(document).ready(function () {
    $("header").before($("header").clone(true).addClass("-sticky")); 
    $(window).scroll(function() {
        var t = $(this).scrollTop();
        if (t > 55){
            $(".-sticky").addClass("-stuck");
        } else {
            $(".-sticky").removeClass("-stuck");
        }
        console.log(t)
    });
}); 
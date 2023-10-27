let text = $('#ohol');
let element = $('#g2h55');

var clicks = 0;

text.click(function()
{
    if(clicks != 14)
    {
        clicks++;
    }else
    {
        element.removeClass("hidden");

        setTimeout(function(){
            element.addClass("hidden");
        }, 5000);

        clicks = 0;
    }
    
});
jQuery(function($) {

// Replace all button-like hyperlinks with real buttons
$(".buttons a").each(function(index, value)
{
    var button = document.createElement("button");
    button.onclick=function() { if (value.target == "_blank") {window.open(value.href);} else {location.href = value.href;} return false; };
    button.appendChild(value.firstChild);
    $(value).replaceWith(button)
});

});

jQuery(function($) {

// Replace all button-like hyperlinks with real buttons
$(".startbuttons a").each(function(index, value)
{
    var button = document.createElement("button");
    button.onclick=function() { location.href = value.href; return false; };
    button.appendChild(value.firstChild);
    $(value).replaceWith(button)
});

});

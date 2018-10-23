define(['jquery'], function($) {

return {
  init: function() {
    // this function should be in your page load function
    $('.carousel-item').click(function(){
        window.open($(this).attr("data-link"), "_blank");
    });

    $( document ).ready(function(){
        $('#mooveslideshow_foursection').carousel({
          interval: 4000
        })
    });
  }
}});

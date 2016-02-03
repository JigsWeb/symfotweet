$(function(){
  var last_tweet_id = $('.tweet').last().data('id');
  var scrollPermission = true;
  var refreshPermission = true;

   $(window).scroll(function(){
       if($(document).height()==$(window).scrollTop()+$(window).height() && scrollPermission){
         scrollPermission = false;
         $.post(window.location.pathname+"/next", {
           last_tweet_id: last_tweet_id
         },function(data){
           $("#tweets").append(data.html);
           last_tweet_id = data.last_tweet_id;
           scrollPermission = true;
         });
       }
   });

   $("#refresh").on('click',function(){
     refreshPermission = false;
     $.get(window.location.pathname+"/next",function(data){
       $("#tweets").empty().append(data.html);
       refreshPermission = true;
     });
   })
});

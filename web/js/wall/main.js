$(function(){
  // var last_tweet_id = $('.tweet').last().data('id');
  var scrollPermission = true;
  var refreshPermission = true;

  var params = {
    count: 1,
    last_tweet_id: false,
    first: true
  }

  // $(window).scroll(function(){
  //   if($(document).height()==$(window).scrollTop()+$(window).height() && scrollPermission){
  //     scrollPermission = false;
  //     $.post(window.location.pathname+"/next", {
  //       last_tweet_id: last_tweet_id
  //     },function(data){
  //       $("#tweets").append(data.html);
  //       last_tweet_id = data.last_tweet_id;
  //       scrollPermission = true;
  //     });
  //   }
  // });

  // setInterval(function(){
  //   $.get(window.location.pathname+"/next",function(data){
  //     $("#tweets").html(data.html);
  //   });
  // }, 3000);
  var getTweet = function(){
    $.post(window.location.pathname+"/next", params,function(data){
      $("#tweets").append(data.html);

      params.last_tweet_id = data.last_tweet_id;
      if(params.first) params.first = false;

      if($('body').height() < $(window).height()){
        getTweet();
      }
    });
  }

  getTweet();
});

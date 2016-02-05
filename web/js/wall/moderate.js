$(function(){
  var moderateEvent = function(){
    $(".active").on('click',function() {
      var id_str = $(this).closest('.tweet').data('id');

      if(Boolean($(this).data('active'))){
        $(this).removeClass('glyphicon-minus').addClass('glyphicon-plus');
        $(this).data('active',0);

        $.get(url = window.location.pathname+"/"+id_str+"/0");
      }
      else{
        $(this).removeClass('glyphicon-plus').addClass('glyphicon-minus');
        $(this).data('active',1);

        $.get(url = window.location.pathname+"/"+id_str+"/1");
      }
    });
  }

  var params = {
    count: 1,
    last_tweet_id: $('.tweet').last().data('id'),
    first: true
  }

  var paramsRecent = {
    count: 1,
    since_tweet_id: $('.tweet').first().data('id'),
    first: false
  }

  var getTweet = function(){
    $.post(window.location.pathname+"/next", params, function(data){
      console.log(data);
      if(data.html.length){
        $('#warning').hide();
        $("#tweets").append(data.html);

        params.last_tweet_id = $('.tweet').last().data('id');
        if(params.first) params.first = false;
      }
      else{
        $("#warning").show();
      }

      setTimeout(function() {
        if($('body').height() < $(window).height()){
          getTweet();
        }
        else{
          recentTweet();
          oldTweet();
        }
      },3000);
    });
  }

  var recentTweet = function(){
    setInterval(function(){
      $.post(window.location.pathname+"/next",paramsRecent,function(data){
        if(data.html.length){
          $("#tweets").prepend(data.html);
          paramsRecent.since_tweet_id = $('.tweet').first().data('id');
        }
      });
    }, 3000);
  }

  var scrollPermission = true;

  var oldTweet = function(){
    params.count = 2;
    $(window).scroll(function(){
      if($(document).height()==$(window).scrollTop()+$(window).height()){
        scrollPermission = false;
        $.post(window.location.pathname+"/next", params, function(data){
          if(data.html.length){
            $("#tweets").append(data.html);
            params.last_tweet_id = data.last_tweet_id;
          }
          scrollPermission = true;
        });
      }
    });
  };

  $('#warning').hide();
  moderateEvent();
  getTweet();
});

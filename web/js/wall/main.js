$(function(){
  // var last_tweet_id = $('.tweet').last().data('id');
  var scrollPermission = true;
  var refreshPermission = true;

  params = {
    count: 1,
    first: true,
    last_tweet_id: $('.tweet').last().data('id')
  }

  var getCount = function(cb){
    if($('body').height() > $(window).height()){
      $(".tweet").last().remove();
      getCount(cb);
    }
    else{
      cb($('.tweet').length);
    }
  };

  var refreshTweets = function(count){
    params.count = count;
    delete params.last_tweet_id;

    setInterval(function(){
      $.post(window.location.pathname+"/next", params, function(data){
        if(data.html){
          $('#warning').hide();
          $("#tweets").html(data.html);
          if($('body').height() > $(window).height()){
            $(".tweet").last().fadeOut();
            $(".tweet").last().remove();
          }
        }
        else{
          $("#warning").show();
        }
      });
    },3000);
  };

  var getTweet = function(){
    if(Boolean($('#tweets').data('moderate'))){
      getCount(function(count){
        refreshTweets(count);
      });
    }
    else{
      $.post(window.location.pathname+"/next", params, function(data){
        if(data.html){
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
            if($('body').height() > $(window).height()){
              $(".tweet").last().fadeOut();
              $(".tweet").last().remove();
            }
            var count = $('.tweet').length;
            refreshTweets(count);
          }
        },2000);
      });
    }
  }
  $('#warning').hide();
  getTweet();
});

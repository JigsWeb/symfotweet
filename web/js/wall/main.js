$(function(){
  // var last_tweet_id = $('.tweet').last().data('id');
  var scrollPermission = true;
  var refreshPermission = true;

  var params = {
    count: 1,
    last_tweet_id: $('.tweet').last().data('id'),
    first: true
  }

  var getTweet = function(){
    $.post(window.location.pathname+"/next", params, function(data){
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
          var count = $('.tweet').length;
          if($('body').height() > $(window).height()) $(".tweet").last().remove();
          refreshTweets(count);
        }
      },3000);
    });
  }
  $('#warning').hide();
  getTweet();

  var refreshTweets = function(count){
    params.count = count;
    params.last_tweet_id = false;

    setInterval(function(){
      $.post(window.location.pathname+"/next", params, function(data){
        if(data.html.length){
          $('#warning').hide();
          $("#tweets").html(data.html);
          if($('body').height() > $(window).height()) $(".tweet").last().remove();
        }
        else{
          $("#warning").show();
        }
      });
    },3000);
  };
});

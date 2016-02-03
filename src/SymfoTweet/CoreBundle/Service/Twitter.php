<?php

namespace SymfoTweet\CoreBundle\Service;

use Abraham\TwitterOAuth\TwitterOAuth;

class Twitter {
  private $_connection;

  public function __construct($consumer_key,$consumer_secret){
    $this->_connection = new TwitterOAuth($consumer_key, $consumer_secret);
  }

  public function getUser($user){
    $this->_connection->setOauthToken($user->getAccessToken(),$user->getAccessTokenSecret());

    return $this->_connection->get("account/verify_credentials");
  }

  public function getTweets($user,$wall_params,$count,$last_tweet_id){
    $this->_connection->setOauthToken($user->getAccessToken(),$user->getAccessTokenSecret());

    $request_params = array(
      'q' => $wall_params->getType().$wall_params->getText(),
      'result_type' => 'recent',
      'count' => $count
    );

    if($last_tweet_id){
      $request_params['max_id'] = $last_tweet_id;
    }

    return $this->_connection->get("search/tweets",$request_params);
  }
}

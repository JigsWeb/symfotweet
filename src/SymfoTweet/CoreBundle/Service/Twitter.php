<?php

namespace SymfoTweet\CoreBundle\Service;

use Abraham\TwitterOAuth\TwitterOAuth;
use Symfony\Component\HttpFoundation\Session\Session;

class Twitter {
  private $_connection;
  private $_session;

  public function __construct($consumer_key,$consumer_secret){
    $this->_session = new Session;
    $this->_connection = new TwitterOAuth($consumer_key, $consumer_secret);
  }

  public function getUser($user){
    $this->_connection->setOauthToken($user->getAccessToken(),$user->getAccessTokenSecret());

    return $this->_connection->get("account/verify_credentials");
  }

  public function getTweets($user,$wall_params,$count,$last_tweet_id,$since_tweet_id=false){
    $this->_connection->setOauthToken($user->getAccessToken(),$user->getAccessTokenSecret());

    $request_params = array(
      'q' => $wall_params->getType().$wall_params->getText(),
      'result_type' => 'recent',
      'count' => $count
    );

    if($last_tweet_id) $request_params['max_id'] = $last_tweet_id;
    if($since_tweet_id) $request_params['since_id'] = $since_tweet_id;

    return $this->_connection->get("search/tweets",$request_params);
  }

  public function getTweetsById($user,$ids_array){
    $this->_connection->setOauthToken($user->getAccessToken(),$user->getAccessTokenSecret());

    $ids_string = "";

    foreach ($ids_array as $key => $id) {
      $ids_string .= $id;

      if(isset($ids_array[$key+1])) $ids_string .= ",";
    }
    return $this->_connection->get("statuses/lookup",array('id'=>$ids_string));
  }

  public function linkAccount(){
    $request_token = $this->_connection->oauth("oauth/request_token", array("oauth_callback" => "http://127.0.0.1:8000/profile"));

    $this->_session->set('oauth_token',$request_token['oauth_token']);
    $this->_session->set('oauth_token_secret',$request_token['oauth_token_secret']);

    $url = $this->_connection->url("oauth/authorize", array("oauth_token" => $request_token['oauth_token']));

    return $url;
  }

  public function checkAction($oauth_verifier,$user,$em){
    $this->_connection->setOauthToken($this->_session->get('oauth_token'),$this->_session->get('oauth_token_secret'));

    $twitter_info = $this->_connection->oauth("oauth/access_token", array("oauth_verifier" => $oauth_verifier));

    $this->_session->remove('oauth_token');
    $this->_session->remove('oauth_token_secret');

    $user->setTwitterId($twitter_info['user_id']);
    $user->setAccessToken($twitter_info['oauth_token']);
    $user->setAccessTokenSecret($twitter_info['oauth_token_secret']);

    $em->persist($user);
    $em->flush();
  }

}

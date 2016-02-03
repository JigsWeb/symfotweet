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

  public function linkAccount(){
    $request_token = $this->_connection->oauth("oauth/request_token", array("oauth_callback" => "http://127.0.0.1:8000/profile"));

    $session = $this->get('session');

    $session->set('oauth_token',$request_token['oauth_token']);
    $session->set('oauth_token_secret',$request_token['oauth_token_secret']);

    $url = $this->_connection->url("oauth/authorize", array("oauth_token" => $request_token['oauth_token']));

    return $this->redirect($url);
  }

  public function checkAction($oauth_verifier,$user){
    $session = $this->get('session');

    $this->_connection->setOauthToken($session->get('oauth_token'),$session->get('oauth_token_secret'));

    $twitter_info = $this->_connection->oauth("oauth/access_token", array("oauth_verifier" => $oauth_verifier));

    $session->remove('oauth_token');
    $session->remove('oauth_token_secret');

    $user->setTwitterId($twitter_info['user_id']);
    $user->setOauthToken($twitter_info['oauth_token']);
    $user->setOauthTokenSecret($twitter_info['oauth_token_secret']);

    $em = $this->getDoctrine()->getManager();

    $em->persist($user);
    $em->flush();

    return $twitter_info['user_id'];
  }
}

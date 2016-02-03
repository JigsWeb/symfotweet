<?php
namespace SymfoTweet\ClientBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use SymfoTweet\CoreBundle\Entity\User;
use HWI\Bundle\OAuthBundle\Controller\ConnectController as BaseController;

class ConnectController extends BaseController{
  public function connectServiceAction(Request $request, $service){
    $response = parent::connectServiceAction($request, $service);

    foreach($this->get('session')->all() as $key => $value){
      if(is_int(strpos($key, "_hwi_oauth.connect_confirmation"))){
        $om = $this->container->get('doctrine.orm.entity_manager');

        $user = $this->getUser();
        $user->setAccessToken($value['oauth_token']);
        $user->setAccessTokenSecret($value['oauth_token_secret']);

        $om->persist($user);
        $om->flush();

        break;
      }
    }

    return $response;
  }
}

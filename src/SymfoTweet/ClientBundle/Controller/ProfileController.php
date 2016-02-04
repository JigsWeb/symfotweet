<?php

namespace SymfoTweet\ClientBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\ProfileController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class ProfileController extends BaseController
{
  public function showAction()
  {
      $request = Request::createFromGlobals();
      $TwitterService = $this->container->get('core.twitter');

      $user = $this->container->get('security.context')->getToken()->getUser();

      if($request->query->get('oauth_verifier',false)){
        $em = $this->container->get('doctrine')->getEntityManager();
        $TwitterService->checkAction($request->query->get('oauth_verifier'),$user,$em);
      }

      $render_params = array('user' => $user);

      if(empty($user->getTwitterId())){
        $render_params['url'] = $TwitterService->linkAccount();
      }
      else{
        $render_params['twitter_info'] = $TwitterService->getUser($user);
      }

      return $this->container->get('templating')->renderResponse('FOSUserBundle:Profile:show.html.'.$this->container->getParameter('fos_user.template.engine'),$render_params);

  }
}

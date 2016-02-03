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
      $user = $this->container->get('security.context')->getToken()->getUser();
      if (!is_object($user)) {
          throw new AccessDeniedException('This user does not have access to this section.');
      }

      $TwitterService = $this->container->get('core.twitter');
      $twitter_info = $TwitterService->getUser($user);

      return $this->container->get('templating')->renderResponse('FOSUserBundle:Profile:show.html.'.$this->container->getParameter('fos_user.template.engine'), array(
        'user' => $user,
        'twitter_info' => $twitter_info
      ));

  }
}

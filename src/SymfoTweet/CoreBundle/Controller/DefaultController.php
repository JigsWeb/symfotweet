<?php

namespace SymfoTweet\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
      is_null($this->getUser()) ? $url = '/login' : $url = '/profile';

      return $this->redirect($url);
    }
}

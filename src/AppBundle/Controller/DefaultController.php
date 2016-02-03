<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use SymfoTweet\CoreBundle\Form\Type\WallType;
use SymfoTweet\CoreBundle\Entity\Wall;
use SymfoTweet\CoreBundle\Entity\WallParams;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        is_null($this->getUser()) ? $url = '/login' : $url = '/profile';

        return $this->redirect($url);
    }

    /**
     * @Route("/test", name="test")
     */
    public function testAction(){
      $wall = new Wall;
      $form = $this->createForm(WallType::class);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $data = $form->getData();
        $data->setUser($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($data);
        $em->flush();
      }

      return $this->render('default/test.html.twig', array(
          'form' => $form->createView()
      ));
    }
}

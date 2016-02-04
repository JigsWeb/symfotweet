<?php

namespace SymfoTweet\ClientBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use SymfoTweet\CoreBundle\Form\Type\WallType;
use SymfoTweet\CoreBundle\Entity\Wall;
use SymfoTweet\CoreBundle\Entity\WallParams;

/**
 * Wall controller.
 *
 * @Route("/wall")
 */
class WallController extends Controller
{
    /**
     * Lists all Wall entities.
     *
     * @Route("/", name="wall_index")
     * @Method({"GET","POST"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $WallRepository = $em->getRepository('SymfoTweetCoreBundle:Wall');

        if($request->query->get('remove',false)){
          $wallRemove = $WallRepository->find($request->query->get('remove'));
          $em->remove($wallRemove);
          $em->flush();
        }

        $wall = new Wall;
        $form = $this->createForm(WallType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && !isset($_GET['update'])) {
          $data = $form->getData();
          $data->setUser($this->getUser());

          $em->persist($data);
          $em->flush();
        }

        $render_params = array(
          'walls' => $WallRepository->findByUser($this->getUser()),
          'form' => $form
        );

        if($request->query->get('u',false)){
          $wallUpdate = $WallRepository->find($request->query->get('u'));

          $formUpdate = $this->createForm(WallType::class, $wallUpdate);
          $formUpdate->handleRequest($request);

          if ($formUpdate->isSubmitted() && $formUpdate->isValid() && isset($_GET['update'])) {
            $em->persist($wallUpdate);
            $em->flush();
          }

          $render_params['formUpdate'] = $formUpdate;
        }

        return $this->render('SymfoTweetClientBundle:wall:index.html.twig', $render_params);
    }

    public function formAction($form,$u=false){

        $render_params = array('form' => $form->createView());

        if($u){
          $render_params['u'] = $u;
        }

        return $this->render('SymfoTweetClientBundle:wall:form.html.twig',$render_params);
    }

    /**
     * Finds and displays a Wall entity.
     *
     * @Route("/{id}/next", name="wall_show_content")
     * @Method({"GET","POST"})
     */
    public function showContentAction(Wall $wall, Request $request){
      $TwitterService = $this->get('core.twitter');

      $last_tweet_id = $request->request->get('last_tweet_id');
      $count = $request->request->get('count');

      if($request->request->get('first')) $count++;

      if($request->isXmlHttpRequest()){

        $tweets = $TwitterService->getTweets($this->getUser(),$wall->getParams(),$count,$last_tweet_id);

        $last_tweet_id ? array_shift($tweets->statuses) : null;

        $lReturn = array();
        //use renderview instead of render, because renderview returns the rendered template
        $lReturn['html'] = $this->renderView('SymfoTweetClientBundle:wall:show_content.html.twig', array(
          'tweets' => $tweets->statuses
        ));

        $lReturn['last_tweet_id'] = end($tweets->statuses)->id_str;

        return new Response(json_encode($lReturn), 200, array('Content-Type'=>'application/json'));
      }
      else{
        $tweets = $TwitterService->getTweets($this->getUser(),$wall->getParams(),$count,$last_tweet_id);

        return $this->render('SymfoTweetClientBundle:wall:show_content.html.twig', array(
          'tweets' => $tweets->statuses
        ));
      }
    }

    /**
     * Finds and displays a Wall entity.
     *
     * @Route("/{id}", name="wall_show")
     * @Method("GET")
     */
    public function showAction(Wall $wall)
    {
        $twitter = $this->get('core.twitter');
        $twitter_info = $twitter->getUser($wall->getUser());


        return $this->render('SymfoTweetClientBundle:wall:show.html.twig', array(
            'wall' => $wall,
            'twitter_info' => $twitter_info
        ));
    }

    /**
     * Finds and displays a Wall entity.
     *
     * @Route("/{id}/moderate", name="wall_moderate")
     * @Method("GET")
     */
    public function moderateAction(Wall $wall){

    }
}

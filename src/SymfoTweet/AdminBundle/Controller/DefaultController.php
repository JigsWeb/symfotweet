<?php

namespace SymfoTweet\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use SymfoTweet\CoreBundle\Form\Type\WallType;
use FOS\UserBundle\Form\Type\RegistrationFormType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use SymfoTweet\CoreBundle\Entity\Wall;
use SymfoTweet\CoreBundle\Entity\User;


/**
 * Default controller.
 *
 * @Route("/admin")
 * @Security("has_role('ROLE_ADMIN')")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/wall",name="admin_wall")
     */
    public function wallAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $WallRepository = $em->getRepository('SymfoTweetCoreBundle:Wall');

        $walls = $WallRepository->findAll();

        $wall = new Wall;
        $form = $this->createForm(WallType::class, $wall);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && !isset($_GET['update'])) {
          $data = $form->getData();
          $data->setUser($this->getUser());

          $em->persist($data);
          $em->flush();
        }

        $render_params = array(
          'walls' => $walls,
          'form' => $form
        );

        if(isset($_GET['u'])){
          $wallUpdate = $WallRepository->find($_GET['u']);

          $formUpdate = $this->createForm(WallType::class, $wallUpdate);
          $formUpdate->handleRequest($request);

          if ($formUpdate->isSubmitted() && $formUpdate->isValid() && isset($_GET['update'])) {
            $em->persist($wallUpdate);
            $em->flush();
          }

          $render_params['formUpdate'] = $formUpdate;
        }

        return $this->render('SymfoTweetAdminBundle:wall:index.html.twig', $render_params);
    }

    public function formWallAction($form,$u=false){

        $render_params = array('form' => $form->createView());

        if($u){
          $render_params['u'] = $u;
        }

        return $this->render('SymfoTweetAdminBundle:wall:form.html.twig',$render_params);
    }

    /**
     * @Route("/user",name="admin_user")
     */
    public function UserAction(Request $request){
      $em = $this->getDoctrine()->getManager();

      $UserRepository = $em->getRepository('SymfoTweetCoreBundle:User');
      $userManager = $this->get('fos_user.user_manager');

      $page = $request->get('p',1);

      $user = $userManager->createUser();
      $form = $this->createForm(RegistrationFormType::class, $user);
      $form->handleRequest($request);

      if ($request->get('action') != "update" && $form->isSubmitted() && $form->isValid()){
        $user->setEnabled('true');
        $userManager->updatePassword($user);

        $em->persist($user);
        $em->flush();
      }

      if($request->get('action') == 'delete'){
        $userDelete = $UserRepository->find($request->get('id'));
        $userManager->deleteUser($userDelete);
      }

      $users = $UserRepository->findBy(
        array(),
        array('username' => 'ASC'),
        10,
        ($page - 1)*10
      );

      $render_params = array(
        'users' => $users,
        'form' => $form->createView(),
        'page' => $page,
        'pages' => ceil(count($UserRepository->findAll()) / 10)
      );

      if($request->get('id')){
        $userUpdate = $UserRepository->find($request->get('id'));
        if(!is_null($userUpdate)){
          $formUpdate = $this->createForm(RegistrationFormType::class, $userUpdate)
            ->add('enabled', CheckboxType::class,array(
              'required' => false
            ))
            ->remove('plainPassword');

          $formUpdate->handleRequest($request);

          if ($formUpdate->isSubmitted() && $formUpdate->isValid()){
            $em->persist($formUpdate->getData());
            $em->flush();
          }

          $render_params['formUpdate'] = $formUpdate->createView();
          $render_params['user'] = $userUpdate;
        }
      }

      return $this->render('SymfoTweetAdminBundle:user:index.html.twig',$render_params);
    }
}

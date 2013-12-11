<?php
/**
 * This file ist published under GPLv3
 * Licence: http://www.gnu.org/licenses/gpl-3.0.txt
 * User: chaser
 * Date: 11/21/13
 * Time: 11:47 AM
 */

namespace HSP\AdminBundle\Controller;

use HSP\AdminBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
  /**
   * generates user list
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function userHandlingAction()
  {
    $userManager = $this->get('fos_user.user_manager');
    $users = $userManager->findUsers();
    return $this->render('HSPAdminBundle:Default:userlist.html.twig', array(
      'name' => "userHandler",
      'users' => $users
    ));
  }

  /**
   * confirmation handler to delete user
   * @param $username
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function userDeleteConfirmAction($username)
  {
    $uM = $this->get('fos_user.user_manager');
    $user = $uM->findUserBy(array('username' => $username));
    if ($user->getIsNotDeleteable() == 1) {
      $this->get('session')->getFlashbag()->set('notice', $username . ' cannot be deleted!');
      return new RedirectResponse($this->generateUrl('hsp_admin_user_handling'));
    }
    return $this->render('HSPAdminBundle:Default:userdeleteconfirm.html.twig', array(
      'username' => $username
    ));
  }

  /**
   * delete user
   * @param $username
   * @return RedirectResponse
   */
  public function userDeleteAction($username)
  {
    $userManager = $this->get('fos_user.user_manager');
    $user = $userManager->findUserBy(array('username' => $username));
    $userManager->deleteUser($user);
    $this->get('session')->getFlashBag()->set('notice', $user . ' has been deleted!');

    return new RedirectResponse($this->generateUrl('hsp_admin_user_handling'));
  }

  /**
   * add or edit user
   * @param null $username
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function userAddEditAction($username = null, Request $request)
  {
    if ($username !== null) {
      $userManager = $this->get('fos_user.user_manager');
      if (!($user = $userManager->findUserBy(array('username' => $username)))) {
        $this->get('session')->getFlashbag()->add('notice', $username . ' not found!');
        $username = null;
        return new RedirectResponse($this->generateUrl('hsp_admin_user_handling'));
      }
    }
    if ($username == null) {
      $user = new User();
    }
    $form = $this->createFormBuilder($user)
      ->add('username', 'text', array('required' => true, 'label' => 'Username'))
      ->add('realname', 'text', array('required' => true, 'label' => 'Name'))
      ->add('password', 'repeated', array('required' => true,
        'type' => 'password',
        'first_options' => array('label' => 'Password'),
        'second_options' => array('label' => 'Confirm Password')))
      ->add('email', 'repeated', array('required' => true,
        'type' => 'text',
        'first_options' => array('label' => 'Email'),
        'second_options' => array('label' => 'Confirm Email')))
      ->add('roles', 'choice', array(
        'choices' => array(
          'ROLE_USER' => 'User',
          'ROLE_ADMIN' => 'Admin',
          'ROLE_SUPER_ADMIN' => 'Superadmin',
        ),
        'multiple' => true,
      ))
      ->add('enabled', 'checkbox', array('value' => 1, 'required' => false))


      ->add('save', 'submit')
      ->getForm();
    $form->handleRequest($request);
    if ($form->isValid()) {
      $this->get('session')->getFlashBag('notice', $username . ' has been saved.');
      $em = $this->getDoctrine()->getManager();
      $formData = $form->getData();
      $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
      $password = $encoder->encodePassword($formData->getPassword(), $formData->getSalt());
      $user->setPassword($password);
      $em->persist($user);
      $em->flush();
      return new RedirectResponse($this->generateUrl('hsp_admin_user_handling'));
    }
    return $this->render('HSPAdminBundle:Default:useraddedit.html.twig', array('addEditForm' => $form->createView()));
  }
} 
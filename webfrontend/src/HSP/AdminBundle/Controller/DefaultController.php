<?php

namespace HSP\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// TODO: replace all 'name' => occurences by proper template
class DefaultController extends Controller {
	// public function indexAction($name)
	public function indexAction() {
		$user = $this->container->get ( 'security.context' )->getToken ()->getUser ();
		// return $this->render('HSPAdminBundle:Default:index.html.twig', array('name' => $name));
		return $this->render ( 'HSPAdminBundle:Default:index.html.twig', array (
				'name' => "admin" 
		) );
	}
	public function userHandlingAction() {
		$userManager = $this->get ( 'fos_user.user_manager' );
		$users = $userManager->findUsers ();
		return $this->render ( 'HSPAdminBundle:Default:userlist.html.twig', array (
				'name' => "userHandler",
				'users' => $users 
		) );
	}
	public function userEditHandlingAction($username) {
		return $this->render ( 'HSPAdminBundle:Default:useredit.html.twig', array (
				'username' => $username 
		) );
	}
	public function userDeleteConfirmAction($username) {
		return $this->render ( 'HSPAdminBundle:Default:userdeleteconfirm.html.twig', array (
				'username' => $username 
		) );
	}
	public function userDeleteAction($username) {
		$userManager = $this->get ( 'fos_user.user_manager' );
		$user = $userManager->findUserBy(array('username' => $username));
		$userManager->deleteUser($user);
		return $this->render ( 'HSPAdminBundle:Default:userdelete.html.twig', array (
				'username' => $user 
		) );
	}
}

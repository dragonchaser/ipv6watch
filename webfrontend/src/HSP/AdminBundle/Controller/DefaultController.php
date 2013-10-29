<?php

namespace HSP\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller {
	public function indexAction() {
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
		/*return $this->render ( 'HSPAdminBundle:Default:userdelete.html.twig', array (
				'username' => $user 
		) );*/
        $this->get('session')->getFlashBag()->set('notice', $user.' has been deleted!');

        return new RedirectResponse($this->generateUrl('users'));
	}
}

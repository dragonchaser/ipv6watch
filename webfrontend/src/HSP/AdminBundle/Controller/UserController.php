<?php
/**
 * This file ist published under GPLv3
 * Licence: http://www.gnu.org/licenses/gpl-3.0.txt
 * User: chaser
 * Date: 11/21/13
 * Time: 11:47 AM
 */

namespace HSP\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

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

		return new RedirectResponse($this->generateUrl('users'));
	}
} 
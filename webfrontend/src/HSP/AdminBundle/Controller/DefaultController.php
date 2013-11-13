<?php

namespace HSP\AdminBundle\Controller;

use HSP\PageBundle\Entity\IPV6RouterData;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
	/**
	 * Default index page of the admin controller
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function indexAction()
	{
		return $this->render('HSPAdminBundle:Default:index.html.twig', array(
			'name' => "admin"
		));
	}

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
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function routerHandlingAction()
	{
		$repository = $this->getDoctrine()->getRepository('HSPPageBundle:IPV6RouterData');
		$routers = $repository->findAll();
		return $this->render('HSPAdminBundle:Default:routerlist.html.twig', array('routers' => $routers));
	}

	/**
	 * create user form to add a router, and create new entry if postdata is given
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function routerAddAction(Request $request)
	{
		$tmpRouter = new IPV6RouterData();
		$form = $this->createFormBuilder($tmpRouter)
			->add('routername', 'text', array('label' => 'Routername', 'required' => true))
			->add('ipv6Address', 'text', array('label' => 'IPV6 address', 'required' => false))
			->add('ipv4Address', 'text', array('label' => 'IPV4 address', 'required' => true))
			->add('macAddress', 'text', array('label' => 'Macaddress', 'required' => false))
			->add('userName', 'text', array('label' => 'SSH username', 'required' => true))
			->add('password', 'password', array('label' => 'SSH password', 'required' => true))
			->add('active', 'checkbox', array('label' => 'Status (checked = active, unchecked = disabled)', 'required' => false))
			->add('save', 'submit')
			->getForm();
		$form->handleRequest($request);
		if ($form->isValid()) {
			/* TODO: add form validation for ipv4 addresses etc */
			$this->get('session')->getFlashBag()->set('notice', 'added router ' . $tmpRouter->getRouterName());
			$em = $this->getDoctrine()->getManager();
			$em->persist($tmpRouter);
			$em->flush();
		}
		return $this->render('HSPAdminBundle:Default:routeradd.html.twig', array('form' => $form->createView()));
	}

	/**
	 * generate list of ipv6 leases
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function leaseListAction()
	{
		$repository = $this->getDoctrine()->getRepository('HSPPageBundle:IPV6LogEntry');
		$entries = $repository->findAll();
		return $this->render('HSPAdminBundle:Default:leaselist.html.twig', array('leases' => $entries));
	}

	/**
	 * create form to edit user
	 * @param $username
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function userEditHandlingAction($username)
	{
		/* TODO: add acl handling */
		return $this->render('HSPAdminBundle:Default:useredit.html.twig', array(
			'username' => $username
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

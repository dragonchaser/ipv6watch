<?php
/* TODO: add acl handling */
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
	public function routerAddEditAction($routerid = -1, Request $request)
	{
		$tmpRouter = new IPV6RouterData();
		$form = $this->createFormBuilder($tmpRouter)
			->add('routername', 'text', array('label' => 'Routername', 'required' => true))
			->add('ipv6Address', 'text', array('label' => 'IPV6 address', 'required' => false))
			->add('ipv4Address', 'text', array('label' => 'IPV4 address / FQDN', 'required' => true))
			->add('macAddress', 'text', array('label' => 'Macaddress', 'required' => false))
			->add('userName', 'text', array('label' => 'SSH username', 'required' => true))
			->add('password', 'password', array('label' => 'SSH password', 'required' => true))
			->add('active', 'checkbox', array('label' => 'Status (checked = active, unchecked = disabled)', 'required' => false, 'value' => 1))
			->add('save', 'submit')
			->getForm();
		$form->handleRequest($request);
		if ($form->isValid()) {
			$this->get('session')->getFlashBag()->set('notice', 'added router ' . $tmpRouter->getRouterName());
			$em = $this->getDoctrine()->getManager();
			$em->persist($tmpRouter);
			$em->flush();
		}
		return $this->render('HSPAdminBundle:Default:routeradd.html.twig', array('form' => $form->createView()));
	}

	/**
	 * delete router from list
	 * TODO: implement!
	 * @param $routerid
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function routerDeleteAction($routerid, Request $request)
	{
		return $this->render('HSPAdminBundle:Default:index.html.twig', array('name' => $routerid));
	}

	/**
	 * generate list of ipv6 leases
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function leaseListAction(Request $request)
	{
		$form = $this->createFormBuilder()
			->add('filter', 'text', array('label' => 'search for', 'required' => false))
			->add('filterBy', 'choice', array(
				'choices' => array(
					'any' => 'any',
					'ipv6' => 'IPV6 Address',
					'ipv4' => 'IPV4 Address',
					'max' => 'MAC Address'
				),
				'label' => 'in'
			))
			->add('submit', 'submit')
			->getForm();
		$form->handleRequest($request);
		$formData = $form->getData();
		if (!isset($formData['filterBy'])) $formData['filterBy'] = 'any';
		if (!isset($formData['filter'])) $formData['filter'] = ''; else $formData['filter'] = trim($formData['filter']);
		$this->get('session')->getFlashBag()->set('notice', $formData['filterBy']);
		$repository = $this->getDoctrine()->getRepository('HSPPageBundle:IPV6LogEntry');
		if (empty($formData['filter']))
			$entries = $repository->findAll();
		else
			switch ($formData['filter']) {
				case 'ipv6':
					$entries = $repository->findByipv6Address($formData['filter']);
					break;
				case 'ipv4':
					$entries = $repository->findByipv4Address($formData['filter']);
					break;
				case 'mac':
					$entries = $repository->findBymacAddress($formData['filter']);
					break;
				default:
				case 'any';
					$entries = $repository->findByipv6Address($formData['filter']);
					break;
			}
		return $this->render('HSPAdminBundle:Default:leaselist.html.twig',
			array('leases' => $entries, 'form' => $form->createView()));
	}

	/**
	 * lists all existing export files in export folder and allows to download / delete then
	 * TODO: implement!
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function leaseExportsAction(Request $request)
	{
		return $this->render('HSPAdminBundle:Default:index.html.twig', array('name' => 'leaseexport'));
	}

	/**
	 * create form to edit user
	 * TODO: implement!
	 * @param $username
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function userEditHandlingAction($username)
	{
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

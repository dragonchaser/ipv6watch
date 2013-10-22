<?php

namespace HSP\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// TODO: replace all 'name' => occurences by proper template
class DefaultController extends Controller
{
    //public function indexAction($name)
    public function indexAction()
    {
        //return $this->render('HSPAdminBundle:Default:index.html.twig', array('name' => $name));
        return $this->render('HSPAdminBundle:Default:index.html.twig', array('name' => "admin"));
    }
    
    public function userHandlingAction() {
    	return $this->render('HSPAdminBundle:Default:index.html.twig', array('name' => "userHandler"));
    }
}

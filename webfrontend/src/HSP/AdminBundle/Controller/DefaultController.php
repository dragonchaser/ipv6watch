<?php

namespace HSP\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    //public function indexAction($name)
    public function indexAction()
    {
        //return $this->render('HSPAdminBundle:Default:index.html.twig', array('name' => $name));
        return $this->render('HSPAdminBundle:Default:index.html.twig', array('name' => "admin"));
    }
}

<?php

namespace HSP\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
  public function indexAction()
  {
    return $this->render('HSPPageBundle:Default:index.html.twig', array('name' => "default page"));
  }
}

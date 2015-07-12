<?php

namespace Base\SMSGatewayBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BaseSMSGatewayBundle:Default:index.html.twig', array('name' => $name));
    }
}

<?php

namespace SosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('SosBundle:Default:index.html.twig');
    }

    /**
     * @Route("/profil")
     */
    public function profilAction()
    {
        return $this->render('SosBundle:Default:profil.html.twig');
    }


    /**
     * @Route("/dashboard")
     */
    public function dashboardAction()
    {
        return $this->render('SosBundle:Dashboard:dashboard.html.twig');
    }

}
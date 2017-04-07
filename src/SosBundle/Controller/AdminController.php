<?php
namespace SosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class AdminController extends Controller
{
    /**
     * @Route("/demandeRecommandation")
     */
    public function demandeRecommandationAction()
    {
        return $this->render('SosBundle:Dashboard:demandeRecommandation.html.twig');
    }



}
<?php
namespace SosBundle\Controller;

use SosBundle\Entity\Recommandation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;



class AdminController extends Controller
{
    /**
     * @Route("/demandeRecommandation")
     */
    public function demandeRecommandationAction()
    {

        if (isset($_POST['nom_etablissement'])){

            $em = $this->getDoctrine()->getManager();
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $civilite = $em->getRepository('SosBundle:Civilite')->find($_POST['civilite']);

            $newRecommandation = new  Recommandation();
            $newRecommandation->setNomEtablissement($_POST['nom_etablissement']);
            $newRecommandation->setEmail($_POST['email']);
            $newRecommandation->setVille($_POST['ville']);
            $newRecommandation->setNomResponsable($_POST['nom_responsable']);
            $newRecommandation->setValide(0);
            $newRecommandation->setCivilite($civilite);
            $newRecommandation->setUser($user);

            $em->persist($newRecommandation);
            $em->flush();


            $validation="Demande de recommandation envoyée !";
            return $this->render('SosBundle:Dashboard:dashboard.html.twig', array("validation"=>$validation));


        }

        return $this->render('SosBundle:Dashboard:demandeRecommandation.html.twig');
    }



}
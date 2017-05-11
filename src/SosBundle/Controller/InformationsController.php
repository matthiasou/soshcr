<?php

namespace SosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SosBundle\Entity\UserCritere;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;


class InformationsController extends Controller
{
   /**
     * @Route("/informations/contrat")
     */
    public function contratAction(Request $request)
    { 

        $data = array();
        $em = $this->getDoctrine()->getManager();

        $repo = $em->getRepository("SosBundle:Contrat");
        $contrats = $repo->findAll();

        dump($data);
        return $this->render('SosBundle:Informations:contrat.html.twig', array('contrats' => $contrats, 'data' => $data)); 

    }

    /**
     * @Route("/informations/duree")
     */
    public function dureeAction(Request $request)
    {
        $data = array();
        $em = $this->getDoctrine()->getManager();

      // Validation contrat
      if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "contrat" ) {

        $data['contrat'] = $request->get('contrat');

        $repo = $em->getRepository("SosBundle:Contrat");
        $contrat = $repo->find($data['contrat']);                

        dump($data);
        $contrat_duree = $contrat->getDuree();
        return $this->render('SosBundle:Informations:contrat_duree.html.twig', array('contrat_duree' => $contrat_duree, 'data' => $data)); 

        }
    }

    /**
     * @Route("/informations/cursus")
     */
    public function cursusAction(Request $request)
    {

      $data = array();
        $em = $this->getDoctrine()->getManager();

      // Validation ville
      if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "contrat" || $request->get('form') == "duree" ) {

        $data['contrat'] = $request->get('contrat');

        $repo = $em->getRepository("SosBundle:CursusScolaire");
        $cursus_scolaire = $repo->findAll();                

        dump($data);
        return $this->render('SosBundle:Informations:cursus_scolaire.html.twig', array('cursus_scolaire' => $cursus_scolaire, 'data' => $data)); 

      }

    }


}
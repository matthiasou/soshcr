<?php

namespace SosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserController extends Controller
{
	/**
     * @Route("/mesrecommandations")
     */
    public function mesrecommandationsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $recommandations = $em->getRepository('SosBundle:Recommandation')->findBy(array('user' => $user, 'valide' => 1));
        $nbreco = count($recommandations);
        
        return $this->render('SosBundle:User:mesrecommandations.html.twig', array("recommandations" => $recommandations, "nbreco"=>$nbreco));
    }
    /**
     * @Route("/validation/{code}/{id}")
     */
    public function validationAction($code, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('SosBundle:User')->findOneBy(array('id' => $id));
        $usercritere = $em->getRepository('SosBundle:userCritere')->findAll(array('user' => $user));
        $recommandation = $em->getRepository('SosBundle:Recommandation')->findOneBy(array('code' => $code, 'user' => $id));
        if(isset($_POST['envoyer'])){
            $reponse = $_POST['reponse'];
            if($reponse = "oui"){
                foreach ($usercritere as $u){
                    $score = $u->setScore($u->getScore()+10);
                    $em->persist($score);
                    $em->flush();
                }
                $recommandation->setValide(2);
                $em->persist($recommandation);
                $em->flush();
                return $this->redirectToRoute('index');
            }
        }
        return $this->render('SosBundle:User:validation.html.twig', array("recommandation" => $recommandation));
    }
 
    /**
     * @Route("/user/{id}")
     */
    public function publicProfilAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('SosBundle:User')->find($id);

        $formationsArr = array();
        $postesArr = array();
        $contratsArr = array();

        if (!$user->getCriteres()->isEmpty())
        {
            foreach ($user->criteres as $key => $critere) {

                $formations = $critere->getFormation();
                $contrats = $critere->getContrat();
                $poste = $critere->getPoste();
                $experience = $critere->getExperience();
                $duree = $critere->getDuree();
                $cursus = $critere->getCursus();

                $formationsArr = array();
                foreach ($formations as $key => $formation) {
                    $formationsArr[] = $formation->getLibelle();
                }

                $dureesArr = array();
                foreach ($duree as $key => $value) {
                    $dureesArr[] = $value->getLibelle();    
                }

                $cursusArr = array();
                foreach ($cursus as $key => $value) {
                    $cursusArr[] = $value->getLibelle();    
                }

                $postesArr[] = array('poste' => $poste->getLibelle(), 'experience' => $experience->getLibelle());
                $contratsArr[] = array('contrat' => $contrats->getLibelle(), 'duree' => $dureesArr, 'cursus' => $cursusArr);
                $anglaisArr[] = $critere->getNiveauAnglais()->getLibelle();
                $disponibilitesArr[] = json_decode($critere->getDisponibilites());

            }
            $disponibilitesArr = array_unique($disponibilitesArr, SORT_REGULAR);
            $postesArr = array_unique($postesArr, SORT_REGULAR);
            $contratsArr = array_unique($contratsArr, SORT_REGULAR);
            $formationsArr = array_unique($formationsArr);
            $anglaisArr = array_unique($anglaisArr);
            $userCriteres = array('formations' => $formationsArr, 'postes' => $postesArr, 'anglais' => $anglaisArr[0], 'contrats' => $contratsArr, 'disponibilite' => $disponibilitesArr);

            $now = new \DateTime('now');
            $age = $user->getDateNaissance();
            $userAge = $now->diff($age);
            
            return $this->render('SosBundle:User:publicProfil.html.twig', array("user" => $user, 'userCriteres' => $userCriteres, 'userAge' => $userAge->y));  
        }
        else
        {
            return $this->redirectToRoute('usercriteres_step_1');
        }
        
    }
}

<?php

namespace SosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use SosBundle\Entity\SuppressionCompte;
use Symfony\Component\Validator\Constraints\DateTime;

class UserController extends Controller
{
	/**
     * @Route("/mesrecommandations")
     */
    public function mesrecommandationsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $recommandations = $em->getRepository('SosBundle:Recommandation')->findBy(array('user' => $user, 'valide' => 2));
        $nbreco = count($recommandations);
        return $this->render('SosBundle:User:mesrecommandations.html.twig', array("recommandations" => $recommandations, "nbreco"=>$nbreco));
    }

    /**
     * @Route("/delete/confirmed")
     */
    public function deleteConfirmationUserAction(Request $request)
    {
        return $this->render('SosBundle:User:delete_confirmed.html.twig');
    }
 
    /**
     * @Route("/delete")
     * options = { "expose" = true },
     */
    public function deleteUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $raisons = $em->getRepository('SosBundle:RaisonSuppression')->findAll();

        if ($request->get('raison'))
        {
            $raison = $em->getRepository('SosBundle:RaisonSuppression')->find($request->get('raison'));
            $suppression = new SuppressionCompte();
            // si selection "autre", on rempli avec le contenu
            if ($request->get('raison') == 4)
            {
                $suppression->setContenu($request->get('contenu'));
            }
            $suppression->setRaisonSuppression($raison);
            $suppression->setDate(new \DateTime('now'));
            $em->persist($suppression);
            $user = $this->getUser();
            $em->remove($user);
            $em->flush();

           return $this->redirectToRoute('delete_confirmed'); 

        }

        return $this->render('SosBundle:User:delete.html.twig', array('raisons' => $raisons));
    }

    /**
     * @Route("/validation/{code}/{id}")
     */
    public function validationAction($code, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $testcode = $em->getRepository('SosBundle:Recommandation')->findOneBy(array('code' => $code));
        if(empty($testcode)){
            return $this->render('SosBundle:Default:index.html.twig', array("error"=> 'code'));
        }
        else {
        $user = $em->getRepository('SosBundle:User')->findOneBy(array('id' => $id));
        $usercritere = $em->getRepository('SosBundle:userCritere')->findAll(array('user' => $user));
        $recommandation = $em->getRepository('SosBundle:Recommandation')->findOneBy(array('code' => $code, 'user' => $id));

            foreach ($usercritere as $u){
                $score = $u->setScore($u->getScore()+10);
                $em->persist($score);
                $em->flush();
            }
        $recommandation->setValide(2);
        $em->persist($recommandation);
        $em->flush();
        $validation= 'Recommandation effectuée';
        return $this->render('SosBundle:Default:index.html.twig', array("validation"=>$validation));
        }
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
            
            $recommandations = $user->getRecommandations();
            $now = new \DateTime('now');
            $age = $user->getDateNaissance();
            $userAge = $now->diff($age);
            
            return $this->render('SosBundle:User:publicProfil.html.twig', array("user" => $user, 'userCriteres' => $userCriteres, 'userAge' => $userAge->y, 'recommandations' => $recommandations));  
        }
        else
        {
            return $this->redirectToRoute('usercriteres_step_1');
        }
        
    }
}

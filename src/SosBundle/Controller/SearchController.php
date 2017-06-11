<?php

namespace SosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use SosBundle\Entity\Etablissement;
use SosBundle\Entity\Secteur;
use SosBundle\Service\Matching;
use Symfony\Component\Validator\Constraints\DateTime;

class SearchController extends Controller
{

    /**
     * @Route("/search/ville")
     */
    public function villeAction(Request $request)
    {

        return $this->render('SosBundle:Search:ville.html.twig', array('step' => '1'));

    }

    /**
     * @Route("/search/classification")
     */
    public function classificationAction(Request $request)
    {

        $data = array();
        $em = $this->getDoctrine()->getManager();

        // Validation ville
        if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "ville") {

            $alreadyVille = $request->get('ville');
            if (empty($alreadyVille['latitude'])) {
                $geocoder = 'https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyBKuX3xaOa5tYT7bKs8jyEUL3eSiLgUs6M&address=%s&sensor=false';
                $query = sprintf($geocoder, urlencode($_POST['ville']));
                $result = json_decode(file_get_contents($query));

                if ($result == NULL || $result == "" || $result->status == "ZERO_RESULTS" || $result->status == "INVALID_REQUEST" || $result->status == "REQUEST_DENIED") {
                    return $this->render('SosBundle:Search:ville.html.twig', array('error' => 'ville'));
                } else {
                    $json = $result->results[0];
                    $data['ville']['libelle'] = $_POST['ville'];
                    $data['ville']['latitude'] = $json->geometry->location->lat;
                    $data['ville']['longitude'] = $json->geometry->location->lng;
                }
            } else {
                $data['ville'] = $alreadyVille;
            }

            $repo = $em->getRepository("SosBundle:Etablissement");
            $etablissements = $repo->findAll();

            $data['match_employe'] = $this->get('sos.matching')->getNumberOfEmploye($data, $request->get('form'));

            
            return $this->render('SosBundle:Search:classification.html.twig', array('etablissements' => $etablissements, 'data' => $data, 'step' => '2'));


        } else {

            return $this->redirectToRoute($request->get('form'));

        }
    }

    //  /**
    //   * @Route("/search/secteur")
    //   */
    //  public function secteurAction(Request $request)
    //  {

    //      $data = array();
    //      $em = $this->getDoctrine()->getManager();

    //  	// Validation classification
    //  	if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "classification" ) {

    // $data['ville'] = $request->get('ville');
    //  		$data['classification'] = $request->get('classification');

    //       $repo = $em->getRepository("SosBundle:Secteur");
    //       $secteurs = $repo->findAll();

    //       $data['match_employe'] = $this->get('sos.matching')->getNumberOfEmploye($data, $request->get('form'));

    //  		
    //  		return $this->render('SosBundle:Search:secteur.html.twig', array('secteurs' => $secteurs, 'data' => $data, 'step' => '3'));

    //  	}else{
    //          return $this->redirectToRoute($request->get('form'));
    //      }
    //  }

    //  /**
    //   * @Route("/search/service")
    //   */
    //  public function serviceAction(Request $request)
    //  {

    //      $data = array();
    //      $em = $this->getDoctrine()->getManager();

    // 		// Validation secteur activité
    //  	if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "secteur" ) {

    // $data['ville'] = $request->get('ville');
    //  		$data['classification'] = $request->get('classification');
    //  		$data['secteur_activite'] = $request->get('secteur_activite');

    //  		

    //  		$data['match_employe'] = $this->get('sos.matching')->getNumberOfEmploye($data, $request->get('form'));

    // if ($data['secteur_activite'] == 1) {

    // 	$qb = $em->createQueryBuilder();
    // 	$postes = $qb->select('p')
    //             ->from('SosBundle:PosteRecherche','p')
    //             ->where('p.secteur = :secteur_activite')
    //             ->setParameter('secteur_activite', $data['secteur_activite'])
    //             ->getQuery()
    //             ->getResult();

    // 	return $this->render('SosBundle:Search:poste.html.twig', array('postes' => $postes, 'data' => $data, 'step' => '5'));

    // }else if($data['secteur_activite'] == 2){

    //        $repo = $em->getRepository("SosBundle:Service");
    //        $services = $repo->findAll();

    // 	return $this->render('SosBundle:Search:service.html.twig', array('services' => $services, 'data' => $data, 'step' => '4'));

    // }

    //  	}else{
    //          return $this->redirectToRoute($request->get('form'));
    //      }
    //  }

    /**
     * @Route("/search/poste")
     */
    public function posteAction(Request $request)
    {

        $data = array();
        $em = $this->getDoctrine()->getManager();

        // Validation service restauration
        if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "service" || $request->get('form') == "classification") {

            $data['ville'] = $request->get('ville');
            $data['classification'] = $request->get('classification');

            $data['match_employe'] = $this->get('sos.matching')->getNumberOfEmploye($data, $request->get('form'));

            $repoPostes = $em->getRepository('SosBundle:PosteRecherche');
            $postes = $repoPostes->findBy(array(), array('id' => 'desc'));

            $repoSecteurs = $em->getRepository('SosBundle:Secteur');
            $secteurs = $repoSecteurs->findAll();

            $repoServices = $em->getRepository('SosBundle:Service');
            $services = $repoServices->findAll();


            
            return $this->render('SosBundle:Search:poste.html.twig', array('postes' => $postes, 'secteurs' => $secteurs, 'services' => $services, 'data' => $data, 'step' => '5'));

        } else {
            return $this->redirectToRoute($request->get('form'));
        }

    }

    /**
     * @Route("/search/contrat")
     */
    public function contratAction(Request $request)
    {

        $data = array();
        $em = $this->getDoctrine()->getManager();

        // Validation poste
        if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "poste") {

            $data['ville'] = $request->get('ville');
            $data['classification'] = $request->get('classification');
            $data['poste'] = $request->get('poste');

            $data['match_employe'] = $this->get('sos.matching')->getNumberOfEmploye($data, $request->get('form'));

            $repo = $em->getRepository("SosBundle:Contrat");
            $contrats = $repo->findBy(array(), array('id' => 'desc'));

            
            return $this->render('SosBundle:Search:contrat.html.twig', array('contrats' => $contrats, 'data' => $data, 'step' => '6'));

        } else {
            return $this->redirectToRoute($request->get('form'));
        }

    }

    /**
     * @Route("/search/duree")
     */
    public function dureeAction(Request $request)
    {
        $data = array();
        $em = $this->getDoctrine()->getManager();

        // Validation contrat
        if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "contrat") {

            $data['ville'] = $request->get('ville');
            $data['classification'] = $request->get('classification');
            $data['poste'] = $request->get('poste');
            $data['contrat'] = $request->get('contrat');

            $data['match_employe'] = $this->get('sos.matching')->getNumberOfEmploye($data, $request->get('form'));

            $repo = $em->getRepository("SosBundle:Contrat");
            $contrat = $repo->find($data['contrat']);

            
            $contrat_duree = $contrat->getDuree();
            return $this->render('SosBundle:Search:contrat_duree.html.twig', array('contrat_duree' => $contrat_duree, 'data' => $data, 'step' => '7'));

        } else {
            return $this->redirectToRoute($request->get('form'));
        }
    }

    /**
     * @Route("/search/cursus")
     */
    public function cursusAction(Request $request)
    {
        $data = array();
        $em = $this->getDoctrine()->getManager();

        // Validation contrat
        if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "duree" || $request->get('form') == "contrat") {

            $data['ville'] = $request->get('ville');
            $data['classification'] = $request->get('classification');
            $data['poste'] = $request->get('poste');
            $data['contrat'] = $request->get('contrat');

            if (null !== $request->get('contrat_duree')) {
                $data['contrat_duree'] = $request->get('contrat_duree');
            }

            $data['match_employe'] = $this->get('sos.matching')->getNumberOfEmploye($data, $request->get('form'));


            
            $cursus_scolaire_repo = $em->getRepository("SosBundle:CursusScolaire");
            $cursus_scolaire = $cursus_scolaire_repo->findBy(array(), array('id' => 'desc'));
            return $this->render('SosBundle:Search:cursus_scolaire.html.twig', array('cursus_scolaire' => $cursus_scolaire, 'data' => $data, 'step' => '8'));
        } else {
            return $this->redirectToRoute($request->get('form'));
        }
    }

    /**
     * @Route("/search/formation")
     */
    public function formationAction(Request $request)
    {
        $data = array();
        $em = $this->getDoctrine()->getManager();

        // Validation contrat
        if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "duree") {

            $data['ville'] = $request->get('ville');
            $data['classification'] = $request->get('classification');
            $data['poste'] = $request->get('poste');
            $data['contrat'] = $request->get('contrat');

            if (null !== $request->get('contrat_duree')) {
                $data['contrat_duree'] = $request->get('contrat_duree');
            }

            $data['match_employe'] = $this->get('sos.matching')->getNumberOfEmploye($data, $request->get('form'));


            $formation_minimum = $em->getRepository("SosBundle:Formation")->findAll();
            
            return $this->render('SosBundle:Search:formation_minimum.html.twig', array('formation_minimum' => $formation_minimum, 'data' => $data, 'step' => '8'));

        } else {
            return $this->redirectToRoute($request->get('form'));
        }

    }

    /**
     * @Route("/search/experience_minimum")
     */
    public function experienceAction(Request $request)
    {
        $data = array();
        $em = $this->getDoctrine()->getManager();

        // Validation formation minimum
        if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "formation" || $request->get('form') == "contrat") {

            $data['ville'] = $request->get('ville');
            $data['classification'] = $request->get('classification');
            $data['poste'] = $request->get('poste');
            $data['contrat'] = $request->get('contrat');

            // Si on est en CDD CDI
            if (null !== $request->get('formation_minimum')) {
                $data['formation_minimum'] = $request->get('formation_minimum');
            }

            if (null !== $request->get('contrat_duree')) {
                $data['contrat_duree'] = $request->get('contrat_duree');
            }


            $data['match_employe'] = $this->get('sos.matching')->getNumberOfEmploye($data, $request->get('form'));

            $repo = $em->getRepository("SosBundle:Experience");
            $experience_minimum = $repo->findAll();

            
            return $this->render('SosBundle:Search:experience_minimum.html.twig', array('experience_minimum' => $experience_minimum, 'data' => $data, 'step' => '9'));

        } else {
            return $this->redirectToRoute($request->get('form'));
        }
    }

    /**
     * @Route("/search/anglais")
     */
    public function anglaisAction(Request $request)
    {

        $data = array();
        $em = $this->getDoctrine()->getManager();

        // Validation experience minimum
        if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "experience" || $request->get('form') == "cursus") {

            $data['ville'] = $request->get('ville');
            $data['classification'] = $request->get('classification');
            $data['poste'] = $request->get('poste');
            $data['contrat'] = $request->get('contrat');

            // Si on est en CDD CDI
            if (null !== $request->get('formation_minimum')) {
                $data['formation_minimum'] = $request->get('formation_minimum');
            }


            if (null !== $request->get('experience_minimum')) {
                $data['experience_minimum'] = $request->get('experience_minimum');
            } else {
                $data['cursus_scolaire'] = $request->get('cursus_scolaire');

            }

            if (null !== $request->get('contrat_duree')) {
                $data['contrat_duree'] = $request->get('contrat_duree');
            }

            $data['match_employe'] = $this->get('sos.matching')->getNumberOfEmploye($data, $request->get('form'));

            $repo = $em->getRepository("SosBundle:Anglais");
            $niveau_anglais = $repo->findAll();

            
            return $this->render('SosBundle:Search:anglais.html.twig', array('niveau_anglais' => $niveau_anglais, 'data' => $data, 'step' => '9'));

        } else {
            return $this->redirectToRoute($request->get('form'));
        }
    }

    /**
     * @Route("/search/date")
     */
    public function dateAction(Request $request)
    {
        $data = array();
        $em = $this->getDoctrine()->getManager();

        // Validation anglais
        if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "anglais") {

            $data['ville'] = $request->get('ville');
            $data['classification'] = $request->get('classification');
            $data['poste'] = $request->get('poste');
            $data['contrat'] = $request->get('contrat');
            $data['niveau_anglais'] = $request->get('niveau_anglais');

            if (null !== $request->get('cursus_scolaire')) {
                $data['cursus_scolaire'] = $request->get('cursus_scolaire');
            }

            if (null !== $request->get('contrat_duree')) {
                $data['contrat_duree'] = $request->get('contrat_duree');
            }

            if (null !== $request->get('formation_minimum')) {
                $data['formation_minimum'] = $request->get('formation_minimum');
            }

            if (null !== $request->get('experience_minimum')) {
                $data['experience_minimum'] = $request->get('experience_minimum');
            }

            $data['match_employe'] = $this->get('sos.matching')->getNumberOfEmploye($data, $request->get('form'));

            
            return $this->render('SosBundle:Search:date_debut.hml.twig', array('data' => $data, 'step' => '10'));

        } else {
            return $this->redirectToRoute($request->get('form'));
        }
    }

    /**
     * @Route("/search/resultat")
     */
    public function resultatAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        // Validation date
        if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "date") {

            $data['ville'] = $request->get('ville');
            $data['classification'] = $request->get('classification');
            $data['poste'] = $request->get('poste');
            $data['contrat'] = $request->get('contrat');
            $data['niveau_anglais'] = $request->get('niveau_anglais');
            $data['date_debut'] = $request->get('date_debut');

            if (null !== $request->get('cursus_scolaire')) {
                $data['cursus_scolaire'] = $request->get('cursus_scolaire');
            }

            if (null !== $request->get('contrat_duree')) {
                $data['contrat_duree'] = $request->get('contrat_duree');
            }

            if (null !== $request->get('formation_minimum')) {
                $data['formation_minimum'] = $request->get('formation_minimum');
            }

            if (null !== $request->get('experience_minimum')) {
                $data['experience_minimum'] = $request->get('experience_minimum');
            }

            //$this->get('sos.matching')->setScoreEmploye($data);
            $data['employes'] = $this->get('sos.matching')->getEmploye($data);

            foreach ($data['employes'] as $employee) {
                $dateNaisssance = $employee->getDateNaissance();
                $today = new \DateTime('NOW');
                $age = $today->diff($dateNaisssance);

                $employee->age = (int)($age->days / 365);
                $recommandation = $em->getRepository("SosBundle:Recommandation")->findby(array('user' => $employee, 'valide' => 2));
                $nbRecommandation = count($recommandation);
                $employee->nbRecommandation = $nbRecommandation;
                $data['recommandations'] = $recommandation;
            }

            
            return $this->render('SosBundle:Search:resultat.html.twig', array('data' => $data));

        } else {
            return $this->redirectToRoute($request->get('form'));
        }


    }


    /**
     * @Route("/search/demandeCv")
     */
    public function demandeCvAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();


        // Demande le Cv au candidat
        if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "resultat") {

            $action = $_POST['action'];

            $data['ville'] = $request->get('ville');
            $data['classification'] = $request->get('classification');
            $data['poste'] = $request->get('poste');
            $data['contrat'] = $request->get('contrat');
            $data['niveau_anglais'] = $request->get('niveau_anglais');
            $data['date_debut'] = $request->get('date_debut');

            if (null !== $request->get('cursus_scolaire')) {
                $data['cursus_scolaire'] = $request->get('cursus_scolaire');
            }

            if (null !== $request->get('contrat_duree')) {
                $data['contrat_duree'] = $request->get('contrat_duree');
            }

            if (null !== $request->get('formation_minimum')) {
                $data['formation_minimum'] = $request->get('formation_minimum');
            }

            if (null !== $request->get('experience_minimum')) {
                $data['experience_minimum'] = $request->get('experience_minimum');
            }

            $data['employes'] = $this->get('sos.matching')->getEmploye($data);

            foreach ($data['employes'] as $employee) {
                $dateNaisssance = $employee->getDateNaissance();
                $today = new \DateTime('NOW');
                $age = $today->diff($dateNaisssance);

                $employee->age = (int)($age->days / 365);
                $recommandation = $em->getRepository("SosBundle:Recommandation")->findby(array('user' => $employee, 'valide' => 2));
                $nbRecommandation = count($recommandation);
                $employee->nbRecommandation = $nbRecommandation;
            }

            $poste = $em->getRepository("SosBundle:PosteRecherche")->findOneBy(array('id' => $data['poste']));
            $data['demande_poste'] = $poste;
            $contrat = $em->getRepository("SosBundle:Contrat")->findOneBy(array('id' => $data['contrat']));
            $data['demande_contrat'] = $contrat;


            $tab_demande = [];

            if ($action == 'imprimer' && (isset($_POST['mail_demande_utilisateur']))) {
                foreach ($_POST['mail_demande_utilisateur'] as $demandeCV) {
                    $tab_demande[] = $demandeCV;
                }
                $data['mail_demande_utilisateur'] = $tab_demande;

                $template = $this->renderView('SosBundle:Search:imprimer.html.twig', array('data' => $data));
                $html2pdf = $this->get('app.html2pdf');
                $html2pdf->create('P', 'A4', 'fr', true, 'UTF-8', array(0, 0, 10, 15));
                return $html2pdf->generatePdf($template, "ResultatsSOSHCR")->getContent();


            } elseif ($action == 'demandeCV' && (isset($_POST['mail_demande_utilisateur']))) {
                foreach ($_POST['mail_demande_utilisateur'] as $demandeCV) {
                    $tab_demande[] = $demandeCV;
                }
                $data['mail_demande_utilisateur'] = $tab_demande;
                return $this->render('SosBundle:Search:demandeCv.html.twig', array('data' => $data));
            } elseif ($action == 'secteur_activite') {
                $data['match_employe'] = $this->get('sos.matching')->getNumberOfEmploye($data, $request->get('form'));
                $repo = $em->getRepository("SosBundle:Secteur");
                $secteurs = $repo->findAll();
                return $this->render('SosBundle:Search:secteur.html.twig', array('data' => $data, 'secteurs' => $secteurs, 'step' => '3'));
            } elseif ($action == 'classification') {
                $data['match_employe'] = $this->get('sos.matching')->getNumberOfEmploye($data, $request->get('form'));
                $repo = $em->getRepository("SosBundle:Etablissement");
                $etablissements = $repo->findAll();
                return $this->render('SosBundle:Search:classification.html.twig', array('data' => $data, 'etablissements' => $etablissements, 'step' => '2'));
            } elseif ($action == 'poste') {

                $data['match_employe'] = $this->get('sos.matching')->getNumberOfEmploye($data, $request->get('form'));
                $repoPostes = $em->getRepository('SosBundle:PosteRecherche');
                $postes = $repoPostes->findBy(array(), array('id' => 'desc'));

                $repoSecteurs = $em->getRepository('SosBundle:Secteur');
                $secteurs = $repoSecteurs->findAll();

                $repoServices = $em->getRepository('SosBundle:Service');
                $services = $repoServices->findAll();


                return $this->render('SosBundle:Search:poste.html.twig', array('postes' => $postes, 'secteurs' => $secteurs, 'services' => $services, 'data' => $data, 'step' => '5'));


            } elseif ($action == 'contrat') {
                $data['match_employe'] = $this->get('sos.matching')->getNumberOfEmploye($data, $request->get('form'));
                $repo = $em->getRepository("SosBundle:Contrat");
                $contrats = $repo->findBy(array(), array('id' => 'desc'));
                return $this->render('SosBundle:Search:contrat.html.twig', array('data' => $data, 'contrats' => $contrats, 'step' => '6'));
            } elseif ($action == 'contrat_duree') {
                $data['match_employe'] = $this->get('sos.matching')->getNumberOfEmploye($data, $request->get('form'));
                $repo = $em->getRepository("SosBundle:Contrat");
                $contrat = $repo->find($data['contrat']);

                $contrat_duree = $contrat->getDuree();
                return $this->render('SosBundle:Search:contrat_duree.html.twig', array('data' => $data, 'contrat_duree' => $contrat_duree, 'step' => '7'));
            } elseif ($action == 'niveau_anglais') {
                $data['match_employe'] = $this->get('sos.matching')->getNumberOfEmploye($data, $request->get('form'));
                $repo = $em->getRepository("SosBundle:Anglais");
                $niveau_anglais = $repo->findAll();

                return $this->render('SosBundle:Search:anglais.html.twig', array('data' => $data, 'niveau_anglais' => $niveau_anglais, 'step' => '8'));
            } elseif ($action == 'cursus_scolaire') {
                $data['ville'] = $request->get('ville');
                $data['classification'] = $request->get('classification');
                $data['poste'] = $request->get('poste');
                $data['contrat'] = $request->get('contrat');

                if (null !== $request->get('contrat_duree')) {
                    $data['contrat_duree'] = $request->get('contrat_duree');
                }

                $data['match_employe'] = $this->get('sos.matching')->getNumberOfEmploye($data, $request->get('form'));


                $cursus_scolaire_repo = $em->getRepository("SosBundle:CursusScolaire");
                $cursus_scolaire = $cursus_scolaire_repo->findBy(array(), array('id' => 'desc'));
                return $this->render('SosBundle:Search:cursus_scolaire.html.twig', array('cursus_scolaire' => $cursus_scolaire, 'data' => $data, 'step' => '8'));
            } elseif ($action == 'experience_minimum') {
                $data['match_employe'] = $this->get('sos.matching')->getNumberOfEmploye($data, $request->get('form'));
                $data['ville'] = $request->get('ville');
                $data['classification'] = $request->get('classification');
                $data['poste'] = $request->get('poste');
                $data['contrat'] = $request->get('contrat');

                // Si on est en CDD CDI
                if (null !== $request->get('formation_minimum')) {
                    $data['formation_minimum'] = $request->get('formation_minimum');
                }

                if (null !== $request->get('contrat_duree')) {
                    $data['contrat_duree'] = $request->get('contrat_duree');
                }



                $repo = $em->getRepository("SosBundle:Experience");
                $experience_minimum = $repo->findAll();

                return $this->render('SosBundle:Search:experience_minimum.html.twig', array('experience_minimum' => $experience_minimum, 'data' => $data, 'step' => '9'));

            } else {
                return $this->render('SosBundle:Search:resultat.html.twig', array('data' => $data, 'error' => 'resultat'));
            }


        } else if (isset($_POST['message'])) {
            $data['ville'] = $request->get('ville');
            $data['classification'] = $request->get('classification');
            $data['poste'] = $request->get('poste');
            $data['contrat'] = $request->get('contrat');
            $data['niveau_anglais'] = $request->get('niveau_anglais');
            $data['date_debut'] = $request->get('date_debut');

            if (null !== $request->get('cursus_scolaire')) {
                $data['cursus_scolaire'] = $request->get('cursus_scolaire');
            }

            if (null !== $request->get('contrat_duree')) {
                $data['contrat_duree'] = $request->get('contrat_duree');
            }

            if (null !== $request->get('formation_minimum')) {
                $data['formation_minimum'] = $request->get('formation_minimum');
            }

            if (null !== $request->get('experience_minimum')) {
                $data['experience_minimum'] = $request->get('experience_minimum');
            }

            $data['employes'] = $this->get('sos.matching')->getEmploye($data);

            foreach ($data['employes'] as $employee) {
                $dateNaisssance = $employee->getDateNaissance();
                $today = new \DateTime('NOW');
                $age = $today->diff($dateNaisssance);

                $employee->age = (int)($age->days / 365);
                $recommandation = $em->getRepository("SosBundle:Recommandation")->findby(array('user' => $employee, 'valide' => 1));
                $nbRecommandation = count($recommandation);
                $employee->nbRecommandation = $nbRecommandation;
            }

            $poste = $em->getRepository("SosBundle:PosteRecherche")->findOneBy(array('id' => $data['poste']));
            $data['demande_poste'] = $poste;
            $contrat = $em->getRepository("SosBundle:Contrat")->findOneBy(array('id' => $data['contrat']));
            $data['demande_contrat'] = $contrat;


            $validation = "Votre demande de CV a été envoyé avec succès";

            foreach ($_POST['mail_demande_utilisateur'] as $user) {
                $message = \Swift_Message::newInstance()
                    ->setSubject('Nouvelle demande sur SOSHCR')
                    ->setFrom('soshcr@contact.fr')
                    ->setTo($user)
                    ->setBody(
                        $this->renderView(
                            'SosBundle:Search:messageCv.html.twig',
                            array('message' => $_POST['message'])
                        ),
                        'text/html'
                    );
                $this->get('mailer')->send($message);
            }


            return $this->render('SosBundle:Search:resultat.html.twig', array('data' => $data, "validation" => $validation));

        } else {
            return $this->redirectToRoute('index');
        }

    }

    /**
     * @Route("/search/imprimer")
     */
    public function imprimerAction()
    {
        return $this->render('SosBundle:Search:imprimer.html.twig');
    }
}

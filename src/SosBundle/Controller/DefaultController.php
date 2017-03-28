<?php

namespace SosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use SosBundle\Entity\Etablissement;
use SosBundle\Entity\Secteur;
use SosBundle\Service\Matching;

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
     * @Route("/search")
     */
    public function searchAction(Request $request)
    {	

    	$data = array();
        $em = $this->getDoctrine()->getManager();

    	// Validation ville
    	if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "ville" ) {

    		$data['ville'] = $request->get('ville');

    		$doctrine = $this->getDoctrine();
	        $em = $doctrine->getManager();

	        // Si google ne trouve pas les coordonnées de la ville écrite on la cherche dans la BDD
    		if ($data['ville']['latitude'] == "" || $data['ville']['longitude'] == "") {
    			
    			$qb = $em->createQueryBuilder();

    			$result = $qb->select('v')
                  ->from('SosBundle:Ville','v')
                  ->where('v.libelle LIKE :libelle')
                  ->setParameter('libelle', $data['ville']['libelle'])
                  ->getQuery()
                  ->getOneOrNullResult();
		     
                if ($result == null) {
                	return $this->render('SosBundle:Search:ville.html.twig', array('error' => 'La ville ne fait pas partie de notre base de donnée.'));
                }else{
                	$data['ville']['libelle'] = $result->getLibelle();
                	$data['ville']['latitude'] = $result->getLatitude();
                	$data['ville']['longitude'] = $result->getLongitude();
                }

    		}

	        $repo = $em->getRepository("SosBundle:Etablissement");
	        $etablissements = $repo->findAll();

			$data['match_employe'] = $this->get('sos.matching')->getNumberOfEmploye($data, $request->get('form'));

	        dump($data);
    		return $this->render('SosBundle:Search:classification.html.twig', array('etablissements' => $etablissements, 'data' => $data));	
    	}

    	// Validation classification
    	if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "classification" ) {

			$data['ville'] = $request->get('ville');
    		$data['classification'] = $request->get('classification');

    		$doctrine = $this->getDoctrine();
	        $em = $doctrine->getManager();
	        $repo = $em->getRepository("SosBundle:Secteur");
	        $secteurs = $repo->findAll();

	        $data['match_employe'] = $this->get('sos.matching')->getNumberOfEmploye($data, $request->get('form'));

    		dump($data);
    		return $this->render('SosBundle:Search:secteur.html.twig', array('secteurs' => $secteurs, 'data' => $data));	
    	}

   		// Validation secteur activité
    	if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "secteur" ) {

			$data['ville'] = $request->get('ville');
    		$data['classification'] = $request->get('classification');
    		$data['secteur_activite'] = $request->get('secteur_activite');

    		dump($data);

    		$data['match_employe'] = $this->get('sos.matching')->getNumberOfEmploye($data, $request->get('form'));

			if ($data['secteur_activite'] == 1) {

				$qb = $em->createQueryBuilder();
				$postes = $qb->select('p')
	              ->from('SosBundle:PosteRecherche','p')
	              ->where('p.secteur = :secteur_activite')
	              ->setParameter('secteur_activite', $data['secteur_activite'])
	              ->getQuery()
	              ->getResult();

				return $this->render('SosBundle:Search:poste.html.twig', array('postes' => $postes, 'data' => $data));	  	

			}else if($data['secteur_activite'] == 2){

		 		$doctrine = $this->getDoctrine();
		        $em = $doctrine->getManager();
		        $repo = $em->getRepository("SosBundle:Service");
		        $services = $repo->findAll();

				return $this->render('SosBundle:Search:service.html.twig', array('services' => $services, 'data' => $data));	  	

			}
    		
    	}

   		// Validation service restauration
    	if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "service" ) {

			$data['ville'] = $request->get('ville');
    		$data['classification'] = $request->get('classification');
    		$data['secteur_activite'] = $request->get('secteur_activite');
    		$data['service_activite'] = $request->get('service_activite');

			$data['match_employe'] = $this->get('sos.matching')->getNumberOfEmploye($data, $request->get('form'));

			$qb = $em->createQueryBuilder();
			$postes = $qb->select('p')
              ->from('SosBundle:PosteRecherche','p')
              ->where('p.secteur = :secteur_activite')
              ->andWhere('p.service = :service_activite')
              ->setParameters(array('secteur_activite' => $data['secteur_activite'], 'service_activite' => $data['service_activite']))
              ->getQuery()
              ->getResult();

    		dump($data);
			return $this->render('SosBundle:Search:poste.html.twig', array('postes' => $postes, 'data' => $data));	  	
    	}

   		// Validation poste
    	if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "poste" ) {

			$data['ville'] = $request->get('ville');
    		$data['classification'] = $request->get('classification');
    		$data['secteur_activite'] = $request->get('secteur_activite');
    		$data['poste'] = $request->get('poste');

    		if (null !== $request->get('service_activite')) {
    			$data['service_activite'] = $request->get('service_activite');
    		}

			$data['match_employe'] = $this->get('sos.matching')->getNumberOfEmploye($data, $request->get('form'));

    		$doctrine = $this->getDoctrine();
	        $em = $doctrine->getManager();
	        $repo = $em->getRepository("SosBundle:Contrat");
	        $contrats = $repo->findAll();

    		dump($data);
    		return $this->render('SosBundle:Search:contrat.html.twig', array('contrats' => $contrats, 'data' => $data));	
    	}

   		// Validation contrat
    	if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "contrat" ) {

			$data['ville'] = $request->get('ville');
    		$data['classification'] = $request->get('classification');
    		$data['secteur_activite'] = $request->get('secteur_activite');
    		$data['poste'] = $request->get('poste');
    		$data['contrat'] = $request->get('contrat');

    		// Si on est dans la restauration : on ajoute le type de restauration
    		if (null !== $request->get('service_activite')) {
    			$data['service_activite'] = $request->get('service_activite');
    		}

			$data['match_employe'] = $this->get('sos.matching')->getNumberOfEmploye($data, $request->get('form'));

        	$contrat_repo = $em->getRepository("SosBundle:Contrat");
	        $contrat = $contrat_repo->find($data['contrat']);
            $typeContrat = $contrat->getLibelle();

            if( $typeContrat == "CDD" || $typeContrat == 'CDI' ) {
                
                dump($data);
                $contrat_duree = $contrat->getDuree();
                return $this->render('SosBundle:Search:contrat_duree.html.twig', array('contrat_duree' => $contrat_duree, 'data' => $data));  
            
            }else if( $typeContrat == "Extra"){
                
                dump($data);
                $experience_repo = $em->getRepository("SosBundle:Experience");
                $experience_minimum = $experience_repo->findAll();
                return $this->render('SosBundle:Search:experience_minimum.html.twig', array('experience_minimum' => $experience_minimum, 'data' => $data));   

            }else if( $typeContrat == "Stage"){

                dump($data);
                $contrat_duree = $contrat->getDuree();
                return $this->render('SosBundle:Search:contrat_duree.html.twig', array('contrat_duree' => $contrat_duree, 'data' => $data));    
           
            }else if( $typeContrat == "Apprentissage"){

                dump($data);
                $cursus_scolaire_repo = $em->getRepository("SosBundle:CursusScolaire");
                $cursus_scolaire = $cursus_scolaire_repo->findAll();
                return $this->render('SosBundle:Search:cursus_scolaire.html.twig', array('cursus_scolaire' => $cursus_scolaire, 'data' => $data));    
            }
        
		}

   		// Validation duree contrat
    	if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "duree" ) {

			$data['ville'] = $request->get('ville');
    		$data['classification'] = $request->get('classification');
    		$data['secteur_activite'] = $request->get('secteur_activite');
    		$data['poste'] = $request->get('poste');
    		$data['contrat'] = $request->get('contrat');
    		$data['contrat_duree'] = $request->get('contrat_duree');

    		// Si on est dans la restauration : on ajoute le type de restauration
    		if (null !== $request->get('service_activite')) {
    			$data['service_activite'] = $request->get('service_activite');
    		}

            $contrat  = $em->getRepository("SosBundle:Contrat")->find($data['contrat']);
            $typeContrat = $contrat->getLibelle();
			$data['match_employe'] = $this->get('sos.matching')->getNumberOfEmploye($data, $request->get('form'));

            if ($typeContrat == "Stage") {

                $cursus_scolaire  = $em->getRepository("SosBundle:CursusScolaire")->findAll();
                dump($data);            
                return $this->render('SosBundle:Search:cursus_scolaire.html.twig', array('cursus_scolaire' => $cursus_scolaire, 'data' => $data));

            }else{

                $formation_minimum  = $em->getRepository("SosBundle:Formation")->findAll();
                dump($data);            
                return $this->render('SosBundle:Search:formation_minimum.html.twig', array('formation_minimum' => $formation_minimum, 'data' => $data));  

            }

		}

        // Validation formation minimum
        if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "formation" ) {

            $data['ville'] = $request->get('ville');
            $data['classification'] = $request->get('classification');
            $data['secteur_activite'] = $request->get('secteur_activite');
            $data['poste'] = $request->get('poste');
            $data['contrat'] = $request->get('contrat');
            $data['contrat_duree'] = $request->get('contrat_duree');
            $data['formation_minimum'] = $request->get('formation_minimum');

            // Si on est dans la restauration : on ajoute le type de restauration
            if (null !== $request->get('service_activite')) {
                $data['service_activite'] = $request->get('service_activite');
            }

            $data['match_employe'] = $this->get('sos.matching')->getNumberOfEmploye($data, $request->get('form'));

            $repo = $em->getRepository("SosBundle:Experience");
            $experience_minimum = $repo->findAll();

            dump($data);
            return $this->render('SosBundle:Search:experience_minimum.html.twig', array('experience_minimum' => $experience_minimum, 'data' => $data));    

        }

   		// Validation cursus scolaire
    	if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "cursus" ) {

			$data['ville'] = $request->get('ville');
    		$data['classification'] = $request->get('classification');
    		$data['secteur_activite'] = $request->get('secteur_activite');
    		$data['poste'] = $request->get('poste');
    		$data['contrat'] = $request->get('contrat');
    		$data['cursus_scolaire'] = $request->get('cursus_scolaire');

    		// Si on est dans la restauration : on ajoute le type de restauration
    		if (null !== $request->get('service_activite')) {
    			$data['service_activite'] = $request->get('service_activite');
    		}

            $contrat  = $em->getRepository("SosBundle:Contrat")->find($data['contrat']);
            $typeContrat = $contrat->getLibelle();

            if ($typeContrat != "Apprentissage") {
                $data['contrat_duree'] = $request->get('contrat_duree');
            }
			$data['match_employe'] = $this->get('sos.matching')->getNumberOfEmploye($data, $request->get('form'));

        	$repo = $em->getRepository("SosBundle:Anglais");
	        $niveau_anglais = $repo->findAll();

			dump($data);    		
    		return $this->render('SosBundle:Search:anglais.html.twig', array('niveau_anglais' => $niveau_anglais, 'data' => $data));	

		}

        // Validation experience minimum
        if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "experience" ) {

            $data['ville'] = $request->get('ville');
            $data['classification'] = $request->get('classification');
            $data['secteur_activite'] = $request->get('secteur_activite');
            $data['poste'] = $request->get('poste');
            $data['contrat'] = $request->get('contrat');

            // Si on est en CDD CDI
            if (null !== $request->get('formation_minimum')) {
                $data['formation_minimum'] = $request->get('formation_minimum');
            }
            
            if (null !== $request->get('experience_minimum')) {
                $data['experience_minimum'] = $request->get('experience_minimum');
            }
            
            if (null !== $request->get('contrat_duree')) {
                $data['contrat_duree'] = $request->get('contrat_duree');
            }

            // Si on est dans la restauration : on ajoute le type de restauration
            if (null !== $request->get('service_activite')) {
                $data['service_activite'] = $request->get('service_activite');
            }

            $data['match_employe'] = $this->get('sos.matching')->getNumberOfEmploye($data, $request->get('form'));

            $repo = $em->getRepository("SosBundle:Anglais");
            $niveau_anglais = $repo->findAll();

            dump($data);            
            return $this->render('SosBundle:Search:anglais.html.twig', array('niveau_anglais' => $niveau_anglais, 'data' => $data));    

        }

   		// Validation anglais
    	if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "anglais" ) {

			$data['ville'] = $request->get('ville');
    		$data['classification'] = $request->get('classification');
    		$data['secteur_activite'] = $request->get('secteur_activite');
    		$data['poste'] = $request->get('poste');
    		$data['contrat'] = $request->get('contrat');
    		$data['niveau_anglais'] = $request->get('niveau_anglais');

    		// Si on est dans la restauration : on ajoute le type de restauration
    		if (null !== $request->get('service_activite')) {
    			$data['service_activite'] = $request->get('service_activite');
    		}

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

			dump($data);    		
    		return $this->render('SosBundle:Search:date_debut.hml.twig', array('data' => $data));	

		}


   		// Validation date
    	if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "date" ) {


            $data['ville'] = $request->get('ville');
            $data['classification'] = $request->get('classification');
            $data['secteur_activite'] = $request->get('secteur_activite');
            $data['poste'] = $request->get('poste');
            $data['contrat'] = $request->get('contrat');
            $data['niveau_anglais'] = $request->get('niveau_anglais');
            $data['date_debut'] = $request->get('date_debut');

            // Si on est dans la restauration : on ajoute le type de restauration
            if (null !== $request->get('service_activite')) {
                $data['service_activite'] = $request->get('service_activite');
            }

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

			dump($data);		
    		return $this->render('SosBundle:Search:resultat.html.twig', array('data' => $data));	

		}

        return $this->render('SosBundle:Search:ville.html.twig');
    }


}
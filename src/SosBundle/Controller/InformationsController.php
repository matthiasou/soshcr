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

      // Validation cursus
      if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "contrat" || $request->get('form') == "duree" ) {

        $data['contrat'] = $request->get('contrat');
        $data['contrat_duree'] = $request->get('contrat_duree');

        $repo = $em->getRepository("SosBundle:CursusScolaire");
        $cursus_scolaire = $repo->findAll();                

        dump($data);
        return $this->render('SosBundle:Informations:cursus_scolaire.html.twig', array('cursus_scolaire' => $cursus_scolaire, 'data' => $data)); 

      }

    }

    /**
     * @Route("/informations/classification")
     */
    public function classificationAction(Request $request)
    {

      $data = array();
      $em = $this->getDoctrine()->getManager();

      // Validation ville
      if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "cursus" || $request->get('form') == "duree" ) {

        $data['contrat'] = $request->get('contrat');
        $data['contrat_duree'] = $request->get('contrat_duree');  


        if (null != $request->get('cursus_scolaire')) {
          $data['cursus_scolaire'] = $request->get('cursus_scolaire');  
        }
        
        $repo = $em->getRepository("SosBundle:Etablissement");
        $etablissements = $repo->findAll();                

        dump($data);
        return $this->render('SosBundle:Informations:classification.html.twig', array('etablissements' => $etablissements, 'data' => $data)); 

      }

    }

   /**
     * @Route("/informations/ville")
     */
    public function villeAction(Request $request)
    {

      $data = array();
      $em = $this->getDoctrine()->getManager();

      // Validation ville
      if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "classification" ) {

          $data['classification'] = $request->get('classification');
          $data['contrat'] = $request->get('contrat');
          
          // Si on est en CDD CDI
          if (null !== $request->get('contrat_duree')) {
              $data['contrat_duree'] = $request->get('contrat_duree');
          }

          // Si on est en CDD CDI
          if (null !== $request->get('formation_minimum')) {
              $data['formation_minimum'] = $request->get('formation_minimum');
          }
          
          if (null !== $request->get('cursus_scolaire')) {
              $data['cursus_scolaire'] = $request->get('cursus_scolaire');
          }

        $repo = $em->getRepository("SosBundle:Secteur");
        $secteurs = $repo->findAll();                

        dump($data);
        return $this->render('SosBundle:Informations:ville.html.twig', array('data' => $data)); 

      }

    }

   /**
     * @Route("/informations/formation")
     */
    public function formationAction(Request $request)
    {

      $data = array();
      $em = $this->getDoctrine()->getManager();

      // Validation ville
      if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "ville" ) {

        $geocoder = 'http://maps.googleapis.com/maps/api/geocode/json?address=%s&sensor=false';

        $query = sprintf($geocoder, urlencode($_POST['ville']));
        $result = json_decode(file_get_contents($query));
        if ($result == NULL || $result == "" ||  $result->status == "ZERO_RESULTS" || $result->status == "INVALID_REQUEST"  || $result->status == "REQUEST_DENIED" ){

             return $this->render('SosBundle:Informations:ville.html.twig', array('error' => 'ville'));

        }else{
            $json = $result->results[0];
            $data['ville']['libelle'] = $_POST['ville'];
            $data['ville']['latitude'] = $json->geometry->location->lat;
            $data['ville']['longitude'] = $json->geometry->location->lng;
        }

          $data['rayon_emploi'] = $request->get('rayon_emploi');
          $data['classification'] = $request->get('classification');
          $data['contrat'] = $request->get('contrat');
          
          // Si on est en CDD CDI
          if (null !== $request->get('contrat_duree')) {
              $data['contrat_duree'] = $request->get('contrat_duree');
          }

          // Si on est en CDD CDI
          if (null !== $request->get('formation_minimum')) {
              $data['formation_minimum'] = $request->get('formation_minimum');
          }
          
          if (null !== $request->get('cursus_scolaire')) {
              $data['cursus_scolaire'] = $request->get('cursus_scolaire');
          }

        
        $repo = $em->getRepository("SosBundle:Formation");
        $formations = $repo->findAll();                

        dump($data);
        return $this->render('SosBundle:Informations:formation.html.twig', array('formation_minimum' => $formations, 'data' => $data)); 

      }

    }

   /**
     * @Route("/informations/anglais")
     */
    public function anglaisAction(Request $request)
    {

      $data = array();
      $em = $this->getDoctrine()->getManager();

      // Validation ville
      if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "formation" ) {

          $data['ville'] = $request->get('ville');
          $data['rayon_emploi'] = $request->get('rayon_emploi');
          $data['classification'] = $request->get('classification');
          $data['contrat'] = $request->get('contrat');
          
          // Si on est en CDD CDI
          if (null !== $request->get('contrat_duree')) {
              $data['contrat_duree'] = $request->get('contrat_duree');
          }

          // Si on est en CDD CDI
          if (null !== $request->get('formation_minimum')) {
              $data['formation_minimum'] = $request->get('formation_minimum');
          }
          
          if (null !== $request->get('cursus_scolaire')) {
              $data['cursus_scolaire'] = $request->get('cursus_scolaire');
          } 

        $repo = $em->getRepository("SosBundle:Anglais");
        $anglais = $repo->findAll();                

        dump($data);
        return $this->render('SosBundle:Informations:anglais.html.twig', array('niveau_anglais' => $anglais, 'data' => $data)); 

      }

    }

   /**
     * @Route("/informations/secteur")
     */
    public function secteurAction(Request $request)
    {

      $data = array();
      $em = $this->getDoctrine()->getManager();

      // Validation ville
      if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "anglais" ) {

          $data['ville'] = $request->get('ville');
          $data['rayon_emploi'] = $request->get('rayon_emploi');
          $data['classification'] = $request->get('classification');
          $data['contrat'] = $request->get('contrat');
          $data['anglais'] = $request->get('anglais');
          
          // Si on est en CDD CDI
          if (null !== $request->get('contrat_duree')) {
              $data['contrat_duree'] = $request->get('contrat_duree');
          }

          // Si on est en CDD CDI
          if (null !== $request->get('formation_minimum')) {
              $data['formation_minimum'] = $request->get('formation_minimum');
          }
          
          if (null !== $request->get('cursus_scolaire')) {
              $data['cursus_scolaire'] = $request->get('cursus_scolaire');
          }

        $data['classification'] = $request->get('classification');  

        
        $repo = $em->getRepository("SosBundle:Secteur");
        $secteurs = $repo->findAll();                

        dump($data);
        return $this->render('SosBundle:Informations:secteur.html.twig', array('secteurs' => $secteurs, 'data' => $data)); 

      }

    }

    /**
     * @Route("/informations/service")
     */
    public function serviceAction(Request $request)
    {

        $data = array();
        $em = $this->getDoctrine()->getManager();

      // Validation secteur activité
      if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "secteur" ) {
        
          $data['ville'] = $request->get('ville');
          $data['rayon_emploi'] = $request->get('rayon_emploi');
          $data['classification'] = $request->get('classification');
          $data['secteur_activite'] = $request->get('secteur_activite');
          $data['contrat'] = $request->get('contrat');
          $data['anglais'] = $request->get('anglais');
          
          // Si on est en CDD CDI
          if (null !== $request->get('contrat_duree')) {
              $data['contrat_duree'] = $request->get('contrat_duree');
          }

          // Si on est en CDD CDI
          if (null !== $request->get('formation_minimum')) {
              $data['formation_minimum'] = $request->get('formation_minimum');
          }
          
          if (null !== $request->get('cursus_scolaire')) {
              $data['cursus_scolaire'] = $request->get('cursus_scolaire');
          }

          // Si on est dans la restauration : on ajoute le type de restauration
          if (null !== $request->get('service_activite')) {
              $data['service_activite'] = $request->get('service_activite');
          }

        dump($data);

        if ($data['secteur_activite'] == 1) {

          $qb = $em->createQueryBuilder();
          $postes = $qb->select('p')
                  ->from('SosBundle:PosteRecherche','p')
                  ->where('p.secteur = :secteur_activite')
                  ->setParameter('secteur_activite', $data['secteur_activite'])
                  ->getQuery()
                  ->getResult();

          return $this->render('SosBundle:Search:poste.html.twig', array('postes' => $postes, 'data' => $data, 'step' => '5'));     

        }else if($data['secteur_activite'] == 2){

              $repo = $em->getRepository("SosBundle:Service");
              $services = $repo->findAll();

          return $this->render('SosBundle:Informations:service.html.twig', array('services' => $services, 'data' => $data, 'step' => '4'));     

        }

      }else{
            return $this->redirectToRoute($request->get('form'));
        }
    }

    /**
     * @Route("/informations/poste")
     */
    public function posteAction(Request $request)
    {

        $data = array();
        $em = $this->getDoctrine()->getManager();

      // Validation service restauration
      if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "service" || $request->get('form') == "secteur" ) {

          $data['ville'] = $request->get('ville');
          $data['rayon_emploi'] = $request->get('rayon_emploi');
          $data['classification'] = $request->get('classification');
          $data['secteur_activite'] = $request->get('secteur_activite');
          $data['contrat'] = $request->get('contrat');
          $data['anglais'] = $request->get('anglais');
          
          // Si on est en CDD CDI
          if (null !== $request->get('contrat_duree')) {
              $data['contrat_duree'] = $request->get('contrat_duree');
          }

          // Si on est en CDD CDI
          if (null !== $request->get('formation_minimum')) {
              $data['formation_minimum'] = $request->get('formation_minimum');
          }
          
          if (null !== $request->get('cursus_scolaire')) {
              $data['cursus_scolaire'] = $request->get('cursus_scolaire');
          }

          // Si on est dans la restauration : on ajoute le type de restauration
          if (null !== $request->get('service_activite')) {
              $data['service_activite'] = $request->get('service_activite');
          }

            if (isset($data['service_activite'])) {
                $qb = $em->createQueryBuilder();
                $postes = $qb->select('p')
                  ->from('SosBundle:PosteRecherche','p')
                  ->where('p.secteur = :secteur_activite')
                  ->andWhere('p.service = :service_activite')
                  ->setParameters(array('secteur_activite' => $data['secteur_activite'], 'service_activite' => $data['service_activite']))
                    ->orderBy('p.id', 'DESC')
                    ->getQuery()
                  ->getResult();
            }else{
                $qb = $em->createQueryBuilder();
                $postes = $qb->select('p')
                  ->from('SosBundle:PosteRecherche','p')
                  ->where('p.secteur = :secteur_activite')
                  ->setParameter('secteur_activite', $data['secteur_activite'])
                    ->orderBy('p.id', 'DESC')
                  ->getQuery()
                  ->getResult();
            }

        dump($data);
      return $this->render('SosBundle:Informations:poste.html.twig', array('postes' => $postes, 'data' => $data, 'step' => '5'));     
      }

    }

    /**
     * @Route("/informations/experience")
     */
    public function experienceAction(Request $request)
    {    
        $data = array();
        $em = $this->getDoctrine()->getManager();

        // Validation formation minimum
        if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "poste" ) {

          $data['ville'] = $request->get('ville');
          $data['rayon_emploi'] = $request->get('rayon_emploi');
          $data['classification'] = $request->get('classification');
          $data['secteur_activite'] = $request->get('secteur_activite');
          $data['poste'] = $request->get('poste');
          $data['contrat'] = $request->get('contrat');
          $data['anglais'] = $request->get('anglais');
          
          // Si on est en CDD CDI
          if (null !== $request->get('contrat_duree')) {
              $data['contrat_duree'] = $request->get('contrat_duree');
          }

          // Si on est en CDD CDI
          if (null !== $request->get('formation_minimum')) {
              $data['formation_minimum'] = $request->get('formation_minimum');
          }
          
          if (null !== $request->get('cursus_scolaire')) {
              $data['cursus_scolaire'] = $request->get('cursus_scolaire');
          }

          // Si on est dans la restauration : on ajoute le type de restauration
          if (null !== $request->get('service_activite')) {
              $data['service_activite'] = $request->get('service_activite');
          }

            $repo = $em->getRepository("SosBundle:Experience");
            $experience_minimum = $repo->findAll();

            dump($data);
            return $this->render('SosBundle:Informations:experience_minimum.html.twig', array('experience_minimum' => $experience_minimum, 'data' => $data, 'step' => '9'));    
        }
    }

    /**
     * @Route("/informations/date")
     */
    public function dateAction(Request $request)
    {    
        $data = array();
        $em = $this->getDoctrine()->getManager();

        // Validation formation minimum
        if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "experience" ) {

          $data['ville'] = $request->get('ville');
          $data['rayon_emploi'] = $request->get('rayon_emploi');
          $data['classification'] = $request->get('classification');
          $data['secteur_activite'] = $request->get('secteur_activite');
          $data['poste'] = $request->get('poste');
          $data['contrat'] = $request->get('contrat');
          $data['experience_minimum'] = $request->get('experience_minimum');
          $data['anglais'] = $request->get('anglais');
          
          // Si on est en CDD CDI
          if (null !== $request->get('contrat_duree')) {
              $data['contrat_duree'] = $request->get('contrat_duree');
          }

          // Si on est en CDD CDI
          if (null !== $request->get('formation_minimum')) {
              $data['formation_minimum'] = $request->get('formation_minimum');
          }
          
          if (null !== $request->get('cursus_scolaire')) {
              $data['cursus_scolaire'] = $request->get('cursus_scolaire');
          }

          // Si on est dans la restauration : on ajoute le type de restauration
          if (null !== $request->get('service_activite')) {
              $data['service_activite'] = $request->get('service_activite');
          }

            dump($data);
            return $this->render('SosBundle:Informations:date.html.twig', array('data' => $data, 'step' => '9'));    
        }
    }

    /**
     * @Route("/informations/recapitulatif")
     */
    public function recapitulatifAction(Request $request)
    {    
        $data = array();
        $em = $this->getDoctrine()->getManager();

        // Validation formation minimum
        if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "date" ) {

          $data['ville'] = $request->get('ville');
          $data['rayon_emploi'] = $request->get('rayon_emploi');
          $data['classification'] = $request->get('classification');
          $data['secteur_activite'] = $request->get('secteur_activite');
          $data['poste'] = $request->get('poste');
          $data['contrat'] = $request->get('contrat');
          $data['experience_minimum'] = $request->get('experience_minimum');
          $data['anglais'] = $request->get('anglais');
          $data['date'] = $request->get('date');
          
          // Si on est en CDD CDI
          if (null !== $request->get('contrat_duree')) {
              $data['contrat_duree'] = $request->get('contrat_duree');
          }

          // Si on est en CDD CDI
          if (null !== $request->get('formation_minimum')) {
              $data['formation_minimum'] = $request->get('formation_minimum');
          }
          
          if (null !== $request->get('cursus_scolaire')) {
              $data['cursus_scolaire'] = $request->get('cursus_scolaire');
          }

          // Si on est dans la restauration : on ajoute le type de restauration
          if (null !== $request->get('service_activite')) {
              $data['service_activite'] = $request->get('service_activite');
          }

            $userCritere = new UserCritere();

            $poste = $em->getRepository('SosBundle:PosteRecherche')->find($data['poste']);
            $userCritere->setPoste($poste);

            $contrat = $em->getRepository('SosBundle:Contrat')->find($data['contrat']);
            $userCritere->setContrat($contrat);

            $typeContrat = $em->getRepository('SosBundle:TypeContrat')->find($data['contrat_duree']);
            $userCritere->setTypeContrat($typeContrat);

            if (isset($data['service_activite'])) {
              $service = $em->getRepository('SosBundle:Service')->find($data['service_activite']);
              $userCritere->setService($service);
            }

            $secteur = $em->getRepository('SosBundle:Secteur')->find($data['secteur_activite']);
            $userCritere->setSecteur($secteur);

            $etablissement = $em->getRepository('SosBundle:Etablissement')->find($data['classification']);
            $userCritere->setEtablissement($etablissement);

            $experience = $em->getRepository('SosBundle:Experience')->find($data['experience_minimum']);            
            $userCritere->setExperience($experience);

            $formation = $em->getRepository('SosBundle:CursusScolaire')->find($data['formation_minimum']);            
            $userCritere->setCursusScolaire($formation);

            $userCritere->setRayonEmploi($data['rayon_emploi']);

            $anglais = $em->getRepository('SosBundle:Anglais')->find($data['anglais']);            
            $userCritere->setNiveauAnglais($anglais);

            $userCritere->setDateDisponibilite("a:0{'27-12-2015'}");
            $userCritere->setLatitude($data['ville']['latitude']);
            $userCritere->setLongitude($data['ville']['longitude']);
            $userCritere->setVille($data['ville']['libelle']);

            $userCritere->setUser($this->getUser());

            $em->persist($userCritere);
            $em->flush();

            dump($data);
            return $this->redirectToRoute('profil', array('validation' => 'Tes critères on bien été enregistrés'));    
        }
    }

}
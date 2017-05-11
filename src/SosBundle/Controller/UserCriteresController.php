<?php

namespace SosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SosBundle\Entity\UserCritere;
use SosBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;


class UserCriteresController extends Controller
{
   /**
     * @Route("/usercritere")
     */
    public function indexAction(Request $request)
    { 

        return $this->render('SosBundle:UserCriteres:index.html.twig'); 

    }

   /**
     * @Route("/usercritere/step1")
     */
    public function step1Action(Request $request)
    { 

        $em = $this->getDoctrine()->getManager();

        $repoContrats = $em->getRepository("SosBundle:Contrat");
        $contrats = $repoContrats->findAll();

        $repoCursus = $em->getRepository("SosBundle:CursusScolaire");
        $cursusScolaire = $repoCursus->findAll();

        return $this->render('SosBundle:UserCriteres:step1.html.twig', array('contrats' => $contrats, 'cursusScolaire' => $cursusScolaire)); 

    }

    /**
     * @Route("/usercriteres/step2")
     */
    public function step2Action(Request $request)
    {
        $data = array();  
        $em = $this->getDoctrine()->getManager();

      // Validation contrat
      if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "step_1" )
      {
        
        $contrats = $request->get('data');

        foreach ($contrats as $key => $value) {

          if (isset($value['cursus']) && isset($value['duree']))
          {
            $data['contrats'][] = array(
              'contrat' => $key,
              'duree' => $value['duree'],
              'cursus' => $value['cursus']
            ); 
          }
          else if (!isset($value['duree']) && isset($value['cursus']))
          {
            $data['contrats'][] = array(
              'contrat' => $key,
              'cursus' => $value['cursus']
            ); 
          }
          elseif (isset($value['duree']) && !isset($value['cursus'])) {
            $data['contrats'][] = array(
              'contrat' => $key,
              'duree' => $value['duree']
            ); 
          }
          
        }

        // store into the session
        $session = $request->getSession();
        $session->set('contrats', $data['contrats']);

        $repoEtablissement = $em->getRepository('SosBundle:Etablissement');
        $etablissements = $repoEtablissement->findAll();
      
        dump($session->get('contrats'));

        return $this->render('SosBundle:UserCriteres:step2.html.twig', array('etablissements' => $etablissements)); 

      }
      else
      {
        return $this->redirectToRoute('usercriteres_'.$request->get('form'));
      }
    }

    /**
     * @Route("/usercriteres/step3")
     */
    public function step3Action(Request $request)
    {

      $em = $this->getDoctrine()->getManager();

      // Validation contrat
      if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "step_2" )
      {
        
        // store into the session
        $session = $request->getSession();
        $session->set('etablissements', $request->get('etablissements'));

        dump($session->get('contrats'));
        dump($session->get('etablissements'));

        return $this->render('SosBundle:UserCriteres:step3.html.twig'); 

      }
      else
      {
        return $this->redirectToRoute('usercriteres_'.$request->get('form'));
      }
    }

    /**
     * @Route("/usercriteres/step4")
     */
    public function step4Action(Request $request)
    {

        $data = array();
        $em = $this->getDoctrine()->getManager();

      // Validation contrat
      if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "step_3" )
      {
        
        // store into the session
        $session = $request->getSession();
        $session->set('ville', $request->get('ville'));
        $session->set('rayon_emploi', $request->get('rayon_emploi'));

        $repoFormations = $em->getRepository('SosBundle:Formation');
        $formations = $repoFormations->findAll();

        $repoAnglais = $em->getRepository('SosBundle:Anglais');
        $niveauAnglais = $repoAnglais->findAll();

        dump($session->get('contrats'));
        dump($session->get('etablissements'));
        dump($session->get('ville'));
        dump($session->get('rayon_emploi'));


        return $this->render('SosBundle:UserCriteres:step4.html.twig', array('formations' => $formations, 'niveauAnglais' => $niveauAnglais)); 

      }
      else
      {
        return $this->redirectToRoute('usercriteres_'.$request->get('form'));
      }
    }

    /**
     * @Route("/usercriteres/step5")
     */
    public function step5Action(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

      // Validation contrat
      if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "step_4" )
      {
        
        // store into the session
        $session = $request->getSession();
        $session->set('formation', $request->get('formation'));
        $session->set('anglais', $request->get('anglais'));

        $qb = $em->createQueryBuilder();
        $postesHotellerie = $qb->select('ph')
                ->from('SosBundle:PosteRecherche','ph')
                ->where('ph.secteur = :secteur_activite')
                ->setParameter('secteur_activite', 1)
                ->getQuery()
                ->getResult();

        dump($session->get('contrats'));
        dump($session->get('etablissements'));
        dump($session->get('ville'));
        dump($session->get('rayon_emploi'));
        dump($session->get('formation'));
        dump($session->get('anglais'));

        $repoExperiences = $em->getRepository('SosBundle:Experience');
        $experiences = $repoExperiences->findAll();

        return $this->render('SosBundle:UserCriteres:step5.html.twig', array('postesHotellerie' => $postesHotellerie, 'experiences' => $experiences)); 

      }
      else
      {
        return $this->redirectToRoute('usercriteres_'.$request->get('form'));
      }
    }

    /**
     * @Route("/usercriteres/step6")
     */
    public function step6Action(Request $request)
    {

        $data = array();
        $em = $this->getDoctrine()->getManager();

      // Validation contrat
      if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "step_5" )
      {


        $postesHotellerie = $request->get('data');

        foreach ($postesHotellerie as $key => $value) {

          if (isset($value['poste']) && !empty($value['poste']) && isset($value['experience']) && !empty($value['experience']))
          {
            $data['postes'][] = array(
              'poste' => $key,
              'experience' => $value['experience']
            ); 
          }
          
        }

        $session = $request->getSession();
        if (isset($data['postes'])) 
        {
          $session->set('postes', $data['postes']);
        }

        $qb = $em->createQueryBuilder();
        $postesRestauration = $qb->select('p')
                ->from('SosBundle:PosteRecherche','p')
                ->where('p.secteur = :secteur_activite')
                ->andWhere('p.service = :service_activite')
                ->setParameters(array('secteur_activite' => 2, 'service_activite' => 1))
                ->getQuery()
                ->getResult();

        dump($session->get('contrats'));
        dump($session->get('etablissements'));
        dump($session->get('ville'));
        dump($session->get('rayon_emploi'));
        dump($session->get('formation'));
        dump($session->get('anglais'));
        dump($session->get('postes'));

        $repoExperiences = $em->getRepository('SosBundle:Experience');
        $experiences = $repoExperiences->findAll();

        return $this->render('SosBundle:UserCriteres:step6.html.twig', array('postesRestauration' => $postesRestauration, 'experiences' => $experiences)); 

      }
      else
      {
        return $this->redirectToRoute('usercriteres_'.$request->get('form'));
      }
    }

    /**
     * @Route("/usercriteres/step7")
     */
    public function step7Action(Request $request)
    {

        $data = array();
        $em = $this->getDoctrine()->getManager();

      // Validation contrat
      if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "step_6" )
      {


        $postesRestauration1 = $request->get('data');

        foreach ($postesRestauration1 as $key => $value) {

          if (isset($value['poste']) && !empty($value['poste']) && isset($value['experience']) && !empty($value['experience']))
          {
            $data['postes'][] = array(
              'poste' => $key,
              'experience' => $value['experience']
            ); 
          }
          
        }

        $session = $request->getSession();
        if (isset($data['postes'])) 
        {
          foreach($session->get('postes') as $value){
            array_push($data['postes'], $value);
          }
          $session->set('postes', $data['postes']);
        }

        $qb = $em->createQueryBuilder();
        $postesRestauration = $qb->select('p')
                ->from('SosBundle:PosteRecherche','p')
                ->where('p.secteur = :secteur_activite')
                ->andWhere('p.service = :service_activite')
                ->setParameters(array('secteur_activite' => 2, 'service_activite' => 2))
                ->getQuery()
                ->getResult();

        dump($session->get('contrats'));
        dump($session->get('etablissements'));
        dump($session->get('ville'));
        dump($session->get('rayon_emploi'));
        dump($session->get('formation'));
        dump($session->get('anglais'));
        dump($session->get('postes'));

        $repoExperiences = $em->getRepository('SosBundle:Experience');
        $experiences = $repoExperiences->findAll();

        return $this->render('SosBundle:UserCriteres:step7.html.twig', array('postesRestauration' => $postesRestauration, 'experiences' => $experiences)); 

      }
      else
      {
        return $this->redirectToRoute('usercriteres_'.$request->get('form'));
      }
    }


    /**
     * @Route("/usercriteres/step8")
     */
    public function step8Action(Request $request)
    {

        $data = array();
        $em = $this->getDoctrine()->getManager();

      // Validation contrat
      if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "step_7" )
      {

        $postesRestauration2 = $request->get('data');
       
        foreach ($postesRestauration2 as $key => $value) {

          if (isset($value['poste']) && !empty($value['poste']) && isset($value['experience']) && !empty($value['experience']))
          {
            $data['postes'][] = array(
              'poste' => $key,
              'experience' => $value['experience']
            ); 
          }

        }

        $session = $request->getSession();
        if (isset($data['postes'])) 
        {
          foreach($session->get('postes') as $value){
            array_push($data['postes'], $value);
          }
          $session->set('postes', $data['postes']);
        }
      
        dump($session->get('contrats'));
        dump($session->get('etablissements'));
        dump($session->get('ville'));
        dump($session->get('rayon_emploi'));
        dump($session->get('formation'));
        dump($session->get('anglais'));
        dump($session->get('postes'));

        return $this->render('SosBundle:UserCriteres:step8.html.twig'); 

      }
      else
      {
        return $this->redirectToRoute('usercriteres_'.$request->get('form'));
      }
    }

    /**
     * @Route("/usercriteres/step9")
     */
    public function step9Action(Request $request)
    {

        $data = array();
        $em = $this->getDoctrine()->getManager();

      // Validation contrat
      if ($request->isMethod('POST') && null !== $request->get('form') && $request->get('form') == "step_8" )
      {

        $session = $request->getSession();
        $session->set('disponibilite', $request->get('disponibilite'));

        $resultat = array(
          'contrats' => $session->get('contrats'),
          'etablissements' => $session->get('etablissements'),
          'ville' => $session->get('ville'),
          'rayon_emploi' => $session->get('rayon_emploi'),
          'formation' => $session->get('formation'),
          'anglais' => $session->get('anglais'),
          'postes' => $session->get('postes'),
          'disponibilite' => $session->get('disponibilite'),
          );

        dump($resultat);

        return $this->render('SosBundle:UserCriteres:step9.html.twig'); 

      }
      else
      {
        return $this->redirectToRoute('usercriteres_'.$request->get('form'));
      }
    }

}
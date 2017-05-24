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
            $civ = $_POST['civilite'];
            $civilite = $em->getRepository('SosBundle:Civilite')->findOneBy(array('id' => $civ));
            if(isset($_POST['valider'])){

            $validation="Demande de recommandation envoyée !";
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
            $message = \Swift_Message::newInstance()
                    ->setSubject('Demande de recommandation')
                    ->setFrom('soshcr@contact.fr')
                    ->setTo($_POST['email'])
                    ->setBody(
                        $this->renderView(
                            'SosBundle:Admin:mailrecommandations.html.twig'
                        ),
                        'text/html'
                    );
                $this->get('mailer')->send($message);
            }

            return $this->render('SosBundle:Dashboard:dashboard.html.twig', array("validation"=>$validation, "user" => $user));


        }

        return $this->render('SosBundle:Dashboard:demandeRecommandation.html.twig');
    }
    /**
     * @Route("admin/recommandations")
     */
    public function recommandationsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $users = $em->getRepository('SosBundle:User')->findAll();
        $recommandations = $em->getRepository('SosBundle:Recommandation')->findBy(array('valide' => 0));
        if (isset($_POST['valider'])){
            foreach($recommandations as $recommandation){
                $recommandation->setValide(1);
                $em->flush($recommandation);
                return $this->redirectToRoute('recommandations');
            }
        }
        if (isset($_POST['supprimerreco'])) 
        {
            if (is_array($_POST['id_recommandation'])) 
            {
                foreach($_POST['id_recommandation'] as $value)
                {
                    $reco = $em->getRepository('SosBundle:Recommandation')->findOneBy(array('id' => $value));
                    $em->remove($reco);
                    $em->flush();   
                }
                return $this->redirectToRoute('recommandations');
            }
        }
        return $this->render('SosBundle:Admin:recommandations.html.twig', array("recommandations" => $recommandations, "users" => $users));
    }
    /**
     * @Route("admin/utilisateurs")
     */
    public function utilisateursAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $utilisateurs = $em->getRepository('SosBundle:User')->findAll();
        if(isset($_POST['rechercher']) || isset($_POST['rechercherAll']))
        {
            if(isset($_POST['rechercherAll']))
            {
                return $this->render('SosBundle:Admin:utilisateurs.html.twig', array("utilisateurs" => $utilisateurs));
            }
            if(isset($_POST['rechercher']) && ((!empty($_POST['nom'])) || !empty($_POST['prenom']) || !empty($_POST['telephone'])))
            {   
                $requete = "SELECT * FROM utilisateur ";
                $bool = 0;
                if( ((!empty($_POST['nom'])) || !empty($_POST['prenom']) || !empty($_POST['telephone'])) )
                {
                    $requete .= " where ";
                }
                if((!empty($_POST['nom'])))
                {
                    $requete .= " nom='".$_POST['nom']."'";
                    $bool = 1;
                }
                if((!empty($_POST['prenom'])))
                {
                    if( $bool == 1 )
                    {
                        $requete .= " or ";
                    }
                    $requete .= " prenom='".$_POST['prenom']."'";
                    $bool = 1;
                }
                if((!empty($_POST['telephone'])))
                {
                    if( $bool == 1 )
                    {
                        $requete .= " or ";
                    }
                    $requete .= " telephone='".$_POST['telephone']."'";
                    $bool = 1;
                }
                
                    $connection = $em->getConnection();
                    $statement = $connection->prepare($requete);
                    $statement->execute();
                    $result1 = $statement->fetchAll();
                    foreach ($result1 as $res){
                        $datenaissance = $res['date_naissance'];
                        $date = new \DateTime($datenaissance);
                        $today = new \DateTime('NOW');
                        $dateInterval = $date->diff($today);
                        $age = $dateInterval->y;

                        $nbRecommandation = 0;
                    }
                    return $this->render('SosBundle:Admin:utilisateurs.html.twig', array("result1" => $result1, "age" => $age, "nbRecommandation" => $nbRecommandation));

            }
        }
        if (isset($_POST['supprimer'])) 
        {
            $value = $_POST['id_utilisateur'];
            $user = $em->getRepository('SosBundle:User')->findOneBy(array('id' => $value));
            
            $em->remove($user);
            $em->flush();
            return $this->redirectToRoute('utilisateurs'); 
        }
        foreach ($utilisateurs as $utilisateur){
            $u = $utilisateur->getId();
            $recommandation = $em->getRepository("SosBundle:Recommandation")->findby(array('user' => $utilisateur,'valide'=> 1));
            $nbRecommandation = count($recommandation);
            
            //$recommandations = $em->getRepository('SosBundle:Recommandation')->findBy(array('user' => $u);
        }
    return $this->render('SosBundle:Admin:utilisateurs.html.twig', array("utilisateurs" => $utilisateurs, "nbRecommandation" => $nbRecommandation));

    }

    /**
     * @Route("admin/validation")
     */
    public function validationAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('SosBundle:User')->findBy(array('enabled' => 1));

        if (isset($_POST['valider'])){
            if (is_array($_POST['id_utilisateur'])) 
            {
                foreach($_POST['id_utilisateur'] as $value)
                {
                    $user = $em->getRepository('SosBundle:User')->findOneBy(array('id' => $value));
                    $em->setEnabled(1);
                    $em->flush();
                    return $this->redirectToRoute('validation');
                }
            }
        }
        return $this->render('SosBundle:Admin:validation.html.twig', array("users" => $users));
    }

    /**
     * @Route("admin/statistiques")
     */
    public function statistiquesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare("SELECT COUNT(*) as nb FROM utilisateur where date_abonnement is not null");
        $statement->execute();
        $results = $statement->fetchAll();
        $statement = $connection->prepare("SELECT COUNT(*) as nb FROM paiement WHERE YEAR(date) = YEAR(CURDATE()) AND MONTH(date) = MONTH(CURDATE())");
        $statement->execute();
        $result1 = $statement->fetchAll();
        $statement = $connection->prepare("SELECT COUNT(*) as nb FROM paiement WHERE YEAR(date) = YEAR(CURDATE()) AND MONTH(date) = MONTH(CURDATE()- INTERVAL 1 MONTH)");
        $statement->execute();
        $result2 = $statement->fetchAll();
        $statement = $connection->prepare("SELECT COUNT(*) as nb FROM utilisateur");
        $statement->execute();
        $result3 = $statement->fetchAll();
       
        return $this->render('SosBundle:Admin:statistiques.html.twig', array('results' => $results, 'result1' => $result1, 'result2' => $result2, 'result3' => $result3));
    }
    /**
     * @Route("admin/signalements")
     */
    public function signalementsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare("SELECT * FROM signalement");
        $statement->execute();
        $results = $statement->fetchAll();
        $statement = $connection->prepare("SELECT COUNT(*) as nb FROM signalement");
        $statement->execute();
        $results2 = $statement->fetchAll();
        if (isset($_POST['supprimer'])) 
        {
            $value = $_POST['id_signalement'];
            $signalement = $em->getRepository('SosBundle:Signalement')->findOneBy(array('id' => $value));
            $em->remove($signalement);
            $em->flush();
            return $this->redirectToRoute('signalements'); 
        }
        return $this->render('SosBundle:Admin:signalements.html.twig', array('results' => $results, 'results2' => $results2));
    }

    /**
     * @Route("admin/recommandationsutilisateurs")
     */
    public function recommandationsByUserAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('SosBundle:User')->findAll(); 
        $recommandations = $em->getRepository('SosBundle:Recommandation')->findBy(array('user' => $users), array('user' => 'ASC'));

        if (isset($_POST['supprimer'])){
            if (is_array($_POST['id_recommandation'])) 
            {
                foreach($_POST['id_recommandation'] as $value)
                {
                    $recommandation = $em->getRepository('SosBundle:Recommandation')->findOneBy(array('id' => $value));
                    $em->remove($recommandation);
                    $em->flush();
                    return $this->redirectToRoute('recommandationsutilisateurs');
                }
            }
        }
        return $this->render('SosBundle:Admin:recommandationsutilisateurs.html.twig', array("recommandations" => $recommandations));
 
    }

}
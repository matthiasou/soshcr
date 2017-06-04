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
        $em = $this->getDoctrine()->getManager();
        $today = new \DateTime('NOW');
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $recommandations = $em->getRepository('SosBundle:Recommandation')->findBy(array('user' => $user, 'date' => $today));
        if (isset($_POST['nom_etablissement'])){
            $civ = $_POST['civilite'];
            $civilite = $em->getRepository('SosBundle:Civilite')->findOneBy(array('id' => $civ));
            if(isset($_POST['valider']) && $recommandations == null){
                $validation="Demande de recommandation envoyée !";
                $newRecommandation = new  Recommandation();
                $newRecommandation->setNomEtablissement($_POST['nom_etablissement']);
                $newRecommandation->setEmail($_POST['email']);
                $newRecommandation->setVille($_POST['ville']);
                $newRecommandation->setNomResponsable($_POST['nom_responsable']);
                $newRecommandation->setValide(0);
                $newRecommandation->setCivilite($civilite);
                $newRecommandation->setUser($user);
                $newRecommandation->setDate($today);
                $characts    = 'abcdefghijklmnopqrstuvwxyz';
                $characts   .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $characts   .= '1234567890';
                $code_aleatoire      = '';
                for($i=0;$i < 5;$i++)
                {
                    $code_aleatoire .= substr($characts,rand()%(strlen($characts)),1);
                } 
                $newRecommandation->setCode($code_aleatoire);

                $em->persist($newRecommandation);
                $em->flush();

                $message = \Swift_Message::newInstance()
                    ->setSubject('Demande de recommandation envoyée')
                    ->setFrom('soshcr@contact.fr')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'SosBundle:Admin:maildemanderecommandations.html.twig',
                                array(
                                    'user' => $user)
                        ),
                        'text/html'
                    );
                $this->get('mailer')->send($message);
                return $this->render('SosBundle:Dashboard:dashboard.html.twig', array("validation"=>$validation, "user" => $user));
            }
            elseif(isset($_POST['valider']) && $recommandations != null){ 
                return $this->render('SosBundle:Dashboard:demandeRecommandation.html.twig', array("error" => 'recommandation'));
            }
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
        if (isset($_POST['valider']))
        {
            if (isset($_POST['id_recommandation']) && is_array($_POST['id_recommandation'])) 
            {
                foreach($_POST['id_recommandation'] as $reco)
                {
                    $recommandation = $em->getRepository('SosBundle:Recommandation')->findOneBy(array('id' => $reco));
                    $utilisateur = $recommandation->getUser();
                    $recommandation->setValide(1);
                    $code = $recommandation->getCode();
                    $em->persist($recommandation);
                    $em->flush();
                    $message = \Swift_Message::newInstance()
                        ->setSubject('Demande de recommandation')
                        ->setFrom('soshcr@contact.fr')
                        ->setTo($recommandation->getEmail())
                        ->setBody(
                            $this->renderView(
                                'SosBundle:Admin:mailrecommandations.html.twig',
                                    array(
                                        'utilisateur' => $utilisateur,
                                        'code' => $code)
                            ),
                            'text/html'
                        );
                    $this->get('mailer')->send($message);
                }
            }
            return $this->redirectToRoute('recommandations');
        }
            
        
        if (isset($_POST['supprimerreco'])) 
        {
            if (isset($_POST['id_recommandation']) && is_array($_POST['id_recommandation'])) 
            {
                foreach($_POST['id_recommandation'] as $value)
                {
                    $reco = $em->getRepository('SosBundle:Recommandation')->findOneBy(array('id' => $value));
                    $message = \Swift_Message::newInstance()
                    ->setSubject('Suppression de votre recommandation')
                    ->setFrom('soshcr@contact.fr')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'SosBundle:Admin:suppression_recommandation.html.twig'
                        ),
                        'text/html'
                    );
                    $this->get('mailer')->send($message);
                    $em->remove($reco);  
                }
                $em->flush();
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
        $reco = $em->getRepository('SosBundle:Recommandation')->findBy(array('user' => $utilisateurs));
        if(isset($_POST['rechercher']) || isset($_POST['rechercherAll']))
        {
            if(isset($_POST['rechercherAll']))
            {   
                foreach ($utilisateurs as $u){
                    $user = $em->getRepository('SosBundle:User')->findOneBy(array('id' => $u));
                    $id = $user->getId();
                    $nbRecommandation = count($user->getRecommandations());
                    $items[$id]=$nbRecommandation;
                }
                return $this->render('SosBundle:Admin:utilisateurs.html.twig', array("utilisateurs" => $utilisateurs, 'items' => $items));
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
                        $u = $em->getRepository('SosBundle:User')->findOneBy(array('id' => $res));
                        $datenaissance = $res['date_naissance'];
                        $date = new \DateTime($datenaissance);
                        $today = new \DateTime('NOW');
                        $dateInterval = $date->diff($today);
                        $age = $dateInterval->y;
                        $nbRecommandation = count($u->getRecommandations());
                    }

                    return $this->render('SosBundle:Admin:utilisateurs.html.twig', array("result1" => $result1, "age" => $age, 'nbRecommandation' => $nbRecommandation));

            }
        }
        if (isset($_POST['supprimer'])) 
        {
            if (is_array($_POST['id_utilisateur'])) 
            {
                foreach($_POST['id_utilisateur'] as $value)
                {   
                    $user = $em->getRepository('SosBundle:User')->findOneBy(array('id' => $value));
                    $message = \Swift_Message::newInstance()
                    ->setSubject('Suppression de votre compte')
                    ->setFrom('soshcr@contact.fr')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'SosBundle:Admin:suppression_compte.html.twig'
                        ),
                        'text/html'
                    );
                    $this->get('mailer')->send($message);
                    $em->remove($user);
                    $em->flush();
                    
                }
            }
            return $this->redirectToRoute('utilisateurs'); 
        }
        $items = array();
        $count = 1;
        foreach ($utilisateurs as $u){
            $user = $em->getRepository('SosBundle:User')->findOneBy(array('id' => $u));
            $id = $user->getId();
            $nbRecommandation = count($user->getRecommandations());
            $items[$id]=$nbRecommandation;
        }

    return $this->render('SosBundle:Admin:utilisateurs.html.twig', array("utilisateurs" => $utilisateurs, 'items' => $items));

    }

    /**
     * @Route("admin/validation")
     */
    public function validationAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('SosBundle:User')->findBy(array('enabled' => 1));
        if (isset($_POST['valider'])){
                foreach($users as $value)
                {
                    $user = $em->getRepository('SosBundle:User')->findOneBy(array('id' => $value));
                    $user->setEnabled(1);
                    $em->flush();
                    return $this->redirectToRoute('validation');
                }
        }
        if (isset($_POST['supprimerprofil'])){
            if (is_array($_POST['id_utilisateur'])) 
            {
                foreach($_POST['id_utilisateur'] as $value)
                {
                    $user = $em->getRepository('SosBundle:User')->findOneBy(array('id' => $value));
                    $em->remove($user);
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
        $statement = $connection->prepare("SELECT COUNT(*) as nb FROM utilisateur ");
        $statement->execute();
        $result3 = $statement->fetchAll();
        $statement = $connection->prepare("SELECT COUNT(*) as nb FROM utilisateur");
        $statement->execute();
        $result12 = $statement->fetchAll();
        $statement = $connection->prepare("SELECT COUNT(*) as nb FROM utilisateur");
        $statement->execute();
        $result13 = $statement->fetchAll();
        $statement = $connection->prepare("SELECT COUNT(*) as nb FROM suppression_compte, raison_suppression WHERE suppression_compte.raison_suppression_id = raison_suppression.id AND raison_suppression.id = 1 AND YEAR(date) = YEAR(CURDATE()) AND MONTH(date) = MONTH(CURDATE()) ");
        $statement->execute();
        $result4 = $statement->fetchAll();
        $statement = $connection->prepare("SELECT COUNT(*) as nb FROM suppression_compte, raison_suppression WHERE suppression_compte.raison_suppression_id = raison_suppression.id AND raison_suppression.id = 1");
        $statement->execute();
        $result14 = $statement->fetchAll();
        $statement = $connection->prepare("SELECT COUNT(*) as nb FROM suppression_compte, raison_suppression WHERE suppression_compte.raison_suppression_id = raison_suppression.id AND raison_suppression.id = 1 AND YEAR(date) = YEAR(CURDATE()) AND MONTH(date) = MONTH(CURDATE()- INTERVAL 1 MONTH) ");
        $statement->execute();
        $result5 = $statement->fetchAll();
        $statement = $connection->prepare("SELECT COUNT(*) as nb FROM suppression_compte, raison_suppression WHERE suppression_compte.raison_suppression_id = raison_suppression.id AND raison_suppression.id = 2 AND YEAR(date) = YEAR(CURDATE()) AND MONTH(date) = MONTH(CURDATE()) ");
        $statement->execute();
        $result6 = $statement->fetchAll();
        $statement = $connection->prepare("SELECT COUNT(*) as nb FROM suppression_compte, raison_suppression WHERE suppression_compte.raison_suppression_id = raison_suppression.id AND raison_suppression.id = 2");
        $statement->execute();
        $result15 = $statement->fetchAll();
        $statement = $connection->prepare("SELECT COUNT(*) as nb FROM suppression_compte, raison_suppression WHERE suppression_compte.raison_suppression_id = raison_suppression.id AND raison_suppression.id = 2 AND YEAR(date) = YEAR(CURDATE()) AND MONTH(date) = MONTH(CURDATE()- INTERVAL 1 MONTH) ");
        $statement->execute();
        $result7 = $statement->fetchAll();
        $statement = $connection->prepare("SELECT COUNT(*) as nb FROM suppression_compte, raison_suppression WHERE suppression_compte.raison_suppression_id = raison_suppression.id AND raison_suppression.id = 3 AND YEAR(date) = YEAR(CURDATE()) AND MONTH(date) = MONTH(CURDATE()) ");
        $statement->execute();
        $result8 = $statement->fetchAll();
        $statement = $connection->prepare("SELECT COUNT(*) as nb FROM suppression_compte, raison_suppression WHERE suppression_compte.raison_suppression_id = raison_suppression.id AND raison_suppression.id = 3");
        $statement->execute();
        $result16 = $statement->fetchAll();
        $statement = $connection->prepare("SELECT COUNT(*) as nb FROM suppression_compte, raison_suppression WHERE suppression_compte.raison_suppression_id = raison_suppression.id AND raison_suppression.id = 3 AND YEAR(date) = YEAR(CURDATE()) AND MONTH(date) = MONTH(CURDATE()- INTERVAL 1 MONTH) ");
        $statement->execute();
        $result9 = $statement->fetchAll();
        $statement = $connection->prepare("SELECT COUNT(*) as nb FROM suppression_compte, raison_suppression WHERE suppression_compte.raison_suppression_id = raison_suppression.id AND raison_suppression.id = 4 AND YEAR(date) = YEAR(CURDATE()) AND MONTH(date) = MONTH(CURDATE()) ");
        $statement->execute();
        $result10 = $statement->fetchAll();
        $statement = $connection->prepare("SELECT COUNT(*) as nb FROM suppression_compte, raison_suppression WHERE suppression_compte.raison_suppression_id = raison_suppression.id AND raison_suppression.id = 4");
        $statement->execute();
        $result17 = $statement->fetchAll();
        $statement = $connection->prepare("SELECT COUNT(*) as nb FROM suppression_compte, raison_suppression WHERE suppression_compte.raison_suppression_id = raison_suppression.id AND raison_suppression.id = 4 AND YEAR(date) = YEAR(CURDATE()) AND MONTH(date) = MONTH(CURDATE()- INTERVAL 1 MONTH) ");
        $statement->execute();
        $result11 = $statement->fetchAll();
        $statement = $connection->prepare("SELECT COUNT(*) as nb FROM utilisateur WHERE YEAR(date_inscription) = YEAR(CURDATE()) AND MONTH(date_inscription) = MONTH(CURDATE()- INTERVAL 1 MONTH) ");
        $statement->execute();
        $result18 = $statement->fetchAll();
        $statement = $connection->prepare("SELECT COUNT(*) as nb FROM utilisateur WHERE YEAR(date_inscription) = YEAR(CURDATE()) AND MONTH(date_inscription) = MONTH(CURDATE()) ");
        $statement->execute();
        $result19 = $statement->fetchAll();
       
        return $this->render('SosBundle:Admin:statistiques.html.twig', array('results' => $results, 'result1' => $result1, 'result2' => $result2, 'result3' => $result3, 'result4' => $result4, 'result5' => $result5, 'result6' => $result6, 'result7' => $result7, 'result8' => $result8, 'result9' => $result9, 'result10' => $result10, 'result11' => $result11, 'result12' => $result12, 'result13' => $result13, 'result14' => $result14, 'result15' => $result15, 'result16' => $result16, 'result17' => $result17, 'result18' => $result18, 'result19' => $result19));
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
    /**
     * @Route("medaille/{id}")
     */
    public function medailleAction($id)
    {
        if ($id == 1){
        return $this->render('SosBundle:Admin:medaille1.html.twig');
        }
        if ($id == 3){
        return $this->render('SosBundle:Admin:medaille3.html.twig');
        }
        if ($id == 10){
        return $this->render('SosBundle:Admin:medaille10.html.twig');
        }
        if ($id == 20){
        return $this->render('SosBundle:Admin:medaille20.html.twig');
        }
    }

}
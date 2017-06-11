<?php

namespace SosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SosBundle\Entity\UserCritere;
use SosBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use SosBundle\Entity\Signalement;
use Symfony\Component\Form\Extension\Core\Type\FormType;


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
     * @Route("/profil")
     */
    public function profilAction(Request $request)
    {

        if ($request->get('telephone') && $request->get('email'))
        {
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $user->setTelephone($request->get('telephone'));
            $user->setEmail($request->get('email'));
            $em->persist($user);
            $em->flush();
        }

        return $this->render('SosBundle:Default:profil.html.twig');
    }


    /**
     * @Route("/dashboard")
     */
    public function dashboardAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        return $this->render('SosBundle:Dashboard:dashboard.html.twig', array("user" => $user));
    }

    /**
     * @Route("/abonnement")
     */
    public function abonnementAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if(isset($_POST['4'])){
            $amount = 0.01;
            return $this->redirectToRoute('payment', array('amount' => $amount, 'user' => $user));

        }
        if(isset($_POST['7'])){
            $amount = 7;            
            return $this->redirectToRoute('payment', array('amount' => $amount, 'user' => $user));
        }
        return $this->render('SosBundle:Dashboard:abonnement.html.twig', array("user" => $user));
    }

    /**
     * @Route("/dashboard_admin")
     */
    public function dashboardAdminAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        return $this->render('SosBundle:Dashboard:dashboard_admin.html.twig', array("user" => $user));
    }

    /**
     * @Route("/cgv")
     */
    public function cgvAction()
    {
        return $this->render('SosBundle:Default:cgv.html.twig');
    }

    /**
     * @Route("/contact")
     */
    public function contactAction()
    {
        if(isset($_POST['envoyer'])){
            if (($_POST['C_FirstName'] != "") && ($_POST['C_LastName'] != "") && ($_POST['C_EmailAddress'] != "") && ($_POST['C_Company'] != "")){
                $validation = "Message envoyé avec succès";
                $nom = $_POST['C_FirstName'];
                $prenom = $_POST['C_LastName'];
                $email = $_POST['C_EmailAddress'];
                $telephone = $_POST['C_Company'];
                $message = \Swift_Message::newInstance()
                        ->setSubject('Demande de contact SOSHCR')
                        ->setFrom($_POST['C_EmailAddress'])
                        ->setTo('contact@soshcr.fr')
                        ->setBody(
                            $this->renderView(
                                'SosBundle:Default:demande_contact.html.twig',
                                array('message' => $_POST['Comments'],
                                    'nom' => $nom,
                                    'prenom' => $prenom,
                                    'email' => $email,
                                    'telephone' => $telephone)
                            ),
                            'text/html'
                        );
                    $this->get('mailer')->send($message);
                    return $this->render('SosBundle:Default:contact.html.twig', array("validation" => $validation));
            }
            else {
                return $this->render('SosBundle:Default:contact.html.twig', array("error" => 'contact'));

            }
        }
            
        return $this->render('SosBundle:Default:contact.html.twig');
        
    }
    /**
     * @Route("/signalement_profil")
     */
    public function signalementProfilAction()
    {
        $em = $this->getDoctrine()->getManager();
        if(isset($_POST['envoyer'])){
            if (($_POST['Raison'] != "") && ($_POST['Comments'] != "") && ($_POST['C_FirstName2'] != "") && ($_POST['C_LastName2'] != "")&& ($_POST['C_EmailAddress2'] != "") && ($_POST['C_Company2'] != "")){
                $validation = "Votre signalement a été envoyé avec succès";
                $nom = $_POST['C_FirstName'];
                $prenom = $_POST['C_LastName'];
                $email = $_POST['C_EmailAddress'];
                $telephone = $_POST['C_Company'];
                $raison = $_POST['Raison'];
                $contenu = $_POST['Comments'];
                $nomsignaleur = $_POST['C_FirstName2'];
                $prenomsignaleur = $_POST['C_LastName2'];
                $emailsignaleur = $_POST['C_EmailAddress2'];
                $telephonesignaleur = $_POST['C_Company2'];

                $signalement = new Signalement();
                $signalement->setNom($nom);
                $signalement->setPrenom($prenom);
                $signalement->setEmail($email);
                $signalement->setTelephone($telephone);
                $signalement->setRaison($raison);
                $signalement->setProposition($contenu);
                $signalement->setNomsignaleur($nomsignaleur);
                $signalement->setPrenomsignaleur($prenomsignaleur);
                $signalement->setEmailsignaleur($emailsignaleur);
                $signalement->setTelephonesignaleur($telephonesignaleur);
                $em->persist($signalement);
                $em->flush();
                $message = \Swift_Message::newInstance()
                        ->setSubject('Signalement d un profil')
                        ->setFrom('contact@soshcr.fr')
                        ->setTo('contact@soshcr.fr')
                        ->setBody(
                            $this->renderView(
                                'SosBundle:Default:signalement_profil_mail.html.twig',
                                array('message' => $_POST['Raison'],
                                    'nom' => $nom,
                                    'prenom' => $prenom,
                                    'email' => $email,
                                    'telephone' => $telephone,
                                    'nom2' => $nomsignaleur,
                                    'prenom2' => $prenomsignaleur,
                                    'email2' => $emailsignaleur,
                                    'telephone2' => $telephonesignaleur,
                                    'proposition' => $contenu
                            )),
                            'text/html'
                        );
                    $this->get('mailer')->send($message);

                $message2 = \Swift_Message::newInstance()
                        ->setSubject('Votre signalement a été pris en compte')
                        ->setFrom('contact@soshcr.fr')
                        ->setTo($_POST['C_EmailAddress2'])
                        ->setBody(
                            $this->renderView(
                                'SosBundle:Default:signalement_profil_mail2.html.twig',
                                array('message' => $_POST['Raison'],
                                    'nom' => $nom,
                                    'prenom' => $prenom,
                                    'email' => $email,
                                    'telephone' => $telephone,
                                    'nom2' => $nomsignaleur,
                                    'prenom2' => $prenomsignaleur,
                                    'email2' => $emailsignaleur,
                                    'telephone2' => $telephonesignaleur,
                                    'proposition' => $contenu
                            )),
                            'text/html'
                        );
                    $this->get('mailer')->send($message2);

                    return $this->render('SosBundle:Default:signalement_profil.html.twig', array("validation" => $validation));
            }
            else {
                return $this->render('SosBundle:Default:signalement_profil.html.twig', array("error" => 'contact'));

            }
        }
        return $this->render('SosBundle:Default:signalement_profil.html.twig');
    }

    /**
     * @Route("/cron_user")
     */
    public function cronUserAction()
    {
        //Suppression des comptes avec abonnement expiré
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository("SosBundle:User")->findAll();
        $today = new \DateTime('NOW');
        foreach($users as $user){
             $age = $today->diff($user->getDateAbonnement());
             

             if(($age->days)+1 == 0 || $age->invert == 1){
                 $em->remove($user);
                 $em->flush();
             }
             if(($age->days <= 5) && ($age->invert == 0) && ($user->getMessage5J() == 0) ){
                 $message = \Swift_Message::newInstance()
                     ->setSubject('Expiration dans 5 jours !')
                     ->setFrom('soshcr@contact.fr')
                     ->setTo($user->getEmail())
                     ->setBody(
                         $this->renderView(
                             'SosBundle:Search:message5J.html.twig',
                             array('prenom' => $user->getPrenom())
                         ),
                         'text/html'
                     );
                 $this->get('mailer')->send($message);

                 $user->setMessage5J(1);
                 $em->persist($user);
                 $em->flush($user);
             }

         }
        return $this->render('SosBundle:Default:index.html.twig');

    }
}
<?php

namespace SosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SosBundle\Entity\UserCritere;
use SosBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\FormType;


class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        // //Suppression des comptes avec abonnement expiré
        // $em = $this->getDoctrine()->getManager();
        // $users = $em->getRepository("SosBundle:User")->findAll();
        // $today = new \DateTime('NOW');
        // foreach($users as $user){   
        //     $age = $today->diff($user->getDateAbonnement());

        //     if($age->days == 0 || $age->invert == 1){
        //         $em->remove($user);
        //         $em->flush();
        //     }
        //     if(($age->days <= 5) && ($age->invert == 0) && ($user->getMessage5J() == 0) ){
        //         $message = \Swift_Message::newInstance()
        //             ->setSubject('Expiration dans 5 jours !')
        //             ->setFrom('soshcr@contact.fr')
        //             ->setTo($user->getEmail())
        //             ->setBody(
        //                 $this->renderView(
        //                     'SosBundle:Search:message5J.html.twig',
        //                     array('prenom' => $user->getNom())
        //                 ),
        //                 'text/html'
        //             );
        //         $this->get('mailer')->send($message);

        //         $user->setMessage5J(1);
        //         $em->flush($user);
        //     }

        // }
        return $this->render('SosBundle:Default:index.html.twig');
    }

    /**
     * @Route("/profil")
     */
    public function profilAction()
    {
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
     * @Route("/dashboard_admin")
     */
    public function dashboardAdminAction()
    {
        return $this->render('SosBundle:Dashboard:dashboard_admin.html.twig');
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
        if (isset($_POST['envoyer'])){
             $message = \Swift_Message::newInstance()
                    ->setSubject('Demande de contact SOSHCR')
                    ->setFrom($_POST['C_EmailAddress'])
                    ->setTo('contact@soshcr.fr')
                    ->setBody(
                        $this->renderView(
                            'SosBundle:Default:demande_contact.html.twig',
                            array('message' => $_POST['Comments'])
                        ),
                        'text/html'
                    );
                $this->get('mailer')->send($message);
            }
        
        return $this->render('SosBundle:Default:contact.html.twig');
    }
}
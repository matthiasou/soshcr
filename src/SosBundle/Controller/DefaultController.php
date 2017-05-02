<?php

namespace SosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SosBundle\Entity\UserCritere;
use SosBundle\Entity\User;
use SosBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
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
    public function profilAction(Request $request)
    {   

        if ($request->isMethod('post')) {

            $user = $this->getUser();
            
            $user->setPrenom($request->get('prenom'));
            $user->setNom($request->get('nom'));
            $user->setTelephone($request->get('telephone'));
      
            $user->setDateNaissance(new \DateTime($request->get('dateNaissance')));
            $user->setEmail($request->get('email'));

            $em = $this->getDoctrine()->getManager();
            // Étape 1 : On « persiste » l'entité
            $em->persist($user);
            // Étape 2 : On « flush » tout ce qui a été persisté avant
            $em->flush();
        }

        return $this->render('SosBundle:Default:profil.html.twig');
    }


    /**
     * @Route("/dashboard")
     */
    public function dashboardAction()
    {
        return $this->render('SosBundle:Dashboard:dashboard.html.twig');
    }

}
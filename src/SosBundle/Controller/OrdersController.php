<?php

namespace SosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Payplug\Resource\Payment;
use Payplug\Payplug;
use SosBundle\Entity\Order;


class OrdersController extends Controller
{
    public function paymentAction()
    {
        if(isset($_POST['15'])){
            $amount = 15;
        }
        if(isset($_POST['30'])){
            $amount = 30;
        }
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $utilisateur = $em->getRepository('SosBundle:User')->findOneBy(array('id' => $user));
        $order = new Order();
        $connection = $em->getConnection();
        $repository = $em->getRepository('SosBundle:Order');
        $query = $repository->createQueryBuilder('o')
        ->select('MAX(o.id)')
        ->getQuery();
        $id = $query->getResult();
        $idorder = $id['0']['1'];
        $orderid = $idorder+1;
        Payplug::setSecretKey('sk_test_1Ku7CEQ0UC5sT5y4N2ZBR2');
        $parameters = [
          'amount'   => $amount * 100, 
          'currency' => 'EUR',
          'customer' => [ 
               'email'      => $user->getEmail(),
               'first_name' => $user->getPrenom(),
               'last_name'  => $user->getNom(),
          ],
          'metadata' => [ 
               'order_id' => $orderid,
               'customer_id' => $user->getId(),
          ],
          'hosted_payment' => [
               'return_url' => 'https://soshcr.fr/payment/success/'.$orderid,
               'cancel_url' => 'https://soshcr.fr/payment/error/'.$orderid,
          ]
        ];
        
    try {
        $payment = Payment::create($parameters);
        $pay = $payment->hosted_payment->payment_url;
        $order->setAmount($amount);
        $order->setUser($user);
        $order->setDate(new \DateTime('NOW'));
        $idpay = $payment->id;
        $order->setIdpayplug($idpay);
        $em->persist($order);
        $em->flush();
    } catch (ConnectionException $e) {
        $this->log("Connection  with the PayPlug API failed.");
    } catch (InvalidPaymentException $e) {
        $this->log("Payment object provided is invalid.");
    } catch (UndefinedAttributeException $e) {
        $this->log("Requested attribute is undefined.");
    } catch (HttpException $e) {
        $this->log("Http errors.");
    } catch (PayplugException $e) {
        $this->log($e->getMessage());
    } catch (\Exception $e) {
        $this->log($e->getMessage());
    }
        return $this->render('SosBundle:Dashboard:payment.html.twig', array('user' => $user, 'pay' => $pay, 'payment' =>$payment));
    }
    
    public function successAction()
    {   
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $iduser = $user->getId();
        $utilisateur = $em->getRepository('SosBundle:User')->findOneBy(array('id' => $user));
        $connection = $em->getConnection();
        $repository = $em->getRepository('SosBundle:Order');
        $query = $repository->createQueryBuilder('o')
        ->select('MAX(o.id)')
        ->where('o.user = :id')
        ->setParameter('id', $iduser)
        ->getQuery()
        ->getSingleResult();
        $id = $query[1];
        $order = $em->getRepository('SosBundle:Order')->findOneBy(array('id' => $id));
        Payplug::setSecretKey('sk_test_1Ku7CEQ0UC5sT5y4N2ZBR2');
        $payplugid = $order->getIdpayplug();
        $payment = Payment::retrieve($payplugid);
        if($payment->is_paid)
        {
            $order->setIsvalide(1);
            if($order->getAmount() == 15){
                $months = '+12 months';
            }
            if($order->getAmount() == 30){
                $months = '+36 months';
            }
            $dateabo = $utilisateur->getDateAbonnement()->modify($months)->format('Y-m-d H:i:s');
            $utilisateur->setDateAbonnement(new \DateTime($dateabo));
            $em->persist($utilisateur);
            $em->flush();
        $validation = "Merci d avoir prolongé ton compte";
        }
        return $this->render('SosBundle:Dashboard:dashboard.html.twig', array('validation' => $validation, 'user' =>$user));
    }
    public function errorAction()
    {
        return $this->render('SosBundle:Orders:error.html.twig');

    }
}
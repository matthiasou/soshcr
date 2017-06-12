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
            $months = '+12 months';
        }
        if(isset($_POST['30'])){
            $amount = 30;
            $months = '+36 months';
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
               'return_url' => 'http://localhost:8888/soshcr2/web/app_dev.php/payment/sucess/'.$orderid,
               'cancel_url' => 'https://soshcr.fr/payment/error/'.$orderid,
          ]
        ];
        
    try {
        $payment = Payment::create($parameters);
        $pay = $payment->hosted_payment->payment_url;
        dump($payment);
        die();
        $order->setAmount($amount);
        $order->setUser($user);
        $order->setDate(new \DateTime('NOW'));
        $dateabo = $utilisateur->getDateAbonnement()->modify($months)->format('Y-m-d H:i:s');
        $utilisateur->setDateAbonnement(new \DateTime($dateabo));
        $em->persist($order);
        $em->persist($utilisateur);
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
    public function sucessAction()
    {
        dump($payment);
        die();
        return $this->render('SosBundle:Orders:sucess.html.twig');

    }
    public function errorAction()
    {
        return $this->render('SosBundle:Orders:error.html.twig');

    }
}
<?php

namespace SosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Payplug\Resource\Payment;
use Payplug\Payplug;

class OrdersController extends Controller
{
    public function paymentAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        Payplug::setSecretKey('sk_test_1Ku7CEQ0UC5sT5y4N2ZBR2');
        $parameters = [
          'amount'   => 10 *10, 
          'currency' => 'EUR',
          'customer' => [ // optionnel
               'email'      => $user->getEmail(),
               'first_name' => $user->getPrenom(),
               'last_name'  => $user->getNom(),
          ],
          'metadata' => [ 
               'order_id' => 42,
               'customer_id' => $user->getId(),
          ],
          'hosted_payment' => [
               'return_url' => 'https://soshcr.fr/dev/soshcr2/web/app_dev.php/payment/success/42',
               'cancel_url' => 'https://soshcr.fr/dev/soshcr2/web/app_dev.php/payment/error/42',
          ]
        ];
        
        try {
    $payment = Payment::create($parameters);
    $pay = $payment->hosted_payment->payment_url;
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
        return $this->render('SosBundle:Dashboard:payment.html.twig', array('user' => $user, 'pay' => $pay));
    }
    public function sucessAction()
    {
        return $this->render('SosBundle:Orders:sucess.html.twig');

    }
    public function errorAction()
    {
        return $this->render('SosBundle:Orders:error.html.twig');

    }
}
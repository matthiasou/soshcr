<?php

namespace SosBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use JMS\Payment\CoreBundle\Form\ChoosePaymentMethodType;
use SosBundle\Entity\Order;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use JMS\Payment\CoreBundle\PluginController\Result;
use JMS\Payment\CoreBundle\Plugin\Exception\Action\VisitUrl;
use JMS\Payment\CoreBundle\Plugin\Exception\ActionRequiredException;

/**
 * @Route("/orders")
 */
class OrdersController extends Controller
{

/**
 * @Route("/new/{amount}")
 */
public function newAction($amount)
{
    $em = $this->getDoctrine()->getManager();

    $order = new Order($amount);
    $em->persist($order);
    $em->flush();
    return $this->redirect($this->generateUrl('show', [
        'id' => $order->getId(),
    ]));
}
/**
 * @Route("/{id}/show")
 * @Template
 */
public function showAction(Request $request, Order $order)
{
	$config = [
    'paypal_express_checkout' => [
        'return_url' => "http://localhost:8888/soshcr2/web/app_dev.php/".$order->getId()."/payment/complete",
        'cancel_url' => "http://localhost:8888/soshcr2/web/app_dev.php/".$order->getId()."/payment/cancel"
    ],
];
	$form = $this->createForm(ChoosePaymentMethodType::class, null, [
	    'amount'   => '00.01',
	    'currency' => 'EUR',
	    'allowed_methods' => ['paypal_express_checkout'],
        'predefined_data' => $config,
	]);
	$form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        $ppc = $this->get('payment.plugin_controller');
        $ppc->createPaymentInstruction($instruction = $form->getData());

        $order->setPaymentInstruction($instruction);

        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush($order);
        $idorder = $order->getId();
        return $this->redirect($this->generateUrl('paymentCreate', [
            'id' => $order->getId(),
        ]));
    }
    return $this->render('SosBundle:Orders:show.html.twig', array(
        'order' => $order,
        'form' => $form->createView()
    ));
}
private function createPayment($order)
{
    $instruction = $order->getPaymentInstruction();
    $pendingTransaction = $instruction->getPendingTransaction();

    if ($pendingTransaction !== null) {
        return $pendingTransaction->getPayment();
    }

    $ppc = $this->get('payment.plugin_controller');
    $amount = $instruction->getAmount() - $instruction->getDepositedAmount();

    return $ppc->createPayment($instruction->getId(), $amount);
}

/**
 * @Route("/{id}/payment/create")
 */
public function paymentCreateAction(Order $order)
{
    $payment = $this->createPayment($order);
    $ppc = $this->get('payment.plugin_controller');
    $result = $ppc->approveAndDeposit($payment->getId(), $payment->getTargetAmount());

    if ($result->getStatus() === Result::STATUS_SUCCESS) {
        
        return $this->redirect($this->generateUrl('paymentComplete', array('id' => $id)));
    }
    if ($result->getStatus() === Result::STATUS_PENDING) {
    $ex = $result->getPluginException();

    if ($ex instanceof ActionRequiredException) {
        $action = $ex->getAction();

        if ($action instanceof VisitUrl) {
            return $this->redirect($action->getUrl());
        }
    }
	}

    //return $this->redirectToRoute('abonnement');
    throw $result->getPluginException();

    // In a real-world application you wouldn't throw the exception. You would,
    // for example, redirect to the showAction with a flash message informing
    // the user that the payment was not successful.
}
/**
 * @Route("/{id}/payment/complete")
 */
public function paymentCompleteAction(Order $order)
{
    return new Response('Payment complete');
}
/**
 * @Route("/{id}/payment/cancel")
 */
public function paymentCancelAction(Order $order)
{
    return new Response('Payment cancel');
}

}
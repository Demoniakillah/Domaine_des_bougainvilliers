<?php

namespace DDB\ReservBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use DDB\ReservBundle\Entity\Reservation;
use DDB\ReservBundle\Form\ReservationType;
use DDB\ReservBundle\Entity\Paypal;


class ReservController extends Controller
{
    public function indexAction()
    {
        $listePhotos=array();
        $em=$this->getDoctrine()->getManager();
        $bungalows = $em->getRepository('DDBAdminBundle:Bungalow')->findAll();
        for($i=1;$i<=3;$i++){
            $Photos = $em->getRepository('DDBAdminBundle:Photo')->findBy(array('bungalowId'=>$i));
            array_push($listePhotos, $Photos);
        }
        return $this->render('DDBReservBundle:Reserv:index.html.twig',array('bungalows'=>$bungalows,'listePhotos'=>$listePhotos));
    }
    
    public function detailsAction($id){
        $em=$this->getDoctrine()->getManager();
        $bungalow = $em->getRepository('DDBAdminBundle:Bungalow')->find($id);
        $listePhotos = $em->getRepository('DDBAdminBundle:Photo')->listePhotobyBungalow($id);
        return $this->render('DDBReservBundle:Reserv:details.html.twig',array('listePhotos'=>$listePhotos,'bungalow'=>$bungalow));
    }
    
    public function reservAction($id,Request $request){
        $em=$this->getDoctrine()->getManager();
        $bungalow = $em->getRepository('DDBAdminBundle:Bungalow')->find($id);
        $listeReservations=$em->getRepository('DDBReservBundle:Reservation')->findByBungalow($id);
        $reservation = new Reservation();
        $form = $this->get('form.factory')->create(new ReservationType,$reservation);
        if($form->handleRequest($request)->isValid()){
            $reservation->setBungalow($bungalow->getId());
            $jours = $reservation->getDebut()->diff($reservation->getFin())->format('%a');
            $reservation->setMontant($jours*$bungalow->getTarif());
            
            if($reservation->getDebut()>=$reservation->getFin()){
                $request->getSession()->getFlashBag()->add('notice', 'La période de réservation doit être au moins d\'un jour');
                return $this->render('DDBReservBundle:Reserv:reserv.html.twig',array('listeReservations'=>$listeReservations,'bungalow'=>$bungalow,'form'=>$form->createView()));
            }
            if($em->getRepository('DDBReservBundle:Reservation')->isAlreadyReserved_($reservation)){
                $request->getSession()->getFlashBag()->add('notice', 'Désolé, ce bungalow est déjà réservé pour cette période. Merci de sélectionner d\'autres dates');
                return $this->render('DDBReservBundle:Reserv:reserv.html.twig',array('listeReservations'=>$listeReservations,'bungalow'=>$bungalow,'form'=>$form->createView()));
            }
            if($em->getRepository('DDBReservBundle:Reservation')->isAlreadyReserved($reservation)){
                $request->getSession()->getFlashBag()->add('notice', 'Désolé, ce bungalow est déjà réservé pour cette période');
                return $this->render('DDBReservBundle:Reserv:reserv.html.twig',array('listeReservations'=>$listeReservations,'bungalow'=>$bungalow,'form'=>$form->createView()));
            }
            if($reservation->getDebut()<$reservation->getDate()){
                $request->getSession()->getFlashBag()->add('notice', 'Dates de réservation invalides, réservations à partir de demain');
                return $this->render('DDBReservBundle:Reserv:reserv.html.twig',array('listeReservations'=>$listeReservations,'bungalow'=>$bungalow,'form'=>$form->createView()));
            }
            if($reservation->getFin()<$reservation->getDate()){
                $request->getSession()->getFlashBag()->add('notice', 'Dates de réservation invalides, veuillez revoir votre sélection');
                return $this->render('DDBReservBundle:Reserv:reserv.html.twig',array('listeReservations'=>$listeReservations,'bungalow'=>$bungalow,'form'=>$form->createView()));
            }
            $session = $this->getRequest()->getSession();
            $session->set('reservation' , serialize($reservation));
            $session->set('bungalow' , serialize($bungalow));
            return $this->render('DDBReservBundle:Reserv:confirm.html.twig',array('reservation'=>$reservation));
        }
        return $this->render('DDBReservBundle:Reserv:reserv.html.twig',array('listeReservations'=>$listeReservations,'bungalow'=>$bungalow,'form'=>$form->createView()));
    }
    
    public function calendrierAction($id){
        $listeReservations=$this
                ->getDoctrine()
                ->getManager()
                ->getRepository('DDBReservBundle:Reservation')
                ->findByBungalow($id);
        return $this->render('DDBReservBundle:Reserv:calendrier.html.twig',
                array('listeReservations'=>$listeReservations,'id'=>$id));
    }
    
    public function okAction(Request $request) {
        $paypal = new Paypal(
                    $this->container->getParameter('paypal_username'),
                    $this->container->getParameter('paypal_password'),
                    $this->container->getParameter('paypal_signature'),
                    $this->container->getParameter('paypal_url')
                    );
        $reponse = $paypal->request('GetExpressCheckoutDetails', array(
            'TOKEN'=>filter_input(INPUT_GET,'token')
        ));
        if($reponse){
            //var_dump($reponse);
        }
        else{
            return $this->render('DDBReservBundle:Reserv/Errors:unknown.html.twig');
        }
        
        $em = $this->getDoctrine()->getManager();
        
        $reservation_temp = $em->getRepository('DDBReservBundle:Reservation')->findByToken(filter_input(INPUT_GET, 'token'));
        if (null==$reservation_temp) {
            return $this->render('DDBReservBundle:Reserv/Errors:unknown.html.twig');
        }
        else{
            $reservation = $reservation_temp[0];
        }
        
        $bungalow_temp = $em->getRepository('DDBAdminBundle:Bungalow')->findByNumero($reservation->getBungalow());
        if(null==$bungalow_temp) {
            return $this->render('DDBReservBundle:Reserv/Errors:unknown.html.twig');
        }
        else{
            $bungalow = $bungalow_temp[0];
        }

        $nb_jours = $reservation->getDebut()->diff($reservation->getFin())->format('%R%a');
        $reponse = $paypal->request('DoExpressCheckoutPayment', array(
            'TOKEN'=>  filter_input(INPUT_GET, 'token'),
            'PAYERID'=>filter_input(INPUT_GET,'PayerID'),
            'PAYMENTREQUEST_0_AMT'=>$reservation->getMontant(),
            'PAYMENTREQUEST_0_CURRENCYCODE'=>'EUR',
            'PAYMENTACTION'=>'Sale',
            'PAYMENTREQUEST_0_ITEMAMT'=>$reservation->getMontant(),
            'L_PAYMENTREQUEST_0_NAME0'=>'Bungalow '.$bungalow->getNom(),
            'L_PAYMENTREQUEST_0_DESC0'=>'',
            'L_PAYMENTREQUEST_0_AMT0'=>$bungalow->getTarif(),
            'L_PAYMENTREQUEST_0_QTY0'=>$nb_jours
        ));
        if($reponse){
            $reservation->setTransactionID($reponse['PAYMENTINFO_0_TRANSACTIONID']);
            $reservation->setTraitee(true);
            $reservation->setValidee(true);
            $reservation->setTraiteeLe(new \DateTime());
            $em=$this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();
            
            //mail client
            $to = $reservation->getEmail();
            $sujet = 'Confirmation de réservation';
            $headers='From: Domaine des Bougainvilliers <'.$this->container->getParameter('mailer_host_service').'>'. "\r\n";
            $headers.= "MIME-Version: 1.0" . "\r\n";
            $headers.= "Content-type: text/html; charset=utf-8" . "\r\n";
            $message = $this->
                    renderView('DDBReservBundle:Reserv:mailClient.html.twig',
                            array('bungalow'=>$bungalow,'reservation'=>$reservation)
                            );
            @mail($to, $sujet, $message, $headers);
            $to_ = $this->container->getParameter('mailer_host');
            $sujet_ = 'Nouvelle réservation';
            $headers_ ='From: Service Domaine des Bougainvilliers <'.$this->container->getParameter('mailer_host_service').'>'. "\r\n";
            $headers_ .= "MIME-Version: 1.0" . "\r\n";
            $headers_ .= "Content-type: text/html; charset=utf-8" . "\r\n";
            $message_ = $this->
                    renderView('DDBReservBundle:Reserv:mailAdmin.html.twig',
                            array('reservation'=>$reservation)
                            );
            @mail($to_, $sujet_, $message_, $headers_);
            $paypal_adresse = $this->container->getParameter('paypal');
            return $this->render('DDBReservBundle:Reserv:ok.html.twig',array('paypal'=>$paypal_adresse));
        }
        else{
            $em->remove($reservation);
            $em->flush();
            if($paypal->errors['L_ERRORCODE0']==="11607"){
                return $this->render('DDBReservBundle:Reserv:Errors/dejaPaye.html.twig');
            }
            if($paypal->errors['L_ERRORCODE0']==="10416"){
                return $this->render('DDBReservBundle:Reserv/Errors:tropEssais.html.twig');
            }
            return $this->render('DDBReservBundle:Reserv/Errors:unknown.html.twig');
        }
    }
    
    public function cancelAction() {
        $em = $this->getDoctrine()->getManager();
        $reservation_temp = $em->getRepository('DDBReservBundle:Reservation')->findByToken(filter_input(INPUT_GET, 'token'));
        if (null==$reservation_temp) {
            return $this->render('DDBReservBundle:Reserv/Errors:unknown.html.twig');
        }
        else{
            $reservation = $reservation_temp[0];
        }
        $em->remove($reservation);
        $em->flush();
        return $this->render('DDBReservBundle:Reserv:cancel.html.twig');
    }
    
    public function confirmAction() {
        $session = $this->getRequest()->getSession();
        $reservation = unserialize($session->get('reservation'));
        $bungalow = unserialize($session->get('bungalow'));
        $nb_jours = $reservation->getDebut()->diff($reservation->getFin())->format('%R%a');

            $cancelurl = $this->container->getParameter('paypal_cancelurl');
            $returnurl = $this->container->getParameter('paypal_returnurl');
            $validate_url = $this->container->getParameter('paypal_validateurl');
            
            $paypal = new Paypal(
                    $this->container->getParameter('paypal_username'),
                    $this->container->getParameter('paypal_password'),
                    $this->container->getParameter('paypal_signature'),
                    $this->container->getParameter('paypal_url')
                    );
            
            $params = array(
                'RETURNURL'=>$returnurl,
                'CANCELURL'=>$cancelurl,
                'PAYMENTREQUEST_0_AMT'=>$reservation->getMontant(),
                'PAYMENTREQUEST_0_CURRENCYCODE'=>'EUR',
                'PAYMENTREQUEST_0_SHIPPINGAMT'=>'00.0',
                'PAYMENTREQUEST_0_ITEMAMT'=>$reservation->getMontant(),
                'L_PAYMENTREQUEST_0_NAME0'=>'Bungalow '.$bungalow->getNom(),
                'L_PAYMENTREQUEST_0_DESC0'=>'',
                'L_PAYMENTREQUEST_0_AMT0'=>$bungalow->getTarif(),
                'L_PAYMENTREQUEST_0_QTY0'=>$nb_jours
            );
            
            $reponse = $paypal->request('SetExpressCheckout', $params);
            if($reponse){
                $em = $this->getDoctrine()->getManager();
                $reservation->setToken($reponse['TOKEN']);
                $reservation->setValidee(false);
                $em->persist($reservation);
                $em->flush();
                $paypal = $validate_url.$reponse['TOKEN'];
                return $this->redirect($paypal);
            }
            else{
                var_dump($paypal->errors);
                die('Erreur');
            }
    }
}

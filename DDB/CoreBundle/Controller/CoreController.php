<?php
//src\DDB\CoreBundle\Controller\CoreController.php

namespace DDB\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use DDB\CoreBundle\Entity\CAdmin;

class CoreController extends Controller{
    
    public function indexAction(){
        $em = $this->getDoctrine()->getManager();
        $accueil = $em->getRepository('DDBAdminBundle:Accueil')->find(1);
        $album = $em->getRepository('DDBAdminBundle:Album')->findAll();
        $commentaires = $em->getRepository('DDBCoreBundle:Livredor')->findBy(array(),array('date'=>'DESC'),5);
        return $this->render('DDBCoreBundle:Core:index.html.twig',array('album'=>$album,'accueil'=>$accueil,'commentaires'=>$commentaires));
    }
    
    public function photosAction(){
		$album = array();
        return $this->render('DDBCoreBundle:Photos:index.html.twig',array('album'=>$album));
    }
    
    public function livredorAction(\Symfony\Component\HttpFoundation\Request $request){
        $em = $this->getDoctrine()->getManager();
        $livredor = $em->getRepository('DDBCoreBundle:Livredor')->findAll();
        $commentaire = new \DDB\CoreBundle\Entity\Livredor();
        $form = $this->get('form.factory')->create(new \DDB\CoreBundle\Form\LivredorType,$commentaire);
        if ($form->handleRequest($request)->isValid()){
            $em ->persist($commentaire);
            $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'Commentaire enregistr�. Merci');
        return $this->redirect($this->generateUrl('ddb_core_livredor'));
        }
        return $this->render('DDBCoreBundle:Livredor:index.html.twig',array('form'=>$form->createView(),'livredor'=>$livredor));
    }
    
    public function geographieAction() {
        $plan=$this->getDoctrine()->getManager()->getRepository('DDBAdminBundle:Plan')->find(1);
        return $this->render('DDBCoreBundle:Geographie:index.html.twig',array('plan'=>$plan));
    }
    
    public function contactAction() {
        $contact=$this->getDoctrine()->getManager()->getRepository('DDBAdminBundle:Contact')->find(1);
        return $this->render('DDBCoreBundle:Contact:index.html.twig',array('contact'=>$contact));
    }
    
    public function cgvAction() {
        return $this->render('DDBCoreBundle::cgv.html.twig');
    }
    
    public function cadminAction(\Symfony\Component\HttpFoundation\Request $request){
        $cadmin = new CAdmin();
        $form = $this->get('form.factory')->create(new \DDB\CoreBundle\Form\CAdminType,$cadmin);
        if ($form->handleRequest($request)->isValid()){
            if($cadmin->getEmail()=="no@reply.to"){
                $from = "admin@audomainedesbougainvilliers.com";
            }
            $to      = 'raymond.royglen@gmail.com ';
            $subject = $cadmin->getObjet();
            $message = $cadmin->getMessage();
            $headers = 'From: '.$from.  "\r\n" .
            'X-Mailer: PHP/' . phpversion();
            if(mail($to, $subject, $message,$headers)){
                $request->getSession()->getFlashBag()->add('notice', 'Votre message a été envoyé avec succès. Merci de votre attention. Cordialement');
            }
            else{
		$request->getSession()->getFlashBag()->add('alert', 'Désolé, erreur lors de l\'envoi du message');
            }   
        }
        return $this->render('DDBCoreBundle::cadmin.html.twig',array('form'=>$form->createView()));
    }

}

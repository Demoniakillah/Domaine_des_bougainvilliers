<?php

namespace DDB\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    public function deleteCommentaireAction($id, Request $request) {
        $em = $this->getDoctrine()
                ->getManager();
        $commentaire = $em->getRepository('DDBCoreBundle:Livredor')->find($id);
        $em->remove($commentaire);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'Commentaire supprimé');
        return $this->redirect($this->generateUrl('ddb_admin_commentaires'));
    }
    
    public function commentairesAction(){
        $em=$this->getDoctrine()->getManager();
        $commentaires = $em->getRepository('DDBCoreBundle:Livredor')->findAll();
        return $this->render('DDBAdminBundle:Admin:commentaires.html.twig',array('commentaires'=>$commentaires));
    }
    
    public function planAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $plan = $em->getRepository('DDBAdminBundle:Plan')->find(1);
        $form = $this->get('form.factory')->create(new \DDB\AdminBundle\Form\PlanType,$plan);
        if($form->handleRequest($request)->isValid()){
            $em->persist($plan);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Texte modifié avec succes');
            return $this->redirect($this->generateUrl('ddb_admin_plan'));
        }
        return $this->render('DDBAdminBundle:Admin:plan.html.twig',array('form'=>$form->createView()));
    }
    
    public function contactAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository('DDBAdminBundle:Contact')->find(1);
        $form = $this->get('form.factory')->create(new \DDB\AdminBundle\Form\ContactType,$contact);
        if($form->handleRequest($request)->isValid()){
            $em->persist($contact);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Texte modifié avec succes');
            return $this->redirect($this->generateUrl('ddb_admin_contact'));
        }
        return $this->render('DDBAdminBundle:Admin:contact.html.twig',array('form'=>$form->createView()));
    }
    
    public function deleteAlbumAction($id, Request $request) {
        $em=$this->getDoctrine()->getManager();
        $album=$em->getRepository('DDBAdminBundle:Album')->find($id);
        @unlink($album->getUploadDir().'/'.$album->getUrl());
        $em->remove($album);
        $em->flush();
        $request->getSession()->getFlashBag()->add('notice', 'Photo supprimée');
        return $this->redirect($this->generateUrl('ddb_admin_album'));
    }
    
    public function albumAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $album_complet = $em->getRepository('DDBAdminBundle:Album')->findAll();
        $album = new \DDB\AdminBundle\Entity\Album();
        $form = $this->get('form.factory')->create(new \DDB\AdminBundle\Form\AlbumType,$album);
        if($form->handleRequest($request)->isValid()){
            $album->upload();
            $positionMax = $em->getRepository('DDBAdminBundle:Album')->getPosMax();
            if ($positionMax[0][0] == null) {
                $positionMax = 0;
            }
            else{
                $positionMax = $positionMax[0]['max_pos'];
            }
            $album->setPosition($positionMax+1);
            $em->persist($album);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Photo ajoutée');
            return $this->redirect($this->generateUrl('ddb_admin_album'));
        }
        return $this->render('DDBAdminBundle:Admin:album.html.twig',array('album'=>$album_complet,'form'=>$form->createView()));
    }
    
    public function adminAction()
    {
        $em = $this->getDoctrine()->getManager();
        $bungalows=$em->getRepository('DDBAdminBundle:Bungalow')->findAll();
        return $this->render('DDBAdminBundle:Admin:admin.html.twig',array('bungalows'=>$bungalows));
    }
    
    public function loginAction()
    {
      if ($this->get('request')->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
          $error = $this->get('request')->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
      } else {
          $error = $this->get('request')->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
      }
 
      return $this->render('DDBAdminBundle:Admin:login.html.twig', array(
          'last_username' => $this->get('request')->getSession()->get(SecurityContext::LAST_USERNAME),
          'error' => $error,
      ));
    }
    
    public function viewAction($id, Request $request){
        $em = $this->getDoctrine()->getManager();
        $bungalow=$em->getRepository('DDBAdminBundle:Bungalow')->find($id);
        $form = $this->get('form.factory')->create(new \DDB\AdminBundle\Form\BungalowType,$bungalow);
        $photo = new \DDB\AdminBundle\Entity\Photo();
        $form_photo = $this->get('form.factory')->create(new \DDB\AdminBundle\Form\PhotoType,$photo);
        $listePhotos = $em->getRepository('DDBAdminBundle:Photo')->listePhotobyBungalow($bungalow->getId());
        $bungalows = $em->getRepository('DDBAdminBundle:Bungalow')->findAll();
        if($form_photo->handleRequest($request)->isValid()){
            $photo->setBungalowId($bungalow->getId());
            $photo->upload();
            $em->persist($photo);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Photo ajoutée');
            return $this->redirect($this->generateUrl('bungalow_view',array('id'=>$bungalow->getId())));
        }
        if ($form->handleRequest($request)->isValid()){
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Modification bien enregistrée');
            return $this->render('DDBAdminBundle:Admin:admin.html.twig',array('bungalows'=>$bungalows));
        }
    return $this->render('DDBAdminBundle:Admin:view.html.twig',array('form_photo'=>$form_photo->createView(),'listePhotos'=>$listePhotos,'bungalow'=>$bungalow,'form'=>$form->createView()));
    }
    
    public function deletePhotoAction($id_photo, Request $request){
        $em=$this->getDoctrine()->getManager();
        $photo=$em->getRepository('DDBAdminBundle:Photo')->find($id_photo);
        $id=$photo->getBungalowId();
        @unlink($photo->getUploadDir().'/'.$photo->getUrl());
        $em->remove($photo);
        $em->flush();
        $bungalow=$em->getRepository('DDBAdminBundle:Bungalow')->find($id);
        $request->getSession()->getFlashBag()->add('notice', 'Photo supprimée');
        return $this->redirect($this->generateUrl('bungalow_view',array('id'=>$bungalow->getId())));
    }
    
    public function reservationsAction($type,$val){
        $em=$this->getDoctrine()->getManager()->getRepository('DDBReservBundle:Reservation');
        switch ($type) {
            case 'month':
                $historique=$em->findReservationsByMonth($val);
                break;
            case 'limit':
                if ($val == 'null') {
                    $historique = $em->findAllReservations();
                } else {
                    $historique = $em->findReservations($val);
                }
                break;
        }
        return $this->render('DDBAdminBundle:Admin:reserv.html.twig',
    array('historique'=>$historique
        ));
    }
    
    public function accueilAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $bungalows=$em->getRepository('DDBAdminBundle:Bungalow')->findAll();
        $accueil = $em->getRepository('DDBAdminBundle:Accueil')->find(1);
        $form = $this->get('form.factory')->create(new \DDB\AdminBundle\Form\AccueilType,$accueil);
        if($form->handleRequest($request)->isValid()){
            $em->persist($accueil);
            $em->flush();
            $request->getSession()->getFlashBag()->add('notice', 'Texte de l\'accueil modifié');
            return $this->redirect($this->generateUrl('ddb_admin_accueil'));
        }
        return $this->render('DDBAdminBundle:Admin:accueil.html.twig',array('bungalow'=>$bungalows,'form'=>$form->createView()));
    }
    
    public function traiterAction($reservation_id,$action){
        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository('DDBReservBundle:Reservation')->find($reservation_id);
        $em->remove($reservation);
        $em->flush();
        return $this->redirect($this->generateUrl('ddb_admin_reservations'));
    }
    
    public function passwordForgotenAction() {
            $to = $this->container->getParameter('mailer_host');
            $sujet = 'Mot de passe';
            $headers='From: Service des Bougainvilliers <'.$this->container->getParameter('mailer_host_service').'>'. "\r\n";
            $message = 'identifiant:  | mot de passe: ';
            mail($to, $sujet, $message, $headers);
            return $this->redirect($this->generateUrl('ddb_admin_homepage'));
    }
    
    public function helpAction() {
        return $this->render('DDBAdminBundle:Admin:help.html.twig');
    }
}

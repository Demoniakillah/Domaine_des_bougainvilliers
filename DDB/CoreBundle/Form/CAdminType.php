<?php

namespace DDB\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CAdminType extends AbstractType{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                    'name',
                    'text',
                    array(
                        'label'=>'Votre nom',
                        'required'=>false,
                        'attr'=>array(
                            'onclick'=>'var name=document.getElementById(\'cadmin_name\').value; if(name==\'@nonymous\' ){document.getElementById(\'cadmin_name\').value=\'\';}',
                            'onblur'=>'var name=document.getElementById(\'cadmin_name\').value; if(name==\'\'){document.getElementById(\'cadmin_name\').value=\'@nonymous\';}')))
            ->add('objet','text',array(
                'label'=>'Objet du message', 
                'required'=>false,
                'attr'=>array(
                            'onclick'=>'var objet=document.getElementById(\'cadmin_objet\').value; if(objet==\'Demande de contact\' ){document.getElementById(\'cadmin_objet\').value=\'\';}',
                            'onblur'=>'var objet=document.getElementById(\'cadmin_objet\').value; if(objet==\'\'){document.getElementById(\'cadmin_objet\').value=\'Demande de contact\';}')))
            ->add('email','email',array(
                'label'=>'Adresse e-mail', 
                'required'=>false,
                'attr'=>array(
                            'onclick'=>'var email=document.getElementById(\'cadmin_email\').value; if(email==\'no@reply.to\' ){document.getElementById(\'cadmin_email\').value=\'\';}',
                            'onblur'=>'var email=document.getElementById(\'cadmin_email\').value; if(email==\'\'){document.getElementById(\'cadmin_email\').value=\'no@reply.to\';}')))
            ->add('message','textarea', array(
                        'label'=>'Votre message *', 
                        'required'=>true, 
                        'attr'=>array(
                            'onclick'=>'var message=document.getElementById(\'cadmin_message\').value; if(message==\'Votre message\' ){document.getElementById(\'cadmin_message\').value=\'\';}',
                            'onblur'=>'var message=document.getElementById(\'cadmin_message\').value; if(message==\'\'){document.getElementById(\'cadmin_message\').value=\'Votre message\';}')))
            ->add('Envoyer','submit',array('attr'=>array('class'=>'btn btn-primary')))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DDB\CoreBundle\Entity\CAdmin'
        ));
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'cadmin';
    }
}
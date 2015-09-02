<?php

namespace DDB\ReservBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReservationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('debut','date',array('label'=>'Du','format' => 'dd-MM-yyyy'))
            ->add('fin','date',array('label'=>'Au','format' => 'dd-MM-yyyy'))
            ->add('cp','number',array('label'=>'Code postal'))
            ->add('ville')
            ->add('civilite','choice',array('choices'=>array('Mr'=>'Mr','Mme'=>'Mme','Mlle'=>'Mlle')))
            ->add('nom')
            ->add('prenom')
            ->add('adresse')
            ->add('email','email')
            ->add('telephone')
            ->add('ok','submit',array('label'=>'Continuer'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DDB\ReservBundle\Entity\Reservation'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ddb_reservbundle_reservation';
    }
}

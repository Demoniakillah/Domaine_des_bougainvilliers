<?php

namespace DDB\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BungalowType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('tarif','number',array('label'=>'Tarif:'))
                ->add('nom','text',array('label'=>'Nom du bungalow:'))
                ->add('resume','textarea',array('label'=>'Résumé:'))
                ->add('texte','textarea',array('label'=>'Texte:'))
                ->add('valider','submit',array('label'=>'Enregistrer'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DDB\AdminBundle\Entity\Bungalow'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ddb_adminbundle_bungalow';
    }
}

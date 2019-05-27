<?php

namespace AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AdminType extends AbstractType {

    /**
     * 
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('enabled', CheckboxType::class, array('label' => 'Activer', 'required' => false))
                ->add('email', EmailType::class, array('label' => 'Email'))
                ->add('newpass', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les mots de passe ne sont pas identique.',
                    'options' => array('attr' => array("autocomplete" => "off")),
                    'label' => 'Mot de passe',
                    'mapped' => false,
                    'required' => true,
                    'first_options' => array('attr' => array('placeholder' => 'Mot de passe', 'style' => 'width: 100%;')),
                    'second_options' => array('attr' => array('placeholder' => 'Répétez le mot de passe', 'style' => 'width: 100%;'))
                        )
                )
                ->add('nom')
                ->add('prenom')
                ->add('submit', SubmitType::class)
        ;
    }

    /**
     * 
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AdminBundle\Entity\Admin'
        ));
    }

    /**
     * 
     * @return string
     */
    public function getBlockPrefix() {
        return 'adminbundle_admin';
    }

}

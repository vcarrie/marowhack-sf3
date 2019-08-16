<?php

namespace MediaBundle\Form\Type;

use MediaBundle\Entity\Media;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('path', TextType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'definima.media.path.placeholder',
                    'readonly' => $options['path_readonly'],
                    'required' => $options['required'],
                    'style' => 'opacity: 0;top: 0;right:0;position: absolute;'],
            ])
            ->add('alt', TextType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'definima.media.alt.placeholder']
            ])
            ->add('conf', TextType::class, [
                'required' => false,
                'attr' => ['style' => 'opacity: 0;top: 0;right:0;position: absolute;'],
            ])
            ->add('name', TextType::class, [
                'required' => false,
                'attr' => ['style' => 'opacity: 0;top: 0;right:0;position: absolute;'],
            ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);
        $view->vars = array_replace($view->vars, [
            'conf' => $options['conf'],
            'tree' => $options['tree'],
            'allow_alt' => $options['allow_alt'],
            'display_front' => $options['display_front'],
            'minimum_width' => $options['minimum_width'],
            'minimum_height' => $options['minimum_height'],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required' => false,
            'data_class' => Media::class,
            'by_reference' => false,
            'allow_alt' => true,
            'path_readonly' => false,
            'conf' => false,
            'tree' => 0,
            'error_bubbling' => false,
            'display_front' => false,
            'minimum_height' => 0,
            'minimum_width' => 0,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'definima_media';
    }

}

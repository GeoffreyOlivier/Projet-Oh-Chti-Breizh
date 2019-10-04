<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('telephone', TelType::class, ['label'=>'Telephone:', 'attr'=>['required'   => true]])
            ->add('password', PasswordType::class, ['mapped' => false, 'label'=>'Mot de passe:', 'attr'=>['required'   => true]])
            ->add('username', TextType::class, ['label'=>'pseudo:', 'attr'=>['required'   => true]])
            ->add('email', EmailType::class, ['label'=>'Email:', 'attr'=>['required'   => true]])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}

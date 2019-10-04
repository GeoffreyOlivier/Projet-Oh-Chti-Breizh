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

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('telephone', TelType::class, ['label'=>'Telephone:', 'attr'=>['disabled'   => true]])
            ->add('password', PasswordType::class, ['mapped' => false, 'label'=>'MDP:' ,'attr'=>['disabled'   => true , 'placeholder'=>'Votre mot de passe']])
            ->add('username', TextType::class, ['label'=>'Prenom:', 'attr'=>['disabled'   => true]])
            ->add('email', EmailType::class, ['label'=>'Email:', 'attr'=>['disabled'   => true]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}

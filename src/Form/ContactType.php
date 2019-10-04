<?php

namespace App\Form;

use App\Entity\Contact;
use function PHPSTORM_META\type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Form\CallbackTransformer;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, ['attr' => ['name' => 'nom', 'required' => true]])
            ->add('prenom', TextType::class, ['attr' => ['required' => true]])
            ->add('telephone', TelType::class, ['attr' => ['required' => true]])
            ->add('email', EmailType::class, ['attr' => ['required' => true]])
            ->add('message', TextareaType::class, ['attr' => array('cols' => '45', 'rows' => '5')]);

//        $builder->get('message')
//        ->addModelTransformer(new CallbackTransformer(
//            function ($tagsAsArray) {
//                // transform the array to a string
//                return implode(', ', $tagsAsArray);
//            },
//            function ($tagsAsString) {
//                // transform the string back to an array
//                return explode(', ', $tagsAsString);
//            }
//        ))
//        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}

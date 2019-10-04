<?php

namespace App\Form;


use App\Entity\Categorie;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class, ['label'=>'Nom:', 'attr'=>['required'   => true]])
            ->add('description',TextType::class, ['label'=>'description:'])
            ->add('prix',NumberType::class,['label'=>'Prix', 'attr'=>['required'   => true]])
            ->add('region',NumberType::class,['label'=>'Region 0=aucune 1=bzh 2=flam', 'attr'=>['required'=> true]])
            ->add('photo', FileType::class, ['label' => 'image(JPG)', 'data_class'=>null, 'attr'=>['required'   => true]])
            ->add('categorie',EntityType::class,['class' => Categorie::class,'choice_label' => 'libelle'])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Advert;
use App\Entity\Image;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AdvertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Advert Name',
                ),
            ))
            ->add('price', MoneyType::class )
            ->add('creationDate', DateTimeType::class, array(
                'label' => 'Creation Date',
                'date_widget' => 'single_text'
            ))
            ->add('description', TextareaType::class)
            ->add('image', FileType::class, array(
                'label' => 'Image'
            ))
            ->add('categories', EntityType::class, [
                'mapped' => false,
                'class' => Category::class,
                'choice_label' => 'categories',
                'multiple' => true,
                'expanded' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Advert::class,
        ]);
    }
}

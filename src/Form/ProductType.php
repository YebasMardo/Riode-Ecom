<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductImage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{

    public function __construct(private FormListenerFactory $formListenerFactory) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre'])
            ->add('description', TextType::class, ['label' => 'Description'])
            ->add('price', IntegerType::class, ['label' => 'Prix'])
            ->add('promoPrice', IntegerType::class, ['label' => 'Prix Promo', 'required' => false])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Catégorie'
            ])
            ->add('productImages', CollectionType::class, [
                'entry_type' => ProductImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Images du produit',
                'prototype' => true,            // par défaut c'est true si allow_add = true, mais explicite c'est clair
                'prototype_name' => '__name__',
                'entry_options' => ['label' => false],
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, $this->formListenerFactory->attachTimestamps())
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

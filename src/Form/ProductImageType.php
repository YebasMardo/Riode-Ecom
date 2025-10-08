<?php

namespace App\Form;

use App\Entity\ProductImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champ géré par VichUploaderBundle
            ->add('imageFile', VichImageType::class, [
                'label' => 'Image du produit',
                'required' => false,
                'allow_delete' => true,     // bouton pour supprimer
                'download_uri' => false,    // pas de lien de téléchargement
                'image_uri' => true,        // affiche l’image actuelle si elle existe
            ])

            // Champs supplémentaires si tu veux les voir (facultatif)
            ->add('name', HiddenType::class)
            ->add('size', HiddenType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductImage::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Marque;
use App\Entity\Produit;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomProduit')
            ->add('prixProduit')
            ->add('Categorie', EntityType::class, [
                'class'         => Categorie::class,
                'choice_label'  => 'nomCategorie',
                'label'         => 'Catégorie(s) du produit',
                'multiple'      => true,
                'mapped'        => false
            ])
            ->add('Marque', EntityType::class, [
                'class'         => Marque::class,
                'choice_label'  => 'nomMarque',
                'label'         => 'Marque',
                //'mapped'        => false
            ])
            ->add('imagProduit', FileType::class)
            ->add('descriptionProduit')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}

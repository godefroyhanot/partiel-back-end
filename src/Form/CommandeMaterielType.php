<?php

namespace App\Form;

use App\Entity\CommandeMateriel;
use App\Entity\Materiel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeMaterielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('materiel', EntityType::class, [
                'class' => Materiel::class,
                'choice_label' => function (Materiel $materiel) {
                    return sprintf('%s (Dispo: %d) - %.2f€', 
                        $materiel->getNom(), 
                        $materiel->getQuantiteDisponible(),
                        (float)$materiel->getPrixLocation()
                    );
                },
                'label' => 'Matériel',
                'attr' => [
                    'class' => 'form-control'
                ],
                'query_builder' => function ($repository) {
                    return $repository->createQueryBuilder('m')
                        ->where('m.quantiteDisponible > 0')
                        ->orderBy('m.nom', 'ASC');
                }
            ])
            ->add('quantite', IntegerType::class, [
                'label' => 'Quantité',
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CommandeMateriel::class,
        ]);
    }
}

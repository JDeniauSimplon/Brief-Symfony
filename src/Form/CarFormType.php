<?php

namespace App\Form;

use App\Validator\UniqueCar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('brand', TextType::class, [
                'label' => 'Marque',
            ])
            ->add('model', TextType::class, [
                'label' => 'Modèle',
                'attr' => [
                    'class' => 'car_form_model',
                ],
            ])
            ->add('seats', IntegerType::class, [
                'label' => 'Places',
                'attr' => [
                    'class' => 'car_form_seats',
                ],
            ])
            ->add('created', DateTimeType::class, [
                'data' => new \DateTime(),
                'label' => 'Véhicule ajouté le',
                'disabled' => true,
            ]);

        // Ajouter la contrainte de validation pour vérifier si l'utilisateur a déjà une voiture
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $car = $event->getData();

            $user = $car->getOwner();
            $existingCar = $this->entityManager->getRepository(Car::class)->findOneBy(['owner' => $user]);

            if ($existingCar && $existingCar !== $car) {
                $form->get('owner')->addError(new FormError('Un utilisateur ne peut avoir qu\'une seule voiture.'));
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}

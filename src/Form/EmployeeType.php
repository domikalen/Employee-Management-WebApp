<?php

namespace App\Form;

use App\Entity\Employee;
use App\Entity\Role;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'required' => true,
                'attr' => ['autocomplete' => 'off'],
            ])
            ->add('phone', TextType::class, [
                'label' => 'Phone',
                'required' => true,
                'attr' => ['autocomplete' => 'off'],
            ])
            ->add('email', TextType::class, [
                'label' => 'Email',
                'required' => true,
            ])
            ->add('roles', EntityType::class, [
                'class' => Role::class,
                'choice_label' => 'title',
                'label' => 'Roles',
                'multiple' => true,
                'expanded' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->where('r.isVisible = :visible')
                        ->setParameter('visible', true)
                        ->orderBy('r.title', 'ASC');
                },
                'attr' => [
                    'class' => 'roles-container'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'class' => 'large-textarea',
                    'autocomplete' => 'off'
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Profile Image',
                'required' => false,
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employee::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Wish;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class WishType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,['label' => 'Title',
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter a title',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Your title should be longer',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ])
            ]])
            ->add('description',TextType::class,['label' => 'Description',
            'constraints' => [
                new Length([
                    'min' => 6,
                    'minMessage' => 'Your description should be longer',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ])
            ]])
            ->add('author',TextType::class,['label' => 'Author',
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter an author',
                ]),
                new Length([
                    'min' => 2,
                    'minMessage' => 'Your author should have a longer name',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ])
            ]])
            ->add('category',EntityType::class,[
                'class' => Category::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u');
                },
                'choice_label' => 'name'
            ])
            ->add('Valider',SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Wish::class,
        ]);
    }
}

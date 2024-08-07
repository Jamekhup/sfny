<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Type;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Product Name'],
                'label' => false,
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Product should not be blank.']),
                    new Length([
                        'min' => 3,
                        'max' => 255,
                        'minMessage' => 'Product must be at least 3 characters long.',
                        'maxMessage' => 'Product cannot be longer than 255 characters.',
                    ]),
                    new Type([
                        'type' => 'string',
                        'message' => 'The value should be of type string.',
                    ]),
                ],
            ])
            ->add('price', NumberType::class, [
                'scale' => 2,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Price in SGD'],
                'label' => false,
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Price should not be blank.']),
                    new Length([
                        'min' => 1,
                        'max' => 11,
                        'minMessage' => 'Price must be at least 1 characters long.',
                        'maxMessage' => 'Price cannot be longer than 11 characters.',
                    ]),
                    new Type([
                        'type' => 'numeric',
                        'message' => 'The value should be of type numeric.',
                    ]),
                ],
            ])
            ->add('stock', NumberType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Stock Quantity'],
                'label' => false,
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Stock should not be blank.']),
                    new Length([
                        'min' => 1,
                        'max' => 11,
                        'minMessage' => 'Stock must be at least 1 characters long.',
                        'maxMessage' => 'Stock cannot be longer than 11 characters.',
                    ]),
                    new Type([
                        'type' => 'number',
                        'message' => 'The value should be of type number.',
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => 'Description', 'rows' => 10],
                'label' => false,
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Description should not be blank.']),
                    new Length([
                        'min' => 3,
                        'max' => 255,
                        'minMessage' => 'Description must be at least 3 characters long.',
                        'maxMessage' => 'Description cannot be longer than 255 characters.',
                    ]),
                    new Type([
                        'type' => 'string',
                        'message' => 'The value should be of type string.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}

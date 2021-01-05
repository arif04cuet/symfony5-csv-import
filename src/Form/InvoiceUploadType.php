<?php

namespace App\Form;

use App\Entity\InvoiceSourceFile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class InvoiceUploadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file_name', FileType::class, [
                'label' => 'Upload Csv File :',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => true,

                //unmapped fields can't define their validation using annotations
                //in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'text/x-comma-separated-values',
                            'text/comma-separated-values',
                            'text/x-csv',
                            'text/csv',
                            'text/plain',
                            'application/x-csv',
                            'application/csv'

                        ],
                        'mimeTypesMessage' => 'Please upload a valid csv file',
                    ])
                ],


                'attr' => ['accept' => '.csv'],

            ])
            ->add('save', SubmitType::class, ['label' => 'Submit', 'attr' => ['class' => 'btn btn-primary'],]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => InvoiceSourceFile::class,
        ]);
    }
}

<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PurchaseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        parent::buildForm($builder, $options); // TODO: Change the autogenerated stub
        $builder
            ->add('product')
            ->add('taxNumber')
            ->add('couponCode')
            ->add('paymentProcessor');
    }
}
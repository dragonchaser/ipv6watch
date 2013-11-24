<?php

namespace HSP\AdminBundle\Form\Type;

use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationFormType extends BaseType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    parent::buildForm($builder, $options);

    // add your custom field
    $builder->add('name');
  }

  public function getName()
  {
    return 'hsp_admin_user_registration';
  }
}

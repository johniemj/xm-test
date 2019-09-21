<?php 
namespace App\Form;

use App\Entity\Symbol;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInt;

class SymbolForm extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
    ->add('companySymbol', TextType::class, [
      'required' => true,
    ])
    ->add('startDate', TextType::class , [
      'attr' => ['class' => 'date'],
      'required' => true
    ])
    ->add('endDate',TextType::class , [
      'attr' => ['class' => 'date'],
      'required' => true
    ])
    ->add('email', EmailType::class, [
      'required' => true
    ])
    ->add('search', SubmitType::class, [
      'label' => 'Search'
    ])
    ->add('emailSend', CheckboxType::class, [
      'mapped' => false,
      'required' => false,
      'label' => 'Send email after search'
    ]);
  }

  public function configureOptions(OptionsResolver $resolver)
    {
      $resolver->setDefaults([
          'data_class' => Symbol::class,
      ]);
    }
}
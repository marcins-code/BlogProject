<?php

namespace App\Form;

use App\Entity\Category;
use App\Form\Type\SwitcherType;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class CategoryFormType extends AbstractType
{

    /**
     * @var CategoryRepository
     */
    private $repository;

    public function __construct(CategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $request = Request::createFromGlobals();
        $path = $request->getPathInfo();
        $pattern = '/^\/admin\/category\/edit\//';
        if (preg_match($pattern, $path)) {
            $cat_id = preg_replace($pattern, '', $path);
        } else {

            $cat_id = 0;
        }

        $builder
            ->add('category', TextType::class, [
                'required' => true,

            ])
            ->add('icon')
            ->add('description')
            ->add('isEnabled', SwitcherType::class,[
                'required'=>false,
            ] )
            ->add('parent', EntityType::class, [
                'class' => Category::class,
                'choices' => $this->repository->findOnlyMainCategories($cat_id),
                'placeholder' => 'choose',
                'required' => false
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'icon_before' => 'far fa-save',
                'attr' => ['class' => 'uk-button-dark uk-button ']
            ])
            ->add('save_exit', SubmitType::class, [
                'label' => 'Save and exit',
                'icon_before' => 'fas fa-sign-out-alt',
                'attr' => ['class' => 'uk-button-dark uk-button']
            ])
            ->add('save_new', SubmitType::class, [
                'label' => 'Save and new',
                'icon_before' => 'far fa-plus-square',
                'attr' => ['class' => 'uk-button-dark uk-button']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}


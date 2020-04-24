<?php

namespace App\Form;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('category', TextType::class,[
                'required'=>false,
            ])
            ->add('icon')
            ->add('description')
            ->add('isEnabled')
            ->add('parent', EntityType::class, [
                'class' => Category::class,
                'choices' => $this->repository->findOnlyMainCategories($cat_id),
                'placeholder' => 'choose',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use App\Form\Type\SwitcherType;
use App\Repository\CategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
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
        $builder
            ->add('title', TextType::class, [
                'required'=>false,
            ])
            ->add('category', EntityType::class, [
                'class'=>Category::class,
                'choices'=>$this->repository->findEnabledCategories(),
                'placeholder'=>'choose category',
                'required'=>false,
            ])
            ->add('content',null, [
                'attr'=>['class'=>'codemirror']
            ])
            ->add('isPublished', SwitcherType::class, [
                'required'=>false
            ])

            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'article' => 'article',
                    'lesson' => 'lesson',
                ],
            ])

            ->add('moviePath', null,[
                'required'=>false
            ])

            ->add('movies', TextType::class, [
                'required'=>false,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save',
                'icon_before' => 'far fa-save',
                'only_icon'=>true,
                'attr' => ['class' => 'uk-button-success']
            ])
            ->add('save_exit', SubmitType::class, [
                'label' => 'Save and exit',
                'icon_before' => 'fas fa-sign-out-alt',
                'attr' => ['class' => 'uk-button-info']
            ])
            ->add('save_new', SubmitType::class, [
                'label' => 'Save and new',
                'icon_before' => 'far fa-plus-square',
                'attr' => ['class' => 'uk-button-info']
            ]);

        $builder->get('movies')
            ->addModelTransformer(new CallbackTransformer(
                function ($tagsAsArray) {
                    // transform the array to a string
                    return implode(', ', $tagsAsArray);
                },
                function ($tagsAsString) {
                    // transform the string back to an array
                    return explode(', ', $tagsAsString);
                }
            ))
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}

<?php


namespace App\Form\Extension;


use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ButtonWithIconsExtension extends AbstractTypeExtension
{

    /**
     * Gets the extended types.
     *
     * @return iterable
     */
    public static function getExtendedTypes(): iterable
    {
        return [ButtonType::class];
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['icon_before'] = $options['icon_before'] ?? '';
        $view->vars['icon_after'] = $options['icon_after'] ?? '';
        $view->vars['only_icon'] = $options['only_icon'] ?? '';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'icon_before' => null,
            'icon_after' => null,
            'only_icon' => false,
        ]);
    }
}

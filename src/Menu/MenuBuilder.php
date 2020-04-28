<?php

namespace App\Menu;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Exception;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Knp\Menu\MenuItem;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class MenuBuilder implements ContainerAwareInterface
{

    use ContainerAwareTrait;

    private $menu;

    private $factory;

    private $repository;


    public function __construct(FactoryInterface $factory, CategoryRepository $repository)
    {
        $this->factory = $factory;
        $this->repository = $repository;
    }


    /**
     * @param FactoryInterface $factory
     * @param array $options
     *
     * @return ItemInterface
     */

    public function mainMenu(array $options)
    {
        $this->menu = $this->factory->createItem('root')->setChildrenAttributes(['class' => 'uk-nav-default uk-nav-parent-icon', 'uk-nav' => "multiple: false"]);
        $this->menu->addChild('Home', [
            'route' => 'homepage',
            'extras' => ['icon_before' => 'fas fa-house-damage'],
        ]);

        $pages = $this->repository->findEnabledCategories();

        try {
            $this->buildPageTree($pages);
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
        return $this->menu;
    }

    /**
     * @param array $options
     * @return ItemInterface
     */
    public function adminMenu(array $options)
    {
        $menu = $this->factory->createItem('root')->setChildrenAttributes(['class' => 'uk-nav-default', 'data-uk-nav' => '']);

        $menu->addChild('Articles', [
            'route' => 'category_index',
            'extras'=>['icon_before'=>'far fa-newspaper']
        ]);
        $menu->addChild('Categories', [
            'route' => 'category_index',
            'extras'=>['icon_before'=>'fas fa-stream']
            ]);


        return $menu;
    }

    /**
     *
     * @param array $pages
     * @param Category $parent
     * @param MenuItem $menuItem
     *
     * @throws Exception
     */
    private function buildPageTree(array $pages, $parent = null, $menuItem = null)
    {
        $categoriesRoute = 'categories';

        foreach ($pages as $page) {
            // If page doesn't have a parent, and no menuItem was passed then this is a top level add.
            if (empty($page->getParent()) && empty($menuItem))
                $parentMenu = $this->menu->addChild($page->getCategory(),
                    ['uri' => $page->getSlug(),
                        'extras' => ['icon_before' => $page->getIcon()],
                    ])
                    ->setLinkAttributes(['class' => 'uk-parent']);

            // if the current page's parent is === supplied parent, go deeper
            if ($page->getParent() === $parent) {
                // if a menuItem was given, then this page is a child so added it to the provided menu.
                if (!empty($menuItem))

                    $parentMenu = $menuItem->setChildrenAttributes(['class' => 'uk-nav-default uk-nav-sub uk-nav-parent-icon', 'uk-nav' => "multiple: false"])
                        ->setLabelAttribute('label', 'wefewf')
                        ->setAttributes(['class' => 'uk-parent', 'label' => 'wefwef'])
                        ->addChild($page->getCategory(),
                            ['uri' => $page->getSlug(),

                            ])->setLinkAttributes(['class' => count($page->getChildren()),]);

                $this->buildPageTree($pages, $page, $parentMenu);
            }
        }
    }



}

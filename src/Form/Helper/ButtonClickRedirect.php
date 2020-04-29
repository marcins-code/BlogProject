<?php


namespace App\Form\Helper;


//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ButtonClickRedirect extends AbstractController
{


    /**
     * @var Request
     */
//    private $request;
    /**
     * @var Response
     */
//    private $response;

//    public function __construct(Response $response)
//    {
//        $this->request = $request;
//        $this->response = $response;
//    }

    public   function redirectButtonsOnNewForm($form, $editRoute, $itemId)
    {
        if ($form->get('save')->isClicked()) {

            return $this->redirectToRoute($editRoute,['id'=>$itemId]);
//            return        $this->request->

        }
    }
}

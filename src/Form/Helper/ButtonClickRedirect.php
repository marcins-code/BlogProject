<?php


namespace App\Form\Helper;


class ButtonClickRedirect
{

    public function redirectOnClick($buttonName, $route, $paramsName=null, $paramValue=null)
    {
        if ($form->get($buttonName)->isClicked()) {

            return $this->redirectToRoute($route,[$paramsName=>$paramValue]);
        }
    }
}

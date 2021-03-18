<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/home", name="")
     */
    public function home()
    {
        $user = $this->getUser();

        if($user){
            return $this->render('default/home.html.twig');
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
}
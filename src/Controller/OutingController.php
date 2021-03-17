<?php

namespace App\Controller;

use App\Entity\Outing;
use App\Form\OutingFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OutingController extends AbstractController
{
    /**
     * @Route("/outing", name="outing")
     */
    public function index(): Response
    {
        return $this->render('outing/index.html.twig', [
            'controller_name' => 'OutingController',
        ]);
    }

    //Fonction qui ajoute une nouvelle sortie
    /**
     * @Route("/outing/add", name="new_outing")
     */
    public function add(EntityManagerInterface $em)
    {
        $outing = new Outing();
        $outingForm = $this->createForm(OutingFormType::class, $outing);

        return $this->render('outing/add.html.twig', [
            "outingForm" => $outingForm->createView()
        ]);
    }

}

<?php

namespace App\Controller;

use App\Entity\Outing;
use App\Entity\OutingSearch;
use App\Entity\State;
use App\Form\OutingCancelFormType;
use App\Form\OutingFormType;
use App\Form\OutingSearchType;
use Doctrine\ORM\EntityManager;
use App\Kernel;
use App\Repository\OutingRepository;
use App\Repository\StateRepository;
use Doctrine\DBAL\Exception\ConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OutingController extends AbstractController
{

    /**
     * @Route("/", name="home")
     * @throws \Doctrine\DBAL\Driver\Exception
     */
    public function list(EntityManagerInterface $em, Request $request, PaginatorInterface $paginator): Response
    {
        $outingSearch = new OutingSearch();
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $currentUser = $this->getUser();
        $userId = $currentUser->getId();

        $outingSearchForm = $this->createForm(OutingSearchType::class, $outingSearch);
        $outingSearchForm->handleRequest($request);

        $outingRepo = $this->getDoctrine()->getRepository(Outing::class);
        $outings = $paginator->paginate($outingRepo->findPublishedOutings($userId, $outingSearch),
        $request->query->getInt('page', 1),
        10);


        return $this->render("default/home.html.twig", [
            'outings' => $outings,
            'currentUser' => $currentUser,
            'outingSearch' => $outingSearchForm->createView(),
        ]);
    }

    //Fonction qui ajoute une nouvelle sortie
    /**
     * @Route("/outing/add", name="new_outing", methods={"GET"})
     */
    public function add(EntityManagerInterface $em)
    {
        $outing = new Outing();
        $outingForm = $this->createForm(OutingFormType::class, $outing);

        return $this->render('outing/add.html.twig', [
            "outingForm" => $outingForm->createView()
        ]);
    }

    /**
     * @Route("/outing/add", name="new_outing_create", methods={"POST"})
     */
    public function createOuting(Request $request, EntityManagerInterface $em)
    {
        $stateRepo = $this->getDoctrine()->getRepository(State::class);

        $user = $this->getUser();
        $outingRepo = $this->getDoctrine()->getRepository(Outing::class);
        $outing = new Outing();

        $outingForm = $this->createForm(OutingFormType::class, $outing);
        $outingForm->handleRequest($request);

        if($outingForm->isValid() && $outingForm->isSubmitted()){
            $today = new \DateTime();

            // Vérification des dates
            if($outingForm->get('dateHourStart')->getData() < $today){

                $this->addFlash('warning', 'La date de sortie n\'est pas valide.');
                return $this->redirectToRoute('new_outing');

            }elseif ($outingForm->get('deadlineRegistration')->getData() < $today ||
                $outingForm->get('deadlineRegistration')->getData() > $outingForm->get('dateHourStart')->getData()){

                $this->addFlash('warning', 'La date d\'inscription n\'est pas valide');
                return $this->redirectToRoute('new_outing');
            }

            if($outingForm->get('create')->isClicked()){
                $state = $stateRepo->find(1);
                $outing->setOUsers($user);
                $outing->setState($state);
                $em->persist($outing);
                $em->flush();
                $this->addFlash('success', 'La sortie a été créée.');
            } elseif($outingForm->get('publish')->isClicked()){
                $state = $stateRepo->find(2);
                $outing->setOUsers($user);
                $outing->setState($state);
                $em->persist($outing);
                $em->flush();
                $this->addFlash('success', 'La sortie a été publiée');
            }
        }
        return $this->redirectToRoute('new_outing');
    }



    /**
     * @Route("/outing/modify/{idOuting}", name="outing_modify", methods={"GET", "POST"})
     * @param Request $request
     * @param $idOuting
     * @param OutingRepository $outingRepo
     * @param StateRepository $stateRepo
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function modifyOuting(Request $request, $idOuting, OutingRepository $outingRepo, StateRepository $stateRepo,
                                    EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $outing = $outingRepo->find($idOuting);
        $currentUser = $this->getUser();


        if($currentUser->getId() != $outing->getOUsers()->getId()){
            $this->addFlash('warning', 'Vous n\'êtes pas l\'organisateur de cette sortie'  );
            return $this->redirectToRoute('home');

        }
        if($outing->getState()->getId() != 1){
            $this->addFlash('warning','Vous ne pouvez plus modifier la sortie');
            return $this->redirectToRoute('home');
        }
        $outingForm = $this->createForm(OutingFormType::class, $outing, array(
            'action' => 'http://localhost/projet_sortir/public/outing/modify/' . $idOuting
        ));

        $outingForm->handleRequest($request);

        if($outingForm->isSubmitted() && $outingForm->isValid() ){
            $today = new \DateTime();
            if ($outingForm->get('remove')->isClicked()){
                $em->remove($outing);
                $em->flush();
                $this->addFlash('success',  'La sortie a été supprimée');
                return $this->redirectToRoute('home');
            }
            // Vérification des dates
            if($outingForm->get('dateHourStart')->getData() < $today){

                $this->addFlash('warning', 'La date de sortie n\'est pas valide.');
                return $this->render('outing/modifyOuting.html.twig', [
                    'outingForm' => $outingForm->createView(),
                    'outing' => $outing
                ]);

            }elseif ($outingForm->get('deadlineRegistration')->getData() < $today ||
                $outingForm->get('deadlineRegistration')->getData() > $outingForm->get('dateHourStart')->getData()){

                $this->addFlash('warning', 'La date d\'inscription n\'est pas valide');
                return $this->render('outing/modifyOuting.html.twig', [
                    'outingForm' => $outingForm->createView(),
                    'outing' => $outing
                ]);
            }

            if($outingForm->get('modify')->isClicked()){
                $em->persist($outing);
                $em->flush();
                $this->addFlash('success', 'La sortie a été enregistrée');
            }elseif ($outingForm->get('publish')->isClicked()){
                $state = $stateRepo->find(2);
                $outing->setState($state);
                $em->persist($outing);
                $em->flush();
                $this->addFlash('success', 'La sortie a été publiée');
            }

            return $this->redirectToRoute('home');
        }
        return $this->render('outing/modifyOuting.html.twig', [
            'outingForm' => $outingForm->createView(),
            'outing' => $outing
        ]);
    }

    /**
     * @Route("/outing/cancel/{idOuting}", name="outing_cancel")
     * @param Request $request
     * @param $idOuting
     * @param EntityManagerInterface $em
     * @param OutingRepository $outingRepo
     * @return Response
     */
    public function cancel(Request $request, $idOuting, EntityManagerInterface $em, OutingRepository $outingRepo, StateRepository $stateRepo)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $outing = $outingRepo->find($idOuting);

        if($outing->getState()->getId() != 2){
            $this->addFlash('warning', 'Vous ne pouvez pas annuler cette sortie');
            return $this->redirectToRoute('home');
        }

        $outingCancelForm = $this->createForm(OutingCancelFormType::class, $outing, array(
            'action' => 'http://localhost/projet_sortir/public/outing/cancel/' . $idOuting
        ));

        $outingCancelForm->handleRequest($request);

        if($outingCancelForm->isSubmitted() && $outingCancelForm->isValid() ){
            $outing = $outingRepo->find($idOuting);
            $state = $stateRepo->find('6');
            $outing->setState($state);
            $cancelMotive = $outingCancelForm->get('cancelMotive')->getData();
            $outing->setCancelMotive($cancelMotive);
            $outing->unregisterAllUsers();

            $em->persist($outing);
            $em->flush();

            $this->addFlash('success', 'Votre sortie a été annulée');
            return $this->redirectToRoute('home');
        }

        return $this->render('outing/cancelOuting.html.twig', [
           'outingCancelForm' => $outingCancelForm->createView(),
            'outing' => $outing
        ]);
    }
}

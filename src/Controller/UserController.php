<?php


namespace App\Controller;


use App\Entity\Image;
use App\Entity\Outing;
use App\Entity\User;
use App\Form\UserProfileFormType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/myprofile", name="user_myprofile", methods={"GET"})
     */
    public function myProfile(): \Symfony\Component\HttpFoundation\Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $userRepo = $this->getDoctrine()->getRepository(User::class);

        $user = $this->getUser();
        $form = $this->createForm(UserProfileFormType::class, $user);

        $user = $userRepo->find($this->getUser());

        return $this->render('user/myprofile.html.twig', [
            'userForm' => $form->createView(),
            'user' => $user,
        ]);
    }

    /**
     * @Route("/myprofile", name="user_myprofile_modify", methods={"POST"})
     * @throws UniqueConstraintViolationException
     */
    public function modify(Request $request, EntityManagerInterface $em,
                           UserPasswordEncoderInterface $encoder)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepo->find($this->getUser());

        $userForm = $this->createForm(UserProfileFormType::class, $user);
        $userForm->handleRequest($request);

        $password = $userForm->get("password")->getData();


        if ($userForm->isValid() && $userForm->isSubmitted()) {

            $image = new Image();
            $imageFile = $userForm->get('photo')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName() . '.' . $imageFile->guessExtension(), PATHINFO_FILENAME);

                $imageFile->move(
                    $this->getParameter('image_directory'),
                    $originalFilename
                );
                $image->setImageFileName($originalFilename);
                $user->setImage($image);
                $em->persist($image);
            }

            $hashed = $encoder->encodePassword($user, $password);
            $user->setPassword($hashed);
            $em->persist($user);
            try {
                $em->flush();

                $this->addFlash('notice', 'Profil mis à jour');

            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('error', 'Le pseudo ou l\'e-mail sont déjà utilisés.');

            } finally {

                return $this->render('user/myprofile.html.twig', [
                    'user' => $user,
                    'userForm' => $userForm->createView()
                ]);
            }

        }


        return $this->render('user/myprofile.html.twig', [
            'userForm' => $userForm->createView(),
            'user' => $user,
        ]);
    }

    //FONCTION qui retourne img_profile  les données utilisateur

    /**
     * @Route ("/{id}", name="user_infos",
     *     requirements={"id": "\d+"}, methods={"GET"})
     */
    public function infos($id, Request $request)
    {
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepo->find($id);

        return $this->render('user/infos.html.twig', ["user" => $user]);
    }

    /**
     * @Route("/register/{idOuting}", name="user_register_outing")
     * @param $idOuting
     * @return Response
     */
    public function register($idOuting, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $currentUser = $this->getUser();
        $today = new \DateTime();

        $outingRepo = $this->getDoctrine()->getRepository(Outing::class);

        $currentOuting = $outingRepo->find($idOuting);

        if ($currentUser->getId() == $currentOuting->getOUsers()->getId()) {
            $this->addFlash('warning', 'Vous êtes l\'organisateur de cette sortie, vous êtes déjà inscrit.');
            return $this->redirectToRoute('home');
        }

        if ($currentOuting->getDeadlineRegistration() < $today) {
            $this->addFlash('warning', 'Incription impossible : Les inscriptions sont cloturées.');
            return $this->redirectToRoute('home');
        }
        try {
            $currentOuting->addRegisteredUser($currentUser);
            $em->persist($currentOuting);
            $em->flush();

        } catch (UniqueConstraintViolationException $e) {
            $this->addFlash('warning', 'Vous êtes déjà inscrit à la sortie ');
            return $this->redirectToRoute('home');
        }


        $this->addFlash('success', 'Vous êtes inscrit à la sortie');
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/unregister/{idOuting}", name="user_unregister_outing")
     * @param $idOuting
     * @param EntityManagerInterface $em
     */
    public function unregister($idOuting, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $currentUser = $this->getUser();

        $outingRepo = $this->getDoctrine()->getRepository(Outing::class);
        $currentOuting = $outingRepo->find($idOuting);
        $currentOuting->unregisterUser($currentUser);

        $em->persist($currentOuting);
        $em->flush();


        $this->addFlash('success', 'Vous êtes désinscrit de la sortie');
        return $this->redirectToRoute('home');
    }
}
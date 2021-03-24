<?php


namespace App\Controller;


use App\Entity\Image;
use App\Entity\Outing;
use App\Entity\User;
use App\Form\UserProfileFormType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntityValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Valid;
use Symfony\Component\Validator\Validator\ValidatorInterface;


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
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function modify(Request $request, EntityManagerInterface $em, ValidatorInterface $validator, UserPasswordEncoderInterface $encoderService)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $user = $this->getUser();

        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepo->find($user->getId());

        $userForm = $this->createForm(UserProfileFormType::class, $user);
        $userForm->handleRequest($request);

        $password = $userForm->get("password")->getData();

        $match = $encoderService->isPasswordValid($user, $password);


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

            if ($match) {
                if ($userForm->get('changePassword')->getData()) {
                    $hashed = $encoderService->encodePassword($user, $userForm->get('changePassword')->getData());
                    $user->setPassword($hashed);
                }
            } else {
                $this->addFlash('error', 'Mot de passe incorrect.');
                return $this->redirectToRoute('user_myprofile');
            }

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
    public function infos($id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');

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

        if ($currentOuting->getState()->getId() != 2) {
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
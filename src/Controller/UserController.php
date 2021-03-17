<?php


namespace App\Controller;


use App\Entity\User;
use App\Form\UserProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Forms;
use Symfony\Component\HttpFoundation\Request;
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
        $user = $this->getUser();
        $form = $this->createForm(UserProfileFormType::class, $user);

        return $this->render('user/myprofile.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/myprofile", name="user_myprofile_modify", methods={"POST"})
     */
    public function modify(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $user = $userRepo->find($this->getUser());

        $userForm = $this->createForm(UserProfileFormType::class, $user);
        $userForm->handleRequest($request);

        $password = $userForm->get("password")->getData();
        $confirmation = $request->request->get("confirmation");

        if ($userForm->isValid() && $userForm->isSubmitted()) {
            $hashed = $encoder->encodePassword($user, $password);
            $user->setPassword($hashed);
            $em->persist($user);
            $em->flush();

            $this->addFlash('notice', 'Profil mis à jour');
            return $this->redirectToRoute('user_myprofile');
        }


        return $this->render('user/myprofile.html.twig', [
            'userForm' => $userForm->createView(),
        ]);
    }

    //FONCTION qui retourne les données utilisateur
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
}
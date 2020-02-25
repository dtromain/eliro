<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ProfileFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @return RedirectResponse|Response
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $username = $request->get('username');

        $participant = $this->getDoctrine()->getRepository(Participant::class)->findOneBy(['username'=>$username]);

        if($participant == null){
            $participant = $this->getUser();
        }

        if($participant == $this->getUser()) {

            $form = $this->createForm(ProfileFormType::class, $participant);
            $form->handleRequest($request);

            $old_password = $form->get('old_password')->getData();
            if($old_password && !$encoder->isPasswordValid($participant, $old_password)) {
                $form->get('old_password')->addError(new FormError('Le mot de passe est invalide.'));
            }

            if ($form->isSubmitted() && $form->isValid()) {
                $plain_password = $form->get('plain_password')->getData();

                if($plain_password && $old_password) {
                    $encoded_password = $encoder->encodePassword($participant, $plain_password);
                    $participant->setPassword($encoded_password);
                }

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($participant);
                $entityManager->flush();
                return $this->redirectToRoute('profile');
            }

            return $this->render('profile/index.html.twig', [
                'form' => $form->createView()
            ]);

        } else {

            return $this->render('profile/profile.html.twig', [
                'participant' => $participant
            ]);
        }


    }
}

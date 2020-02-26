<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ProfileFormType;
use App\Form\RegisterFormType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
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
     * @Route("/profile/{username?}", name="profile")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface $em
     * @param ParticipantRepository $pr
     * @param string $username
     * @return RedirectResponse|Response
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em, ParticipantRepository $pr, string $username = null) {
        $participant = $pr->findOneBy(['username'=>$username]);
        if($participant == null){
            $participant = $this->getUser();
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
                $em->persist($participant);
                $em->flush();
                return $this->redirectToRoute('profile');
            }
            return $this->render('profile/index.html.twig', [
                'form' => $form->createView()
            ]);
        }
        return $this->render('profile/profile.html.twig', [
            'participant' => $participant
        ]);
    }

    /**
     * @Route("register", name="register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em) {
        $participant = new Participant();
        $form = $this->createForm(RegisterFormType::class, $participant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $plain_password = $form->get('password')->getData();
            $encoded_password = $encoder->encodePassword($participant, $plain_password);
            $participant->setPassword($encoded_password);
            $em->persist($participant);
            $em->flush();
        }
        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}

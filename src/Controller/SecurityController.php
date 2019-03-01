<?php

namespace App\Controller;

use App\Entity\Agent;
use App\Form\RegisterType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController 
{

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $utils)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('homepage');
        }

        $username = $utils->getLastUsername();
        $error = $utils->getLastAuthenticationError();
        return $this->render('security/login.html.twig', [
            'username' => $username,
            'error' => $error
        ]);
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        if ($this->getUser())
        {
            return $this->redirectToRoute('homepage');
        }

        $agent = new Agent();
        $form = $this->createForm(RegisterType::class, $agent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $hash = $encoder->encodePassword($agent, $agent->getPassword());
            $agent->setPassword($hash);
            $manager->persist($agent);
            $manager->flush();

            $this->addFlash('success', 'Register Successfully..');

            return $this->redirectToRoute('login');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        
    }
}
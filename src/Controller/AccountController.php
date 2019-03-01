<?php

namespace App\Controller;

use App\Form\EditAgentType;
use App\Form\SocialMediaType;
use App\Entity\UpdatePassword;
use App\Form\UpdatePasswordType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="account")
     * @IsGranted("ROLE_USER")
     */
    public function myAgent()
    {
        $agent = $this->getUser();
        return $this->render('account/myAgent.html.twig', [
            'agent' => $agent
        ]);
    }

    /**
     * @Route("/account/edit", name="account_edit")
     * @IsGranted("ROLE_USER")
     */
    public function edit(Request $request, ObjectManager $manager)
    {
        $agent = $this->getUser();
        $form = $this->createForm(EditAgentType::class, $agent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $manager->persist($agent);
            $manager->flush();

            $this->addFlash('success', 'Edit Agent Successfully..');
            return $this->redirectToRoute('account');

        }

        return $this->render('account/editAgent.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/account/social", name="account_social")
     * @IsGranted("ROLE_USER")
     */
    public function socialMedia(Request $request, ObjectManager $manager)
    {
        $agent = $this->getUser();
        $form = $this->createForm(SocialMediaType::class, $agent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($agent);
            $manager->flush();

            $this->addFlash('success', 'Social Media Agent Edit Successfully..');
            return $this->redirectToRoute('account');

        }

        return $this->render('account/socialMediaAgent.html.twig', [
            'form' => $form->createView()
        ]);       
    }

    /**
     * @Route("/account/password", name="account_password")
     * @IsGranted("ROLE_USER")
     */
    public function updatePassword(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $agent = $this->getUser();
        $updatePassword = new UpdatePassword();
        $form = $this->createForm(UpdatePasswordType::class, $updatePassword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            if (!password_verify($updatePassword->getOldPassword(), $agent->getPassword())) {
                $form->get('oldPassword')->addError(new FormError('Current password is incorrect'));
            } else {

                $hash = $encoder->encodePassword($agent, $updatePassword->getNewPassword());
                $agent->setPassword($hash);
                $manager->persist($agent);
                $manager->flush();

                $this->addFlash('success', 'Password Edit Successfully...');
                return $this->redirectToRoute('account');

            }
        }

        return $this->render('account/updatePassword.html.twig', [
            'form' => $form->createView()
        ]);

    }

}

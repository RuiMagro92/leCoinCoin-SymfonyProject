<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        
            $form = $this->createFormBuilder()
                ->add('username')
                ->add('email', EmailType::class)
                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'required' => true,
                    'first_options' => ['label' => 'Password'],
                    'second_options' => ['label' => 'Confirm Password']
                ])
                ->add('register', SubmitType::class, [
                    'attr' => [
                        'class' => 'btn btn-success float-left'
                    ]
                ])
                ->getForm()

            ;
            $form->handleRequest($request);

            if ($form->isSubmitted()) {
                $data = $form->getData();
                $user = new User;
                $user->setUsername($data['username']);
                $user->setEmail($data['email']);
                $user->setPassword(
                    $passwordEncoder->encodePassword($user, $data['password'])
                );
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                return $this->redirect($this->generateUrl('app_login'));

            }



            return $this->render('registration/index.html.twig', [
                'form' => $form->createView()    
            ]);
    }
}

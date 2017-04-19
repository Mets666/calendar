<?php


namespace AppBundle\Controller;

use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends DefaultController
{
    /**
     * @Route("/register", name="registration")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if($form->isValid()) {
                try {
                    $password = $this->get('security.password_encoder')
                        ->encodePassword($user, $form["password"]->getData());
                    $user->setPassword($password);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();
                } catch (\Exception $e) {
                    $this->addFlash(
                        'error',
                        'Unable to register account!'
                    );
                    return $this->redirectToRoute('time_log');
                }
                $this->addFlash(
                    'success',
                    'Account successfully registered!'
                );

                return $this->redirectToRoute('login');
            }
        }

        return $this->render(
            'registration/register.html.twig',
            array('form' => $form->createView())
        );
    }
}
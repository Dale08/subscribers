<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 29.03.2019
 * Time: 23:41
 */

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     * @Template()
     */
    public function index(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return [
            'last_username' => $lastUsername,
            'error' => $error
        ];
    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout()
    {
        return $this->redirectToRoute('app_homepage');
    }
}
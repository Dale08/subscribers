<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 31.03.2019
 * Time: 11:35
 */

namespace App\Controller\Cabinet;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SubscribeController extends AbstractController
{
    /**
     * @Route("/cabinet/ajax/add-subscription", name="ajax_add_subscription", methods={"GET"})
     */
    public function ajaxSetSubscriptionAction(Request $request, \Swift_Mailer $mailer)
    {
        if(!$request->isXmlHttpRequest()){
            return new RedirectResponse($this->generateUrl('app_homepage'));
        }
        $response = ['success' => 0];
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $id = $request->get('sub_id');
        $sub_user = $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        if(!$sub_user){
            $response['message'] = 'Subscription user not exists';
            return new JsonResponse($response, 200);
        }
        if($user->getSubscriptions()->add($sub_user)){
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            dump($mailer);

            $message = (new \Swift_Message('Subscription'))
                ->setFrom('subscription@subscription.local')
                ->setTo($sub_user->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/subscription.html.twig',
                        ['email' => $user->getEmail()]
                    ),
                    'text/html'
                );

            $mailer->send($message);

            $response['link'] = '<a class="btn btn-danger subscribe" href="'.$this->generateUrl('ajax_remove_subscription').'" data-user-id="'.$id.'" role="button">Unsubscribe</a>';
            $response['message'] = 'User added successful';
            $response['success'] = 1;
            return new JsonResponse($response, 200);
        } else {
            $response['message'] = 'Can\'t add this user';
        }

        return new JsonResponse($response, 200);

    }

    /**
     * @Route("/cabinet/ajax/remove-subscription", name="ajax_remove_subscription", methods={"GET"})
     */
    public function ajaxRemoveSubscriptionAction(Request $request)
    {
        if(!$request->isXmlHttpRequest()){
            return new RedirectResponse($this->generateUrl('app_homepage'));
        }
        $response = ['success' => 0];
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $id = $request->get('sub_id');
        $sub_user = $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        if(!$sub_user){
            $response['message'] = 'Subscription user not exists';
            return new JsonResponse($response, 200);
        }
        if($user->getSubscriptions()->removeElement($sub_user)){
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $response['link'] = '<a class="btn btn-success subscribe" href="'.$this->generateUrl('ajax_add_subscription').'" data-user-id="'.$id.'" role="button">Subscribe</a>';
            $response['message'] = 'User removed successful';
            $response['success'] = 1;
            return new JsonResponse($response, 200);
        } else {
            $response['message'] = 'Can\'t add this user';
        }

        return new JsonResponse($response, 200);

    }

}
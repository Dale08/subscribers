<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 31.03.2019
 * Time: 11:35
 */

namespace App\Controller\Cabinet;


use App\Entity\User;
use App\Services\UserNotifications;
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
    public function ajaxSetSubscription(Request $request, UserNotifications $notifications)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if(!$request->isXmlHttpRequest()){
            return new RedirectResponse($this->generateUrl('app_homepage'));
        }
        $response = ['success' => 0];
        $user = $this->getUser();
        $id = $request->get('sub_id');
        $sub_user = $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        if(!$sub_user){
            $response['message'] = 'Subscription user not exists';
            return new JsonResponse($response, 404);
        }
        if($user->getSubscriptions()->add($sub_user)){
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $notifications->sendSubscriptionEmail('subscription@subscription.local', $sub_user->getEmail(), 'emails/subscription.html.twig', $user->getEmail());

            $response['link'] = $this->generateUrl('ajax_remove_subscription');
            $response['text'] = 'Unsubscribe';
            $response['message'] = 'User added successful';
            $response['success'] = 1;
            return new JsonResponse($response, 200);
        } else {
            $response['message'] = 'Can\'t add this user';
        }

        return new JsonResponse($response, 400);

    }

    /**
     * @Route("/cabinet/ajax/remove-subscription", name="ajax_remove_subscription", methods={"GET"})
     */
    public function ajaxRemoveSubscription(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        if(!$request->isXmlHttpRequest()){
            return new RedirectResponse($this->generateUrl('app_homepage'));
        }
        $response = ['success' => 0];
        $user = $this->getUser();
        $id = $request->get('sub_id');
        $sub_user = $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        if(!$sub_user){
            $response['message'] = 'Subscription user not exists';
            return new JsonResponse($response, 404);
        }
        if($user->getSubscriptions()->removeElement($sub_user)){
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $response['link'] = $this->generateUrl('ajax_add_subscription');
            $response['text'] = 'Subscribe';
            $response['message'] = 'User removed successful';
            $response['success'] = 1;
            return new JsonResponse($response, 200);
        } else {
            $response['message'] = 'Can\'t add this user';
        }

        return new JsonResponse($response, 400);

    }

}
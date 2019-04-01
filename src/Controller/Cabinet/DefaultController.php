<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 30.03.2019
 * Time: 12:01
 */

namespace App\Controller\Cabinet;

use App\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Post;
use App\Entity\User;

/**
 * @Route("/cabinet", name="app_cabinet")
 * @Template()
 */

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="_main")
     * @Template()
     */
    public function indexAction()
    {
        dump($this->container);
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $subns = $user->getSubscriptions()->map(function($obj){return $obj->getId();})->getValues();
        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findBy( array('author' => $subns), array('id' => 'DESC') );
        return [
            'posts' =>  $posts
        ];
    }

    /**
     * @Route("/posts", name="_posts")
     * @Template()
     */
    public function posts()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $posts = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findBy(['author' => $user]);
        return [
            'posts'  =>  $posts,
        ];
    }

    /**
     * @Route("/post/add", name="_post_add")
     * @Template()
     */
    public function postAdd(Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $post = new Post();
        $post->setAuthor($user);
        $form = $this->createForm(PostType::class, $post);
        $form->add('submit', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($post);
            $em->flush();

            $this->addFlash('success', 'Post Saved');

            return $this->redirectToRoute('app_cabinet_posts');
        }

        return [
            'form'  =>  $form->createView(),
        ];
    }

    /**
     * @Route("/post/{id}/edit", name="_post_edit")
     * @Template()
     */
    public function postEdit($id, Request $request)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $post = $this
            ->getDoctrine()
            ->getRepository(Post::class)
            ->find($id);

        if(!$post){
            throw $this->createNotFoundException('Post Not Found');
        }
        $form = $this->createForm(PostType::class, $post);
        $form->add('submit', SubmitType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($post);
            $em->flush();

            $this->addFlash('success', 'Post Saved');

            return $this->redirectToRoute('app_cabinet_posts');
        }

        return [
            'form'  =>  $form->createView(),
        ];
    }

    /**
     * @Route("/users", name="_users")
     * @Template()
     */
    public function users()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $subns = $user->getSubscriptions()->map(function($obj){return $obj->getId();})->getValues();
        $users = $this
            ->getDoctrine()
            ->getRepository(User::class)
            ->findOtherUsers($user);
        return [
            'users' =>  $users,
            'subns'  =>  $subns,
            ];
    }

}
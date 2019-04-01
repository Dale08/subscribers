<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 01.04.2019
 * Time: 14:07
 */

namespace App\Services;


class UserNotifications
{

    private $mailer;

    private $twig;

    /**
     * UserNotifications constructor.
     * @param $mailer
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }


    public function sendSubscriptionEmail($from, $to, $tpl, $name)
    {

        $message = (new \Swift_Message('Subscription'))
            ->setFrom($from)
            ->setTo($to)
            ->setBody(
                $this->twig->render(
                    $tpl,
                    ['email' => $name]
                ),
                'text/html'
            );

        $this->mailer->send($message);
    }
}
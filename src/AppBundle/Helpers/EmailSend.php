<?php

namespace AppBundle\Helpers;

use Symfony\Component\DependencyInjection\ContainerInterface;

class EmailSend
{

    protected $container;
    protected $mailer;

    /**
     * EmailSend constructor.
     * @param \Swift_Mailer $mailer
     * @param ContainerInterface $container
     */
    public function __construct(\Swift_Mailer $mailer, ContainerInterface $container)
    {
        $this->container = $container;
        $this->mailer = $mailer;
    }

    /**
     * @param string|array $from
     * @param null $nameFrom
     * @param string $to
     * @param string $subject
     * @param string $template
     * @param array $datas
     * @return bool
     * @throws \Twig\Error\Error
     */
    public function sendMail($from, $to, $subject, $template, $datas, $nameFrom = null)
    {
        $engine = $this->container->get('templating');
        $renderMailHtml = $engine->render($template . '.html.twig', $datas);
        $renderMailTxt = $engine->render($template . '.txt.twig', $datas);

        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from, $nameFrom)
            ->setTo($to)
            ->setBody($renderMailHtml, 'text/html')
            ->addPart($renderMailTxt, 'text/plain');

        $result = $this->mailer->send($message);

        if ($result) {
            return true;
        }

        return false;
    }

}

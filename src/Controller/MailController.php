<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\MailRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Carbon\Carbon;

class MailController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route("/mail", name="mail")
     */
    public function index(MailRepository $mailList): Response
    {

        $list = $mailList->findAll();
        
        foreach ($list as $key) {
            if (Carbon::now()->greaterThanOrEqualTo($key->getDate())) {
                $entityManager = $this->getDoctrine()->getManager();

                $this->SendMail($key->getUserID());

                $entityManager->remove($key);
                $entityManager->flush();
        
            }
        }

        return $this->json([
            'status' => "ok"
        ]);
    }


    private function SendMail($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        $transport = Transport::fromDsn('smtp://2f68f5cf6cbd19:a98e318915f5c6@smtp.mailtrap.io:2525?encryption=tls&auth_mode=login');
        $mailer = new Mailer($transport);

        $email = (new Email())
        ->from('hello@example.com')
        ->to($user->getEmail())
        ->subject('Welcome!')
        ->text('Welcom to example.com!');

        $mailer->send($email);
        
        return;
    }

    /**
     * @Route("/maillist", name="mail_list")
     */
    public function maillist(MailRepository $mailList): Response
    {        
        return $this->json([
            'users' => $mailList->findAll()
        ]);
    }

}

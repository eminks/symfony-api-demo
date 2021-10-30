<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Mail;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Carbon\Carbon;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_all", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->json([
            'users' => $userRepository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"POST"})
     */
    public function new(Request $request, ValidatorInterface $validator): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
            
        $user = new User();
        $user->setUsername($request->get('name'));
        $user->setTc($request->get('tc'));
        $user->setAddress($request->get('address'));
        $user->setPhoneNumber($request->get('phoneNumber'));
        $user->setEmail($request->get('email'));

        $errors = $validator->validate($user);
    
        if (count($errors) > 0) {
            return $this->json($this->violationsJson($errors));
        }
            
        $entityManager->persist($user);
        $entityManager->flush();

        $this->SendMail($user->getId());

        return $this->json([
            'status' => "ok"
        ]);

    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->json([
            'user' => $user
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"PUT"})
     */
    public function edit(Request $request, ValidatorInterface $validator, int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find(['id' => $id]);

        if (!$user) {
            return $this->json([
                "errors" => "Not Found"
            ]);
        }
 
        $user->setUsername($request->get('name'));
        $user->setTc($request->get('tc'));
        $user->setAddress($request->get('address'));
        $user->setPhoneNumber($request->get('phoneNumber'));
        $user->setEmail($request->get('email'));

        $errors = $validator->validate($user);

        if (count($errors) > 0) {
            return $this->json($this->violationsJson($errors));
        }
        
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json([
            'status' => "ok"
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(int $id): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->find(['id' => $id]);
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json([
            'status' => "ok"
        ]);
    }

    private function SendMail($id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $mail = new Mail();
        $mail->setUserID($id);
        $mail->setDate(Carbon::now()->add(1, 'day'));

        $entityManager->persist($mail);
        $entityManager->flush();

        return;
    }

    public function violationsJson($violationsList)
    {
        $errorList = [];

        foreach ($violationsList as $violation) {
            $errorList[] = array($violation->getPropertyPath() => $violation->getMessage());
        }

        return array(
            "errors" => $errorList
        );
    }
}
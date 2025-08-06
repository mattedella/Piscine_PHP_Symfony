<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use App\Entity\UserORMForm;

final class Ex03Controller extends AbstractController
{
    #[Route('/ex03/create_table', name: 'ex03_create_table')]
    public function createTable(EntityManagerInterface $em): Response
    {
        $schemaTool = new SchemaTool($em);
        $metadata = [$em->getClassMetadata(UserORMForm::class)];

        try {
            // Check if table exists first to avoid error
            $conn = $em->getConnection();
            $sm = method_exists($conn, 'createSchemaManager') ? $conn->createSchemaManager() : $conn->getSchemaManager();
            $tableName = $metadata[0]->getTableName();

            if ($sm->tablesExist([$tableName])) {
                $message = "ℹ️ Table '$tableName' already exists. No action taken.";
            } else {
                // Create only your entity table
                $schemaTool->createSchema($metadata);
                $message = "✅ Table '$tableName' created successfully.";
            }
        } catch (\Exception $e) {
            $message = '❌ Failed to create table: ' . $e->getMessage();
        }

        return new Response($message);
    }

    #[Route('/ex03/form', name: 'ex03_form')]
     public function showForm(): Response
    {
        return $this->render('ex03/form.html.twig');
    }

    #[Route('/ex03/submit', name: 'ex03_submit', methods: ['POST'])]
    public function handleForm(Request $request, EntityManagerInterface $em): Response
    {
        $username = $request->request->get('username');
        $email = $request->request->get('email');

        // Check if user already exists
        $existing = $em->getRepository(UserOrmForm::class)->findOneBy([
            'username' => $username,
        ]) ?? $em->getRepository(UserOrmForm::class)->findOneBy([
            'email' => $email,
        ]);

        if ($existing) {
            $message = '⚠️ User with same username or email already exists.';
        } else {
            $user = new UserOrmForm();
            $user->setUsername($username);
            $user->setName($request->request->get('name'));
            $user->setEmail($email);
            $user->setEnable($request->request->has('enable'));
            $user->setBirthdate(new \DateTime($request->request->get('birthdate')));
            $user->setAddress($request->request->get('address'));

            $em->persist($user);
            $em->flush();

            $message = '✅ User inserted successfully.';
        }

        return new Response($message . '<br><a href="' . $this->generateUrl('ex03_form') . '">Back to form</a>');
    }

    #[Route('/ex03/show', name: 'ex03_show')]
    public function showUsers(EntityManagerInterface $em): Response
    {
        $users = $em->getRepository(UserOrmForm::class)->findAll();

        return $this->render('ex03/users.html.twig', [
            'users' => $users,
        ]);
    }
}

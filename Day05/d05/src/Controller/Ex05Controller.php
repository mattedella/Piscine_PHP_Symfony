<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use App\Entity\UserORMDelete;

final class Ex05Controller extends AbstractController
{
    #[Route('/ex05/create_table', name: 'ex05_create_table')]
    public function createTable(EntityManagerInterface $em): Response
    {
        $conn = $em->getConnection();
        $schemaTool = new SchemaTool($em);
        $metadata = $em->getClassMetadata(UserORMDelete::class);
        
        $tableName = $metadata->getTableName();
        $schemaManager = $conn->createSchemaManager();
        
        try {
            if (!$schemaManager->tablesExist([$tableName])) {
                $schemaTool->createSchema([$metadata]);
                $message = "✅ Table '{$tableName}' created.";
            } else {
                $message = "ℹ️ Table '{$tableName}' already exists.";
            }
        } catch (\Exception $e) {
            $message = '❌ Error: ' . $e->getMessage();
        }
    
        return new Response($message);
    }

    #[Route('/ex05/form', name: 'ex05_form')]
     public function showForm(): Response
    {
        return $this->render('ex05/form.html.twig');
    }

    #[Route('/ex05/submit', name: 'ex05_submit', methods: ['POST'])]
    public function handleForm(Request $request, EntityManagerInterface $em): Response
    {
        $username = $request->request->get('username');
        $email = $request->request->get('email');

        // Check if user already exists
        $existing = $em->getRepository(UserOrmDelete::class)->findOneBy([
            'username' => $username,
        ]) ?? $em->getRepository(UserOrmDelete::class)->findOneBy([
            'email' => $email,
        ]);

        if ($existing) {
            $message = '⚠️ User with same username or email already exists.';
        } else {
            $user = new UserOrmDelete();
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

        return new Response($message . '<br><a href="' . $this->generateUrl('ex05_form') . '">Back to form</a>');
    }

    #[Route('/ex05/show', name: 'ex05_show')]
    public function show(EntityManagerInterface $em): Response
    {
        $users = $em->getRepository(UserORMDelete::class)->findAll();
        $message = $_GET['message'] ?? null;

        return $this->render('ex05/users.html.twig', [
            'users' => $users,
            'message' => $message,
        ]);
    }

    #[Route('/ex05/delete/{id}', name: 'ex05_delete')]
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(UserORMDelete::class)->find($id);

        if (!$user) {
            $message = '❌ User not found.';
        } else {
            $em->remove($user);
            $em->flush();
            $message = '✅ User deleted successfully.';
        }

        return $this->redirectToRoute('ex05_show', ['message' => $message]);
    }
}

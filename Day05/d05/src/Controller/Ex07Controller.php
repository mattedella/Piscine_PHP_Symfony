<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use App\Entity\UserORMUpdate;

final class Ex07Controller extends AbstractController
{
    #[Route('/ex07/create_table', name: 'ex07_create_table')]
    public function createTable(EntityManagerInterface $em): Response
    {
        $conn = $em->getConnection();
        $schemaTool = new SchemaTool($em);
        $metadata = $em->getClassMetadata(UserORMUpdate::class);
        
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

    #[Route('/ex07/form', name: 'ex07_form')]
     public function showForm(): Response
    {
        return $this->render('ex07/form.html.twig');
    }

    #[Route('/ex07/submit', name: 'ex07_submit', methods: ['POST'])]
    public function handleForm(Request $request, EntityManagerInterface $em): Response
    {
        $username = $request->request->get('username');
        $email = $request->request->get('email');

        // Check if user already exists
        $existing = $em->getRepository(UserOrmUpdate::class)->findOneBy([
            'username' => $username,
        ]) ?? $em->getRepository(UserOrmUpdate::class)->findOneBy([
            'email' => $email,
        ]);

        if ($existing) {
            $message = '⚠️ User with same username or email already exists.';
        } else {
            $user = new UserOrmUpdate();
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

        return new Response($message . '<br><a href="' . $this->generateUrl('ex07_form') . '">Back to form</a>');
    }

    #[Route('/ex07/show', name: 'ex07_show')]
    public function show(EntityManagerInterface $em): Response
    {
        $users = $em->getRepository(UserORMUpdate::class)->findAll();
        $message = $_GET['message'] ?? null;

        return $this->render('ex07/users.html.twig', [
            'users' => $users,
            'message' => $message,
        ]);
    }

    #[Route('/ex07/edit/{id}', name: 'ex07_edit')]
    public function editForm(UserORMUpdate $user): Response
    {
        return $this->render('ex07/edit.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/ex07/update/{id}', name: 'ex07_update', methods: ['POST'])]
    public function update(Request $request, UserORMUpdate $user, EntityManagerInterface $em): Response
    {
        try {
            $user->setUsername($request->request->get('username'));
            $user->setName($request->request->get('name'));
            $user->setEmail($request->request->get('email'));
            $user->setEnable($request->request->has('enable'));
            $user->setBirthdate(new \DateTime($request->request->get('birthdate')));
            $user->setAddress($request->request->get('address'));

            $em->flush();

            $message = '✅ User updated successfully.';
        } catch (\Exception $e) {
            $message = '❌ Update failed: ' . $e->getMessage();
        }

        return $this->redirectToRoute('ex07_show', ['message' => $message]);
    }

    #[Route('/ex07/delete/{id}', name: 'ex07_delete')]
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(UserORMUpdate::class)->find($id);

        if (!$user) {
            $message = '❌ User not found.';
        } else {
            $em->remove($user);
            $em->flush();
            $message = '✅ User deleted successfully.';
        }

        return $this->redirectToRoute('ex07_show', ['message' => $message]);
    }
}

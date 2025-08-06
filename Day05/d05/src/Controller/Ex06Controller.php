<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Connection;

class Ex06Controller extends AbstractController
{
    #[Route('/ex06/create_table', name: 'ex06_create_table')]
    public function createTable(Connection $connection): Response
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS users_update (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(255) UNIQUE NOT NULL,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                enable BOOLEAN NOT NULL,
                birthdate DATETIME NOT NULL,
                address LONGTEXT NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";

        try {
            $connection->executeStatement($sql);
            $message = '✅ Table "users_update" created successfully (or already exists).';
        } catch (\Exception $e) {
            $message = '❌ Error creating table: ' . $e->getMessage();
        }

        return $this->render('ex06/index.html.twig', [
            'message' => $message,
        ]);
    }

    #[Route('ex06/form', name: 'ex06_form')]
    public function showForm(): Response {

        return $this->render('ex06/form.html.twig');
    }

    #[Route('ex06/execute', name: 'ex06_execute', methods: ['POST'])]
    public function handleForm(Request $request, Connection $connection): Response {

        $data = [
            'username' => $request->request->get('username'),
            'name' => $request->request->get('name'),
            'email' => $request->request->get('email'),
            'enable' => $request->request->has('enable') ? 1 : 0,
            'birthdate' => $request->request->get('birthdate'),
            'address' => $request->request->get('address'),
        ];

        $sql = "INSERT IGNORE INTO users_update (username, name, email, enable, birthdate, address)
                VALUES (:username, :name, :email, :enable, :birthdate, :address)";

        try {
            $connection->executeStatement($sql, $data);
            $message = '✅ User inserted successfully (or already exists).';
        } catch (\Exception $e) {
            $message = '❌ Failed to insert: ' . $e->getMessage();
        }

        return new Response($message . '<br><a href="' . $this->generateUrl('ex06_form') . '">Back to form</a>');
    }

    #[Route('ex06/show', name: 'ex06_show')]
    public function showUsers(Request $request, Connection $connection): Response
    {
        $message = $request->query->get('message');

        $users = $connection->fetchAllAssociative('SELECT * FROM users_update');

        return $this->render('ex06/users.html.twig', [
            'users' => $users,
            'message' => $message,
        ]);
    }

    #[Route('/ex06/edit/{id}', name: 'ex06_edit')]
    public function edit(Connection $connection, int $id): Response
    {
        $user = $connection->fetchAssociative('SELECT * FROM users_update WHERE id = ?', [$id]);

        if (!$user) {
            return new Response('❌ User not found', 404);
        }

        return $this->render('ex06/edit.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/ex06/update/{id}', name: 'ex06_update', methods: ['POST'])]
    public function update(Connection $connection, Request $request, int $id): Response
    {
        try {
            $sql = "UPDATE users_update SET username = :username, name = :name, email = :email, enable = :enable, birthdate = :birthdate, address = :address WHERE id = :id";
            $params = [
                'id' => $id,
                'username' => $request->request->get('username'),
                'name' => $request->request->get('name'),
                'email' => $request->request->get('email'),
                'enable' => $request->request->has('enable') ? 1 : 0,
                'birthdate' => $request->request->get('birthdate'),
                'address' => $request->request->get('address'),
            ];

            $connection->executeStatement($sql, $params);

            return $this->redirectToRoute('ex06_show', ['message' => '✅ User updated successfully.']);
        } catch (\Exception $e) {
            return $this->redirectToRoute('ex06_show', ['message' => '❌ Update failed: ' . $e->getMessage()]);
        }
    }

    #[Route('/ex06/delete/{id}', name: 'ex06_delete')]
    public function deleteUser(int $id, Connection $connection): Response
    {
        $user = $connection->fetchAssociative('SELECT * FROM users_update WHERE id = ?', [$id]);

        if (!$user) {
            $message = '❌ User not found.';
        } else {
            $connection->executeStatement('DELETE FROM users_update WHERE id = ?', [$id]);
            $message = '✅ User deleted successfully.';
        }

        return $this->redirectToRoute('ex06_show', ['message' => $message]);
    }
}

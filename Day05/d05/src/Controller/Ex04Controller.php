<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Connection;

class Ex04Controller extends AbstractController
{
    #[Route('/ex04/create_table', name: 'ex04_create_table')]
    public function createTable(Connection $connection): Response
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS users_delete (
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
            $message = '✅ Table "users_delete" created successfully (or already exists).';
        } catch (\Exception $e) {
            $message = '❌ Error creating table: ' . $e->getMessage();
        }

        return $this->render('ex04/index.html.twig', [
            'message' => $message,
        ]);
    }

    #[Route('ex04/form', name: 'ex04_form')]
    public function showForm(): Response {

        return $this->render('ex04/form.html.twig');
    }

    #[Route('ex04/execute', name: 'ex04_execute', methods: ['POST'])]
    public function handleForm(Request $request, Connection $connection): Response {

        $data = [
            'username' => $request->request->get('username'),
            'name' => $request->request->get('name'),
            'email' => $request->request->get('email'),
            'enable' => $request->request->has('enable') ? 1 : 0,
            'birthdate' => $request->request->get('birthdate'),
            'address' => $request->request->get('address'),
        ];

        $sql = "INSERT IGNORE INTO users_delete (username, name, email, enable, birthdate, address)
                VALUES (:username, :name, :email, :enable, :birthdate, :address)";

        try {
            $connection->executeStatement($sql, $data);
            $message = '✅ User inserted successfully (or already exists).';
        } catch (\Exception $e) {
            $message = '❌ Failed to insert: ' . $e->getMessage();
        }

        return new Response($message . '<br><a href="' . $this->generateUrl('ex04_form') . '">Back to form</a>');
    }

    #[Route('ex04/show', name: 'ex04_show')]
    public function showUsers(Request $request, Connection $connection): Response
    {
        $message = $request->query->get('message');

        $users = $connection->fetchAllAssociative('SELECT * FROM users_delete');

        return $this->render('ex04/users.html.twig', [
            'users' => $users,
            'message' => $message,
        ]);
    }

    #[Route('/ex04/delete/{id}', name: 'ex04_delete')]
    public function deleteUser(int $id, Connection $connection): Response
    {
        $user = $connection->fetchAssociative('SELECT * FROM users_delete WHERE id = ?', [$id]);

        if (!$user) {
            $message = '❌ User not found.';
        } else {
            $connection->executeStatement('DELETE FROM users_delete WHERE id = ?', [$id]);
            $message = '✅ User deleted successfully.';
        }

        return $this->redirectToRoute('ex04_show', ['message' => $message]);
    }
}

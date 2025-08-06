<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Connection;

class Ex02Controller extends AbstractController
{
    #[Route('/ex02/create_table', name: 'ex02_create_table')]
    public function createTable(Connection $connection): Response
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS users_form (
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
            $message = '✅ Table "users_form" created successfully (or already exists).';
        } catch (\Exception $e) {
            $message = '❌ Error creating table: ' . $e->getMessage();
        }

        return $this->render('ex02/index.html.twig', [
            'message' => $message,
        ]);
    }

    #[Route('ex02/form', name: 'ex02_form')]
    public function showForm(): Response {

        return $this->render('ex02/form.html.twig');
    }

    #[Route('ex02/execute', name: 'ex02_execute', methods: ['POST'])]
    public function handleForm(Request $request, Connection $connection): Response {

        $data = [
            'username' => $request->request->get('username'),
            'name' => $request->request->get('name'),
            'email' => $request->request->get('email'),
            'enable' => $request->request->has('enable') ? 1 : 0,
            'birthdate' => $request->request->get('birthdate'),
            'address' => $request->request->get('address'),
        ];

        $sql = "INSERT IGNORE INTO users_form (username, name, email, enable, birthdate, address)
                VALUES (:username, :name, :email, :enable, :birthdate, :address)";

        try {
            $connection->executeStatement($sql, $data);
            $message = '✅ User inserted successfully (or already exists).';
        } catch (\Exception $e) {
            $message = '❌ Failed to insert: ' . $e->getMessage();
        }

        return new Response($message . '<br><a href="' . $this->generateUrl('ex02_form') . '">Back to form</a>');
    }

    #[Route('ex02/show', name: 'ex02_show')]
    public function showUsers(Connection $connection): Response
    {
        $users = $connection->fetchAllAssociative('SELECT * FROM users_form');

        return $this->render('ex02/users.html.twig', [
            'users' => $users,
        ]);
    }
}

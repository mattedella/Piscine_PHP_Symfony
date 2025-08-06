<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\DBAL\Connection;

class Ex00Controller extends AbstractController
{
    #[Route('/ex00/create_table', name: 'ex00_create_table')]
    public function createTable(Connection $connection): Response
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS users (
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
            $message = '✅ Table "users" created successfully (or already exists).';
        } catch (\Exception $e) {
            $message = '❌ Error creating table: ' . $e->getMessage();
        }

        return $this->render('ex00/index.html.twig', [
            'message' => $message,
        ]);
    }
}

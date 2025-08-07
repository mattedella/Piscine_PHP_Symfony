<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\RelatedOrm;

final class Ex10Controller extends AbstractController
{

    #[Route('/ex10/create_table', name: 'ex10_table')]
    public function createTable(EntityManagerInterface $em, Connection $connection): Response{
        $sql = ("
            CREATE TABLE IF NOT EXISTS raw_data (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255),
                age INT,
                email VARCHAR(255)
            )
        ");
        try {
            $metadata = $em->getClassMetadata(RelatedOrm::class);
            $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($em);
            $schemaTool->updateSchema([$metadata], true);
            $connection->executeStatement($sql);
            $message = '✅ Table "raw_data" created successfully (or already exists).';
        }
        catch (\Exception $e) {
            $message = '❌ Error creating table: ' . $e->getMessage();
        }

        return $this->render('ex10/index.html.twig', [
            'message' => $message,
        ]);
    }

    #[Route('/ex10/load', name: 'ex10_load')]
    public function loadData(EntityManagerInterface $em, Connection $conn): Response {
        
        $filePath = $this->getParameter('kernel.project_dir') . '/public/data/people.txt';

        if (!file_exists($filePath)) {
            return new Response("File not found: $filePath");
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            [$name, $age, $email] = array_map('trim', explode(',', $line));

            $conn->insert('raw_data', [
                'name' => $name,
                'age' => (int)$age,
                'email' => $email,
            ]);

            $person = new RelatedOrm();
            $person->setName($name);
            $person->setAge((int)$age);
            $person->setEmail($email);
            $em->persist($person);
        }

        $em->flush();

        return new Response("Data imported successfully.");
    }

    #[Route('/ex10/list_data', name: 'ex10_list_data')]
    public function listData(EntityManagerInterface $em): Response
    {
        $people = $em->getRepository(RelatedOrm::class)->findAll();

        $html = '<h2>Stored ORM Data</h2><table border="1"><tr><th>Name</th><th>Email</th><th>Age</th></tr>';
        foreach ($people as $person) {
            $html .= sprintf(
                '<tr><td>%s</td><td>%s</td><td>%d</td></tr>',
                htmlspecialchars($person->getName()),
                htmlspecialchars($person->getEmail()),
                $person->getAge()
            );
        }
        $html .= '</table><br><a href="/ex10/load_data">Reload File Data</a>';

        return new Response($html);
    }
}


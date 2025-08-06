<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use App\Entity\UserORM;

final class Ex01Controller extends AbstractController
{
    #[Route('/ex01/create_table', name: 'ex01_create_table')]
    public function createTable(EntityManagerInterface $em): Response
    {
        $schemaTool = new SchemaTool($em);
        $metadata = [$em->getClassMetadata(UserORM::class)];

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
}

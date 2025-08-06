<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Connection;

class Ex08Controller extends AbstractController
{
    #[Route('/ex08/create_table', name: 'ex08_create_table')]
    public function createTable(Connection $connection): Response
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS persons (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL UNIQUE,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            enable BOOLEAN NOT NULL,
            birthdate DATE NOT NULL
            )
        ";

        try {
            $connection->executeStatement($sql);
            $message = '✅ Table "persons" created successfully (or already exists).';
        } catch (\Exception $e) {
            $message = '❌ Error creating table: ' . $e->getMessage();
        }

        return $this->render('ex08/index.html.twig', [
            'message' => $message,
        ]);
    }

    #[Route('/ex08/add_marital_status', name: 'ex08_add_marital_status')]
    public function addMaritalStatus(Connection $connection): Response
    {
        $sql = "
            ALTER TABLE persons
            ADD COLUMN marital_status ENUM('single', 'married', 'widower') DEFAULT 'single'
        ";

        try {
            $connection->executeStatement($sql);
            $message = '✅ Column "marital_status" added.';
        } catch (\Exception $e) {
            $message = '❌ Failed to add column: ' . $e->getMessage();
        }

        return new Response($message);
    }

    #[Route('ex08/form', name: 'ex08_form')]
    public function showForm(): Response {

        return $this->render('ex08/form.html.twig');
    }

    #[Route('ex08/execute', name: 'ex08_execute', methods: ['POST'])]
    public function handleForm(Request $request, Connection $connection): Response {

        $sql = "
            INSERT INTO persons (username, name, email, enable, birthdate, marital_status)
            VALUES (:username, :name, :email, :enable, :birthdate, :marital_status)
        ";

        try {
            $connection->executeStatement($sql, [
                'username' => $request->request->get('username'),
                'name' => $request->request->get('name'),
                'email' => $request->request->get('email'),
                'enable' => $request->request->has('enable') ? 1 : 0,
                'birthdate' => $request->request->get('birthdate'),
                'marital_status' => $request->request->get('marital_status'),
            ]);

            $message = '✅ Person inserted successfully.';
        } catch (\Exception $e) {
            $message = '❌ Failed to insert person: ' . $e->getMessage();
        }

        return new Response($message . '<br><a href="' . $this->generateUrl('ex08_form') . '">Back to form</a>');
    }

    #[Route('ex08/show', name: 'ex08_show')]
    public function showUsers(Request $request, Connection $connection): Response
    {
        $message = $request->query->get('message');

        $users = $connection->fetchAllAssociative('SELECT * FROM persons');

        return $this->render('ex08/users.html.twig', [
            'users' => $users,
            'message' => $message,
        ]);
    }

    #[Route('/ex08/create_related_tables', name: 'ex08_create_related_tables')]
    public function createRelatedTables(Connection $connection): Response
    {
        try {
            $connection->executeStatement("
                CREATE TABLE IF NOT EXISTS bank_accounts (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    iban VARCHAR(34) NOT NULL,
                    person_id INT UNIQUE,
                    FOREIGN KEY (person_id) REFERENCES persons(id) ON DELETE CASCADE
                )
            ");

            $connection->executeStatement("
                CREATE TABLE IF NOT EXISTS addresses (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    address TEXT NOT NULL,
                    person_id INT,
                    FOREIGN KEY (person_id) REFERENCES persons(id) ON DELETE CASCADE
                )
            ");

            $message = '✅ Tables "bank_accounts" and "addresses" created with relationships.';
        } catch (\Exception $e) {
            $message = '❌ Error: ' . $e->getMessage();
        }

        return new Response($message);
    }

    #[Route('/ex08/new_bank_account', name: 'ex08_new_bank_account')]
    public function newBankAccountForm(): Response
    {
        return $this->render('ex08/new_bank_account.html.twig');
    }

    #[Route('/ex08/insert_bank_account', name: 'ex08_insert_bank_account', methods: ['POST'])]
    public function insertBankAccount(Request $request, Connection $connection): Response
    {
        $data = [
            'iban' => $request->request->get('iban'),
            'person_id' => $request->request->get('person_id'),
        ];

        try {
            $sql = "INSERT INTO bank_accounts (iban, person_id)
                    VALUES (:iban, :person_id)";
            $connection->executeStatement($sql, $data);
            $message = '✅ Bank account inserted successfully.';
        } catch (\Exception $e) {
            $message = '❌ Failed to insert bank account: ' . $e->getMessage();
        }

        return new Response($message . '<br><a href="' . $this->generateUrl('ex08_new_bank_account') . '">Back</a>');
    }

    #[Route('/ex08/new_address', name: 'ex08_new_address')]
    public function newAddressForm(): Response
    {
    return $this->render('ex08/new_address.html.twig');
    }


    #[Route('/ex08/insert_address', name: 'ex08_insert_address', methods: ['POST'])]
    public function insertAddress(Request $request, Connection $connection): Response
    {
        $data = [
            'address' => $request->request->get('address'),
            'person_id' => $request->request->get('person_id'),
        ];

        try {
            $sql = "INSERT INTO addresses (address, person_id)
                    VALUES (:address, :person_id)";
            $connection->executeStatement($sql, $data);
            $message = '✅ Address inserted successfully.';
        } catch (\Exception $e) {
            $message = '❌ Failed to insert address: ' . $e->getMessage();
        }

        return new Response($message . '<br><a href="' . $this->generateUrl('ex08_new_address') . '">Back</a>');
    }


    #[Route('/ex08/person/{id}', name: 'ex08_view_person')]
    public function viewPerson(int $id, Connection $connection): Response
    {
        try {
            // Get person
            $person = $connection->fetchAssociative("SELECT * FROM persons WHERE id = ?", [$id]);

            if (!$person) {
                throw new \Exception('Person not found.');
            }

            // Get bank account (1:1)
            $bankAccount = $connection->fetchAssociative("SELECT * FROM bank_accounts WHERE person_id = ?", [$id]);

            // Get addresses (1:N)
            $addresses = $connection->fetchAllAssociative("SELECT * FROM addresses WHERE person_id = ?", [$id]);

            return $this->render('ex08/view_person.html.twig', [
                'person' => $person,
                'bank_account' => $bankAccount,
                'addresses' => $addresses,
            ]);
        } catch (\Exception $e) {
            return new Response('❌ Error: ' . $e->getMessage());
        }
    }

}

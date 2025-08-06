<?php

namespace App\Controller;

use App\Entity\PersonsOrm;
use App\Entity\BankAccountOrm;
use App\Entity\AddressOrm;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class Ex09Controller extends AbstractController
{
    #[Route('/ex09/create_tables', name: 'ex09_create_tables')]
    public function createSchema(EntityManagerInterface $em): Response
    {
        $tool = new SchemaTool($em);
        $metadata = [
            $em->getClassMetadata(PersonsOrm::class),
            $em->getClassMetadata(BankAccountOrm::class),
            $em->getClassMetadata(AddressOrm::class),
        ];
        try {
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

    #[Route('/ex09/form', name: 'ex09_form')]
    public function newPersonForm(): Response
    {
        return $this->render('ex09/form.html.twig');
    }

    #[Route('/ex09/insert_person', name: 'ex09_insert_person', methods: ['POST'])]
    public function insertPerson(Request $request, EntityManagerInterface $em): Response
    {
        $person = new PersonsOrm();
        $person->setUsername($request->request->get('username'));
        $person->setName($request->request->get('name'));
        $person->setEmail($request->request->get('email'));
        $person->setBirthdate(new \DateTime($request->request->get('birthdate')));
        $person->setEnable($request->request->has('enable'));
        $person->setMaritalStatus($request->request->get('marital_status'));

        try {
            $em->persist($person);
            $em->flush();
            $message = "✅ Person inserted.";
        } catch (\Exception $e) {
            $message = "❌ Error: " . $e->getMessage();
        }

        return new Response($message . '<br><a href="' . $this->generateUrl('ex09_form') . '">Back</a>');
    }

    #[Route('/ex09/new_account', name: 'ex09_new_account')]
    public function newAccountForm(EntityManagerInterface $em): Response
    {
        $persons = $em->getRepository(PersonsOrm::class)->findAll();

        return $this->render('ex09/new_account.html.twig', [
            'persons' => $persons
        ]);
    }

    #[Route('/ex09/insert_account', name: 'ex09_insert_account', methods: ['POST'])]
    public function insertAccount(Request $request, EntityManagerInterface $em): Response
    {
        $personId = $request->request->get('person_id');
        $iban = $request->request->get('iban');

        $person = $em->getRepository(PersonsOrm::class)->find($personId);

        if (!$person) {
            return $this->redirectToRoute('ex09_new_account', [
                'error' => 'Person not found.'
            ]);
        }

        $account = new BankAccountOrm();
        $account->setIban($iban);
        $account->setPerson($person);

        $em->persist($account);
        $em->flush();

        return $this->redirectToRoute('ex09_new_account', [
            'success' => 'Bank account added successfully.'
        ]);
    }

    #[Route('/ex09/new_address', name: 'ex09_new_address')]
    public function newAddressForm(EntityManagerInterface $em): Response
    {
        $persons = $em->getRepository(PersonsOrm::class)->findAll();

        return $this->render('ex09/new_address.html.twig', [
            'persons' => $persons
        ]);
    }

    #[Route('/ex09/insert_address', name: 'ex09_insert_address', methods: ['POST'])]
    public function insertAddress(Request $request, EntityManagerInterface $em): Response
    {
        $personId = $request->request->get('person_id');
        $addressText = $request->request->get('address');

        $person = $em->getRepository(PersonsOrm::class)->find($personId);

        if (!$person) {
            return $this->redirectToRoute('ex09_new_address', [
                'error' => 'Person not found.'
            ]);
        }

        $address = new AddressOrm();
        $address->setAddress($addressText);
        $address->setPerson($person);

        $em->persist($address);
        $em->flush();

        return $this->redirectToRoute('ex09_new_address', [
            'success' => 'Address added successfully.'
        ]);
    }

    #[Route('/ex09/list', name: 'ex09_list')]
    public function listAll(EntityManagerInterface $em): Response
    {
        $persons = $em->getRepository(PersonsOrm::class)->findAll();

        return $this->render('ex09/list.html.twig', [
            'persons' => $persons,
        ]);
    }

}


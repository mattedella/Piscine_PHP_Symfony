<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class Ex11Controller extends AbstractController
{
    #[Route('/ex11/list', name: 'ex11_list')]
    public function list(Request $request, Connection $conn): Response
    {
        $sort = in_array($request->query->get('sort'), ['name', 'email']) ? $request->query->get('sort') : 'name';
        $order = $request->query->get('order') === 'desc' ? 'DESC' : 'ASC';
        $fromDate = $request->query->get('fromDate');

        $where = '';
        $params = [];

        if ($fromDate) {
            $where = 'WHERE p.birthdate >= :fromDate';
            $params['fromDate'] = $fromDate;
        }

        $sql = "
            SELECT p.name, p.email, p.birthdate, b.iban
            FROM persons_orm p
            JOIN bank_account_orm b ON p.id = b.person_id
            $where
            ORDER BY p.$sort $order
        ";

        $results = $conn->fetchAllAssociative($sql, $params);

        return $this->render('ex11/list.html.twig', [
            'people' => $results,
            'sort' => $sort,
            'order' => $order,
            'filterDate' => $fromDate,
        ]);
    }
}

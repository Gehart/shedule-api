<?php

namespace App\Http\Controllers;

use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Http\Request;

class HealthCheckController extends Controller
{
    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return string
     */
    public function check(Request $request, EntityManagerInterface $entityManager): string
    {
        // проверяем соединение с БД
        $entityManager->getConnection()->connect();

        return 'It\'s good';
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return string
     */
    public function test(Request $request, EntityManagerInterface $entityManager): string
    {
        // проверяем соединение с БД
        $entityManager->getConnection()->connect();

        return 'Hello, front';
    }
}

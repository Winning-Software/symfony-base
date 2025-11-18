<?php

declare(strict_types=1);

namespace App\_Core\Controller;

use App\Auth\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HealthCheckController extends AbstractApplicationController
{
    #[Route('/health-check/database-connection', name: 'app_health_check_database_connection')]
    public function checkDatabaseConnection(Request $request, EntityManagerInterface $entityManager): Response
    {
        try {
            $entityManager->getConnection()->executeQuery('SELECT 1');

            return $this->json(['success' => true]);
        } catch (\Throwable $e) {
            return $this->json([
                'success' => false,
            ], 500);
        }
    }

    #[Route('/health-check/default-tables-exist', name: 'app_health_check_default_tables_exist')]
    public function checkDefaultTablesExist(Request $request, EntityManagerInterface $entityManager): Response
    {
        try {
            $entityManager->getRepository(User::class)->findAll();

            return $this->json(['success' => true]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
            ], 500);
        }
    }
}

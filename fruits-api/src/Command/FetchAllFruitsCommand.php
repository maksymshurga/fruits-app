<?php

namespace App\Command;

use App\Entity\Fruit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class FetchAllFruitsCommand extends Command
{
    protected static $defaultName = 'fruits:fetch';
    protected $entityManager;
    protected $mailer;

    public function __construct(
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
    ) {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // fetch all fruits data from https://fruityvice.com/
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://www.fruityvice.com/api/fruit/all', [
            'verify_peer' => false,
            'verify_host' => false
        ]);

        if ($response->getStatusCode() === 200) {
            // The request was successful
            $fruits = $response->toArray();

            // Truncate fruits table
            $connection = $this->entityManager->getConnection();
            $platform   = $connection->getDatabasePlatform();

            $connection->executeUpdate($platform->getTruncateTableSQL(
                $this->entityManager->getClassMetadata(Fruit::class)->getTableName()
            ));

            // Loop through the list of fruits and save them to Fruit entity
            foreach ($fruits as $fruit) {
                $fruitEntity = new Fruit();
                $fruitEntity->setName($fruit['name']);
                $fruitEntity->setFamily($fruit['family']);
                $fruitEntity->setOrders($fruit['order']);
                $fruitEntity->setGenus($fruit['genus']);
                $fruitEntity->setCalories($fruit['nutritions']['calories']);
                $fruitEntity->setFat($fruit['nutritions']['fat']);
                $fruitEntity->setSugar($fruit['nutritions']['sugar']);
                $fruitEntity->setCarbohydrates($fruit['nutritions']['carbohydrates']);
                $fruitEntity->setProtein($fruit['nutritions']['protein']);
                $fruitEntity->setFavorite(false);

                $this->entityManager->persist($fruitEntity);
                $this->entityManager->flush();
            }

            // Send an email
            $email = (new Email())
                ->from($_ENV['MAILER_FROM_EMAIL'])
                ->to($_ENV['MAILER_TO_EMAIL'])
                ->subject('Success with fetching fruits data')
                ->text('All Fruits are saved into DB');

            $this->mailer->send($email);
        } else {
            echo 'Failed to retrieve fruits: HTTP status code ' . $response->getStatusCode() . "\n";
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}

<?php

namespace App\Command;

use App\DTO\InstagramImageDTO;
use App\Http\InstagramApiClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

#[AsCommand(
    name: 'app:instagram-api-client',
)]
class FetchInstagramDataCommand extends Command
{
    protected static $defaultName = 'app:instagram-api-client';


    public function __construct(
        private EntityManagerInterface $entityManager,
        private InstagramApiClientInterface $instagramApiClient,
        private SerializerInterface $serializer
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Retrieve Posts from the Yahoo Finance API')
            ->addArgument('username', InputArgument::REQUIRED, 'login username');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->instagramApiClient->fetchInstagramData($input->getArgument('mrbeast'));

        if ($response->getStatusCode() !== JsonResponse::HTTP_OK) {
            $output->writeln($response->getContent());
            return Command::FAILURE;
        }

        /** @var InstagramImageDTO $instagramImages */
        $instagramImages = $this->serializer->deserialize($response->getContent(), InstagramImageDTO::class, 'json');
        $this->entityManager->persist($instagramImages);
        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}

<?php

namespace App\Tests\feature;

use App\Entity\Stock;
use App\http\FakeYahooFinanceApiClient;
use App\Tests\DatabaseDependantTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class RefreshStockProfileCommandTest extends DatabaseDependantTestCase
{

    /** @test */
    public function non_200_status_code_responses_are_handled_correctly(): void
    {
        $application = new Application(self::$kernel);

        // Command
        $command = $application->find('app:refresh-stock-profile');
        $commandTester = new CommandTester($command);

        $repo = $this->entityManager->getRepository(Stock::class);
        FakeYahooFinanceApiClient::$content = '{"symbol":"AMZN","shortName":"Amazon.com, Inc.","region":"US","exchangeName":"NasdaqGS","currency":"USD","price":3258.7083,"previousClose":3172.69,"priceChange":86.02}';

        // DO SOMETHING
        $commandStatus = $commandTester->execute([
            'symbol' => 'AMZN',
            'region' => 'US'
        ]);

        $stock = $repo->findOneBy(['symbol' => 'AMZN']);

        $this->assertSame('USD', $stock->getCurrency());
        $this->assertSame('NasdaqGS', $stock->getExchangeName());
        $this->assertSame('AMZN', $stock->getSymbol());
        $this->assertSame('Amazon.com, Inc.', $stock->getShortName());
        $this->assertSame('US', $stock->getRegion());
        $this->assertGreaterThan(50, $stock->getPreviousClose());
        $this->assertGreaterThan(50, $stock->getPrice());
    }
}

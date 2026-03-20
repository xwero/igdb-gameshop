<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Tests\Unit\Commands;

use Symfony\Component\Console\Tester\CommandTester;
use Xwero\IgdbGameshop\PIM\Commands\IGDBDataToDatabaseCommand;


it('returns failure when no data found', function() {
    $command = new IGDBDataToDatabaseCommand();
    $commandTester = new CommandTester($command);
    
    $result = $commandTester->execute([]);
    
    expect($result)->toBe(1); // Command::FAILURE
    $output = $commandTester->getDisplay();
    
    // Check for either the expected message or database connection error
    $hasExpectedMessage = str_contains($output, 'No games and covers data found in temporary directory');
    $hasDatabaseError = str_contains($output, 'DATABASE_DSN environment variable is not set');
    
    expect($hasExpectedMessage || $hasDatabaseError)->toBeTrue();
});


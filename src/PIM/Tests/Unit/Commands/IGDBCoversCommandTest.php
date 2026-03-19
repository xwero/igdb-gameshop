<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Tests\Unit;

use Xwero\IgdbGameshop\PIM\Commands\IGDBCoversCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Xwero\IgdbGameshop\PIM\DTO\GamesDTO;
use Xwero\IgdbGameshop\PIM\DTO\GamesDTOCollection;
use Xwero\IgdbGameshop\Shared\Data\TempFiles;

it('has correct command name', function() {
    $command = new IGDBCoversCommand();
    expect($command->getName())->toBe('igdb:import-covers');
});

it('requires twitchId argument', function() {
    $command = new IGDBCoversCommand();
    $definition = $command->getDefinition();

    expect($definition->hasArgument('twitchId'))->toBeTrue();
    expect($definition->getArgument('twitchId')->isRequired())->toBeTrue();
});

it('requires oauthSecret argument', function() {
    $command = new IGDBCoversCommand();
    $definition = $command->getDefinition();
    
    expect($definition->hasArgument('oauthSecret'))->toBeTrue();
    expect($definition->getArgument('oauthSecret')->isRequired())->toBeTrue();
});

it('returns failure when no games found', function() {
    $command = new IGDBCoversCommand();
    $tester = new CommandTester($command);
    $tempDir = 'test_temp_' . uniqid();
    
    $result = $tester->execute([
        'twitchId' => 'test_id',
        'oauthSecret' => 'test_secret',
        '--tempDir' => $tempDir,
    ]);
    
    expect($result)->toBe(1); // Command::FAILURE
    expect($tester->getDisplay())->toContain('No games found.');
});

it('returns failure when access token cannot be obtained', function() {
    $tempDir = 'test_temp_' . uniqid();
    $tempFiles = new TempFiles($tempDir);
    $games = new GamesDTOCollection(
        new GamesDTO(0, '{"name": "Game 1 content"}'),
        new GamesDTO(1, '{"name": "Game 2 content"}')
    );

    $tempFiles->multiStoreGames($games);

    $command = new IGDBCoversCommand();
    $tester = new CommandTester($command);

    $result = $tester->execute([
        'twitchId' => 'invalid_id',
        'oauthSecret' => 'invalid_secret',
        '--tempDir' => $tempDir,
    ]);

    expect($result)->toBe(1); // Command::FAILURE
    expect($tester->getDisplay())->toContain('Failed to get valid access token');

    // Cleanup
    if (file_exists($tempDir . '/games_0.json')) unlink($tempDir . '/games_0.json');
    if (file_exists($tempDir . '/games_1.json')) unlink($tempDir . '/games_1.json');
    if (is_dir($tempDir)) rmdir($tempDir);
});
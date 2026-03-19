<?php

declare(strict_types=1);

namespace Xwero\IgdbGameshop\PIM\Tests\Unit;

use Xwero\IgdbGameshop\PIM\Commands\IGDBGamesCommand;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Input\ArrayInput;

it('has correct command name', function() {
    $command = new IGDBGamesCommand();
    expect($command->getName())->toBe('igdb:import-games');
});

it('requires twitchId argument', function() {
    $command = new IGDBGamesCommand();
    $definition = $command->getDefinition();
    
    expect($definition->hasArgument('twitchId'))->toBeTrue();
    expect($definition->getArgument('twitchId')->isRequired())->toBeTrue();
});

it('requires oauthSecret argument', function() {
    $command = new IGDBGamesCommand();
    $definition = $command->getDefinition();
    
    expect($definition->hasArgument('oauthSecret'))->toBeTrue();
    expect($definition->getArgument('oauthSecret')->isRequired())->toBeTrue();
});

it('returns failure when access token cannot be obtained', function() {
    $command = new IGDBGamesCommand();
    $tester = new CommandTester($command);
    
    $result = $tester->execute([
        'twitchId' => 'invalid_id',
        'oauthSecret' => 'invalid_secret'
    ]);
    
    expect($result)->toBe(1); // Command::FAILURE
    expect($tester->getDisplay())->toContain('Failed to get valid access token.');
});
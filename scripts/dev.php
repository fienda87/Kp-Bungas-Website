<?php

declare(strict_types=1);

$commands = [
    'php artisan serve',
    'php artisan queue:listen --tries=1 --timeout=0',
    'npm run dev',
];

$names = 'server,queue,vite';
$colors = '#93c5fd,#c4b5fd,#fdba74';

if (function_exists('pcntl_fork')) {
    $commands[] = 'php artisan pail --timeout=0';
    $names = 'server,queue,vite,logs';
    $colors = '#93c5fd,#c4b5fd,#fdba74,#fb7185';
}

$quotedCommands = array_map(
    static fn (string $command): string => '"' . $command . '"',
    $commands,
);

$command = sprintf(
    'npx concurrently -c "%s" %s --names=%s --kill-others',
    $colors,
    implode(' ', $quotedCommands),
    $names,
);

passthru($command, $exitCode);

exit($exitCode);
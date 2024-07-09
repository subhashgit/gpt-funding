<?php

namespace Deployer;

require 'recipe/symfony.php';

// Config

set('repository', 'git@github.com:gulaandrij/gpt-funding.git');

add('shared_files', [
    '.nvmrc',
]);
add('shared_dirs', [
    './docker',
]);
add('writable_dirs', []);

// Hosts

host('161.35.39.132')
    ->set('remote_user', 'root')
    ->set('deploy_path', '/var/www/gpt-funding')
;

task('db:dump', function () {
    run('cd {{release_or_current_path}} && docker compose -f docker-compose.yml exec database pg_dump -U funding -Z 5 prod > /var/www/gpt-funding/prod{{release_name}}.zip');
});

// Hooks

before('deploy:prepare', 'db:dump');

after('deploy:failed', 'deploy:unlock');

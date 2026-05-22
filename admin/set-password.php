#!/usr/bin/env php
<?php

/**
 * Eenmalig wachtwoord instellen voor /admin/
 *
 * Gebruik: php admin/set-password.php "jouw-geheime-wachtwoord"
 */

if (PHP_SAPI !== 'cli') {
    fwrite(STDERR, "Dit script werkt alleen via de command line.\n");
    exit(1);
}

$password = $argv[1] ?? '';

if (strlen($password) < 8) {
    fwrite(STDERR, "Gebruik een wachtwoord van minimaal 8 tekens.\n");
    fwrite(STDERR, "Voorbeeld: php admin/set-password.php \"MijnSterkWachtwoord123\"\n");
    exit(1);
}

require_once __DIR__ . '/../includes/admin-auth.php';

$hash = password_hash($password, PASSWORD_DEFAULT);

if (!admin_write_config($hash)) {
    fwrite(STDERR, "Kon admin.config.php niet schrijven.\n");
    exit(1);
}

echo "Wachtwoord opgeslagen in includes/admin.config.php\n";
echo "Beheer: " . dirname(__DIR__) . "/admin/\n";

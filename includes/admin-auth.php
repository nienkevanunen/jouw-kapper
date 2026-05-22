<?php

function admin_config_path(): string
{
    return __DIR__ . '/admin.config.php';
}

function admin_load_config(): ?array
{
    $path = admin_config_path();
    if (!is_file($path)) {
        return null;
    }

    $config = require $path;

    return is_array($config) ? $config : null;
}

function admin_is_configured(): bool
{
    $config = admin_load_config();

    return !empty($config['password_hash']);
}

function admin_start_session(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'httponly' => true,
            'samesite' => 'Strict',
        ]);
        session_start();
    }
}

function admin_is_logged_in(): bool
{
    admin_start_session();

    return !empty($_SESSION['admin_logged_in']);
}

function admin_require_login(): void
{
    if (!admin_is_logged_in()) {
        header('Location: index.php');
        exit;
    }
}

function admin_login(string $password): bool
{
    $config = admin_load_config();
    if (!$config || empty($config['password_hash'])) {
        return false;
    }

    if (!password_verify($password, $config['password_hash'])) {
        return false;
    }

    admin_start_session();
    session_regenerate_id(true);
    $_SESSION['admin_logged_in'] = true;

    return true;
}

function admin_logout(): void
{
    admin_start_session();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
    session_destroy();
}

function admin_csrf_token(): string
{
    admin_start_session();
    if (empty($_SESSION['admin_csrf'])) {
        $_SESSION['admin_csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['admin_csrf'];
}

function admin_verify_csrf(): bool
{
    admin_start_session();
    $token = $_POST['csrf'] ?? '';

    return is_string($token)
        && !empty($_SESSION['admin_csrf'])
        && hash_equals($_SESSION['admin_csrf'], $token);
}

function admin_write_config(string $passwordHash): bool
{
    $content = "<?php\n\nreturn [\n    'password_hash' => " . var_export($passwordHash, true) . ",\n];\n";

    return file_put_contents(admin_config_path(), $content, LOCK_EX) !== false;
}

function admin_uploaded_image_path(array $file, string $folder): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK || empty($file['tmp_name'])) {
        return null;
    }

    $mimeType = mime_content_type($file['tmp_name']);
    $extensions = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
    ];

    if (!isset($extensions[$mimeType])) {
        return null;
    }

    $targetDir = __DIR__ . '/../img/' . trim($folder, '/');
    if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true)) {
        return null;
    }

    $name = pathinfo($file['name'] ?? 'image', PATHINFO_FILENAME);
    $name = strtolower(preg_replace('/[^a-zA-Z0-9-]+/', '-', $name));
    $name = trim($name, '-') ?: 'image';
    $filename = $name . '-' . date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.' . $extensions[$mimeType];
    $targetPath = $targetDir . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        return null;
    }

    return 'img/' . trim($folder, '/') . '/' . $filename;
}

function admin_file_from_nested_upload(array $files, string $field, int $index): ?array
{
    if (!isset($files[$field]['name'][$index])) {
        return null;
    }

    return [
        'name' => $files[$field]['name'][$index],
        'type' => $files[$field]['type'][$index] ?? '',
        'tmp_name' => $files[$field]['tmp_name'][$index] ?? '',
        'error' => $files[$field]['error'][$index] ?? UPLOAD_ERR_NO_FILE,
        'size' => $files[$field]['size'][$index] ?? 0,
    ];
}

function admin_apply_prices_from_post(array $prices, array $post): array
{
    $nextPrices = ['columns' => []];

    foreach ($post['columns'] ?? [] as $columnPost) {
        $sections = [];

        foreach ($columnPost['sections'] ?? [] as $sectionPost) {
            if (!empty($sectionPost['remove'])) {
                continue;
            }

            $title = trim($sectionPost['title'] ?? '');
            $entries = [];

            foreach ($sectionPost['entries'] ?? [] as $entryPost) {
                if (!empty($entryPost['remove'])) {
                    continue;
                }

                $type = $entryPost['type'] ?? 'simple';

                if ($type === 'note') {
                    $note = trim($entryPost['note'] ?? '');
                    if ($note !== '') {
                        $entries[] = ['note' => $note];
                    }
                    continue;
                }

                if ($type === 'group') {
                    $group = trim($entryPost['group'] ?? '');
                    $lines = admin_price_lines_from_post($entryPost['lines'] ?? []);
                    $note = trim($entryPost['note'] ?? '');
                    $bullets = admin_price_bullets_from_text($entryPost['bullets'] ?? '');

                    if ($group === '' && empty($lines) && $note === '' && empty($bullets)) {
                        continue;
                    }

                    $entry = [];
                    if ($group !== '') {
                        $entry['group'] = $group;
                    }
                    if (!empty($lines)) {
                        $entry['lines'] = $lines;
                    }
                    if ($note !== '') {
                        $entry['note'] = $note;
                    }
                    if (!empty($bullets)) {
                        $entry['bullets'] = $bullets;
                    }

                    $entries[] = $entry;
                    continue;
                }

                $text = trim($entryPost['text'] ?? '');
                $price = trim($entryPost['price'] ?? '');
                if ($text !== '' || $price !== '') {
                    $entries[] = [
                        'text' => $text,
                        'price' => $price,
                    ];
                }
            }

            if ($title === '' && empty($entries)) {
                continue;
            }

            $section = [
                'title' => $title,
                'entries' => $entries,
            ];

            if (!empty($sectionPost['vanaf'])) {
                $section['vanaf'] = true;
            }

            $sections[] = $section;
        }

        $nextPrices['columns'][] = ['sections' => $sections];
    }

    return $nextPrices;
}

function admin_price_lines_from_post(array $linesPost): array
{
    $lines = [];

    foreach ($linesPost as $linePost) {
        if (!empty($linePost['remove'])) {
            continue;
        }

        $text = trim($linePost['text'] ?? '');
        $price = trim($linePost['price'] ?? '');
        if ($text === '' && $price === '') {
            continue;
        }

        $line = [
            'text' => $text,
            'price' => $price,
        ];

        if (!empty($linePost['muted'])) {
            $line['muted'] = true;
        }

        $lines[] = $line;
    }

    return $lines;
}

function admin_price_bullets_from_text(string $text): array
{
    $bullets = [];

    foreach (preg_split('/\r\n|\r|\n/', $text) as $line) {
        $line = trim($line);
        if ($line !== '') {
            $bullets[] = $line;
        }
    }

    return $bullets;
}

function admin_apply_page_text_from_post(array $pageText, array $post): array
{
    return admin_clean_text_values($post);
}

function admin_clean_text_values(array $values): array
{
    $clean = [];

    foreach ($values as $key => $value) {
        if (is_array($value)) {
            $clean[$key] = admin_clean_text_values($value);
            continue;
        }

        $clean[$key] = trim((string) $value);
    }

    return $clean;
}

function admin_apply_site_from_post(array $site, array $post): array
{
    $site['phone']['display'] = trim($post['phone_display'] ?? $site['phone']['display']);
    $site['phone']['tel'] = trim($post['phone_tel'] ?? $site['phone']['tel']);
    $site['phone']['footer'] = trim($post['phone_footer'] ?? $site['phone']['footer']);
    $site['email'] = trim($post['email'] ?? $site['email']);
    $site['bookingUrl'] = trim($post['bookingUrl'] ?? $site['bookingUrl']);
    $site['facebookUrl'] = trim($post['facebookUrl'] ?? ($site['facebookUrl'] ?? ''));

    foreach ($post['addresses'] ?? [] as $i => $addrPost) {
        if (!isset($site['addresses'][$i])) {
            continue;
        }
        $site['addresses'][$i]['street'] = trim($addrPost['street'] ?? '');
        $site['addresses'][$i]['line2'] = trim($addrPost['line2'] ?? '');
        $site['addresses'][$i]['fullHtml'] = trim($addrPost['fullHtml'] ?? '');
        $site['addresses'][$i]['mapsLink'] = trim($addrPost['mapsLink'] ?? '');
    }

    return $site;
}

function admin_apply_opening_hours_from_post(array $hours, array $post): array
{
    $hours['defaults']['zaterdag_sluit'] = trim($post['default_zaterdag_sluit'] ?? $hours['defaults']['zaterdag_sluit']);

    if (!empty($hours['dateRules'][0])) {
        $hours['dateRules'][0]['from'] = trim($post['rule_from'] ?? $hours['dateRules'][0]['from']);
        $hours['dateRules'][0]['variables']['zaterdag_sluit'] = trim(
            $post['rule_zaterdag_sluit'] ?? $hours['dateRules'][0]['variables']['zaterdag_sluit']
        );
    }

    foreach ($post['days'] ?? [] as $i => $dayPost) {
        if (!isset($hours['days'][$i])) {
            continue;
        }
        $hours['days'][$i]['hours'] = trim($dayPost['hours'] ?? $hours['days'][$i]['hours']);
    }

    return $hours;
}

function admin_apply_promotions_from_post(array $promotions, array $post, array $files = []): array
{
    $items = [];

    foreach ($post['items'] ?? [] as $i => $itemPost) {
        if (!isset($promotions['items'][$i])) {
            continue;
        }

        if (!empty($itemPost['remove'])) {
            continue;
        }

        $item = $promotions['items'][$i];
        $item['text'] = trim($itemPost['text'] ?? '');
        $item['image'] = trim($itemPost['image'] ?? $item['image']);

        $uploadedFile = admin_file_from_nested_upload($files, 'promotion_images', (int) $i);
        if ($uploadedFile) {
            $uploadedPath = admin_uploaded_image_path($uploadedFile, 'acties');
            if ($uploadedPath) {
                $item['image'] = $uploadedPath;
            }
        }

        $items[] = $item;
    }

    $newText = trim($post['new_text'] ?? '');
    $newImage = trim($post['new_image'] ?? '');
    if (!empty($files['new_promotion_image'])) {
        $uploadedPath = admin_uploaded_image_path($files['new_promotion_image'], 'acties');
        if ($uploadedPath) {
            $newImage = $uploadedPath;
        }
    }

    if ($newText !== '' && $newImage !== '') {
        $items[] = [
            'col' => 'col-md-4',
            'image' => $newImage,
            'text' => $newText,
        ];
    }

    $promotions['items'] = $items;

    return $promotions;
}

function admin_apply_gallery_from_post(array $gallery, array $post, array $files = []): array
{
    $items = [];

    foreach ($post['items'] ?? [] as $i => $itemPost) {
        if (!isset($gallery['items'][$i]) || !empty($itemPost['remove'])) {
            continue;
        }

        $item = $gallery['items'][$i];
        $item['alt'] = trim($itemPost['alt'] ?? ($item['alt'] ?? 'Portfolio foto'));
        $items[] = $item;
    }

    foreach ($files['gallery_images']['name'] ?? [] as $i => $name) {
        $uploadedFile = admin_file_from_nested_upload($files, 'gallery_images', (int) $i);
        if (!$uploadedFile) {
            continue;
        }

        $uploadedPath = admin_uploaded_image_path($uploadedFile, 'gallery');
        if ($uploadedPath) {
            array_unshift($items, [
                'image' => $uploadedPath,
                'alt' => 'Portfolio foto',
            ]);
        }
    }

    $gallery['items'] = $items;

    return $gallery;
}

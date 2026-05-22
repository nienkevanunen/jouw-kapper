<?php

function data_path(string $filename): string
{
    return __DIR__ . '/../data/' . $filename;
}

function load_content(string $filename): array
{
    $path = data_path($filename);
    if (!is_file($path)) {
        return [];
    }

    $json = file_get_contents($path);
    $data = json_decode($json, true);

    return is_array($data) ? $data : [];
}

function content_text(array $content, string $path, string $fallback = ''): string
{
    $value = $content;

    foreach (explode('.', $path) as $part) {
        if (!is_array($value) || !array_key_exists($part, $value)) {
            return $fallback;
        }

        $value = $value[$part];
    }

    return is_scalar($value) ? (string) $value : $fallback;
}

function format_copyright_notice(string $suffix, int $startYear = 2019): string
{
    $currentYear = (int) date('Y');
    $yearRange = $currentYear > $startYear ? $startYear . '-' . $currentYear : (string) $startYear;

    return '© ' . $yearRange . ' ' . ltrim($suffix);
}

function content_lines(string $text): array
{
    $lines = [];

    foreach (preg_split('/\r\n|\r|\n/', $text) as $line) {
        $line = trim($line);
        if ($line !== '') {
            $lines[] = $line;
        }
    }

    return $lines;
}

function content_nl2br(string $text): string
{
    return nl2br(htmlspecialchars($text, ENT_QUOTES, 'UTF-8'), false);
}

function save_content(string $filename, array $data): bool
{
    $json = json_encode(
        $data,
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
    );

    if ($json === false) {
        return false;
    }

    return file_put_contents(data_path($filename), $json . "\n", LOCK_EX) !== false;
}

function get_active_address(array $site): array
{
    $today = new DateTime();
    $addresses = $site['addresses'] ?? [];

    foreach ($addresses as $address) {
        if (!empty($address['from'])) {
            $from = new DateTime($address['from']);
            if ($today < $from) {
                continue;
            }
        }

        if (!empty($address['until'])) {
            $until = new DateTime($address['until']);
            if ($today >= $until) {
                continue;
            }
        }

        return $address;
    }

    return $addresses[0] ?? [];
}

function get_schedule_variable(string $name, array $openingHours): string
{
    $today = new DateTime();
    $value = $openingHours['defaults'][$name] ?? '';

    foreach ($openingHours['dateRules'] ?? [] as $rule) {
        if (empty($rule['from'])) {
            continue;
        }

        $from = new DateTime($rule['from']);
        if ($today >= $from && isset($rule['variables'][$name])) {
            $value = $rule['variables'][$name];
        }
    }

    return $value;
}

function format_price(string $price): string
{
    if ($price === '' || stripos($price, '€') !== false || stripos($price, 'overleg') !== false) {
        return $price;
    }

    return '€' . $price;
}

function render_price_row(array $line): void
{
    $muted = !empty($line['muted']);
    $labelClass = $muted ? 'text-muted' : '';
    $priceTag = $muted ? 'div' : 'b';
    $price = format_price($line['price'] ?? '');

    echo '<div class="d-flex justify-content-between align-items-center">';
    echo '<div class="' . $labelClass . '">' . htmlspecialchars($line['text']) . '</div>';
    if ($price !== '') {
        echo '<div><' . $priceTag . '>' . htmlspecialchars($price) . '</' . $priceTag . '></div>';
    } else {
        echo '<div></div>';
    }
    echo '</div>';
}

function render_price_section(array $section): void
{
    echo '<div class="col-md-12 col-xs-12">';
    echo '<div class="service-card">';
    echo '<div class="service-card-heading d-flex justify-content-between align-items-center">';
    echo '<h3>' . htmlspecialchars($section['title']) . '</h3>';
    if (!empty($section['vanaf'])) {
        echo '<div class="service-from">vanaf</div>';
    }
    echo '</div>';
    echo '<ul class="list-group list-group-flush service-list">';

    foreach ($section['entries'] ?? [] as $entry) {
        echo '<li class="list-group-item';

        if (!empty($entry['text']) && empty($entry['lines']) && empty($entry['group']) && empty($entry['note'])) {
            echo ' d-flex justify-content-between align-items-center';
        }

        echo '">';

        if (!empty($entry['note']) && empty($entry['group']) && empty($entry['lines']) && empty($entry['text'])) {
            echo '<small class="text-muted">' . htmlspecialchars($entry['note']) . '</small>';
        } elseif (!empty($entry['group'])) {
            echo '<div class="d-flex justify-content-between align-items-center"><strong>' . htmlspecialchars($entry['group']) . '</strong><div></div></div>';
            foreach ($entry['lines'] ?? [] as $line) {
                render_price_row($line);
            }
            if (!empty($entry['note'])) {
                echo '<small class="text-muted">' . htmlspecialchars($entry['note']) . '</small>';
            }
            if (!empty($entry['bullets'])) {
                echo '<small class="text-muted"><ul style="margin-bottom: 0; padding-left: 1.5rem;">';
                foreach ($entry['bullets'] as $bullet) {
                    echo '<li>' . htmlspecialchars($bullet) . '</li>';
                }
                echo '</ul></small>';
            }
        } elseif (!empty($entry['lines'])) {
            foreach ($entry['lines'] as $line) {
                render_price_row($line);
            }
        } elseif (!empty($entry['text'])) {
            $price = format_price($entry['price'] ?? '');
            echo htmlspecialchars($entry['text']) . ' <div><b>' . htmlspecialchars($price) . '</b></div>';
        }

        echo '</li>';
    }

    echo '</ul>';
    echo '</div>';
    echo '</div>';
}

function render_price_sections(array $prices): void
{
    foreach ($prices['columns'] ?? [] as $column) {
        echo '<div class="col-lg-6">';
        echo '<div class="row">';
        foreach ($column['sections'] ?? [] as $section) {
            render_price_section($section);
        }
        echo '</div>';
        echo '</div>';
    }
}

function render_opening_hours(array $openingHours, array $variables = []): void
{
    foreach ($openingHours['days'] ?? [] as $day) {
        $hours = $day['hours'];

        foreach ($variables as $key => $value) {
            $hours = str_replace('{' . $key . '}', $value, $hours);
        }

        echo '<li class="list-group-item d-flex justify-content-between align-items-center">';
        echo htmlspecialchars($day['day']) . ' <b>' . htmlspecialchars($hours) . '</b>';
        echo '</li>';
    }
}

function render_promotions(array $promotions): void
{
    foreach ($promotions['items'] ?? [] as $item) {
        $colClass = $item['col'] ?? 'col-md-4';
        echo '<div class="' . htmlspecialchars($colClass) . '">';
        echo '<div class="card mb-4 box-shadow promo-card">';
        echo '<img class="card-img-top" src="' . htmlspecialchars($item['image']) . '" alt="">';
        echo '<div class="card-body">';
        echo '<p class="card-text">' . htmlspecialchars($item['text']) . '</p>';
        echo '</div></div></div>';
    }
}

function render_gallery(array $gallery): void
{
    foreach ($gallery['items'] ?? [] as $item) {
        if (empty($item['image'])) {
            continue;
        }

        $image = htmlspecialchars($item['image']);
        $alt = htmlspecialchars($item['alt'] ?? 'Portfolio foto');
        echo '<a href="' . $image . '" class="venobox" data-gall="gallery-carousel">';
        echo '<img src="' . $image . '" alt="' . $alt . '">';
        echo '</a>';
    }
}

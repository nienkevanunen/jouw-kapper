<?php

require_once __DIR__ . '/../includes/content.php';
require_once __DIR__ . '/../includes/admin-auth.php';

admin_start_session();

$message = '';
$messageType = 'success';
$tab = $_GET['tab'] ?? 'prijzen';

if (isset($_GET['logout'])) {
    admin_logout();
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    if (!admin_is_configured()) {
        $message = 'Beheer is nog niet ingesteld. Vraag je webbouwer om het wachtwoord te configureren.';
        $messageType = 'danger';
    } elseif (admin_login($_POST['password'] ?? '')) {
        header('Location: index.php?tab=' . urlencode($tab));
        exit;
    } else {
        $message = 'Onjuist wachtwoord.';
        $messageType = 'danger';
    }
}

if (admin_is_logged_in() && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save'])) {
    if (!admin_verify_csrf()) {
        $message = 'Ongeldige sessie. Probeer opnieuw.';
        $messageType = 'danger';
    } else {
        $form = $_POST['form'] ?? '';
        $ok = false;

        switch ($form) {
            case 'prijzen':
                $prices = load_content('prices.json');
                $prices = admin_apply_prices_from_post($prices, $_POST['prices'] ?? []);
                $ok = save_content('prices.json', $prices);
                $tab = 'prijzen';
                break;
            case 'teksten':
                $pageText = load_content('page-text.json');
                $pageText = admin_apply_page_text_from_post($pageText, $_POST['page_text'] ?? []);
                $ok = save_content('page-text.json', $pageText);
                $tab = 'teksten';
                break;
            case 'openingstijden':
                $hours = load_content('opening-hours.json');
                $hours = admin_apply_opening_hours_from_post($hours, $_POST);
                $ok = save_content('opening-hours.json', $hours);
                $tab = 'openingstijden';
                break;
            case 'contact':
                $site = load_content('site.json');
                $site = admin_apply_site_from_post($site, $_POST);
                $ok = save_content('site.json', $site);
                $tab = 'contact';
                break;
            case 'acties':
                $promotions = load_content('promotions.json');
                $promotions = admin_apply_promotions_from_post($promotions, $_POST, $_FILES);
                $ok = save_content('promotions.json', $promotions);
                $tab = 'acties';
                break;
            case 'portfolio':
                $gallery = load_content('gallery.json');
                $gallery = admin_apply_gallery_from_post($gallery, $_POST, $_FILES);
                $ok = save_content('gallery.json', $gallery);
                $tab = 'portfolio';
                break;
        }

        if ($ok) {
            $message = 'Opgeslagen! Bekijk de website om het resultaat te controleren.';
            $messageType = 'success';
        } else {
            $message = 'Opslaan mislukt. Probeer het opnieuw.';
            $messageType = 'danger';
        }
    }
}

function admin_h(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function admin_price_entry_type(array $entry): string
{
    if (!empty($entry['note']) && empty($entry['group']) && empty($entry['lines']) && empty($entry['text'])) {
        return 'note';
    }

    if (!empty($entry['group']) || !empty($entry['lines']) || !empty($entry['bullets'])) {
        return 'group';
    }

    return 'simple';
}

function admin_price_bullets_text(array $entry): string
{
    return implode("\n", $entry['bullets'] ?? []);
}

function admin_type_selected(string $actual, string $expected): string
{
    return $actual === $expected ? ' selected' : '';
}

function admin_text_value(array $text, string $path): string
{
    return content_text($text, $path);
}

function admin_render_text_input(array $text, string $path, string $label, bool $multiline = false, int $rows = 3): void
{
    $name = 'page_text';
    foreach (explode('.', $path) as $part) {
        $name .= '[' . $part . ']';
    }

    echo '<div class="form-group">';
    echo '<label>' . admin_h($label) . '</label>';
    if ($multiline) {
        echo '<textarea class="form-control" rows="' . $rows . '" name="' . admin_h($name) . '">' . admin_h(admin_text_value($text, $path)) . '</textarea>';
    } else {
        echo '<input type="text" class="form-control" name="' . admin_h($name) . '" value="' . admin_h(admin_text_value($text, $path)) . '">';
    }
    echo '</div>';
}

function admin_render_price_line(array $line, string $prefix): void
{
    echo '<div class="price-line border rounded p-2 mb-2" data-price-line>';
    echo '<div class="form-row align-items-end">';
    echo '<div class="col-md-5 form-group mb-md-0">';
    echo '<label class="small mb-1">Naam</label>';
    echo '<input type="text" class="form-control form-control-sm" name="' . admin_h($prefix) . '[text]" value="' . admin_h($line['text'] ?? '') . '" placeholder="Bijv. Kort">';
    echo '</div>';
    echo '<div class="col-md-3 form-group mb-md-0">';
    echo '<label class="small mb-1">Prijs</label>';
    echo '<input type="text" class="form-control form-control-sm" name="' . admin_h($prefix) . '[price]" value="' . admin_h($line['price'] ?? '') . '" placeholder="33,50">';
    echo '</div>';
    echo '<div class="col-md-2 form-group mb-md-0">';
    echo '<div class="form-check mt-md-4">';
    echo '<input class="form-check-input" type="checkbox" name="' . admin_h($prefix) . '[muted]" value="1"' . (!empty($line['muted']) ? ' checked' : '') . '>';
    echo '<label class="form-check-label small">Subtiel</label>';
    echo '</div>';
    echo '</div>';
    echo '<div class="col-md-2 text-md-right">';
    echo '<button type="button" class="btn btn-sm btn-outline-danger" data-remove-line>Verwijder</button>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

function admin_render_price_entry(array $entry, string $prefix): void
{
    $type = admin_price_entry_type($entry);
    $lines = $entry['lines'] ?? [];

    echo '<div class="price-entry border rounded p-3 mb-3" data-price-entry>';
    echo '<div class="d-flex justify-content-between align-items-start mb-3">';
    echo '<div class="form-group mb-0 price-entry-type">';
    echo '<label class="small mb-1">Type</label>';
    echo '<select class="form-control form-control-sm" name="' . admin_h($prefix) . '[type]" data-entry-type>';
    echo '<option value="simple"' . admin_type_selected($type, 'simple') . '>Prijsregel</option>';
    echo '<option value="group"' . admin_type_selected($type, 'group') . '>Subcategorie</option>';
    echo '<option value="note"' . admin_type_selected($type, 'note') . '>Opmerking</option>';
    echo '</select>';
    echo '</div>';
    echo '<button type="button" class="btn btn-sm btn-outline-danger" data-remove-entry>Verwijder item</button>';
    echo '</div>';

    echo '<div data-entry-panel="simple">';
    echo '<div class="form-row">';
    echo '<div class="col-md-7 form-group">';
    echo '<label class="small">Naam</label>';
    echo '<input type="text" class="form-control form-control-sm" name="' . admin_h($prefix) . '[text]" value="' . admin_h($entry['text'] ?? '') . '" placeholder="Bijv. Pony knippen">';
    echo '</div>';
    echo '<div class="col-md-5 form-group">';
    echo '<label class="small">Prijs</label>';
    echo '<input type="text" class="form-control form-control-sm" name="' . admin_h($prefix) . '[price]" value="' . admin_h($entry['price'] ?? '') . '" placeholder="10,00 of In overleg">';
    echo '</div>';
    echo '</div>';
    echo '</div>';

    echo '<div data-entry-panel="group">';
    echo '<div class="form-group">';
    echo '<label class="small">Subcategorie titel <span class="text-muted">(optioneel)</span></label>';
    echo '<input type="text" class="form-control form-control-sm" name="' . admin_h($prefix) . '[group]" value="' . admin_h($entry['group'] ?? '') . '" placeholder="Bijv. Blow out">';
    echo '</div>';
    echo '<div class="price-lines" data-lines>';
    foreach ($lines as $li => $line) {
        admin_render_price_line($line, $prefix . '[lines][' . $li . ']');
    }
    echo '</div>';
    echo '<button type="button" class="btn btn-sm btn-outline-secondary mb-3" data-add-line>Prijs toevoegen</button>';
    echo '<div class="form-group">';
    echo '<label class="small">Opmerking onder subcategorie <span class="text-muted">(optioneel)</span></label>';
    echo '<input type="text" class="form-control form-control-sm" name="' . admin_h($prefix) . '[note]" value="' . admin_h($type === 'group' ? ($entry['note'] ?? '') : '') . '">';
    echo '</div>';
    echo '<div class="form-group mb-0">';
    echo '<label class="small">Bulletpunten <span class="text-muted">(optioneel, één per regel)</span></label>';
    echo '<textarea class="form-control form-control-sm" rows="3" name="' . admin_h($prefix) . '[bullets]">' . admin_h(admin_price_bullets_text($entry)) . '</textarea>';
    echo '</div>';
    echo '</div>';

    echo '<div data-entry-panel="note">';
    echo '<div class="form-group mb-0">';
    echo '<label class="small">Opmerking</label>';
    echo '<input type="text" class="form-control form-control-sm" name="' . admin_h($prefix) . '[note]" value="' . admin_h($type === 'note' ? ($entry['note'] ?? '') : '') . '" placeholder="Bijv. Elke knipbeurt is met droog föhnen">';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

$pageTitle = admin_is_logged_in() ? 'Website beheer' : 'Inloggen';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <title><?php echo admin_h($pageTitle); ?> – Jouw Kapper</title>
  <link href="../lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f4f4f4; }
    .admin-header { background: #2c2c2c; color: #fff; padding: 1rem 0; margin-bottom: 2rem; }
    .admin-header a { color: #fff; }
    .admin-header .btn-light { color: #2c2c2c; }
    .nav-tabs .nav-link { color: #555; }
    .nav-tabs .nav-link.active { font-weight: 600; }
    .price-section-title { border-bottom: 2px solid #ddd; padding-bottom: .5rem; margin: 1.5rem 0 1rem; }
    .card-form { background: #fff; border-radius: 6px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
    .login-box { max-width: 400px; margin: 4rem auto; }
    .admin-thumb { width: 120px; height: 90px; object-fit: cover; border-radius: 4px; background: #eee; }
    .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(170px, 1fr)); gap: 1rem; }
    .price-column { background: #fafafa; border: 1px solid #e5e5e5; border-radius: 6px; padding: 1rem; }
    .price-section-card { background: #fff; border: 1px solid #ddd; border-radius: 6px; padding: 1rem; margin-bottom: 1rem; }
    .price-entry { background: #fbfbfb; }
    .price-line { background: #fff; }
    .price-entry-type { min-width: 180px; }
    [data-entry-panel] { display: none; }
  </style>
</head>
<body>

<header class="admin-header">
  <div class="container d-flex justify-content-between align-items-center">
    <strong>Jouw Kapper – beheer</strong>
    <?php if (admin_is_logged_in()) : ?>
      <div>
        <a href="../index.php" class="btn btn-sm btn-outline-light mr-2" target="_blank">Bekijk website</a>
        <a href="?logout=1" class="btn btn-sm btn-light">Uitloggen</a>
      </div>
    <?php endif; ?>
  </div>
</header>

<div class="container pb-5">

<?php if ($message) : ?>
  <div class="alert alert-<?php echo admin_h($messageType); ?>"><?php echo admin_h($message); ?></div>
<?php endif; ?>

<?php if (!admin_is_configured()) : ?>
  <div class="alert alert-warning">
    Beheer is nog niet actief. Stel eerst een wachtwoord in via de server (zie <code>data/README.md</code>).
  </div>
<?php endif; ?>

<?php if (!admin_is_logged_in()) : ?>
  <div class="login-box card-form">
    <h2 class="h4 mb-4">Inloggen</h2>
    <form method="post">
      <input type="hidden" name="login" value="1">
      <div class="form-group">
        <label for="password">Wachtwoord</label>
        <input type="password" class="form-control" id="password" name="password" required autofocus>
      </div>
      <button type="submit" class="btn btn-dark btn-block" <?php echo admin_is_configured() ? '' : 'disabled'; ?>>Inloggen</button>
    </form>
  </div>
<?php else :

$prices = load_content('prices.json');
$pageText = load_content('page-text.json');
$hours = load_content('opening-hours.json');
$site = load_content('site.json');
$promotions = load_content('promotions.json');
$gallery = load_content('gallery.json');
$csrf = admin_csrf_token();
?>

<ul class="nav nav-tabs mb-4">
  <li class="nav-item"><a class="nav-link <?php echo $tab === 'prijzen' ? 'active' : ''; ?>" href="?tab=prijzen">Prijzen</a></li>
  <li class="nav-item"><a class="nav-link <?php echo $tab === 'teksten' ? 'active' : ''; ?>" href="?tab=teksten">Teksten</a></li>
  <li class="nav-item"><a class="nav-link <?php echo $tab === 'openingstijden' ? 'active' : ''; ?>" href="?tab=openingstijden">Openingstijden</a></li>
  <li class="nav-item"><a class="nav-link <?php echo $tab === 'contact' ? 'active' : ''; ?>" href="?tab=contact">Contact</a></li>
  <li class="nav-item"><a class="nav-link <?php echo $tab === 'portfolio' ? 'active' : ''; ?>" href="?tab=portfolio">Portfolio</a></li>
  <li class="nav-item"><a class="nav-link <?php echo $tab === 'acties' ? 'active' : ''; ?>" href="?tab=acties">Acties</a></li>
</ul>

<?php if ($tab === 'prijzen') : ?>
<div class="card-form">
  <p class="text-muted">
    Pas categorieën, subcategorieën en prijzen aan. Vul prijzen zonder €-teken in, bijvoorbeeld <code>33,50</code>, of gebruik tekst zoals <code>In overleg</code>.
  </p>
  <form method="post" id="prices-form">
    <input type="hidden" name="save" value="1">
    <input type="hidden" name="form" value="prijzen">
    <input type="hidden" name="csrf" value="<?php echo admin_h($csrf); ?>">

    <div id="price-editor" class="row" data-price-editor>
      <?php foreach ($prices['columns'] ?? [] as $ci => $column) : ?>
        <div class="col-lg-6 mb-4">
          <div class="price-column" data-price-column="<?php echo $ci; ?>">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h3 class="h5 mb-0">Prijskolom <?php echo $ci + 1; ?></h3>
              <button type="button" class="btn btn-sm btn-outline-secondary" data-add-section>Categorie toevoegen</button>
            </div>

            <div data-sections>
              <?php foreach ($column['sections'] ?? [] as $si => $section) : ?>
                <div class="price-section-card" data-price-section>
                  <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="form-group flex-grow-1 mb-0 mr-3">
                      <label class="small">Categorienaam</label>
                      <input type="text" class="form-control" name="prices[columns][<?php echo $ci; ?>][sections][<?php echo $si; ?>][title]" value="<?php echo admin_h($section['title'] ?? ''); ?>" placeholder="Bijv. Knippen Unisex">
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger mt-4" data-remove-section>Verwijder categorie</button>
                  </div>

                  <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="prices[columns][<?php echo $ci; ?>][sections][<?php echo $si; ?>][vanaf]" value="1" id="price-vanaf-<?php echo $ci; ?>-<?php echo $si; ?>" <?php echo !empty($section['vanaf']) ? 'checked' : ''; ?>>
                    <label class="form-check-label small" for="price-vanaf-<?php echo $ci; ?>-<?php echo $si; ?>">Toon “vanaf” bij deze categorie</label>
                  </div>

                  <div data-entries>
                    <?php foreach ($section['entries'] ?? [] as $ei => $entry) :
                      $prefix = "prices[columns][{$ci}][sections][{$si}][entries][{$ei}]";
                      admin_render_price_entry($entry, $prefix);
                    endforeach; ?>
                  </div>

                  <div class="btn-group btn-group-sm" role="group" aria-label="Prijs items toevoegen">
                    <button type="button" class="btn btn-outline-secondary" data-add-entry="simple">Prijsregel toevoegen</button>
                    <button type="button" class="btn btn-outline-secondary" data-add-entry="group">Subcategorie toevoegen</button>
                    <button type="button" class="btn btn-outline-secondary" data-add-entry="note">Opmerking toevoegen</button>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="d-flex flex-wrap align-items-center mt-3">
      <button type="submit" class="btn btn-dark mr-2 mb-2">Prijzen opslaan</button>
      <button type="button" class="btn btn-outline-secondary mb-2" data-reset-prices>Reset naar laatst opgeslagen versie</button>
      <small class="text-muted ml-md-3 mb-2">Reset werkt zolang je nog niet op opslaan hebt gedrukt.</small>
    </div>
  </form>
</div>

<?php elseif ($tab === 'teksten') : ?>
<div class="card-form">
  <p class="text-muted">Pas de vaste teksten op de website aan. Gebruik bij velden met meerdere regels één item per regel.</p>
  <form method="post">
    <input type="hidden" name="save" value="1">
    <input type="hidden" name="form" value="teksten">
    <input type="hidden" name="csrf" value="<?php echo admin_h($csrf); ?>">

    <h4 class="h5 mb-3">SEO / browser</h4>
    <?php admin_render_text_input($pageText, 'meta.title', 'Paginatitel'); ?>
    <?php admin_render_text_input($pageText, 'meta.description', 'Omschrijving voor zoekmachines', true, 4); ?>
    <?php admin_render_text_input($pageText, 'meta.keywords', 'Zoekwoorden', true, 4); ?>

    <hr class="my-4">
    <h4 class="h5 mb-3">Menu</h4>
    <div class="form-row">
      <div class="col-md-4"><?php admin_render_text_input($pageText, 'nav.home', 'Home'); ?></div>
      <div class="col-md-4"><?php admin_render_text_input($pageText, 'nav.services', 'Diensten'); ?></div>
      <div class="col-md-4"><?php admin_render_text_input($pageText, 'nav.hours', 'Openingstijden'); ?></div>
      <div class="col-md-4"><?php admin_render_text_input($pageText, 'nav.updates', 'Updates'); ?></div>
      <div class="col-md-4"><?php admin_render_text_input($pageText, 'nav.portfolio', 'Portfolio'); ?></div>
      <div class="col-md-4"><?php admin_render_text_input($pageText, 'nav.promotions', 'Acties'); ?></div>
      <div class="col-md-4"><?php admin_render_text_input($pageText, 'nav.contact', 'Contact'); ?></div>
    </div>

    <hr class="my-4">
    <h4 class="h5 mb-3">Bovenaan de pagina</h4>
    <?php admin_render_text_input($pageText, 'intro.logoAlt', 'Logo alt-tekst'); ?>
    <?php admin_render_text_input($pageText, 'intro.bookingLinkText', 'Afspraak-link tekst'); ?>
    <?php admin_render_text_input($pageText, 'intro.videoFallback', 'Video fallback tekst'); ?>
    <div class="form-row">
      <div class="col-md-6"><?php admin_render_text_input($pageText, 'intro.partnerPrefix', 'Partner tekst'); ?></div>
      <div class="col-md-6"><?php admin_render_text_input($pageText, 'intro.partnerName', 'Partner naam'); ?></div>
    </div>

    <hr class="my-4">
    <h4 class="h5 mb-3">Secties</h4>
    <div class="form-row">
      <div class="col-md-6"><?php admin_render_text_input($pageText, 'sections.servicesTitle', 'Diensten titel'); ?></div>
      <div class="col-md-6"><?php admin_render_text_input($pageText, 'sections.servicesText', 'Diensten tekst'); ?></div>
      <div class="col-md-6"><?php admin_render_text_input($pageText, 'sections.hoursTitle', 'Openingstijden titel'); ?></div>
      <div class="col-md-6"><?php admin_render_text_input($pageText, 'sections.hoursText', 'Openingstijden tekst'); ?></div>
      <div class="col-md-6"><?php admin_render_text_input($pageText, 'sections.portfolioTitle', 'Portfolio titel'); ?></div>
      <div class="col-md-6"><?php admin_render_text_input($pageText, 'sections.portfolioText', 'Portfolio tekst'); ?></div>
      <div class="col-md-6"><?php admin_render_text_input($pageText, 'sections.promotionsTitle', 'Acties titel'); ?></div>
      <div class="col-md-6"><?php admin_render_text_input($pageText, 'sections.promotionsText', 'Acties tekst'); ?></div>
      <div class="col-md-4"><?php admin_render_text_input($pageText, 'sections.contactTitle', 'Contact titel'); ?></div>
      <div class="col-md-4"><?php admin_render_text_input($pageText, 'sections.contactTextBeforeLink', 'Contact tekst voor link'); ?></div>
      <div class="col-md-2"><?php admin_render_text_input($pageText, 'sections.contactLinkText', 'Contact linktekst'); ?></div>
      <div class="col-md-2"><?php admin_render_text_input($pageText, 'sections.contactTextAfterLink', 'Contact tekst na link'); ?></div>
    </div>

    <hr class="my-4">
    <h4 class="h5 mb-3">Facebook updates</h4>
    <?php admin_render_text_input($pageText, 'updates.eyebrow', 'Label boven titel'); ?>
    <?php admin_render_text_input($pageText, 'updates.title', 'Titel'); ?>
    <?php admin_render_text_input($pageText, 'updates.text', 'Tekst', true, 3); ?>
    <?php admin_render_text_input($pageText, 'updates.pills', 'Labels onder tekst (één per regel)', true, 3); ?>
    <?php admin_render_text_input($pageText, 'updates.buttonText', 'Knoptekst'); ?>
    <?php admin_render_text_input($pageText, 'updates.feedLinkText', 'Fallback link in Facebook blok'); ?>
    <div class="form-row">
      <div class="col-md-5"><?php admin_render_text_input($pageText, 'updates.fallbackBeforeLink', 'Fallback tekst voor link'); ?></div>
      <div class="col-md-3"><?php admin_render_text_input($pageText, 'updates.fallbackLinkText', 'Fallback linktekst'); ?></div>
      <div class="col-md-4"><?php admin_render_text_input($pageText, 'updates.fallbackAfterLink', 'Fallback tekst na link'); ?></div>
    </div>

    <hr class="my-4">
    <h4 class="h5 mb-3">Contactformulier</h4>
    <?php admin_render_text_input($pageText, 'contactForm.successMessage', 'Succesmelding'); ?>
    <div class="form-row">
      <div class="col-md-4"><?php admin_render_text_input($pageText, 'contactForm.namePlaceholder', 'Naam placeholder'); ?></div>
      <div class="col-md-4"><?php admin_render_text_input($pageText, 'contactForm.nameValidation', 'Naam foutmelding'); ?></div>
      <div class="col-md-4"><?php admin_render_text_input($pageText, 'contactForm.emailPlaceholder', 'E-mail placeholder'); ?></div>
      <div class="col-md-4"><?php admin_render_text_input($pageText, 'contactForm.emailValidation', 'E-mail foutmelding'); ?></div>
      <div class="col-md-4"><?php admin_render_text_input($pageText, 'contactForm.subjectPlaceholder', 'Onderwerp placeholder'); ?></div>
      <div class="col-md-4"><?php admin_render_text_input($pageText, 'contactForm.subjectValidation', 'Onderwerp foutmelding'); ?></div>
      <div class="col-md-6"><?php admin_render_text_input($pageText, 'contactForm.messagePlaceholder', 'Bericht placeholder'); ?></div>
      <div class="col-md-6"><?php admin_render_text_input($pageText, 'contactForm.messageValidation', 'Bericht foutmelding'); ?></div>
      <div class="col-md-6"><?php admin_render_text_input($pageText, 'contactForm.submitButton', 'Verzendknop'); ?></div>
    </div>

    <hr class="my-4">
    <h4 class="h5 mb-3">Verhuis-popup</h4>
    <?php admin_render_text_input($pageText, 'modal.title', 'Titel'); ?>
    <?php admin_render_text_input($pageText, 'modal.closeLabel', 'Sluit-label'); ?>
    <?php admin_render_text_input($pageText, 'modal.lead', 'Hoofdtekst'); ?>
    <?php admin_render_text_input($pageText, 'modal.address', 'Adres', true, 2); ?>
    <?php admin_render_text_input($pageText, 'modal.buttonText', 'Knoptekst'); ?>
    <?php admin_render_text_input($pageText, 'modal.footer', 'Kleine tekst onderaan'); ?>

    <hr class="my-4">
    <h4 class="h5 mb-3">Footer</h4>
    <?php admin_render_text_input($pageText, 'footer.logoAlt', 'Logo alt-tekst'); ?>
    <?php admin_render_text_input($pageText, 'footer.aboutText', 'Over tekst', true, 4); ?>
    <div class="form-row">
      <div class="col-md-4"><?php admin_render_text_input($pageText, 'footer.linksTitle', 'Links titel'); ?></div>
      <div class="col-md-4"><?php admin_render_text_input($pageText, 'footer.contactTitle', 'Contact titel'); ?></div>
      <div class="col-md-4"><?php admin_render_text_input($pageText, 'footer.facebookTitle', 'Facebook titel'); ?></div>
      <div class="col-md-4"><?php admin_render_text_input($pageText, 'footer.country', 'Land'); ?></div>
      <div class="col-md-4"><?php admin_render_text_input($pageText, 'footer.mobileLabel', 'Mobiel label'); ?></div>
      <div class="col-md-4"><?php admin_render_text_input($pageText, 'footer.emailLabel', 'E-mail label'); ?></div>
      <div class="col-md-4"><?php admin_render_text_input($pageText, 'footer.copyright', 'Copyright (jaar wordt automatisch toegevoegd)'); ?></div>
    </div>
    <?php admin_render_text_input($pageText, 'footer.facebookText', 'Facebook tekst', true, 2); ?>

    <button type="submit" class="btn btn-dark mt-3">Teksten opslaan</button>
    <button type="reset" class="btn btn-outline-secondary mt-3 ml-2">Reset naar laatst opgeslagen versie</button>
  </form>
</div>

<?php elseif ($tab === 'openingstijden') : ?>
<div class="card-form">
  <form method="post">
    <input type="hidden" name="save" value="1">
    <input type="hidden" name="form" value="openingstijden">
    <input type="hidden" name="csrf" value="<?php echo admin_h($csrf); ?>">

    <h4 class="h5 mb-3">Dagelijkse tijden</h4>
    <?php foreach ($hours['days'] ?? [] as $i => $day) : ?>
      <div class="form-row align-items-center mb-2">
        <div class="col-4"><strong><?php echo admin_h($day['day']); ?></strong></div>
        <div class="col-8">
          <input type="text" class="form-control" name="days[<?php echo $i; ?>][hours]" value="<?php echo admin_h($day['hours']); ?>">
          <?php if (strpos($day['hours'], '{') !== false) : ?>
            <small class="text-muted">Gebruik <code>{zaterdag_sluit}</code> voor het wisselende sluitingstijdstip</small>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>

    <hr class="my-4">
    <h4 class="h5 mb-3">Zaterdag (seizoen)</h4>
    <div class="form-group">
      <label>Standaard sluitingstijd (vóór de wisseldatum)</label>
      <input type="text" class="form-control col-md-4" name="default_zaterdag_sluit" value="<?php echo admin_h($hours['defaults']['zaterdag_sluit'] ?? ''); ?>" placeholder="13:30">
    </div>
    <?php if (!empty($hours['dateRules'][0])) : ?>
    <div class="form-group">
      <label>Vanaf datum (nieuwe tijden gelden vanaf)</label>
      <input type="date" class="form-control col-md-4" name="rule_from" value="<?php echo admin_h($hours['dateRules'][0]['from'] ?? ''); ?>">
    </div>
    <div class="form-group">
      <label>Sluitingstijd zaterdag vanaf die datum</label>
      <input type="text" class="form-control col-md-4" name="rule_zaterdag_sluit" value="<?php echo admin_h($hours['dateRules'][0]['variables']['zaterdag_sluit'] ?? ''); ?>" placeholder="16:30">
    </div>
    <?php endif; ?>

    <button type="submit" class="btn btn-dark mt-3">Openingstijden opslaan</button>
  </form>
</div>

<?php elseif ($tab === 'contact') : ?>
<div class="card-form">
  <form method="post">
    <input type="hidden" name="save" value="1">
    <input type="hidden" name="form" value="contact">
    <input type="hidden" name="csrf" value="<?php echo admin_h($csrf); ?>">

    <h4 class="h5 mb-3">Telefoon & e-mail</h4>
    <div class="form-group">
      <label>Telefoon (weergave)</label>
      <input type="text" class="form-control" name="phone_display" value="<?php echo admin_h($site['phone']['display'] ?? ''); ?>">
    </div>
    <div class="form-group">
      <label>Telefoon (link, zonder spaties)</label>
      <input type="text" class="form-control" name="phone_tel" value="<?php echo admin_h($site['phone']['tel'] ?? ''); ?>" placeholder="+31650747279">
    </div>
    <div class="form-group">
      <label>Telefoon (footer)</label>
      <input type="text" class="form-control" name="phone_footer" value="<?php echo admin_h($site['phone']['footer'] ?? ''); ?>">
    </div>
    <div class="form-group">
      <label>E-mail</label>
      <input type="email" class="form-control" name="email" value="<?php echo admin_h($site['email'] ?? ''); ?>">
    </div>
    <div class="form-group">
      <label>Afspraak-link</label>
      <input type="url" class="form-control" name="bookingUrl" value="<?php echo admin_h($site['bookingUrl'] ?? ''); ?>">
    </div>
    <div class="form-group">
      <label>Facebook-pagina</label>
      <input type="url" class="form-control" name="facebookUrl" value="<?php echo admin_h($site['facebookUrl'] ?? ''); ?>">
    </div>

    <hr class="my-4">
    <h4 class="h5 mb-3">Adressen</h4>
    <?php foreach ($site['addresses'] ?? [] as $i => $addr) : ?>
      <div class="border rounded p-3 mb-3">
        <p class="mb-2">
          <strong>
            <?php if (!empty($addr['from'])) : ?>
              Huidig adres (vanaf <?php echo admin_h($addr['from']); ?>)
            <?php elseif (!empty($addr['until'])) : ?>
              Vorig adres (tot <?php echo admin_h($addr['until']); ?>)
            <?php else : ?>
              Adres <?php echo $i + 1; ?>
            <?php endif; ?>
          </strong>
        </p>
        <div class="form-group">
          <label>Straat</label>
          <input type="text" class="form-control" name="addresses[<?php echo $i; ?>][street]" value="<?php echo admin_h($addr['street'] ?? ''); ?>">
        </div>
        <div class="form-group">
          <label>Plaats / regel 2</label>
          <input type="text" class="form-control" name="addresses[<?php echo $i; ?>][line2]" value="<?php echo admin_h($addr['line2'] ?? ''); ?>">
        </div>
        <div class="form-group">
          <label>Volledig adres (HTML, gebruik &lt;br&gt; voor regels)</label>
          <input type="text" class="form-control" name="addresses[<?php echo $i; ?>][fullHtml]" value="<?php echo admin_h($addr['fullHtml'] ?? ''); ?>">
        </div>
        <div class="form-group mb-0">
          <label>Google Maps-link</label>
          <input type="url" class="form-control" name="addresses[<?php echo $i; ?>][mapsLink]" value="<?php echo admin_h($addr['mapsLink'] ?? ''); ?>">
        </div>
      </div>
    <?php endforeach; ?>

    <button type="submit" class="btn btn-dark mt-3">Contact opslaan</button>
  </form>
</div>

<?php elseif ($tab === 'acties') : ?>
<div class="card-form">
  <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="save" value="1">
    <input type="hidden" name="form" value="acties">
    <input type="hidden" name="csrf" value="<?php echo admin_h($csrf); ?>">

    <?php foreach ($promotions['items'] ?? [] as $i => $item) : ?>
      <div class="border rounded p-3 mb-3">
        <h4 class="h6">Actie <?php echo $i + 1; ?></h4>
        <?php if (!empty($item['image'])) : ?>
          <img class="admin-thumb mb-3" src="../<?php echo admin_h($item['image']); ?>" alt="">
        <?php endif; ?>
        <div class="form-group">
          <label>Afbeelding (pad)</label>
          <input type="text" class="form-control" name="items[<?php echo $i; ?>][image]" value="<?php echo admin_h($item['image'] ?? ''); ?>">
        </div>
        <div class="form-group">
          <label>Nieuwe afbeelding uploaden</label>
          <input type="file" class="form-control-file" name="promotion_images[<?php echo $i; ?>]" accept="image/jpeg,image/png,image/webp,image/gif">
          <small class="text-muted">Laat leeg om de huidige afbeelding te houden.</small>
        </div>
        <div class="form-group mb-0">
          <label>Tekst</label>
          <textarea class="form-control" name="items[<?php echo $i; ?>][text]" rows="3"><?php echo admin_h($item['text'] ?? ''); ?></textarea>
        </div>
        <div class="form-check mt-3">
          <input class="form-check-input" type="checkbox" name="items[<?php echo $i; ?>][remove]" value="1" id="remove-promotion-<?php echo $i; ?>">
          <label class="form-check-label text-danger" for="remove-promotion-<?php echo $i; ?>">Deze actie verwijderen</label>
        </div>
      </div>
    <?php endforeach; ?>

    <hr class="my-4">
    <h4 class="h5 mb-3">Nieuwe actie toevoegen</h4>
    <div class="border rounded p-3 mb-3">
      <div class="form-group">
        <label>Afbeelding uploaden</label>
        <input type="file" class="form-control-file" name="new_promotion_image" accept="image/jpeg,image/png,image/webp,image/gif">
      </div>
      <div class="form-group">
        <label>Of bestaand afbeeldingspad</label>
        <input type="text" class="form-control" name="new_image" placeholder="img/acties/voorbeeld.jpg">
      </div>
      <div class="form-group mb-0">
        <label>Tekst</label>
        <textarea class="form-control" name="new_text" rows="3"></textarea>
      </div>
    </div>

    <button type="submit" class="btn btn-dark mt-3">Acties opslaan</button>
  </form>
</div>

<?php elseif ($tab === 'portfolio') : ?>
<div class="card-form">
  <p class="text-muted">Upload nieuwe portfoliofoto's. Nieuwe foto's komen automatisch vooraan te staan.</p>
  <form method="post" enctype="multipart/form-data">
    <input type="hidden" name="save" value="1">
    <input type="hidden" name="form" value="portfolio">
    <input type="hidden" name="csrf" value="<?php echo admin_h($csrf); ?>">

    <div class="form-group">
      <label>Foto's toevoegen</label>
      <input type="file" class="form-control-file" name="gallery_images[]" accept="image/jpeg,image/png,image/webp,image/gif" multiple>
      <small class="text-muted">Je kunt meerdere bestanden tegelijk selecteren.</small>
    </div>

    <hr class="my-4">
    <h4 class="h5 mb-3">Huidige portfolio</h4>
    <div class="gallery-grid">
      <?php foreach ($gallery['items'] ?? [] as $i => $item) : ?>
        <div class="border rounded p-2">
          <?php if (!empty($item['image'])) : ?>
            <img class="admin-thumb mb-2" src="../<?php echo admin_h($item['image']); ?>" alt="">
            <small class="d-block text-muted mb-2"><?php echo admin_h($item['image']); ?></small>
          <?php endif; ?>
          <div class="form-group">
            <label class="small mb-1">Alt-tekst</label>
            <input type="text" class="form-control form-control-sm" name="items[<?php echo $i; ?>][alt]" value="<?php echo admin_h($item['alt'] ?? 'Portfolio foto'); ?>">
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="items[<?php echo $i; ?>][remove]" value="1" id="remove-gallery-<?php echo $i; ?>">
            <label class="form-check-label text-danger small" for="remove-gallery-<?php echo $i; ?>">Verwijderen</label>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <button type="submit" class="btn btn-dark mt-4">Portfolio opslaan</button>
  </form>
</div>
<?php endif; ?>

<?php endif; ?>

</div>
<script>
(function () {
  var form = document.getElementById('prices-form');
  if (!form) {
    return;
  }

  var editor = form.querySelector('[data-price-editor]');
  var initialEditorHtml = editor.innerHTML;
  var nextId = Date.now();

  function newKey(prefix) {
    nextId += 1;
    return prefix + nextId;
  }

  function priceLineHtml(prefix) {
    return '' +
      '<div class="price-line border rounded p-2 mb-2" data-price-line>' +
        '<div class="form-row align-items-end">' +
          '<div class="col-md-5 form-group mb-md-0">' +
            '<label class="small mb-1">Naam</label>' +
            '<input type="text" class="form-control form-control-sm" name="' + prefix + '[text]" placeholder="Bijv. Kort">' +
          '</div>' +
          '<div class="col-md-3 form-group mb-md-0">' +
            '<label class="small mb-1">Prijs</label>' +
            '<input type="text" class="form-control form-control-sm" name="' + prefix + '[price]" placeholder="33,50">' +
          '</div>' +
          '<div class="col-md-2 form-group mb-md-0">' +
            '<div class="form-check mt-md-4">' +
              '<input class="form-check-input" type="checkbox" name="' + prefix + '[muted]" value="1">' +
              '<label class="form-check-label small">Subtiel</label>' +
            '</div>' +
          '</div>' +
          '<div class="col-md-2 text-md-right">' +
            '<button type="button" class="btn btn-sm btn-outline-danger" data-remove-line>Verwijder</button>' +
          '</div>' +
        '</div>' +
      '</div>';
  }

  function priceEntryHtml(prefix, type) {
    return '' +
      '<div class="price-entry border rounded p-3 mb-3" data-price-entry>' +
        '<div class="d-flex justify-content-between align-items-start mb-3">' +
          '<div class="form-group mb-0 price-entry-type">' +
            '<label class="small mb-1">Type</label>' +
            '<select class="form-control form-control-sm" name="' + prefix + '[type]" data-entry-type>' +
              '<option value="simple">Prijsregel</option>' +
              '<option value="group">Subcategorie</option>' +
              '<option value="note">Opmerking</option>' +
            '</select>' +
          '</div>' +
          '<button type="button" class="btn btn-sm btn-outline-danger" data-remove-entry>Verwijder item</button>' +
        '</div>' +
        '<div data-entry-panel="simple">' +
          '<div class="form-row">' +
            '<div class="col-md-7 form-group">' +
              '<label class="small">Naam</label>' +
              '<input type="text" class="form-control form-control-sm" name="' + prefix + '[text]" placeholder="Bijv. Pony knippen">' +
            '</div>' +
            '<div class="col-md-5 form-group">' +
              '<label class="small">Prijs</label>' +
              '<input type="text" class="form-control form-control-sm" name="' + prefix + '[price]" placeholder="10,00 of In overleg">' +
            '</div>' +
          '</div>' +
        '</div>' +
        '<div data-entry-panel="group">' +
          '<div class="form-group">' +
            '<label class="small">Subcategorie titel <span class="text-muted">(optioneel)</span></label>' +
            '<input type="text" class="form-control form-control-sm" name="' + prefix + '[group]" placeholder="Bijv. Blow out">' +
          '</div>' +
          '<div class="price-lines" data-lines>' + priceLineHtml(prefix + '[lines][' + newKey('line') + ']') + '</div>' +
          '<button type="button" class="btn btn-sm btn-outline-secondary mb-3" data-add-line>Prijs toevoegen</button>' +
          '<div class="form-group">' +
            '<label class="small">Opmerking onder subcategorie <span class="text-muted">(optioneel)</span></label>' +
            '<input type="text" class="form-control form-control-sm" name="' + prefix + '[note]">' +
          '</div>' +
          '<div class="form-group mb-0">' +
            '<label class="small">Bulletpunten <span class="text-muted">(optioneel, één per regel)</span></label>' +
            '<textarea class="form-control form-control-sm" rows="3" name="' + prefix + '[bullets]"></textarea>' +
          '</div>' +
        '</div>' +
        '<div data-entry-panel="note">' +
          '<div class="form-group mb-0">' +
            '<label class="small">Opmerking</label>' +
            '<input type="text" class="form-control form-control-sm" name="' + prefix + '[note]" placeholder="Bijv. Elke knipbeurt is met droog föhnen">' +
          '</div>' +
        '</div>' +
      '</div>';
  }

  function priceSectionHtml(columnIndex, sectionKey) {
    var prefix = 'prices[columns][' + columnIndex + '][sections][' + sectionKey + ']';
    var checkboxId = 'price-vanaf-' + columnIndex + '-' + sectionKey;

    return '' +
      '<div class="price-section-card" data-price-section>' +
        '<div class="d-flex justify-content-between align-items-start mb-3">' +
          '<div class="form-group flex-grow-1 mb-0 mr-3">' +
            '<label class="small">Categorienaam</label>' +
            '<input type="text" class="form-control" name="' + prefix + '[title]" placeholder="Bijv. Nieuwe categorie">' +
          '</div>' +
          '<button type="button" class="btn btn-sm btn-outline-danger mt-4" data-remove-section>Verwijder categorie</button>' +
        '</div>' +
        '<div class="form-check mb-3">' +
          '<input class="form-check-input" type="checkbox" name="' + prefix + '[vanaf]" value="1" id="' + checkboxId + '">' +
          '<label class="form-check-label small" for="' + checkboxId + '">Toon “vanaf” bij deze categorie</label>' +
        '</div>' +
        '<div data-entries>' +
          priceEntryHtml(prefix + '[entries][' + newKey('entry') + ']', 'simple') +
        '</div>' +
        '<div class="btn-group btn-group-sm" role="group" aria-label="Prijs items toevoegen">' +
          '<button type="button" class="btn btn-outline-secondary" data-add-entry="simple">Prijsregel toevoegen</button>' +
          '<button type="button" class="btn btn-outline-secondary" data-add-entry="group">Subcategorie toevoegen</button>' +
          '<button type="button" class="btn btn-outline-secondary" data-add-entry="note">Opmerking toevoegen</button>' +
        '</div>' +
      '</div>';
  }

  function updateEntryPanels(entry) {
    var typeSelect = entry.querySelector('[data-entry-type]');
    var activeType = typeSelect.value;

    entry.querySelectorAll('[data-entry-panel]').forEach(function (panel) {
      var isActive = panel.getAttribute('data-entry-panel') === activeType;
      panel.style.display = isActive ? 'block' : 'none';
      panel.querySelectorAll('input, textarea, select, button').forEach(function (field) {
        field.disabled = !isActive;
      });
    });
  }

  function refreshEditor() {
    form.querySelectorAll('[data-price-entry]').forEach(updateEntryPanels);
  }

  form.addEventListener('change', function (event) {
    if (event.target.matches('[data-entry-type]')) {
      updateEntryPanels(event.target.closest('[data-price-entry]'));
    }
  });

  form.addEventListener('click', function (event) {
    var target = event.target;

    if (target.matches('[data-remove-section]')) {
      target.closest('[data-price-section]').remove();
    }

    if (target.matches('[data-remove-entry]')) {
      target.closest('[data-price-entry]').remove();
    }

    if (target.matches('[data-remove-line]')) {
      target.closest('[data-price-line]').remove();
    }

    if (target.matches('[data-add-section]')) {
      var column = target.closest('[data-price-column]');
      column.querySelector('[data-sections]').insertAdjacentHTML(
        'beforeend',
        priceSectionHtml(column.getAttribute('data-price-column'), newKey('section'))
      );
      refreshEditor();
    }

    if (target.matches('[data-add-entry]')) {
      var section = target.closest('[data-price-section]');
      var titleInput = section.querySelector('input[name$="[title]"]');
      var prefix = titleInput.name.replace(/\[title\]$/, '[entries][' + newKey('entry') + ']');
      section.querySelector('[data-entries]').insertAdjacentHTML('beforeend', priceEntryHtml(prefix, target.getAttribute('data-add-entry')));
      var addedEntry = section.querySelector('[data-entries] [data-price-entry]:last-child');
      addedEntry.querySelector('[data-entry-type]').value = target.getAttribute('data-add-entry');
      updateEntryPanels(addedEntry);
    }

    if (target.matches('[data-add-line]')) {
      var entry = target.closest('[data-price-entry]');
      var typeSelect = entry.querySelector('[data-entry-type]');
      var prefix = typeSelect.name.replace(/\[type\]$/, '[lines][' + newKey('line') + ']');
      entry.querySelector('[data-lines]').insertAdjacentHTML('beforeend', priceLineHtml(prefix));
    }

    if (target.matches('[data-reset-prices]')) {
      if (confirm('Alle niet-opgeslagen prijswijzigingen terugzetten?')) {
        editor.innerHTML = initialEditorHtml;
        refreshEditor();
      }
    }
  });

  form.addEventListener('submit', refreshEditor);
  refreshEditor();
}());
</script>
</body>
</html>

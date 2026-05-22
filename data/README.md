# Site-inhoud bewerken

Deze map bevat alle teksten en prijzen die op de website getoond worden. Je hoeft geen programmeerkennis te hebben — alleen de juiste bestanden openen en aanpassen.

## Bestanden

| Bestand | Wat staat erin? |
|---------|-----------------|
| `prices.json` | Alle prijzen onder Diensten |
| `page-text.json` | Vaste teksten op de pagina, menu, knoppen, footer en SEO |
| `opening-hours.json` | Openingstijden per dag |
| `site.json` | Telefoon, e-mail, adres, afspraak-link |
| `promotions.json` | Acties / promotiekaarten |
| `gallery.json` | Portfoliofoto's |

## Prijzen aanpassen (`prices.json`)

- Prijzen schrijf je **zonder** het €-teken, bijvoorbeeld: `"33,50"`
- Voor tekst zonder prijs (bijv. "In overleg"): `"price": "In overleg"`
- Let op komma's en aanhalingstekens — kopieer een bestaande regel en pas alleen de waarden aan

## Teksten aanpassen (`page-text.json`)

- Dit bestand bevat vaste teksten zoals koppen, introducties, knoppen, menu-items, footer en zoekmachine-teksten
- Velden met meerdere regels, zoals Facebook-labels, gebruik je met één item per regel

## Openingstijden (`opening-hours.json`)

- Onder `days` pas je de tijden per dag aan
- Zaterdag sluit later vanaf een bepaalde datum? Pas `dateRules` aan (datum + nieuwe sluitingstijd)

## Contact & adres (`site.json`)

- `phone`, `email`, `bookingUrl` voor contactgegevens
- `addresses`: het adres dat vanaf een datum geldt staat bovenaan met `"from": "2026-01-01"`

## Acties (`promotions.json`)

- Elke actie heeft `image`, `text` en optioneel `col` (breedte van de kaart)

## Portfolio (`gallery.json`)

- Elke foto heeft `image` en `alt`
- Nieuwe uploads via `/admin/` worden automatisch in `img/gallery/` gezet

## Beheer via de website (aanbevolen)

Ga naar **`/admin/`** op je website en log in met je wachtwoord. Daar kun je prijzen, teksten, openingstijden, contactgegevens, portfoliofoto's en acties aanpassen zonder JSON te bewerken.

Voor afbeeldingen kun je JPG, PNG, WebP of GIF uploaden. Portfoliofoto's worden opgeslagen in `img/gallery/`; actie-afbeeldingen in `img/acties/`.

Op GitHub Pages werkt PHP-login niet. Gebruik daar de statische `/admin/` pagina met een GitHub token om deze JSON-bestanden te wijzigen. Zie `GITHUB_PAGES.md`.

### Wachtwoord instellen (eenmalig)

Op de server, in de projectmap:

```bash
php admin/set-password.php "JouwSterkeWachtwoord"
```

Kies een wachtwoord van minimaal 8 tekens. Dit bestand wordt lokaal aangemaakt: `includes/admin.config.php` (niet in git — upload het wel naar de server na het instellen).

## Handmatig JSON bewerken

Upload de gewijzigde `.json`-bestanden naar de server (map `data/`). Vernieuw de website in je browser.

**Tip:** Gebruik een online [JSON-validator](https://jsonlint.com/) als de site ineens leeg lijkt — dan staat er waarschijnlijk een komma of aanhalingsteken verkeerd.

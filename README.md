# Consulta Femminile di Milano — sito web

Sito della **Consulta Femminile Interassociativa di Milano** ([consultafemminile.org](https://www.consultafemminile.org)):
presenta le tematiche su cui lavora la Consulta, gli articoli e i dialoghi pubblicati, i
progetti patrocinati, le persone e le associazioni che ne fanno parte, la storia e lo statuto.

È costruito con **[Kirby CMS](https://getkirby.com) 5** (PHP, contenuti in file di testo,
nessun database), con HTML, CSS e JavaScript scritti a mano, **senza build**: non servono
Node, npm né compilatori.

> Per chi usa il sito (redazione, amministratori) o vuole fare domande a un assistente AI:
> il file [`docs/contesto-ai.md`](docs/contesto-ai.md) spiega come funziona tutto, in
> italiano, ed è pensato per essere caricato in una chat con un'AI.

## Cosa c'è nel sito

| Pagina | A cosa serve |
|---|---|
| **Home** | Video di apertura, due testi di presentazione, carosello delle tematiche, locandine del patrocinio, consiglio, loghi delle associazioni |
| **Tematiche** | Elenco delle tematiche come capitoli espandibili; ogni tematica ha la sua pagina con gli **articoli** ("Output") |
| **Articoli** | Contributi, interviste, dialoghi ed eventi, con autori, data e immagine di copertina |
| **Con il patrocinio** | Locandine dei progetti patrocinati dalla Consulta |
| **Chi siamo** | Presentazione, storia ("La nostra storia"), consiglio e associazioni; sottopagine **Le persone**, **Le associazioni**, **Contatti** |
| **Lo statuto** | Statuto e regolamento, con la brochure scaricabile |

Il modulo contatti compare in fondo alle pagine principali e manda i messaggi per email.

## Requisiti

- **PHP 8.2 – 8.5** con le estensioni richieste da Kirby (gd o imagick, mbstring, curl…)
- **[Composer](https://getcomposer.org)**
- Un server web (Apache con `mod_rewrite` — c'è già l'`.htaccess` —, oppure il server
  incorporato di PHP per lo sviluppo)
- Una [licenza Kirby](https://getkirby.com/buy) per i siti online (gratis in sviluppo)

## Installazione in locale

```bash
# 1. scarica il progetto
git clone git@github.com:fre-denni/consultafemminile.git
cd consultafemminile

# 2. installa Kirby e le dipendenze (le cartelle kirby/ e vendor/ non sono nel repository)
composer install

# 3. crea i segreti di questa installazione (file ignorato da git)
cp site/config/secrets.example.php site/config/secrets.php
#    poi apri secrets.php e sostituisci i due valori con stringhe casuali, per esempio:
php -r 'echo bin2hex(random_bytes(32)), "\n";'

# 4. avvia il sito
composer start          # → http://localhost:8000
```

In alternativa a `composer start` va bene qualunque ambiente locale (per esempio
[Laravel Herd](https://herd.laravel.com) con un indirizzo `.test`): basta puntare la
cartella del progetto.

**Primo accesso al Panel.** Apri `/panel`: su un'installazione locale senza utenti Kirby
chiede di creare il primo account amministratore. Gli account (`site/accounts/`) non sono nel
repository: ogni installazione ha i propri.

> Il contenuto del sito (`content/`) è nel repository come copia di sviluppo. In produzione il
> contenuto vive solo sul server e si modifica dal Panel: **il deploy non lo sovrascrive** (vedi
> [Pubblicazione](#pubblicazione)).

## Struttura del progetto

```
assets/            CSS globale (variabili, tipografia), JavaScript dei componenti, font, icone, logo
content/           i contenuti: una cartella per pagina (il numero iniziale = pagina "listed", in menu)
site/
  blueprints/      cosa si può modificare nel Panel: pagine, blocchi, campi, ruoli utente
  config/          config.php, metodi dei campi, segreti (secrets.php, non nel repository)
  collections/     elenchi calcolati (tematiche, persone, consiglio, associazioni…)
  plugins/         i plugin del progetto (vedi sotto)
  snippets/        componenti: atoms/ (mattoni), bits/ (componenti), blocks/ (blocchi del Panel)
  templates/       un template per tipo di pagina
kirby/ vendor/     Kirby e dipendenze (installate da Composer, non nel repository)
media/             file generati da Kirby (miniature…), non nel repository
```

**CSS.** I componenti hanno il CSS accanto al proprio snippet (`site/snippets/bits/xxx.css`):
vengono riuniti in un unico file con una firma che cambia a ogni modifica
(`site/plugins/asset-builders`). L'ordine di cascata è dichiarato in `assets/css/global.css`:
`@layer base, atoms, bits, blocks, snippets`. `global.css` e gli script in `assets/js/` non
hanno versione: dopo una modifica forza il ricaricamento del browser.

### Plugin del progetto (`site/plugins/`)

| Plugin | Cosa fa |
|---|---|
| `asset-builders` | Unisce i CSS dei componenti in un solo file con firma |
| `contact-form` | Modulo contatti: validazione, protezione anti-spam, invio email |
| `image-derivatives` | Ridimensiona le immagini al caricamento e prepara le miniature in WebP |
| `image-alt` | Impedisce di salvare pagine con immagini nuove senza testo alternativo (Alt) |
| `seo` | Meta tag, Open Graph, dati strutturati, `sitemap.xml`, `robots.txt` |
| `social-icons` | Icone dei social scelte dal Panel (da `assets/icons/social/`) |
| `panel-registration` | "Richiedi l'accesso" nella pagina di login del Panel |

## Il Panel in breve

Il Panel (`/panel`) è fatto su misura con i blueprint in `site/blueprints/`. Ogni pagina ha un
tab **Contenuto** e un tab **SEO**; le impostazioni generali (payoff, menu, social, SEO
predefinita) sono nella pagina principale **Sito**. Ruoli:

- **Admin** — fa tutto.
- **Editor** — modifica contenuti, immagini e impostazioni del sito; niente utenti, sistema,
  indirizzi (slug), template, spostamenti o eliminazione di pagine.
- **In attesa di approvazione** — ruolo senza permessi: lo ricevono automaticamente le persone
  che usano "Richiedi l'accesso" dalla pagina di login, finché un admin non assegna un ruolo.

Nel menu del Panel c'è anche un link allo **strumento per generare le immagini** in linea col
brand (si apre in una nuova scheda).

## Configurazione

`site/config/config.php` imposta, tra l'altro: WebP per le miniature, Panel senza compilatore
Vue, menu del Panel, e il riconoscimento dello staging (cartella `/staging/`: debug acceso e
installer attivo). I segreti stanno in `site/config/secrets.php` (`content.salt` e
`cookie.key`): **un file diverso per ogni server, mai nel repository**.

Opzioni dei plugin (tutte facoltative, nel `config.php`):

| Opzione | Default | Significato |
|---|---|---|
| `consulta.seo.index` | automatico | `true`/`false` forza l'indicizzazione; di default solo online, mai in locale o su staging |
| `consulta.seo.descriptionLength` | 155 | Lunghezza massima della descrizione per i motori di ricerca |
| `consulta.alt.since` | 2026-10-22 21:00 | Le immagini caricate dopo questa data devono avere l'Alt |
| `consulta.alt.exempt` | `['persone']` | Pagine il cui Alt si genera da codice |
| `consulta.registration.active` | `true` | Attiva/disattiva "Richiedi l'accesso" |
| `consulta.registration.maxUsers` / `maxPending` | 25 / 10 | Tetti agli utenti totali e alle richieste in attesa |
| `consulta.contactForm.from` | `no-reply@consultafemminile.org` | Mittente delle email del modulo contatti |

## Pubblicazione

Il sito è su un hosting Aruba. Il deploy è manuale, da GitHub: tab **Actions → Run workflow**.

- `.github/workflows/deploy-production.yml` carica via FTP i file del repository.
- `.github/workflows/deploy-staging.yml` esegue prima `composer install --no-dev` e poi
  carica via FTP, quindi include anche `kirby/` e `vendor/`.
- Le credenziali FTP sono nei *secrets* del repository (`FTP_SERVER`, `FTP_USERNAME`,
  `FTP_PASSWORD`).
- `.deployignore` elenca ciò che **non** viene caricato: `content/`, `site/accounts/`,
  `site/cache/`, `site/sessions/`, `site/config/.license`, `media/`, `.htpasswd`, la
  documentazione. Quindi in produzione contenuti, utenti e licenza restano quelli del server.
- Sul server servono una volta sola: `site/config/secrets.php` (da `secrets.example.php`) e la
  licenza Kirby attivata per il dominio.

## Documentazione

- [`docs/contesto-ai.md`](docs/contesto-ai.md) — guida completa al sito, in italiano, da
  caricare in un assistente AI per fare domande (uso del Panel, regole delle immagini, SEO,
  ruoli, struttura tecnica).
- [Documentazione di Kirby](https://getkirby.com/docs/guide)

## Licenze

Kirby **non è software libero**: per usarlo online serve una [licenza](https://getkirby.com/license)
per ogni sito. I contenuti sono © Consulta Femminile Interassociativa di Milano; il sito è stato
progettato e sviluppato da [Federico Denni](https://federicodenni.com).

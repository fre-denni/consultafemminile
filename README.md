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

| Pagina                | A cosa serve                                                                                                                            |
| --------------------- | --------------------------------------------------------------------------------------------------------------------------------------- |
| **Home**              | Video di apertura, due testi di presentazione, carosello delle tematiche, locandine del patrocinio, consiglio, loghi delle associazioni |
| **Tematiche**         | Elenco delle tematiche come capitoli espandibili; ogni tematica ha la sua pagina con gli **articoli** ("Output")                        |
| **Articoli**          | Contributi, interviste, dialoghi ed eventi, con autori, data e immagine di copertina                                                    |
| **Con il patrocinio** | Locandine dei progetti patrocinati dalla Consulta                                                                                       |
| **Chi siamo**         | Presentazione, storia ("La nostra storia"), consiglio e associazioni; sottopagine **Le persone**, **Le associazioni**, **Contatti**     |
| **Lo statuto**        | Statuto e regolamento, con la brochure scaricabile                                                                                      |

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

| Plugin               | Cosa fa                                                                                            |
| -------------------- | -------------------------------------------------------------------------------------------------- |
| `asset-builders`     | Unisce i CSS dei componenti in un solo file con firma                                              |
| `contact-form`       | Modulo contatti: validazione, protezione anti-spam, invio email                                    |
| `image-derivatives`  | Ridimensiona le immagini al caricamento e prepara le miniature in WebP                             |
| `image-alt`          | Impedisce di salvare pagine con immagini nuove senza testo alternativo (Alt)                       |
| `seo`                | Meta tag, Open Graph, dati strutturati, `sitemap.xml`, `robots.txt`                                |
| `video-embed`        | Video YouTube/Vimeo negli articoli: solo indirizzi "senza cookie", caricati al clic su "Riproduci" |
| `social-icons`       | Icone dei social scelte dal Panel (da `assets/icons/social/`)                                      |
| `panel-registration` | "Richiedi l'accesso" nella pagina di login del Panel                                               |

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

## Documentazione

- [`docs/contesto-ai.md`](docs/contesto-ai.md) — guida completa al sito, in italiano, da
  caricare in un assistente AI per fare domande (uso del Panel, regole delle immagini, SEO,
  ruoli, struttura tecnica).
- [Documentazione di Kirby](https://getkirby.com/docs/guide)

## Licenze

Kirby **non è software libero**: per usarlo online serve una [licenza](https://getkirby.com/license)
per ogni sito. I contenuti sono © Consulta Femminile Interassociativa di Milano; il sito è stato
progettato e sviluppato da [Federico Denni](https://federicodenni.com).

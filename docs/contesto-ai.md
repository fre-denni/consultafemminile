# Consulta Femminile di Milano — contesto del sito per assistenti AI

> **Come usare questo file.** Caricalo (o incollalo) all'inizio di una chat con un assistente
> AI, poi scrivi la tua domanda: per esempio *"Come aggiungo un articolo?"*, *"Che dimensioni
> deve avere la copertina di una tematica?"*, *"Perché non riesco a salvare questa pagina?"*.

---

## 0. Istruzioni per l'assistente

Sei l'assistente di chi gestisce o sviluppa il sito della **Consulta Femminile Interassociativa
di Milano**. Questo documento descrive il sito com'è fatto oggi.

- Rispondi **in italiano**, con passi brevi e numerati. Usa i nomi esatti delle voci del Panel
  scritti qui (per esempio "Tematiche", "Output", "Tipologia", "Testo alternativo (Alt)").
- Chiedi **chi sta scrivendo** se serve: una persona della redazione (usa il **Panel**, senza
  toccare codice) o uno sviluppatore (lavora sui file). Per la redazione usa le sezioni 1–9, per
  gli sviluppatori la sezione 10.
- Basati **solo su questo documento**. Se qualcosa non c'è, dillo ("non è nel mio contesto") e
  suggerisci di chiedere a chi ha sviluppato il sito (Federico Denni) invece di inventare.
- Il **Panel** è l'area di amministrazione, all'indirizzo `…/panel` del sito. Kirby è il CMS.
- **Mai** chiedere, mostrare o memorizzare password, chiavi, credenziali FTP, token o contenuto
  del file `secrets.php`. Se l'utente li incolla, avvisalo di non farlo.
- Se la domanda richiede un'azione irreversibile (eliminare pagine o utenti, sovrascrivere il
  deploy, cambiare indirizzi di pagine già pubbliche), ricordane le conseguenze prima.

---

## 1. Panoramica

Il sito presenta la Consulta Femminile Interassociativa di Milano, fondata nel 1963: associazioni
diverse che lavorano insieme su tematiche come la violenza di genere, la crisi demografica e il
lavoro delle donne. Contiene:

- **Tematiche** (es. "Generazione Beta", "Contro la violenza di genere"), ciascuna con una
  copertina, una descrizione e degli **articoli** ("Output": contributi, interviste, dialoghi,
  eventi).
- **Con il patrocinio**: le locandine dei progetti patrocinati.
- **Chi siamo**: presentazione, **La nostra storia** (timeline), consiglio, associazioni;
  sottopagine **Le persone**, **Le associazioni della Consulta**, **Contatti**.
- **Lo statuto** e il regolamento.
- Un **modulo contatti** che manda i messaggi per email.

Tecnologia: **Kirby CMS 5** (PHP, contenuti in file di testo, nessun database), codice scritto
a mano senza build. Lingua unica: italiano. Indirizzo di produzione:
`https://www.consultafemminile.org`. Esistono anche una copia di sviluppo (locale) e,
eventualmente, una di prova (staging).

Il **contenuto in produzione si modifica solo dal Panel**: il deploy del codice non tocca
contenuti, utenti né licenza.

---

## 2. Mappa: dove si modifica cosa

Nel Panel ogni pagina ha due tab: **Contenuto** e **SEO**. Le impostazioni generali sono nella
pagina principale **Sito** (la prima voce del menu laterale, col nome del sito).

| Sul sito | Nel Panel | Cosa si modifica |
|---|---|---|
| Home | Sito → **Home** | Video, due descrizioni (etichetta, titolo, testo, sfondo), titolo e sfondo della sezione Tematiche, sfondo delle sezioni Patrocinio / Persone / Associazioni |
| Menu in alto e ordine | Sito → tab **Pagine sito** | Ordine delle pagine di navigazione (si trascinano), payoff del sito |
| Footer: social | Sito → tab **Social Media** | Piattaforma, indirizzo e icona di ogni social |
| SEO predefinita | Sito → tab **SEO** | Descrizione e immagine di condivisione usate quando una pagina non ne ha di proprie |
| Tematiche (elenco) | **Tematiche** | Payoff e testo introduttivo ("Descrizione pagina"); elenco delle tematiche |
| Una tematica | Tematiche → *nome tematica* | Immagini (copertina), Sottotitolo, Descrizione, articoli ("Output") |
| Un articolo | Tematiche → *tematica* → *articolo* | Tipologia, Autori, Descrizione, Data, Immagine, Testo, Correlati |
| Con il patrocinio | **Con il patrocinio** | Payoff, testo, **Locandine** (immagini) |
| Chi siamo | **Chi siamo** | Testo introduttivo, **Blocchi** liberi |
| La nostra storia | Chi siamo → **La nostra storia** | Introduzione e **Capitoli** (anno, etichetta, descrizione, immagini) |
| Le persone | Chi siamo → **Le Persone** | Elenco persone (foto, nome, ruolo, associazione, email, flag Consigliera/Presidenza) |
| Le associazioni | Chi siamo → **Le Associazioni della Consulta** | Elenco associazioni (logo, nomi, sito, foto, descrizione, delegate) |
| Contatti / modulo contatti | Chi siamo → **Contatti** | **Per tutto il sito**: email di destinazione, etichetta, foto, Alt, credito |
| Statuto | **Lo Statuto** | Blocchi (articoli, divisori, brochure scaricabile) |
| Pagina 404 | **Error** | Payoff e testo della pagina "non trovata" |

Le sezioni "Pagina collegata" (nella Home e in Chi siamo) mostrano solo un **collegamento
rapido** alla pagina che gestisce davvero quel contenuto: non si compilano.

**Menu in alto**: Tematiche, Con il patrocinio, Chi siamo, La nostra storia. La **Home** sta in
cima all'elenco del Panel ma non compare nel menu (il logo porta già lì). Le pagine "secondarie"
(Statuto, Error) sono raggiungibili da link, non dal menu.

---

## 3. Utenti e ruoli

| Ruolo | Può | Non può |
|---|---|---|
| **Admin** | Tutto | — |
| **Editor** | Creare, modificare, pubblicare, riordinare e duplicare pagine e articoli; caricare, sostituire, modificare ed eliminare immagini e file; modificare le impostazioni del sito (payoff, social, SEO); cambiare nome, email, lingua e password del proprio account | Gestire altri utenti, Sistema e Lingue; cambiare indirizzo (slug) o template di una pagina, spostarla o eliminarla; cambiare il nome del sito; cambiare il proprio ruolo |
| **In attesa di approvazione** | Niente: non può entrare | — |

L'editor non vede "Utenti" né "Sistema" nel menu.

### Richiedere l'accesso
Sotto il form di login del Panel c'è **"Richiedi l'accesso"** (in inglese "No account yet?
Request access"): nome e cognome, email, password (almeno 8 caratteri, due volte). L'account
nasce con ruolo **In attesa di approvazione**: provando a entrare compare "Il tuo account è in
attesa di approvazione…". Gli admin ricevono un'email con la richiesta.

### Approvare una richiesta (admin)
1. Panel → **Utenti**.
2. Apri l'utente → cambia il **ruolo** in *Editor* (o *Admin*), oppure eliminalo se non lo conosci.

Limiti di sicurezza: massimo 25 utenti totali, 10 richieste in attesa, 5 tentativi di richiesta
per indirizzo IP ogni ora. La licenza di Kirby Basic non pone alcun limite al numero di utenti:
questi tetti sono scelte del sito (modificabili da uno sviluppatore).

---

## 4. Guide passo-passo (redazione)

### 4.1 Aggiungere un articolo
1. Panel → **Tematiche** → la tematica giusta → sezione **Output** → **+**.
2. Scrivi il **titolo** e conferma: la pagina nasce come bozza.
3. Compila **Tipologia** (menu a tendina: *Contributo*, *Intervista*, *Dialogo*, *Evento*),
   **Autore/i** (una riga per autore; se è un membro della Consulta lascia attivo "Membro della
   Consulta", altrimenti disattivalo e, se vuoi, aggiungi un link esterno), **Descrizione**
   (una frase in corsivo sotto il titolo), **Data**.
4. **Immagine**: la copertina (1600 × 1600 px, vedi sezione 5). Senza immagine l'articolo
   **esiste ma non compare** nei caroselli e nelle card (vedi sezione 7).
5. **Testo**: aggiungi un blocco *Testo* (editor con barra degli strumenti) e, dove serve, un
   blocco *Immagine* (con didascalia).
6. Tab **SEO** (facoltativo, vedi sezione 6).
7. Imposta lo stato su **Pubblica** (il pallino colorato in alto) e salva.

**Correlati.** In fondo all'articolo il carosello "Correlati" mostra di default gli altri articoli
della stessa tematica. Dal campo "Output della stessa tematica" puoi spegnerlo e scegliere
manualmente "Altri output da aggiungere".

### 4.2 Aggiungere una tematica
1. Panel → **Tematiche** → sezione **Tematiche** → **+**.
2. Compila **Sottotitolo** e **Descrizione** (obbligatori).
3. Carica le **Immagini**: la **prima** è la copertina. **Il file della copertina deve chiamarsi
   `cover.webp`** (dopo il caricamento: ⋯ → Rinomina): con un altro nome la tematica **non
   compare** in home, nel menu e nell'elenco "Tematiche". Le altre immagini finiscono nel
   carosello della pagina "Tematiche".
4. Aggiungi poi gli articoli (4.1). Pubblica.

### 4.3 Aggiungere o modificare una persona
Chi siamo → **Le Persone** → campo **Persone** → **+**: foto (carica prima la foto nella colonna
"Foto" a destra, poi selezionala), nome, cognome, ruolo (di default "Delegata"), associazione
(testo libero), email.
- **Consigliera**: la persona compare nel carosello del consiglio (Home e Chi siamo).
- **Presidenza**: la mette in cima a quell'elenco (Presidente e Vicepresidente).
- Il testo alternativo delle foto **si genera da solo**: "Foto di profilo di Nome Cognome
  dell'associazione X" (non serve scriverlo).

### 4.4 Aggiungere o modificare un'associazione
Chi siamo → **Le Associazioni della Consulta** → campo **Associazioni** → **+**: logo, nome
completo, nome breve, sito web, foto, descrizione, delegate (elenco di persone). I loghi
compaiono nello scorrimento in Home e nelle schede.

### 4.5 La nostra storia (timeline)
Chi siamo → **La nostra storia**: scrivi l'**Introduzione**, poi aggiungi **Capitoli**: anno,
etichetta (es. "Presidente"), descrizione e immagini. **Una** immagine mostra una foto singola;
**da due a sette** un piccolo carosello.

### 4.6 Locandine del patrocinio
**Con il patrocinio** → **Locandine**: tutte le immagini caricate compaiono nel carosello, nell'ordine
in cui sono elencate (si trascinano). Cliccandole si ingrandiscono.

### 4.7 Modificare la Home
Sito → **Home**: le sezioni seguono l'ordine della pagina (Video, Descrizione uno, Descrizione
due, Tematiche, Patrocinio, Persone, Associazioni). Ogni sezione ha lo **Sfondo** (Bianco, Panna,
Azzurro). Carosello Tematiche, Patrocinio, Consiglio e loghi si riempiono **da soli** dalle
rispettive pagine: dalla Home si cambia solo lo sfondo.

### 4.8 Cambiare l'ordine del menu
Sito → tab **Pagine sito** → "Pagine di navigazione": trascina le voci. (La nostra storia ha una
sezione a parte, non trascinabile.)

### 4.9 Il modulo contatti (valido per tutto il sito)
Chi siamo → **Contatti**: **Email per i messaggi del form** (se vuota: l'indirizzo di riserva
del sito), **Etichetta** (testo a sinistra del riquadro, di default "Contattaci"), **Foto del
form contatti**, **Testo alternativo**, **Didascalia/credito**. Si cambia **una volta sola** e vale
per tutte le pagine che mostrano il modulo. Il modulo accetta messaggi fino a 500 parole, ha
protezioni anti-spam e manda un'email all'indirizzo configurato.

### 4.10 Lo statuto
**Lo Statuto** → **Contenuto**: blocchi *Articolo* (titolo tipo "Art.1", sottotitolo facoltativo,
testo), *Divisore* (stile Banner per separare Statuto e Regolamento), *Link scaricabile*
(brochure: carica il PDF nella colonna "File" e selezionalo nel blocco) e *Testo*. Per un nuovo
articolo conviene **duplicarne uno** e cambiare titolo, sottotitolo e testo.

### 4.11 Il video della Home
Sito → **Home** → **Video**: un solo file `.mp4` o `.webm`. Consigliato: 720p (1280 px di
larghezza), senza audio, circa 8 MB: video più pesanti rallentano il sito.

### 4.12 I social nel footer
Sito → tab **Social Media**: una riga per social (piattaforma, indirizzo, icona). Le icone
disponibili sono i file `.svg` della cartella `assets/icons/social/`: per aggiungerne una nuova
serve uno sviluppatore (o caricare il file in quella cartella).

---

## 5. Immagini

### 5.1 Cosa fa il sito da solo
- Al caricamento ridimensiona le foto a **massimo 2400 px** sul lato lungo (qualità 82), senza
  mai ingrandire quelle più piccole. PDF, video e SVG non vengono toccati.
- Prepara in automatico versioni più piccole in **WebP** (da 240 a 2000 px): il browser scarica
  solo quella che serve.
- Le immagini sono caricate in modo "pigro" (lazy), tranne quelle in cima alla pagina.

### 5.2 Testo alternativo (Alt) — obbligatorio
Ogni immagine ha il campo **Testo alternativo (Alt)**: descrive in una frase cosa mostra
(es. "Due donne parlano sul palco durante il convegno"), senza scrivere "immagine di". Serve a
chi usa uno screen reader e ai motori di ricerca. Per un PDF scrivi il titolo del documento.
- Si compila aprendo l'immagine (clic sulla miniatura) → campo **Testo alternativo (Alt)** → Salva.
- Le immagini senza Alt mostrano **"⚠ Alt mancante"** nelle liste del Panel.
- **Obbligo**: se una pagina contiene immagini **caricate dopo il 22 ottobre 2026** senza Alt, il
  salvataggio della pagina viene **bloccato** con un messaggio che elenca i file. Apri ogni
  immagine, compila l'Alt, salva, poi salva di nuovo la pagina.
- Le immagini già presenti prima di quella data non bloccano nulla (ma vanno completate).
- Eccezione: le foto della pagina **Le Persone** hanno un Alt generato da codice.

### 5.3 Dimensioni consigliate (le massime utili)
Valori in pixel del **file finale**; oltre queste misure non serve andare. Nelle sezioni del
Panel le stesse indicazioni compaiono nel testo di aiuto di ogni campo.

| Dove | File finale | Note |
|---|---|---|
| **Tematica** — copertina (prima immagine) | 2000 × 1250 (16:10) | Si ritaglia a seconda dello schermo e dell'uso: soggetto al centro. **Nome file `cover.webp`** |
| Tematica — altre immagini | 1120 × 840 (4:3) | Carosello della pagina "Tematiche" |
| **Articolo** — copertina | 1600 × 1600 (quadrata) | Card verticale, intestazione dell'articolo, anteprima social |
| Articolo — immagine nel testo | larghezza 1600 | Colonna di testo larga ~740 px; il rapporto si sceglie nel blocco |
| **Patrocinio** — locandine | 1080 × 1600 (2:3, verticale) | Ingrandita arriva a 1600 px sul lato lungo |
| **Persone** — foto | 800 × 1330 (3:5, verticale) | |
| **Associazioni** — logo | larghezza 320 | Meglio SVG con sfondo trasparente; sul sito è alto al massimo ~90 px |
| Associazioni — foto | 1280 × 400 (3,2:1) | Banner della finestra di dettaglio |
| **La nostra storia** — immagini | 1120 × 840 (4:3) | |
| **Contatti** — foto | 800 × 800 (quadrata) | Non compare su mobile |
| **SEO** — immagine di condivisione | 1200 × 630 | Il sito la ritaglia a questa misura |

### 5.4 Strumento per generare le immagini del brand
Nel menu laterale del Panel c'è **"Strumento immagini"** (apre
`https://fre-denni.github.io/consultafemminile_imgtool/` in una nuova scheda): genera pattern
geometrici con i colori del brand. Come usarlo:
1. Imposta Larghezza e Altezza (gli slider vanno da **200 a 3000**, a passi di 10) e i colori.
2. **Attenzione: il file esce 4 volte più grande** dei valori impostati (800 × 800 → file di
   3200 × 3200). Per un file di 2000 × 1250 imposta **500 × 310**.
3. Premi **"Scarica Immagine"**: scarica `img.webp`.
4. Caricalo nel Panel nel punto giusto, **rinominalo se serve** (es. `cover.webp` per le
   tematiche) e compila l'**Alt**.
Valori da impostare nello strumento: copertina tematica 500 × 310; copertina articolo 400 × 400;
locandina 270 × 400; tematica/timeline (altre immagini) 280 × 210; contatti 200 × 200; foto
associazione 640 × 200 (il sito la riduce da solo); condivisione SEO 400 × 210.
Lo strumento è solo un link: **non** carica nulla da solo nel sito.

---

## 6. Editor di testo e blocchi

### 6.1 L'editor di testo
Tutti i campi di testo lungo (testo introduttivo, descrizioni, blocchi di testo) usano lo stesso
editor: **titoli** (livelli 2, 3 e 4), **grassetto**, *corsivo*, **link**, **email** e gli altri stili
di base della barra degli strumenti, **elenchi** puntati e numerati e **citazione**. Titoli ed elenchi sono nel menu a "Tт".
La descrizione in corsivo sotto il titolo di un articolo ha una versione a riga singola (senza
titoli né elenchi). Restano campi semplici le righe brevi (payoff, titoli dei blocchi, sottotitolo).

### 6.2 Blocchi disponibili (dipendono dalla pagina)
| Blocco | Cosa fa | Dove si può usare |
|---|---|---|
| **Testo** | Testo libero con barra degli strumenti (titoli, elenchi, citazioni…) | Articoli, Chi siamo, Statuto |
| **Immagine** | Immagine con didascalia, rapporto (1:1, 16:9, 3:2…) e ritaglio | Articoli |
| **Paragrafo con titolo** | Titolo breve + testo, nel layout "importante" del sito | Chi siamo |
| **Divisore** | Titolo di sezione: stile *Capitolo*, *Sezione* o *Banner*, con sfondo | Chi siamo, Statuto |
| **Articolo** | Titolo ("Art.1"), sottotitolo, testo: per statuto e regolamento | Statuto |
| **Link scaricabile** | Pulsante per scaricare un PDF o aprire un indirizzo | Statuto |
| **Form contatti** | Mostra il modulo contatti (dati dalla pagina Contatti) | Chi siamo |

I blocchi si modificano direttamente nel Panel; si aggiungono col "+" e si trascinano per
riordinarli. Lo **Sfondo** può essere Bianco, Panna o Azzurro.

---

## 7. Regole di visibilità (perché una cosa non compare)

- **Tematica assente** da home, menu o elenco → manca il file **`cover.webp`** nella tematica,
  oppure è la nona (la home ne mostra al massimo 8).
- **Articolo assente** dai caroselli (tematica, "Correlati") → non ha **nessuna immagine**. La sua
  pagina esiste e funziona; manca solo nelle card.
- **Persona assente** dal consiglio → il flag **Consigliera** non è attivo (le altre persone sono
  nella griglia "Le delegate delle associazioni" della pagina Le Persone). Una persona senza
  foto non viene mostrata.
- **Locandina assente** dal carosello del patrocinio → non è un'immagine caricata nella pagina
  **Con il patrocinio**.
- **Pagina assente** dal menu → non è tra le "Pagine di navigazione" (Sito → Pagine sito).
- **Carosello Tematiche in Home vuoto** → nessuna tematica ha `cover.webp`.
- Una pagina in **bozza** non è visibile ai visitatori.

---

## 8. SEO

### 8.1 Cosa si genera da solo
Per ogni pagina il sito produce titolo, descrizione, indirizzo canonico, anteprime per i social
(Open Graph, Twitter) e **dati strutturati** (Organization, WebSite, WebPage, BreadcrumbList e,
per gli articoli, Article con autori, data e sezione). Esistono `sitemap.xml` e `robots.txt`.
- **Titolo**: "Titolo della pagina | Consulta Femminile di Milano" (il nome del sito si aggiunge solo
  se il totale resta entro ~65 caratteri).
- **Descrizione**, nell'ordine: campo SEO della pagina → testo della pagina (descrizione
  dell'articolo, testo introduttivo, descrizione della tematica) → descrizione predefinita del
  sito → payoff del sito. Si taglia a 155 caratteri.
- **Immagine di condivisione**: SEO della pagina → copertina (articoli e tematiche) → immagine
  predefinita del sito. Si ritaglia a 1200 × 630 in JPG.
- Ogni pagina ha **un solo titolo principale (`h1`)**: nelle pagine con titolo grande uguale
  al payoff, l'`h1` è il titolo della pagina, nascosto a schermo ma letto dai motori di ricerca.
- **Indicizzazione solo online**: in locale e su staging il sito dice ai motori di non
  indicizzare; la pagina d'errore (404) è sempre nascosta.

### 8.2 Cosa si può impostare a mano (tab SEO di ogni pagina)
- **Titolo per i motori di ricerca** (consigliato ≤ 60 caratteri, massimo 70).
- **Descrizione** (120–160 caratteri, massimo 160).
- **Immagine di condivisione** (1200 × 630).
- **Nascondi dai motori di ricerca**: la pagina resta raggiungibile dal link ma esce da Google e
  dalla sitemap. (La Home non ha questa opzione: resta sempre indicizzata.)

Conviene scrivere un titolo SEO breve per gli articoli con titolo lungo (più di ~60 caratteri).

---

## 9. Avvisi e problemi frequenti (redazione)

| Problema | Causa e soluzione |
|---|---|
| "Manca il testo alternativo (Alt) di queste immagini…" al salvataggio | Apri ogni immagine elencata, compila **Testo alternativo (Alt)**, salva; poi salva la pagina |
| La tematica non compare in home/menu | La copertina non si chiama `cover.webp`: rinominala dal Panel (⋯ → Rinomina) |
| L'articolo non è nelle card/caroselli | Manca l'immagine di copertina |
| "Non hai i permessi" / non vedo Utenti o Sistema | Sei un **Editor**: quelle aree sono riservate agli admin |
| Non riesco a cambiare l'indirizzo (slug) o eliminare una pagina | Azioni riservate agli admin |
| Dopo la registrazione non riesco a entrare | L'account è "In attesa di approvazione": un admin deve assegnarti un ruolo |
| Ho modificato un testo ma sul sito vedo il vecchio | Ricarica forzando la cache (Maiusc + Ricarica); gli script e il CSS globale non hanno versione |
| I messaggi del modulo non arrivano | Controlla l'indirizzo in Chi siamo → Contatti e la cartella spam; il mittente è `no-reply@consultafemminile.org` |
| Avviso "contenuto salt / cookie signing key" nel Panel (Sistema) | Mancano i segreti del server: serve creare `site/config/secrets.php` (sviluppatore) |
| Banner "Acquista una licenza / Attiva ora" | La licenza Kirby non è attivata per quel dominio (admin → Sistema) |
| L'immagine generata dallo strumento è enorme | Normale: esce 4 volte più grande dei valori impostati; il sito la ridimensiona a 2400 px |

**Cosa non si fa dal Panel** (serve uno sviluppatore): nuovi tipi di pagina o di blocco, modifiche
alla grafica (colori, caratteri, layout), nuove icone social, cambi di dimensioni o di regole
delle immagini, configurazione di email, deploy.

---

## 10. Per sviluppatori

### 10.1 Stack e requisiti
Kirby **5.6** (composer: `getkirby/cms ^5.2`), PHP **8.2–8.5**, Composer. HTML/CSS/JS a mano,
**nessuna build**. Font Figtree locale. Repository: `git@github.com:fre-denni/consultafemminile.git`.

Installazione: `git clone` → `composer install` → copiare `site/config/secrets.example.php` in
`site/config/secrets.php` con due stringhe casuali (`php -r 'echo bin2hex(random_bytes(32));'`)
→ `composer start` (oppure Herd/altro, es. `consultafemminile.test`). `/kirby`, `/vendor`,
`/media`, `site/accounts`, `site/cache`, `site/sessions` e `secrets.php` sono **ignorati da git**.

### 10.2 Struttura
```
assets/css/global.css      variabili CSS, tipografia, @layer base, atoms, bits, blocks, snippets
assets/js/                 uno script per componente (vanilla), caricati dagli snippet
assets/icons/ (+ social/)  icone SVG (stile Phosphor); social/ alimenta il select del Panel
content/                   cartelle di pagina: "N_slug" = listed; home = 1_home (nascosta dal menu)
site/blueprints/           pages/ blocks/ fields/ sections/ tabs/ files/ users/ + site.yml
site/collections/          tematiche, patrocinio, persone, consigliere, delegate, associazioni, timeline
site/plugins/              vedi 10.5
site/snippets/             atoms/ bits/ blocks/ + header, footer, layout, main
site/templates/            home, tematiche, tematica, output, patrocinio, chi-siamo, persone,
                           associazioni, timeline, contatti, statuto, error, default
```
Templates: la pagina **Output** = articolo (`output.php`: `bits/article-header`, blocchi,
`bits/related-outputs`, `bits/article-footer`). Struttura dei contenuti: `1_home`,
`2_tematiche/N_slug/(N_articolo)`, `3_patrocinio`, `4_chi-siamo/{persone,associazioni,timeline,contatti}`,
`statuto`, `error`.

### 10.3 Convenzioni di front-end
- **Componenti**: ogni snippet (`atoms/`, `bits/`, `blocks/`) ha il proprio CSS accanto
  (`nome.css`), dentro `@layer atoms|bits|blocks`. I CSS sono uniti da `bundledAsset()`
  (plugin `asset-builders`) in `media/assets/main.<hash>.css`: cambiano firma da soli.
  **`assets/css/global.css` e gli script `assets/js/*.js` non hanno versione**: dopo una
  modifica forzare il ricaricamento del browser.
- **Pattern "guscio + closure"**: `bits/carousel.php` e `bits/accordion.php` contengono la
  meccanica; gli adattatori (`output-carousel`, `patrocinio-carousel`, `chapter-accordion`,
  `timeline-accordion`) passano closure per il contenuto di ogni elemento.
- **Immagini**: sempre tramite `snippet('atoms/picture', [...])` (WebP, `srcset`, lazy). I
  parametri `sizes` devono essere sottoinsiemi della scala `IMAGE_DERIVATIVE_WIDTHS`
  (240, 320, 400, 640, 800, 1024, 1280, 1600, 2000) di `site/plugins/image-derivatives`. Se
  `alt` non è passato si usa il campo Alt del file; `'alt' => ''` per le immagini decorative.
- **Colori di sfondo**: valori `default` / `tinted` / `blue` (preset `fields/background.yml`).
- **Un solo `h1` per pagina** (`bits/title-page.php` aggiunge un `h1` nascosto con
  `atoms/visually-hidden.css`).

### 10.4 Convenzioni del Panel (blueprint)
- Pagine a **tab**: `contenuto` + `seo` (preset `tabs/seo.yml`, `tabs/seo-home.yml`); Error no
  (e `default.yml` ha ancora i suoi tab `descrizione` e `contenuto`).
- Sezioni "pagina collegata" di sola lettura: `type: pages` con `query`, `createable: false`,
  `sortable: false` (preset `sections/contatti-globali.yml`).
- Campi di testo ricco: `extends: fields/rich-text` (writer: titoli 2–4, elenchi, citazione) o
  `fields/rich-text-inline`; salvano **HTML** da stampare così com'è.
- Blocchi: `site/blueprints/blocks/*.yml` con `preview: fields` e `wysiwyg: true`; lo snippet
  corrispondente è in `site/snippets/blocks/`. `text.yml` e `image.yml` sostituiscono quelli di Kirby.
- Campi a larghezza frazionaria con campo `gap` come distanziatore (`output.yml`, `home.yml`).
- Il **campo `alt`** è in `blueprints/files/default.yml` (obbligatorio); `info: "{{ file.alt.or('⚠ Alt mancante') }}"`
  segna le immagini senza Alt.
- Un `files` **field** (selettore) accetta anche upload; una `files` **section** è l'area di
  caricamento. Una sezione `type: pages` con `query` **non è trascinabile**.
- Con `panel.vue.compiler = false` i plugin del Panel (JS) devono usare **funzioni render**, non
  `template:` in stringa (vedi `panel-registration/index.js`).

### 10.5 Plugin (`site/plugins/`)
- **asset-builders** — `bundledAsset('main', [globs…])` unisce i CSS.
- **contact-form** — route POST `invia-messaggio`; limiti 500 parole / 10000 caratteri; campo
  trappola, CSRF; destinatario da Chi siamo → Contatti (`contact_email`, riserva
  `consfim@gmail.com`); mittente `no-reply@consultafemminile.org` (opzione
  `consulta.contactForm.from`).
- **image-derivatives** — `create` in `blueprints/files/default.yml` (max 2400 px, qualità 82);
  hook che generano subito le miniature WebP (`thumbs.format = webp`, qualità 80).
- **image-alt** — hook `page.update:before` / `site.update:before`: blocca il salvataggio con
  immagini nuove senza Alt (data `consulta.alt.since`; template esenti `consulta.alt.exempt`,
  di default `persone`).
- **seo** — snippet `seo/head` (in `header.php`), classe `Consulta\Seo\Meta` (cascata), route
  `robots.txt` e `sitemap.xml`; campi `seoTitle`, `seoDescription`, `seoImage`, `seoNoindex`;
  opzioni `consulta.seo.index`, `descriptionLength`, `titleLength`. Non usa il plugin
  `tobimori/kirby-seo` (v1 incompatibile con Kirby 5.6, v2 a pagamento).
- **social-icons** — metodo `site.socialIconOptions` (file `.svg` di `assets/icons/social/`).
- **panel-registration** — "Richiedi l'accesso": API pubblica `registration` (GET/POST),
  ruolo `in-attesa`, hook `user.login:before`, opzioni `consulta.registration.*`
  (`active`, `role`, `maxUsers`, `maxPending`, `attempts`, `notify`).

### 10.6 Configurazione (`site/config/config.php`)
`debug` e `panel.install` attivi solo su **staging** (sottocartella `/staging/`); `thumbs`
WebP; `panel.vue.compiler = false`; `panel.menu` (Sito, Lingue, Utenti, Sistema + link
"Strumento immagini"); segreti `content.salt` e `cookie.key` letti da `secrets.php` se esiste.
Ruoli in `site/blueprints/users/` (`editor.yml`, `in-attesa.yml`; `admin` è quello di Kirby).

### 10.7 Pubblicazione
GitHub Actions, avvio **manuale** (Actions → Run workflow), upload **FTP** su Aruba:
`deploy-production.yml` carica i file del repository **senza** eseguire Composer;
`deploy-staging.yml` esegue `composer install --no-dev` e poi carica (quindi include `kirby/` e
`vendor/`). Credenziali nei secrets del repository (`FTP_SERVER`, `FTP_USERNAME`, `FTP_PASSWORD`).
`.deployignore` esclude `content/`, `site/accounts/`, `site/cache/`, `site/sessions/`,
`site/config/.license`, `media/`, `.htpasswd`, `docs/`, `.git/`, `.github/`, `README.md`.
Dunque contenuti, utenti e licenza in produzione **non cambiano col deploy**. Sul server servono
(una volta) `site/config/secrets.php` e la licenza attivata.

### 10.8 Aggiornamenti e test
- Aggiornare Kirby: `composer update getkirby/cms`; poi controllare i plugin e il Panel.
- Da script PHP di servizio: usare `new Kirby()` (senza opzioni) + `$kirby->impersonate('kirby')`
  e **fare un backup** dei contenuti prima di scrivere; mai `new Kirby([...opzioni])` insieme a
  `->update()`.
- Azzerare le cache: svuotare `site/cache/` (i file in `media/` si rigenerano).
- Test dell'invio email e della registrazione: usare `consulta.registration.notify = false` per
  non mandare posta vera.

### 10.9 Come aggiungere…
- **Un tipo di pagina**: `site/blueprints/pages/x.yml` (tab `contenuto` + `seo`), template
  `site/templates/x.php`, eventuale collezione in `site/collections/`.
- **Un blocco**: `site/blueprints/blocks/x.yml` + `site/snippets/blocks/x.php`, poi aggiungerlo ai
  `fieldsets` della pagina.
- **Un componente**: `site/snippets/bits/x.php` + `x.css` (con `@layer bits`); il CSS entra da
  solo nel bundle.
- **Un'icona social**: file `.svg` in `assets/icons/social/`: compare nel select del Panel.

---

## 11. Glossario

- **Panel** — l'area di amministrazione di Kirby (`/panel`).
- **Output** — un articolo (tipo di pagina "output").
- **Tematica** — un tema di lavoro della Consulta; contiene gli Output.
- **Listed / non listed** — pagina "pubblicata nel menu e ordinabile" / pubblicata ma fuori dal
  menu; **bozza** (draft) = non pubblica.
- **Slug** — la parte finale dell'indirizzo di una pagina.
- **Alt** — testo alternativo di un'immagine.
- **Blueprint** — il file che definisce cosa si può modificare nel Panel.
- **Staging** — copia di prova del sito (in una sottocartella `/staging/`).
- **Payoff** — la frase breve sotto/sopra il titolo (quella del sito è usata come titolo grande
  in molte pagine).

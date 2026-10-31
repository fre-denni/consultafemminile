/**
 * Strumento immagini nel Panel (vedi index.php).
 *
 * - k-files-field: estende il campo file di Kirby. Se nel blueprint il campo ha
 *   la chiave `imgtool: LARGHEZZAxALTEZZA` (misura da impostare nello strumento)
 *   accanto a "Carica" e "Seleziona" compare "Genera".
 * - k-imgfiles-section: lo stesso per le sezioni file (tipo `imgfiles`, vedi
 *   index.php): "Genera" accanto a "Aggiungi".
 * - k-imgtool-dialog: finestra con lo strumento ospitato dal sito
 *   (assets/imgtool, in un <iframe> della stessa origine) e il nome del file.
 *   Mentre è aperta espone window.__consultaImgTool = { size, upload(blob) }:
 *   lo strumento ne legge la misura e chiama upload dal suo pulsante
 *   "Aggiungi alla pagina". Niente postMessage, e un altro sito non potrebbe
 *   raggiungerla (il browser lo impedisce tra origini diverse).
 *
 * L'immagine si carica con lo stesso endpoint di upload del campo o della
 * sezione (stessa sessione e stesso token anti-falsificazione di qualunque
 * upload), finisce direttamente nello slot e si segna come "decorativa": alt="" voluto, niente avviso
 * "Alt mancante".
 * Niente build, e una funzione render al posto del template (il Panel gira
 * senza compilatore Vue, vedi config.php).
 */
(() => {
  const BRIDGE = "__consultaImgTool";
  const TOOL_PATH = "assets/imgtool/index.html";

  // Stessa dicitura di File::altInfo() (plugins/image-alt) per la voce di elenco
  const DECORATIVE_INFO = "Decorativa (senza Alt)";

  // Il campo che ha aperto la finestra: la finestra gli passa il file caricato.
  // (Una funzione non può viaggiare tra le props di una finestra del Panel.)
  let target = null;

  // "400x210" → { width: 400, height: 210 }
  const parseSize = (value) => {
    const match = /^(\d+)\s*[x×]\s*(\d+)$/i.exec(String(value || "").trim());
    return match ? { width: Number(match[1]), height: Number(match[2]) } : null;
  };

  // Nome di partenza: "cover.webp" se la pagina non ne ha una, poi immagine-1…
  const freeName = (taken, ext) => {
    if (!taken.includes("cover." + ext)) return "cover." + ext;
    let i = 1;
    while (taken.includes(`immagine-${i}.${ext}`)) i++;
    return `immagine-${i}.${ext}`;
  };

  // Solo caratteri sicuri per un nome di file
  const cleanName = (name) =>
    name
      .trim()
      .toLowerCase()
      .replace(/\s+/g, "-")
      .replace(/[^a-z0-9._-]/g, "")
      .replace(/^[._-]+/, "");

  panel.plugin("consulta/imgtool", {
    components: {
      "k-files-field": {
        extends: "k-files-field",
        props: {
          // Misura da impostare nello strumento, es. "400x210" (chiave del blueprint)
          imgtool: { type: String, default: null },
        },
        computed: {
          buttons() {
            // Il campo originale di Kirby è quello che estendiamo (options.extends)
            const buttons = this.$options.extends.options.computed.buttons.call(this);

            // Come "Carica": solo se c'è ancora posto per un'immagine
            if (this.imgtool && this.hasDropzone) {
              buttons.splice(1, 0, {
                text: "Genera",
                responsive: true,
                icon: "image",
                title: "Genera un'immagine in linea col brand e inseriscila qui",
                click: () => this.openImgtool(),
              });
            }

            return buttons;
          },
        },
        methods: {
          openImgtool() {
            target = {
              // Caricata e segnata come decorativa: la mettiamo nello slot
              done: async (model) => {
                await this.uploadOptions.on.done([model]);
              },
            };
            this.$panel.dialog.open({
              component: "k-imgtool-dialog",
              props: {
                // "pages/x/fields/immagine" (anche dentro una struttura) → la pagina è "pages/x"
                endpoint: this.endpoints.field + "/upload",
                parent: this.endpoints.field.split("/fields/")[0],
                size: parseSize(this.imgtool),
                label: this.label || "",
              },
            });
          },
        },
      },

      "k-imgfiles-section": {
        extends: "k-files-section",
        computed: {
          buttons() {
            const buttons = this.$options.extends.options.computed.buttons.call(this);

            // Come "Aggiungi": solo se si può ancora caricare
            if (this.options.imgtool && this.canAdd && !this.isSelecting) {
              const add = buttons.findIndex((button) => button.icon === this.addIcon);
              buttons.splice(add + 1, 0, {
                text: "Genera",
                responsive: true,
                icon: "image",
                title: "Genera un'immagine in linea col brand e aggiungila qui",
                click: () => this.openImgtool(),
              });
            }

            return buttons;
          },
        },
        methods: {
          openImgtool() {
            const upload = this.options.upload || {};

            target = {
              // Caricata e segnata come decorativa: la sezione si ricarica da sola
              done: async () => {
                this.$events.emit("model.update");
              },
            };
            this.$panel.dialog.open({
              component: "k-imgtool-dialog",
              props: {
                // "pages/x/files" → la pagina è "pages/x"
                endpoint: upload.api,
                parent: String(upload.api || "").replace(/\/files$/, ""),
                attributes: upload.attributes || {},
                size: parseSize(this.options.imgtool),
                label: this.options.headline || "",
              },
            });
          },
        },
      },

      "k-imgtool-dialog": {
        mixins: ["dialog"],
        props: {
          // Dove caricare (percorso dell'API) e la pagina/sito a cui appartiene il file
          endpoint: { type: String, required: true },
          parent: { type: String, required: true },
          // Campi in più dell'upload di una sezione (es. il template del file)
          attributes: { type: Object, default: () => ({}) },
          size: { type: Object, default: null },
          label: { type: String, default: "" },
        },
        data() {
          return { filename: "", taken: [], ready: false };
        },
        computed: {
          src() {
            return new URL(TOOL_PATH, this.$panel.urls.site.replace(/\/?$/, "/")).href;
          },
        },
        async created() {
          try {
            const response = await this.$api.get(this.parent + "/files", { select: "filename" });
            this.taken = (response.data || []).map((file) => file.filename);
          } catch (e) {
            this.taken = [];
          }

          this.filename = freeName(this.taken, "webp");

          // Pronto prima che l'iframe esista: lo strumento legge la misura all'avvio
          window[BRIDGE] = { size: this.size, upload: (blob) => this.upload(blob) };
          this.ready = true;
        },
        beforeDestroy() {
          delete window[BRIDGE];
          target = null;
        },
        methods: {
          async upload(blob) {
            // Il formato lo decide il browser (Safari non scrive WebP): l'estensione lo segue.
            const ext = blob.type === "image/webp" ? "webp" : "png";
            let name = cleanName(this.filename).replace(/\.[a-z0-9]+$/, "") || "immagine";
            name = `${name}.${ext}`;

            if (this.taken.includes(name)) {
              throw new Error(`Esiste già un file chiamato «${name}»: scegli un altro nome.`);
            }

            const body = new FormData();
            for (const [key, value] of Object.entries(this.attributes)) {
              if (value !== null && value !== undefined) body.append(key, value);
            }
            body.append("file", new File([blob], name, { type: blob.type }));

            const response = await fetch(`${this.$panel.urls.api}/${this.endpoint}`, {
              method: "POST",
              body,
              credentials: "same-origin",
              headers: { "x-csrf": this.$panel.system.csrf },
            });
            const json = await response.json().catch(() => ({}));

            if (!response.ok || json.status === "error") {
              throw new Error(json.message || json.error || "Caricamento non riuscito.");
            }

            // Un campo risponde col file già pronto per la lista (id, anteprima, info…),
            // una sezione col file e basta: si aggiorna da sola dopo.
            const model = Array.isArray(json.data) ? json.data[0] : json.data;
            const filename = model?.filename || name;

            // Generata dallo strumento = decorativa: alt vuoto, saltata dai lettori di schermo.
            await this.$api.patch(`${this.parent}/files/${encodeURIComponent(filename)}`, { decorative: true });
            if (model.info !== undefined) model.info = DECORATIVE_INFO;

            await target?.done(model);

            this.$panel.notification.success({
              message: `Immagine inserita: ${filename} (decorativa)`,
              icon: "check",
            });
            this.$panel.dialog.close();

            return { filename };
          },
        },
        render(h) {
          const children = [];
          // Solo le proprietà della finestra di Kirby, non le nostre
          const { endpoint, parent, attributes, size, label, ...dialogProps } = this.$props;

          if (this.ready) {
            children.push(
              h("p", { class: "consulta-imgtool__intro" }, [
                `Genera l'immagine e premi «Aggiungi alla pagina»: viene inserita in «${this.label || "Immagine"}», segnata come decorativa (senza testo alternativo).` +
                  (this.size ? ` Dimensioni già impostate: ${this.size.width} × ${this.size.height}.` : ""),
              ]),
              h("k-text-field", {
                attrs: { label: "Nome del file", value: this.filename, counter: false, icon: "image", name: "filename" },
                on: { input: (value) => (this.filename = value) },
              }),
              h("iframe", {
                class: "consulta-imgtool__frame",
                attrs: { src: this.src, title: "Strumento immagini" },
              })
            );
          }

          return h(
            "k-dialog",
            {
              attrs: { ...dialogProps, size: "huge", cancelButton: "Chiudi", submitButton: false },
              on: { cancel: this.cancel, close: this.close, submit: this.submit },
            },
            children
          );
        },
      },
    },
  });
})();

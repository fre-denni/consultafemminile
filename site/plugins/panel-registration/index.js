/**
 * Pagina di login del Panel con "Richiedi l'accesso".
 *
 * Usa il punto di estensione ufficiale `login` dei plugin del Panel: questo
 * componente prende il posto del form di login e lo riusa così com'è
 * (<k-login-form>), aggiungendo sotto un link che apre il modulo di
 * richiesta. L'account nasce senza permessi e lo approva un amministratore
 * (vedi index.php e classes/Registration.php). Niente build: JS semplice, con
 * una funzione render (il Panel non ha il compilatore di template Vue).
 */
(() => {
  const TEXT = {
    it: {
      toggle: "Non hai un account? Richiedi l'accesso",
      back: "Torna al login",
      name: "Nome e cognome",
      email: "Email",
      password: "Password",
      confirm: "Ripeti la password",
      send: "Invia richiesta",
      mismatch: "Le due password non coincidono.",
      done: "Richiesta inviata. Potrai entrare quando un amministratore avrà approvato il tuo account.",
      failed: "Non è stato possibile inviare la richiesta. Riprova più tardi.",
    },
    en: {
      toggle: "No account yet? Request access",
      back: "Back to login",
      name: "Full name",
      email: "Email",
      password: "Password",
      confirm: "Repeat password",
      send: "Send request",
      mismatch: "The two passwords do not match.",
      done: "Request sent. You will be able to sign in once an administrator approves your account.",
      failed: "The request could not be sent. Please try again later.",
    },
  };

  panel.plugin("consulta/registration", {
    login: {
      props: {
        methods: { type: Array, default: () => [] },
        value: { type: Object, default: () => ({}) },
      },
      data() {
        return {
          mode: "login", // "login" | "register" | "done"
          open: false,
          busy: false,
          error: "",
          website: "", // campo trappola per i bot, mai visibile
          form: { name: "", email: "", password: "", confirm: "" },
        };
      },
      computed: {
        t() {
          const code = (this.$panel.translation?.code || "it").slice(0, 2);
          return TEXT[code] || TEXT.en;
        },
        fields() {
          return {
            name: { label: this.t.name, type: "text", required: true, counter: false, autocomplete: "name", maxlength: 80 },
            email: { label: this.t.email, type: "email", required: true, link: false, autocomplete: "email" },
            password: { label: this.t.password, type: "password", required: true, minLength: 8, counter: false, autocomplete: "new-password" },
            confirm: { label: this.t.confirm, type: "password", required: true, minLength: 8, counter: false, autocomplete: "new-password" },
          };
        },
      },
      async created() {
        try {
          const response = await this.$api.get("registration");
          this.open = response.open === true;
        } catch (e) {
          this.open = false; // se qualcosa non va, il login resta com'è
        }
      },
      methods: {
        show(mode) {
          this.error = "";
          this.mode = mode;
        },
        async send() {
          this.error = "";

          if (this.form.password !== this.form.confirm) {
            this.error = this.t.mismatch;
            return;
          }

          this.busy = true;

          try {
            await this.$api.post("registration", {
              name: this.form.name,
              email: this.form.email,
              password: this.form.password,
              website: this.website,
            });
            this.form = { name: "", email: "", password: "", confirm: "" };
            this.mode = "done";
          } catch (e) {
            this.error = e?.message || this.t.failed;
          } finally {
            this.busy = false;
          }
        },
      },
      // Funzione render e non `template:`: il Panel gira senza compilatore
      // Vue (config.php, panel.vue.compiler = false), quindi i template in
      // stringa non verrebbero compilati.
      render(h) {
        const t = this.t;
        const children = [];

        if (this.error) {
          children.push(
            h("k-login-alert", { nativeOn: { click: () => (this.error = "") } }, this.error)
          );
        }

        if (this.mode === "login") {
          children.push(
            h("k-login-form", {
              attrs: { methods: this.methods, value: this.value },
              on: { error: (e) => this.$emit("error", e) },
            })
          );

          if (this.open) {
            children.push(
              h("p", { class: "consulta-login__switch" }, [
                h("k-button", {
                  attrs: { text: t.toggle, variant: "dimmed", size: "sm" },
                  on: { click: () => this.show("register") },
                }),
              ])
            );
          }
        } else if (this.mode === "register") {
          children.push(
            h(
              "form",
              {
                class: "k-login-form",
                on: {
                  submit: (e) => {
                    e.preventDefault();
                    this.send();
                  },
                },
              },
              [
                h("div", { class: "k-login-fields" }, [
                  h("k-fieldset", {
                    attrs: { fields: this.fields, value: this.form },
                    on: { input: (value) => (this.form = value) },
                  }),
                  h("input", {
                    class: "consulta-login__trap",
                    attrs: { type: "text", name: "website", tabindex: "-1", autocomplete: "off", "aria-hidden": "true" },
                    domProps: { value: this.website },
                    on: { input: (e) => (this.website = e.target.value) },
                  }),
                ]),
                h("footer", { class: "k-login-buttons" }, [
                  h("k-button", {
                    class: "k-login-button k-login-back-button",
                    attrs: { text: t.back, icon: "angle-left", size: "lg", variant: "filled" },
                    on: { click: () => this.show("login") },
                  }),
                  h("k-button", {
                    class: "k-login-button",
                    attrs: { text: t.send, icon: "check", size: "lg", theme: "positive", type: "submit", variant: "filled", disabled: this.busy },
                  }),
                ]),
              ]
            )
          );
        } else {
          children.push(
            h("div", { class: "k-login-form" }, [
              h("p", { class: "consulta-login__done" }, t.done),
              h("footer", { class: "k-login-buttons" }, [
                h("k-button", {
                  class: "k-login-button",
                  attrs: { text: t.back, icon: "angle-left", size: "lg", variant: "filled" },
                  on: { click: () => this.show("login") },
                }),
              ]),
            ])
          );
        }

        return h("div", { class: "consulta-login" }, children);
      },
    },
  });
})();

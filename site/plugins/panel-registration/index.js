/**
 * Pagina di login del Panel con "Richiedi l'accesso".
 *
 * Usa il punto di estensione ufficiale `login` dei plugin del Panel: questo
 * componente prende il posto del form di login e lo riusa così com'è
 * (<k-login-form>), aggiungendo sotto un link che apre il modulo di
 * richiesta. L'account nasce senza permessi e lo approva un amministratore
 * (vedi index.php e classes/Registration.php). Niente build: JS semplice.
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
      template: `
        <div class="consulta-login">
          <k-login-alert v-if="error" @click.native="error = ''">{{ error }}</k-login-alert>

          <template v-if="mode === 'login'">
            <k-login-form :methods="methods" :value="value" @error="$emit('error', $event)" />
            <p v-if="open" class="consulta-login__switch">
              <k-button :text="t.toggle" variant="dimmed" size="sm" @click="show('register')" />
            </p>
          </template>

          <form v-else-if="mode === 'register'" class="k-login-form" @submit.prevent="send">
            <div class="k-login-fields">
              <k-fieldset :fields="fields" :value="form" @input="form = $event" />
              <input v-model="website" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true" class="consulta-login__trap">
            </div>
            <footer class="k-login-buttons">
              <k-button class="k-login-button k-login-back-button" :text="t.back" icon="angle-left" size="lg" variant="filled" @click="show('login')" />
              <k-button class="k-login-button" :text="t.send" icon="check" size="lg" theme="positive" type="submit" variant="filled" :disabled="busy" />
            </footer>
          </form>

          <div v-else class="k-login-form">
            <p class="consulta-login__done">{{ t.done }}</p>
            <footer class="k-login-buttons">
              <k-button class="k-login-button" :text="t.back" icon="angle-left" size="lg" variant="filled" @click="show('login')" />
            </footer>
          </div>
        </div>
      `,
    },
  });
})();

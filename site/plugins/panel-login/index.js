/**
 * Login del Panel con nome utente o email (vedi index.php).
 *
 * Estende il form di login di Kirby (<k-login-form>) cambiando solo due
 * cose: il primo campo è un testo libero "Utente o email" invece di un
 * campo email, e se quello che si scrive non contiene una @ l'accesso passa
 * dalla rotta "login-by-name". Il resto (password, "Ricordami", messaggi
 * d'errore) è quello di Kirby. Niente build: JS semplice.
 */
(() => {
  const LABEL = { it: "Utente o email", en: "Username or email" };

  panel.plugin("consulta/panel-login", {
    components: {
      "k-login-form": {
        extends: "k-login-form",
        computed: {
          fields() {
            const code = (this.$panel.translation?.code || "it").slice(0, 2);
            const fields = {
              email: {
                autofocus: true,
                label: LABEL[code] || LABEL.en,
                type: "text",
                required: true,
                counter: false,
                autocomplete: "username",
              },
            };

            if (this.form === "email-password") {
              fields.password = {
                label: this.$t("password"),
                type: "password",
                minLength: 8,
                required: true,
                autocomplete: "current-password",
                counter: false,
              };
            }

            return fields;
          },
        },
        methods: {
          // Come il login di Kirby, tranne che per chi non scrive una email.
          async login() {
            this.$emit("error", null);
            this.isLoading = true;

            const data = { ...this.user };
            data.email = (data.email || "").trim();
            if (this.mode === "email") data.password = null;
            if (this.isResetForm === true) data.remember = false;

            try {
              if (data.password && !data.email.includes("@")) {
                await this.$api.post("login-by-name", {
                  email: data.email,
                  password: data.password,
                  long: data.remember ?? false,
                });
              } else {
                await this.$api.auth.login(data);
              }

              this.$reload({ globals: ["$system", "$translation"] });
              this.$panel.notification.success({ message: this.$t("welcome") + "!", icon: "smile" });
            } catch (e) {
              this.$emit("error", e);
            } finally {
              this.isLoading = false;
            }
          },
        },
      },
    },
  });
})();

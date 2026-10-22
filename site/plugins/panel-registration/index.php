<?php

use Consulta\Registration\Registration;
use Kirby\Cms\User;
use Kirby\Exception\InvalidArgumentException;
use Kirby\Exception\PermissionException;

require_once __DIR__ . '/classes/Registration.php';

/**
 * Richiesta di accesso dalla pagina di login del Panel.
 *
 * Nel Panel, sotto il form di login compare "Richiedi l'accesso" (vedi
 * index.js): chi si registra ottiene un account senza permessi, che un
 * amministratore approva assegnandogli un ruolo da Utenti — vedi
 * classes/Registration.php e blueprints/users/in-attesa.yml.
 *
 * Opzioni (config.php), prefisso 'consulta.registration.':
 *   active      true   spegne/accende la funzione
 *   role        'in-attesa'   ruolo dato ai nuovi account
 *   maxUsers    25     tetto agli utenti totali (Kirby Basic non ne ha)
 *   maxPending  10     tetto alle richieste in attesa
 *   attempts    5      tentativi per IP ogni ora
 *   notify      true   email agli amministratori a ogni richiesta
 */
Kirby::plugin('consulta/registration', [
  'options' => [
    'cache'      => true, // contatore dei tentativi per IP (vedi Registration::throttle)
    'active'     => true,
    'role'       => 'in-attesa',
    'maxUsers'   => 25,
    'maxPending' => 10,
    'attempts'   => 5,
    'notify'     => true,
  ],
  'api' => [
    'routes' => [
      [
        // Il form si mostra solo se la funzione è attiva e c'è posto.
        'pattern' => 'registration',
        'method'  => 'GET',
        'auth'    => false,
        'action'  => function () {
          return ['code' => 200, 'status' => 'ok', 'open' => Registration::isOpen($this->kirby())];
        },
      ],
      [
        'pattern' => 'registration',
        'method'  => 'POST',
        'auth'    => false,
        'action'  => function () {
          $kirby = $this->kirby();
          $auth  = $kirby->auth();

          // Stesso controllo CSRF del login (vedi kirby/config/api/routes/auth.php).
          if ($auth->type() === 'session' && $auth->csrf() === false) {
            throw new InvalidArgumentException(message: 'Invalid CSRF token');
          }

          Registration::register($kirby, [
            'name'     => $this->requestBody('name'),
            'email'    => $this->requestBody('email'),
            'password' => $this->requestBody('password'),
            'website'  => $this->requestBody('website', ''),
          ]);

          return ['code' => 200, 'status' => 'ok'];
        },
      ],
    ],
  ],
  'hooks' => [
    // Chi è ancora "in attesa" non entra: messaggio chiaro invece di un
    // Panel vuoto o di un errore di permessi.
    'user.login:before' => function (User $user) {
      if ($user->role()->id() === Registration::role()) {
        throw new PermissionException(
          message: 'Il tuo account è in attesa di approvazione: un amministratore deve assegnarti un ruolo prima che tu possa entrare.'
        );
      }
    },
  ],
]);

# Phishing-Simulationsplattform (Plain PHP)

Diese Plattform dient zur internen Durchführung von Phishing-Simulationen zu Schulungszwecken. Sie basiert auf PHP ohne Frameworks, benötigt keine Datenbankserver und kann auf einfachem Shared Hosting betrieben werden.

## Voraussetzungen

- PHP 7.4 oder höher
- Schreibrechte im Webverzeichnis
- FTP-Zugang
- SMTP-Zugang (z. B. Gmail mit App-Passwort)
- [PHPMailer](https://github.com/PHPMailer/PHPMailer) lokal eingebunden (kein Composer erforderlich)

## Verzeichnisstruktur

    /admin.php             → Kampagnensteuerung & Dashboard
    /index.php             → Tracking & Fakeformular
    /guru.php              → Aufklärungsseite
    /stats.php             → JSON-Aggregat für Live-Dashboard
    /config.php            → SMTP- und App-Konfiguration
    /campaigns.json        → Definition der Kampagnen
    /data/                 → CSV-Dateien mit Empfängerdaten
    /templates/            → HTML-E-Mail-Templates mit Platzhaltern
    /templates/*-form.html → Login-Formulare der Kampagnen
    /logs/                 → Logdateien im JSONL-Format
    /phpmailer/            → PHPMailer-Bibliothek

## Konfiguration

### campaigns.json

    [
      { "name": "IT-Test", "csv": "gruppe1.csv",
        "email_template": "it-check.html",
        "login_form": "it-check-form.html" },
      { "name": "Webmail", "csv": "gruppe2.csv",
        "email_template": "webmail-login.html",
        "login_form": "webmail-login-form.html" }
    ]

Die CSV-Dateien liegen in `/data/`,
die Templates und Formulare in `/templates/`.

Die Felder `email_template` und `login_form` verweisen jeweils auf die
HTML-Dateien in diesem Verzeichnis.

### CSV-Format

    email,vorname,nachname
    m.muster@example.org,Max,Muster

## E-Mail-Template

- %Email%
- %Vorname%
- %Name%
- %Link%

Beispiel:

    <p>Hallo %Vorname%,</p>
    <p>bitte bestätigen Sie Ihre Anmeldung unter folgendem Link:</p>
    <p><a href="%Link%">%Link%</a></p>

## Ablauf

1. Empfängerliste und Template per FTP hochladen
2. Kampagne in `campaigns.json` definieren
3. `admin.php` aufrufen und Versand starten

## Tracking

- `index.php` loggt Klicks + zeigt ein Fakeformular
- Formular sendet an `index.php`, loggt Eingaben, leitet zu `guru.php`
 - Protokollierung erfolgt in `/logs/<kampagne>-<timestamp>.jsonl`
   (inkl. Fehlermeldungen `send_error` bzw. `send_exception` bei Problemen
   beim Mailversand)

## Live-Dashboard

- `admin.php` lädt alle 5 Sekunden Daten aus `stats.php`
- Anzeige: versendet / geklickt / abgesendet + Details

## SMTP-Konfiguration (`config.php`)

    return [
        'smtp_host' => 'smtp.gmail.com',
        'smtp_port' => 587,
        'smtp_user' => 'your@gmail.com',
        'smtp_pass' => 'password',
        'smtp_secure' => 'tls',
        'from_email' => 'your@gmail.com',
        'from_name' => 'Admin'
    ];

## Hinweise

- App-Passwort erforderlich bei Gmail
- Zugriff auf `admin.php` absichern (z. B. .htaccess)
- Keine echten Passwörter verwenden

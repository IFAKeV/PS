# Ködermail-Simulationsplattform

Diese Plattform versendet zu Schulungszwecken Nachrichten, die darauf abzielen, Empfängerinnen zur Preisgabe sensibler Informationen oder zum Klick auf manipulierte Inhalte zu bewegen – meist unter Vorspiegelung vertrauenswürdiger Absender. Sie ist per Design nicht geeignet solche Nachrichten zu tatsächlichen Schadzwecken zu verbreiten.
Sie dient - kombiniert mit Schulungen und Aufkärungsvideos - zur Risikosensibilisierung und soll das Sicherheitsbewusstsein und die Sicherheitskompetenz erhöhen

Sie basiert auf PHP ohne Frameworks, benötigt keine Datenbankserver und kann auf einfachem Shared Hosting betrieben werden.

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
        "login_form": "it-check-form.html",
        "subject": "Überprüfung Ihrer IT-Zugangsdaten" },
      { "name": "Webmail", "csv": "gruppe2.csv",
        "email_template": "webmail-login.html",
        "login_form": "webmail-login-form.html",
        "subject": "Bestätigung Ihres Webmail-Kontos" }
    ]

Die CSV-Dateien liegen in `/data/`,
die Templates und Formulare in `/templates/`.

Die Felder `email_template` und `login_form` verweisen jeweils auf die
HTML-Dateien in diesem Verzeichnis. Mit `subject` kann der Betreff der
versendeten E-Mail festgelegt werden.

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
 - Mehrfache Klicks und Formularsendungen werden gezählt und samt eingegebener Daten aufgeführt

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

- App-Passwort erforderlich bei Gmail.
- Zugriff auf `admin.php` absichern (z. B. .htaccess).
- Keine echten Passwörter verwenden.
- Login-Formulare kann man sich über das SingleFile-Plugin für Firefox "leihen".
- Es spart zudem Arbeit vorhandene Mails als Template zu nutzen. Thunderbird kann Mails im Entwurfsstatus als HTML abspeichern.

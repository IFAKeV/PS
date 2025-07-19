# Entwicklungs-Roadmap

Diese Datei dokumentiert, welche Funktionen laut `README.md` vorhanden sein sollen und welche bereits umgesetzt wurden. Zudem listet sie offene Punkte f\u00fcr die n\u00e4chsten Schritte.

## Abgleich Funktionen

- **Kampagnensteuerung & Dashboard (`admin.php`)**: Vorhanden. Versand \u00fcber PHPMailer und einfaches Dashboard mit JSON-Ausgabe.
- **Tracking & Fakeformular (`index.php`)**: Vorhanden. Klicks und Login-Eingaben werden geloggt.
- **Aufkl\u00e4rungsseite (`guru.php`)**: Vorhanden. Zeigt Hinweis nach Formularversand.
- **JSON-Aggregat f\u00fcr Live-Dashboard (`stats.php`)**: Vorhanden. Liest Logdateien und fasst Statistiken zusammen.
- **Konfigurationsdatei (`config.php`)**: Vorhanden. Parameter werden als Array zur\u00fcckgegeben.
- **Kampagnendefinition (`campaigns.json`)**: Vorhanden.
- **CSV-Daten (`/data` Verzeichnis)**: Beispiel-Dateien vorhanden.
- **E-Mail-Templates und Formulare (`/templates`)**: Vorhanden.
- **Logverzeichnis (`/logs`)**: Im Repository bisher nicht enthalten. F\u00fcr die Ausf\u00fchrung notwendig.
- **PHPMailer-Bibliothek**: Eingebunden.

## Offene Punkte

1. **Logverzeichnis bereitstellen**
   - Verzeichnis `logs/` anlegen und bei Bedarf mit `.gitkeep` versionieren (bereits umgesetzt).
2. **Absicherung von `admin.php`**
   - Gem\u00e4\u00df README sollte der Zugriff gesch\u00fctzt werden (z.B. per `.htaccess`). Dies fehlt aktuell komplett im Repository.
3. **README und Konfiguration angleichen**
   - In der README werden Konstanten gezeigt, w\u00e4hrend `config.php` ein Array liefert. Einheitliche Beschreibung w\u00fcnschenswert.
4. **Fehlerbehandlung verbessern**
   - Wenn Dateien (CSV/Template) fehlen oder das Logverzeichnis nicht beschreibbar ist, sollte eine aussagekr\u00e4ftige Fehlermeldung erscheinen.
5. **Sch\u00fctzen sensibler Daten**
   - Logdateien sollten nicht \u00f6ffentlich zug\u00e4nglich sein und gegebenenfalls in `.gitignore` aufgenommen werden.
6. **Optische Aufbereitung des Dashboards**
   - Aktuell werden die Statistiken als JSON ausgegeben. Eine bessere Darstellung k\u00f6nnte die Benutzung erleichtern.

Weitere Schritte k\u00f6nnen sich aus neuen Anforderungen ergeben. Diese Roadmap dient als Grundlage, die offenen Punkte systematisch abzuarbeiten.


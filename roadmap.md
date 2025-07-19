# Entwicklungs-Roadmap

Diese Datei dokumentiert, welche Funktionen laut `README.md` vorhanden sein sollen und welche bereits umgesetzt wurden. Zudem listet sie offene Punkte für die nächsten Schritte.

## Abgleich Funktionen

- **Kampagnensteuerung & Dashboard (`admin.php`)**: Vorhanden. Versand über PHPMailer und einfaches Dashboard mit JSON-Ausgabe.
- **Tracking & Fakeformular (`index.php`)**: Vorhanden. Klicks und Login-Eingaben werden geloggt.
- **Aufkl\u00e4rungsseite (`guru.php`)**: Vorhanden. Zeigt Hinweis nach Formularversand.
- **JSON-Aggregat f\u00fcr Live-Dashboard (`stats.php`)**: Vorhanden. Liest Logdateien und fasst Statistiken zusammen.
- **Konfigurationsdatei (`config.php`)**: Vorhanden. Parameter werden als Array zurückgegeben.
- **Kampagnendefinition (`campaigns.json`)**: Vorhanden.
- **CSV-Daten (`/data` Verzeichnis)**: Beispiel-Dateien vorhanden.
- **E-Mail-Templates und Formulare (`/templates`)**: Vorhanden.
- **Logverzeichnis (`/logs`)**: Im Repository bisher nicht enthalten. Für die Ausführung notwendig.
- **PHPMailer-Bibliothek**: Eingebunden.

## Offene Punkte

1. **Logverzeichnis bereitstellen**
   - Verzeichnis `logs/` anlegen und bei Bedarf mit `.gitkeep` versionieren (bereits umgesetzt).
2. **Absicherung von `admin.php`**
   - Gemäß README sollte der Zugriff geschützt werden (z.B. per `.htaccess`). Dies fehlt aktuell komplett im Repository.
   - Umsetzung erfolgt, wenn alle Funktionen implementiert sind und erfolgreiche Testläuft erfolgt sind.
3. **Fehlerbehandlung verbessern**
   - Wenn Dateien (CSV/Template) fehlen oder das Logverzeichnis nicht beschreibbar ist, sollte eine aussagekrätige Fehlermeldung erscheinen.
4. **Schützen sensibler Daten**
   - Logdateien sollten nicht öffentlich zugänglich sein und gegebenenfalls in `.gitignore` aufgenommen werden.
   - Umsetzung erfolgt, wenn alle Funktionen implementiert sind und erfolgreiche Testläuft erfolgt sind.
5. **Optische Aufbereitung des Dashboards**
   - Aktuell werden die Statistiken als JSON ausgegeben. Eine bessere Darstellung könnte die Benutzung erleichtern.

Weitere Schritte können sich aus neuen Anforderungen ergeben. Diese Roadmap dient als Grundlage, die offenen Punkte systematisch abzuarbeiten.

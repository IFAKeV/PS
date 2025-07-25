# Entwicklungs-Roadmap

## Abgleich Funktionen

- **Kampagnensteuerung & Dashboard (`admin.php`)**: Vorhanden. Versand über PHPMailer und einfaches Dashboard mit JSON-Ausgabe.
- **Tracking & Fakeformular (`index.php`)**: Vorhanden. Klicks und Login-Eingaben werden geloggt.
- **Aufklärungsseite (`guru.php`)**: Vorhanden. Zeigt Hinweis nach Formularversand.
- **JSON-Aggregat für Live-Dashboard (`stats.php`)**: Vorhanden. Liest Logdateien und fasst Statistiken zusammen.
- **Konfigurationsdatei (`config.php`)**: Vorhanden. Parameter werden als Array zurückgegeben.
- **Kampagnendefinition (`campaigns.json`)**: Vorhanden.
- **CSV-Daten (`/data` Verzeichnis)**: Beispiel-Dateien vorhanden.
- **E-Mail-Templates und Formulare (`/templates`)**: Vorhanden.
- **Logverzeichnis (`/logs`)**: Im Repository bisher nicht enthalten. Für die Ausführung notwendig.
- **PHPMailer-Bibliothek**: Eingebunden.

## Offene Punkte

1. **Absicherung von `admin.php`**
   - Umsetzung erfolgt, wenn alle Funktionen implementiert sind und erfolgreiche Testläufe erfolgt sind.
2. **Schützen sensibler Daten**
   - Logdateien sollten nicht öffentlich zugänglich sein und gegebenenfalls in `.gitignore` aufgenommen werden.
   - Umsetzung erfolgt, wenn alle Funktionen implementiert sind und erfolgreiche Testläuft erfolgt sind.
3. **Optische Aufbereitung des Dashboards**
   - Aktuell werden die Statistiken als JSON ausgegeben. Eine bessere Darstellung könnte die Benutzung erleichtern.
4. **Variablen in den Formular-Templates** um die person, welche schon auf den Link geklickt hat persönlich zu begrüßen. In der Hoffnung die Hemmschwelle zu senken auch noch ihr Passwort einzutragen.

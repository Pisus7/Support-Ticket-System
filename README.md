# Support-Ticket-System

## Start Paralleles Arbeiten
Siehe [Anleitung für Cloning](anleitung_clone_pius.md)

Siehe [Aufgabenverteilung im Abschnitt "Installation & Setup"](#aufgabenverteilung)

## Projektbeschreibung
Das Support-Ticket-System ist eine Webanwendung zur Verwaltung von IT-Problemen innerhalb eines Unternehmens. Mitarbeiter können Tickets erstellen, bearbeiten und kommentieren. Support-Mitarbeiter können diese Tickets bearbeiten und den Status verwalten.

## Szenario
Mitarbeiter melden IT-Probleme (z. B. Softwarefehler, Hardwareprobleme oder Netzwerkstörungen). Diese werden als Tickets im System erfasst und anschließend vom Support bearbeitet. Nicht First-Level-Support (Customer Support).

## Datenbankstruktur
**Tabellen**:
- `users` – Benutzerverwaltung
- `tickets` – Support-Tickets
- `categories` – Kategorien für Tickets
- `comments` – Kommentare zu Tickets

**Beziehungen**:

- Ein User kann viele Tickets erstellen (1:n)
- Ein Ticket gehört zu genau einem User (n:1)
- Ein Ticket gehört zu einer Kategorie (n:1)
- Ein Ticket kann viele Kommentare haben (n:1)
- Ein Kommentar gehört zu einem User und einem Ticket (n:1)

## Funktionen
**Ticketsystem**:
- Ticket erstellen
- Ticket anzeigen
- Ticket bearbeiten
- Ticketstatus ändern (z. B. offen, in Bearbeitung, geschlossen)
- Tickets löschen (je nach Rolle)

**Kommentare**:
- Kommentare zu Tickets hinzufügen
- Kommentarverlauf anzeigen

**Kategorien**:
- Tickets einer Kategorie zuordnen

## Benutzer & Rechte
- Benutzer
   - Tickets erstellen und anzeigen
   - Eigene Tickets kommentieren
- Support-Mitarbeiter
   - Alle Tickets bearbeiten
   - Status ändern
   - Kommentare verwalten
- Admin
   - Vollzugriff auf alle Daten
   - Benutzerverwaltung

## Notifications
E-Mail-Benachrichtigung bei:
- neuem Kommentar auf ein Ticket
- Statusänderung eines Tickets

Empfänger: 
- Ticket-Ersteller
- Beteiligte Support-Mitarbeiter
- 
## Sicherheitskonzept & Autorisierung (Paul)
Um die Integrität der Daten und die Privatsphäre der Benutzer zu schützen, wurden folgende Sicherheitsmechanismen implementiert:

1. **Vollständiges User-Management & Authentifizierung:**
   Einsatz von *Laravel Breeze* für kryptografisch sichere Passwörter (Bcrypt) sowie Schutz vor Brute-Force-Angriffen beim Login/Register.

2. **Absicherung kritischer Routen (Middleware):**
   Alle Ticket- und Kommentar-Routen sind durch die `auth`- und `verified`-Middlewares geschützt. Nicht angemeldete Gäste werden automatisch abgefangen und zur Login-Maske umgeleitet (abgesichert via `TicketTest::test_guests_are_redirected_to_login`).

3. **Rechteprüfung mittels Laravel Policies (Data Leakage Protection):**
   Durch die Implementierung der `TicketPolicy` (`view`-Methode) wird auf Controllerebene per `Gate::authorize()` strikt geprüft, ob das angeforderte Ticket dem aktuell angemeldeten Benutzer gehört. Fremde Zugriffe über manipulierte URLs (ID-Guessing) werden sofort mit einem `HTTP 403 Forbidden` blockiert (abgesichert via `TicketTest::test_user_cannot_view_someone_elses_ticket`).

4. **Mass-Assignment-Protection & Validierung:**
   Sämtliche Benutzereingaben werden über *Form Requests* typisiert validiert, bevor sie die Datenbank erreichen. Die Models nutzen das `$fillable`-Array, um das unbefugte Überschreiben kritischer Tabellenspalten (wie `user_id` oder IDs) durch manipuliertes HTML/JSON zu verhindern.

### REST-Matrix & API-Endpunkte

Die Kommunikation zwischen dem React-Frontend und dem Laravel-Backend folgt strikten REST-Prinzipien über *Inertia.js*. Alle Endpunkte (außer der Startseite) sind durch die Authentifizierungs-Middleware geschützt.

| HTTP-Methode | URL-Pfad | Controller-Methode | Routen-Name | Beschreibung (Szenario) | Schutz & Autorisierung |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **GET** | `/` | *Anonyme Closure* | - | Zeigt die öffentliche Willkommensseite mit Login/Register-Links | Keine (Öffentlich) |
| **GET** | `/profile` | `ProfileController@edit` | `profile.edit` | Zeigt das React-Formular zur Verwaltung des eigenen Profils | `auth` |
| **PATCH** | `/profile` | `ProfileController@update` | `profile.update` | Aktualisiert die Profil-Stammdaten des Benutzers | `auth` |
| **DELETE** | `/profile` | `ProfileController@destroy` | `profile.destroy` | Löscht das gesamte Benutzerkonto aus dem System | `auth` |
| **GET** | `/tickets` | `TicketController@index` | `tickets.index` | Zeigt das Dashboard mit einer Liste aller eigenen Tickets | `auth` |
| **GET** | `/tickets/create` | `TicketController@create` | `tickets.create` | Liefert das Ticket-Erstellungsformular mitsamt Kategorien-Dropdown | `auth` |
| **POST** | `/tickets` | `TicketController@store` | `tickets.store` | Validiert und speichert ein neues Support-Ticket in der Datenbank | `auth` (Form Request) |
| **GET** | `/tickets/{ticket}` | `TicketController@show` | `tickets.show` | Detailseite eines Tickets mitsamt dem gesamten Chatverlauf | `auth` + `TicketPolicy` |
| **GET** | `/tickets/{ticket}/edit` | `TicketController@edit` | `tickets.edit` | Formular zur Bearbeitung eines bestehenden Tickets | `auth` + `TicketPolicy` |
| **PUT/PATCH** | `/tickets/{ticket}` | `TicketController@update` | `tickets.update` | Aktualisiert die Ticket-Daten oder den aktuellen `ticket_status` | `auth` + `TicketPolicy` |
| **DELETE** | `/tickets/{ticket}` | `TicketController@destroy` | `tickets.destroy` | Löscht ein Ticket kaskadierend mitsamt all seinen Kommentaren | `auth` + `TicketPolicy` |
| **POST** | `/tickets/{ticket}/comments` | `CommentController@store` | `comments.store` | Sendet eine neue Antwort oder eine interne Notiz im Ticket-Chat ab | `auth` |
| **DELETE** | `/tickets/{ticket}/comments/{comment}` | `comments.destroy` | `comments.destroy` | Löscht einen spezifischen Kommentar dauerhaft aus dem Verlauf | `auth` |

## ER-Diagramm (Entity-Relationship-Diagramm) (Paul)
![ER-Diagramm](er-diagram.png)

## Laravel Architektur

- Models (Eloquent ORM) zur Abbildung der Datenbanktabellen und Beziehungen
- Controllers für die gesamte Backend-Logik und CRUD-Funktionen
- Routes in `web.php` zur Verknüpfung von URLs mit Controller-Funktionen
- Views mit React und Inertia.js für die Benutzeroberfläche
- Validation zur Prüfung von Eingaben
- Middleware für Authentifizierung und Zugriffskontrolle

## Ticket Lifecycle & Status Meanings

Our internal IT Support system uses a 5-stage workflow to track the lifecycle of a ticket efficiently:

| Status        | Target | Description                                                                                                                                            |
|:--------------| :--- |:-------------------------------------------------------------------------------------------------------------------------------------------------------|
| `Open`        | **IT Department** | The ticket was just created by an employee. No admin has assigned themselves to it yet. Needs urgent triage.                                           |
| `In Progress` | **IT Department** | An IT Support agent has taken ownership (`admin_id` in tickets table) and is actively investigating or working on a fix.                               |
| `Pending`     | **Employee** | The IT Department has replied and is waiting for information from the employee (e.g., screenshots, testing confirmation).                              |
| `Resolved`    | **System / Verification** | The issue is fixed. The ticket is marked as resolved either by the admin or the employee. The employee has 24 hours to re-open it if the bug persists. |
| `Closed`      | **Archive** | The ticket is finalized, archived, and read-only. No further comments can be added.                                                                    |

## Tests
- Feature Tests:
   - Ticket erstellen
   - Kommentar hinzufügen
   - Login/Logout
- Validierungstests für Formulare

## Installation & Setup
- Repository klonen
- Dependencies installieren: `composer install`
- `.env` Datei erstellen und konfigurieren
- Datenbank anlegen und Zugangsdaten in `.env` eintragen
- Migrationen ausführen: `php artisan migrate --seed`
- Lokalen Server starten:`php artisan serve`

## Aufgabenverteilung:
- **Pius**: Entwicklung der Benutzeroberfläche mit React, Entwurf des Datenbankmodells, Erstellung von Migrationen und Tests, Fehleranalyse sowie Verwaltung der GitHub-Issues.
- **Paul**: Initialisierung des Laravel-Projekts, Entwicklung der Backend-Logik inklusive CRUD-Funktionalitäten und Controller/Routes, Implementierung der Benutzerverwaltung sowie Erstellung und Dokumentation des Sicherheitskonzepts, ER Diagramm und Datenbankmodell erstellen, Inertia js einrichten mit Laravel Breeze und vorbereiten
- **Gemeinsam**: Ideenfindung, Projektplanung und Qualitätssicherung

## Laufende Dokumentation
Siehe [Befehle-Dokumentation](commands_doc.md)
# Yanp\_XH

YANP ist die Abkürzung von Yet Another News Plugin
(noch ein weiteres News-Plugin).
Es ermöglicht die halbautomatische Handhabung
der Neuigkeiten einer CMSimple\_XH Website,
die in einer Newsbox angezeigt und als RSS-Feed verfügbar gemacht werden können.
Betrachten Sie es als Alternative zur manuellen Erzeugung und
Pflege von Newsboxen oder zur Verwendung einer vollautomatisierten Lösung,
wie WhatsNew oder RSS Feed.
Wenn Sie weiter gehende Ansprüche haben,
sollten sie die Verwendung einer fortgeschrittenen Lösung,
wie [News](https://davidstutz.de/projects/cmsimple-plugins/?News#news)
oder [Realblog\_XH](https://github.com/cmb69/realblog_xh)
in Erwägung ziehen.

## Inhaltsverzeichnis

- [Voraussetzungen](#voraussetzungen)
- [Download](#download)
- [Installation](#installation)
- [Einstellungen](#einstellungen)
- [Verwendung](#verwendung)
- [Einschränkungen](#einschränkungen)
- [Fehlerbehebung](#fehlerbehebung)
- [Lizenz](#lizenz)
- [Danksagung](#danksagung)

## Voraussetzungen

Yanp\_XH ist ein Plugin für CMSimple_XH ≥ 1.7.0.
Es benötigt PHP ≥ 5.4.0.

## Download

Das [aktuelle Release](https://github.com/cmb69/yanp_xh/releases/latest)
kann von Github herunter geladen werden.

## Installation

Die Installation erfolgt wie bei vielen anderen CMSimple\_XH-Plugins auch.
Im [CMSimple\_XH Wiki](https://wiki.cmsimple-xh.org/doku.php/de:installation)
finden sie ausführliche Hinweise.

1.  Sichern Sie die Daten auf Ihrem Server.
2.  Entpacken Sie die ZIP-Datei auf Ihrem Computer.
3.  Laden Sie das gesamte Verzeichnis `yanp/` auf Ihren Server in
    das `plugins/` Verzeichnis von CMSimple\_XH hoch.
4.  Vergeben Sie Schreibrechte für die Unterverzeichnisse `config/`, `css/`,
    und `languages/`.
5.  Navigieren Sie zu `Plugins` → `Yanp` im Administrationsbereich,
    und prüfen Sie, ob alle Voraussetzungen für den Betrieb erfüllt sind.

## Einstellungen

Die Konfiguration des Plugins erfolgt wie bei vielen anderen
CMSimple\_XH-Plugins auch im Administrationsbereich der Website.
Wählen Sie `Plugins` → `Yanp`.

Sie können die Original-Einstellungen von Yanp\_XH unter `Konfiguration` ändern.
Beim Überfahren der Hilfe-Icons mit der Maus
werden Hinweise zu den Einstellungen angezeigt.

Die Lokalisierung wird unter `Sprache` vorgenommen.
Sie können die Zeichenketten in Ihre eigene Sprache übersetzen,
falls keine entsprechende Sprachdatei zur Verfügung steht,
oder sie entsprechend Ihren Anforderungen anpassen.
Achten Sie besonders auf die Einträge in den Abschnitten `Feed` und `News`.
Die möglichen Formatierungszeichen für `News` → `Date Format` sind im
[PHP Handbuch](https://www.php.net/manual/de/datetime.format.php)
beschrieben.

Das Aussehen von Yanp\_XH kann unter `Stylesheet` angepasst werden,
oder alternativ im Stylesheet Ihres Templates,
da die Newsbox und der Feed-Link dort direkt eingebunden werden.

## Verwendung

Die Neuigkeiten von Yanp\_XH beziehen sich auf CMSimple\_XH-Seiten.
Jede Seite kann einen Eintrag in den Neuigkeiten haben.
Um dies zu steuern,
wechseln Sie einfach in den Reiter `News` oberhalb des Editors.
Wenn Sie dort Text als Beschreibung eintragen,
wird die Seite zu den Neuigkeiten hinzugefügt.
Wenn Sie die Beschreibung löschen,
wird die Seite aus den Neuigkeiten entfernt.
Der Zeitstempel der Neuigkeiten wird verwendet,
um diese zu sortieren (aktuelle Neuigkeiten sind oben).
Der Zeitstempel wird aktualisiert, wenn Sie den Reiter sichern,
aber er kann nie aktueller sein als der Zeitstempel
der letzten Bearbeitung der entsprechenden Seite.
Wenn Sie also einen Tippfehler auf der Seite gemacht haben,
den Sie später korrigieren,
ändert das nicht den Zeitstempel der Neuigkeit.
Wenn Sie andererseits die Neuigkeit später ändern,
wird der Zeitstempel nicht geändert,
solange sie nicht auch die Seite selbst speichern.

### Anzeigen der Newsbox

Um die Newsbox anzuzeigen, müssen Sie Ihr Template bearbeiten;
ersetzen Sie den bereits existierenden `newsbox()` Aufruf durch:

````
<?=Yanp_newsbox()?>
````

oder fügen Sie dies ggf. zusätzlich zu bereits bestehenden `newsbox()` Aufrufen ein.

Weiterhin ist es möglich die Newsbox auf einer CMSimple\_XH-Seite anzuzeigen,
indem Sie folgenden Plugin-Aufruf einfügen:

````
{{{PLUGIN:Yanp_newsbox();}}}
````

### RSS-Feed verfügbar machen

Der RSS-Feed wird vielen modernen Browsern automatisch zur Verfügung gestellt,
da ein `<link rel="alternate">` Tag im `<head>` der Seiten
Ihrer Homepage von Yanp\_XH eingefügt wird.
Um zusätzlich das RSS-Icon mit einem Link zum RSS-Feed anzuzeigen,
müssen Sie folgendes in Ihr Template einfügen:

````
<?=Yanp_feedlink()?>
````

Das funktioniert analog zu `mailformlink()`.
Wenn Sie ein anderes Feed-Icon anzeigen möchten,
legen Sie es im `images/` Ordner Ihres Templates ab,
und geben Sie dessen Dateinamen als Parameter an:

````
<?=Yanp_feedlink('dateiname.png')?>
````

Auf jeden Fall sollten Sie den RSS-Feed
[validieren](https://www.rssboard.org/rss-validator/),
um mögliche Probleme zu erkennen.

## Einschränkungen

Wenn die Website mit und ohne www aufgerufen werden kann
(z.B. `www.example.com` und `example.com`)
ohne die eine Variante auf die andere weiter zu leiten,
kann es passieren,
dass der RSS Feed nicht korrekt zu sich selbst zurück verlinkt.
Es wird grundsätzlich empfohlen, dass Sie einen 301 Redirect
von `www.example.com` zu `example.com` oder umgekehrt einrichten.

## Fehlerbehebung

Melden Sie Programmfehler und stellen Sie Supportanfragen entweder auf
[Github](https://github.com/cmb69/yanp_xh/issues)
oder im [CMSimple\_XH Forum](https://cmsimpleforum.com/).


## Lizenz

Yanp\_XH ist freie Software. Sie können es unter den Bedingungen
der GNU General Public License, wie von der Free Software Foundation
veröffentlicht, weitergeben und/oder modifizieren, entweder gemäß
Version 3 der Lizenz oder (nach Ihrer Option) jeder späteren Version.

Die Veröffentlichung von Yanp\_XH erfolgt in der Hoffnung, daß es
Ihnen von Nutzen sein wird, aber *ohne irgendeine Garantie*, sogar ohne
die implizite Garantie der *Marktreife* oder der *Verwendbarkeit für einen
bestimmten Zweck*. Details finden Sie in der GNU General Public License.

Sie sollten ein Exemplar der GNU General Public License zusammen mit
Yanp\_XH erhalten haben. Falls nicht, siehe
<https://www.gnu.org/licenses/>.

© 2011-2021 Christoph M. Becker

Dänische Übersetzung © 2011-2012 Jens Maegard  
Slovakische Übersetzung © 2011-2012 Dr. Martin Sereday  
Tschechische Übersetzung © 2012 Josef Němec

## Danksagung

Das Plugin-Logo wurde von
[cemagraphics](https://cemagraphics.deviantart.com/#/d28bkte) entworfen.
Dieses Plugin verwendet Feed-Icons von
[Perishable Press](https://perishablepress.com/press/2006/08/20/a-nice-collection-of-feed-icons/)
und Free Application Icons von [Aha-Soft](https://www.aha-soft.com/).
Vielen Dank für die Veröffentlichung als Freeware.

Vielen Dank an die Community im [CMSimple\_XH Forum](https://www.cmsimpleforum.com/)
für Tipps, Vorschläge und das Testen.

Zu guter letzt vielen Dank an
[Peter Harteg](https://www.harteg.dk/), den „Vater“ von CMSimple,
und alle Entwickler von [CMSimple\_XH](https://www.cmsimple-xh.org/),
ohne die dieses phantastische CMS nicht existieren würde.

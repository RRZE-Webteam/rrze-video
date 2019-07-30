# Das RRZE-Video-Plugin
Mit dem RRZE-Video-Plugin kann man sowohl Videos aus dem FAU Videoportal als auch Youtube Videos in eine Wordpress Website einbinden.

Die Videos können als Shortcode sowie als Widget eingebunden werden.

## Installation und Vorbereitungen

Nach der Installation des Plugins erscheint ein neuer Menüpunkt "Videos".
Will man eine Beschreibung oder ein eigenes Vorschaubild verwenden, so
muss man einen neuen Videodatensatz anlegen. Ordnet man dem Videodatensatz ein Genre zu, so kann man den Zufallsmodus im Widget verwenden.

Das Plugin unterscheidet zwischen zwei Möglichkeiten Videos auf einer Seite auszugeben. Die erste Möglichkeit ist die Übergabe der Datensatz-ID. Diese finden Sie im Menü->Alle Videos in der Spalte ID, nachdem Sie einen Datensatz angelegt haben.

```
[fauvideo id="398" width="640" height="360" showtitle="1" showinfo="0" titletag="h2"]
```

Die zweite Möglichkeit ist die Übergabe einer Url.

Das Feld "Url" kann je Datensatz einen der folgenden Werte annehmen:

Für ein Video aus dem FAU Videoportal

* http://www.video.uni-erlangen.de/webplayer/id/13950
* http://www.video.uni-erlangen.de/clip/id/8352

Für ein Youtube Video

* https://www.youtube.com/watch?v=DF2aRrr21-M
* https://youtu.be/DF2aRrr21-M
* DF2aRrr21-M (Die Youtube-ID des Videos)

Nachdem der Datensatz angelegt wurde, kann das Video per Shortcode eingebunden werden.

Der Shortcode sieht wie folgt aus:

Für eine Video aus dem FAU Videoportal

```
[fauvideo url="https://www.video.uni-erlangen.de/webplayer/id/13950" showtitle="1" showinfo="1" titletag="h4" rand="Neuigkeiten"]
```
oder
```
[fauvideo url="http://www.video.uni-erlangen.de/clip/id/8352" showtitle="1" showinfo="1" titletag="h4" rand="news"]
```

Für ein Youtube Video

```
[fauvideo url="https://www.youtube.com/watch?v=DF2aRrr21-M" showtitle="1" showinfo="1" titletag="h2"]
```

oder

```
[fauvideo url="https://youtu.be/DF2aRrr21-M"  showtitle="1" showinfo="1" titletag="h2"]
```

oder

```
[fauvideo url="DF2aRrr21-M" showtitle="1" showinfo="1" titletag="h2"]
```

* Das Argument "titletag" kann die Werte zwischen h1 und h6 annehmen. (Überschriftgröße - Default) h2)
* Das Argument "showtitle" kann den Wert 0 oder 1 annehmen. (Der Titel wird angezeigt  - Default 1/on)
* Das Argument "showinfo" kann den Wert 0 oder 1 annehmen. (Es werden Zusatzinformationen wie Author, Download-Link und Copyright angezeigt / Default 1/on)
* Dem Argument "rand" kann der Wert aus der Datensatzspalte Genre übergeben werden. (zufällig Wiedergabe von Videos, welche diesem Genre zugeordnet sind - kein Default)


## Einbindung des Plugins als Widget



Das Plugin kann auch als Widget auf einer Seite oder im Footer eingebunden werden. Hierzu wählen Sie im Menü den Punkt Design->Widgets. Nun kann man das RRZE Video Widget in die Sidebar oder in den Footer ziehen.
Natürlich kann das Widget auch über den Customizer eingebunden werden.

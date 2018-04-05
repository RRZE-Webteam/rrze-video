# Das RRZE-Video-Plugin
Mit dem RRZE-Video-Plugin kann man sowohl Videos aus dem FAU Videoportal als auch Youtube Videos in eine Wordpress Website einbinden.

Die Videos können als Shortcode sowie als Widget eingebunden werden.

## Installation und Vorbereitungen

### __Für Umsteiger vom alten FAU-Video-Plugin auf das neue RRZE-Video-Plugin__


Alter Shortcode (bleibt bestehen):

```
[fauvideo url="..." width="220" height="150" showtitle="..." showinfo="..." titletag="..."]
```

Neuer Shortcode (wird erweitert):

```
[fauvideo id oder url="..." width="..." height="..." showtitle="..." showinfo="..." titletag="..." youtube-support="..." youtube-resolution="..." rand="..."]
```
### __Das neue RRZE-Video-Plugin__

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
[fauvideo url="https://www.video.uni-erlangen.de/webplayer/id/13950" width="640" height="360" showtitle="1" showinfo="1" titletag="h4" rand="Neuigkeiten"]
```
oder
```
[fauvideo url="http://www.video.uni-erlangen.de/clip/id/8352" width="640" height="360" showtitle="1" showinfo="1" titletag="h4" rand="news"]
```

Für ein Youtube Video

```
[fauvideo url="https://www.youtube.com/watch?v=DF2aRrr21-M" width="640" height="360" showtitle="1" showinfo="1" titletag="h2" youtube-resolution="1"]
```

oder

```
[fauvideo url="https://youtu.be/DF2aRrr21-M" width="640" height="360" showtitle="1" showinfo="1" titletag="h2"]
```

oder

```
[fauvideo url="DF2aRrr21-M" width="640" height="360" showtitle="1" showinfo="1" titletag="h2"]
```

* Den Argumenten "width" und "height" wird die Bildgröße in Pixel übergeben - Default 640x360px
* Das Argument "titletag" kann die Werte zwischen h1 und h6 annehmen. (Überschriftgröße - Default) h2)
* Das Argument "showtitle" kann den Wert 0 oder 1 annehmen. (Der Titel wird angezeigt  - Default 1/on)
* Das Argument "showinfo" kann den Wert 0 oder 1 annehmen. (Es werden Zusatzinformationen wie Author, Download-Link und Copyright angezeigt / Default 1/on)
* Dem Argument "youtube-support" kann den Wert 0 oder 1 annehmen. (Videos werden dann mit dem Youtube Player angezeigt - Default 0/off)
* Dem Argument "youtube-resolution" kann einen Wert zwischen 1 und 4 übergeben werden. (Das Vorschaubild wird im Format 16:9 angezeigt - Default 4)
* Dem Argument "rand" kann der Wert aus der Datensatzspalte Genre übergeben werden. (zufällig Wiedergabe von Videos, welche diesem Genre zugeordnet sind - kein Default)

### __Ausrichtung der Videos auf einer Wordpress-Seite__
Die Videos können links- oder rechtsbündig angeordnet werden. Der Text nach dem Video umfließt das Vorschaubild.
```
<div class="alignleft|alignright">

[fauvideo id="http://www.video.uni-erlangen.de/clip/id/8352" width="420"  showtitle="1" showinfo="1" titletag="h4"]

</div>
Lo Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
```

## Einbindung des Plugins als Widget

### __Für Umsteiger vom alten FAU-Video-Plugin auf das neue RRZE-Video-Plugin__

Das alte und neue Widget können parallel eingebunden werden. Das neue Widget orientiert sich am alten Widget, erweitert dieses jedoch um den Zufallsmodus beim Genre.

### __Das neue RRZE-Video-Plugin__

Das Plugin kann auch als Widget auf einer Seite oder im Footer eingebunden werden. Hierzu wählen Sie im Menü den Punkt Design->Widgets. Nun kann man das RRZE Video Widget in die Sidebar oder in den Footer ziehen.
Natürlich kann das Widget auch über den Customizer eingebunden werden.

### Das Widget-Formular hat folgende Felder

* Titel
* ID (mögliche Werte siehe oben)
* Url (mögliche Werte siehe oben)
* width und height des Vorschaubildes (Default 270px x 150px)

```
Zeige Widget Videotitel 

(Wurde ein Titel vergeben und die Option "Ein" ausgewählt, so wird dieser Titel angezeigt. 
Wird "Ein" gewählt und kein Titel eingegeben, so wird der Titel aus dem FAU-Videoportal angezeigt. 
Ist der Schalter auf "Aus" so wird lediglich das Vorschaubild ohne Titel angezeigt.)
```

```
Zeige Metainformatioen

(Wird "Ein" ausgewählt, so werden Zusatzinformationen unter dem Video-Modalfenster angezeigt. 
Ist der Schalter auf "Aus" so werden keine zusätzlichen Infornationen angezeigt.
```

```
Genre

(Hier wird der Zufallsmodus aktiviert. 
Die Selectbox beinhaltet sämtliche Genres die zuvor angelegt wurden.
Videos mit dem gleichen Genre werden dann zufällig ausgegeben.)

!Wichtig: Die Felder ID und URL dürfen nicht gefüllt sein.
```


Youtube Auflösung

* Hier wird die Auflösung des Youtube Vorschaubildes bestimmt.

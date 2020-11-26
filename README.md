# RRZE-Video

Plugin zum Embedding von Videos auf FAU-Websites

## Download 

GitHub-Repo: https://github.com/RRZE-Webteam/rrze-video


## Autor 
RRZE-Webteam , http://www.rrze.fau.de

## Copryright

GNU General Public License (GPL) Version 3


## Zweck 

Mit dem RRZE-Video-Plugin kann man sowohl Videos aus dem FAU Videoportal, YouTube und Vimeo in eine Wordpress Website einbinden.
Die Videos können als Shortcode sowie als Widget eingebunden werden.


## Credits

Als Player wird Plyr.io verwendet: 
* Homepage https://plyr.io/
* GitHub: https://github.com/sampotts/plyr

## Dokumentation

Eine vollständige Dokumentation mit vielen Anwendungsbeispielen findet sich auf der Seite: 
https://www.wordpress.rrze.fau.de/plugins/fau-und-rrze-plugins/rrze-video

## Shortcode

Der Shortcode 

```
[fauvideo]
```

wird verwendet um Videos in Inhaltsbereichen  anzuzeigen.

### Parameter 

Der Shortcode verfügt über folgende Parameter:

Einer der folgenden Parameter ist zwingend anzugeben:

* url=""
    Angabe einer URL zu dem Video bei einem unterstützten Videoprovider.
    Derzeit sind dies: Das Videoportal der FAU, YouTube und Vimeo.
* id=""
    Angabe einer Id eines Eintrags in der Videothek. Die Videothek kann genutzt 
    werden um die URLs zu Videos zu speichern und in Kategorien zu gruppieren. 
* rand=""
    Angabe der Kategorieslug von Einträgen in der Videothek. Wenn in der Kategorie 
    mehrere Videos enthalten sind, wird per Zufall ein Video aus dieser genommen.

Folgender Parameter ist optional und beeinflussen die Ausgabe zusätzlicher Daten:

* show=""
   Dieser Parameter kann folgende Anzeigeioptionen haben:
   - title
        Anzeige des Titels oberhalb des Videos
   - link
        Anzeige des Links zur Originalquelle bei dem verweneten Videoprovider
   - desc
        Anzeige des Videobeschreibung, falls vorhanden
   - meta
        Anzeige weitere Metadaten zum Video, wie dem Autor, dem Provider und weitere Videoformat
   - info
        Anzeige von link, desc und meta.




## Beispielaufrufe als Shortcode

* Anzeige eins Videos aus dem Videoportal der FAU

```
[fauvideo url="https://www.video.uni-erlangen.de/webplayer/id/36475"]
```


* Anzeige eins Videos aus YouTube

```
[fauvideo url="https://www.youtube.com/watch?v=3dMLwu8V3tI"]
```


* Anzeige eins Videos aus Vimeo

```
[fauvideo url="https://vimeo.com/335918196"]
```



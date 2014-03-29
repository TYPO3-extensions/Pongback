###########################################
# Language Setup                          #
###########################################

# Anzeige Default-Sprache im Backend als Deutsch festlegen
mod.SHARED {
   defaultLanguageFlag = de
   defaultLanguageLabel = Deutsch
}

[globalVar=GP:L=1]
    config.sys_language_uid = 1
    config.language = en
    config.locale_all = en_EN
    htmlTag_langKey = en
[global]

sys_language_mode = content_fallback
    sys_language_overlay = hideNonTranslated
    sys_language_uid = 0 
    language = de
    locale_all = de_DE
##Für English dann die globale variabel ausserhalb der Config

[globalVar=GP:L=1]
    config.sys_language_uid = 1
    config.language = en
    config.locale_all = en_EN
    htmlTag_langKey = en
[global]
###zum umstellen der sprache habe ich dann in einem typoscript

[globalVar = GP:L = 1]
    lib.menu.20.value=<div id="Opening"><h4>Opening</h4><span class="opening">Monday to Saturday<br />From 12 to 22 o'clock</span></div>
[global]

##der text darin ist jetzt nur für ein Asia Restaurant gewesen^^
##er ändert quasi lib.menu.20.value wenn  die globalVar = GP: L = 1
##Hab hier noch ein language menu

 20 = HMENU
        20 {
            # Ein Sprach-Menü wird erzeugt
            special = language
            # Reihenfolge und Auswahl der Sprachen im Menü
            special.value = 1,0
            special.normalWhenNoLanguage = 0
            wrap =<ul id="LanguageNavigation">|</ul>
            1 = TMENU
            1 {
                noBlur = 1
                # Standard Sprachen
                NO = 1
                NO {
                    linkWrap =<li class="firstLevel">|</li>
                    # Standard-Titel für den Link wäre Seitenttitel
                    # =&gt; anderer Text als Link-Text (Optionsschift)
                    stdWrap.override = english || deutsch 
                    # Standardmäßige Verlinkung des Menüs ausschalten
                    # Da diese sonstige GET-Parameter nicht enthält
                    doNotLinkIt = 1
                    # Nun wird der Link mit den aktuellen GET-Parametern neu aufgebaut
                    stdWrap.typolink.parameter.data = page:uid
                    stdWrap.typolink.additionalParams = &L=1 || &L=0
                    stdWrap.typolink.addQueryString = 1
                    stdWrap.typolink.addQueryString.exclude = L,id,cHash,no_cache
                    stdWrap.typolink.addQueryString.method = GET
                    stdWrap.typolink.useCacheHash = 1
                    stdWrap.typolink.no_cache = 0

                }
                # Aktive Sprache
                ACT <.NO
                ACT.linkWrap =<li class="firstLevel act">|</li>
                # NO + Übersetzung nicht vorhanden
                USERDEF1 <.NO
                # ACT + Übersetzung nicht vorhanden
                USERDEF2 < .ACT
            }
        }
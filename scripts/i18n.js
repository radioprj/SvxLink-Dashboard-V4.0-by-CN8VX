// ============================================================
// I18N — lekki, czysto kliencki przełącznik języka.
// Wzorem theme-toggle w main.js: bez przeładowania strony,
// wybór zapamiętany w localStorage.
//
// Dodanie nowego języka (np. francuskiego) = dopisanie klucza
// `fr: {...}` niżej + jednej flagi w navbar.php. Zero zmian PHP.
// Brakujący klucz w słowniku = zostaje oryginalny tekst EN z HTML.
// ============================================================
var I18N_KEY = 'svxdash_lang';

var I18N = {
pl: {
        'nav.dashboard':      'Panel',
        'nav.nodes':          'Nody',
        'nav.talkgroups':     'Grupy rozmów',
        'nav.logs':           'Logi',
        'echolink.title':     'Informacje EchoLink NODE',

        'nodes.title':        'Podłączone nody do SVXReflector',
        'nodes.empty':        'Brak podłączonych nodów',

        'tg.page_title':      'Grupy rozmów',
        'tg.total_label':     'Łączna liczba grup rozmów',
        'tg.active_label':    'Ostatnia aktywna grupa rozmów',
        'tg.no_active':       'Brak aktywnej TG',
        'tg.search_placeholder': '🔍 Szukaj po numerze lub nazwie TG...',
        'tg.col_index':       '#',
        'tg.col_number':      'Numer TG',
        'tg.col_name':        'Nazwa grupy rozmów',
        'tg.empty_defined':   'Brak zdefiniowanych grup rozmów w',
        'tg.empty_format':    'Format:',
        'tg.note_intro':      '💡 Każda zmiana w pliku',
        'tg.note_outro':      'zostanie automatycznie odzwierciedlona w tej tabeli. Wystarczy odświeżyć stronę klawiszem',
        'tg.no_results':      '🔍 Nie znaleziono grup rozmów.',
    }
    }
    // en: nie potrzebny — angielski to oryginalny tekst już w HTML
};

function applyLang(lang) {
    CURRENT_LANG = lang;
    var dict = I18N[lang] || {};

    document.querySelectorAll('[data-i18n]').forEach(function (el) {
        // Zapamiętaj oryginalny (angielski) tekst przy pierwszym
        // uruchomieniu — bez tego nie ma do czego "wracać" przy EN.
        if (el.dataset.i18nOrig === undefined) {
            el.dataset.i18nOrig = el.textContent;
        }

        var key = el.getAttribute('data-i18n');
        el.textContent = (dict[key] !== undefined) ? dict[key] : el.dataset.i18nOrig;
    });

    document.querySelectorAll('.lang-flag').forEach(function (btn) {
        btn.classList.toggle('active', btn.dataset.lang === lang);
    });

    document.documentElement.setAttribute('lang', lang);
    try { localStorage.setItem(I18N_KEY, lang); } catch (e) {}
}
function setLang(lang) {
    applyLang(lang);
}

// Bieżący język — do użytku przez main.js przy renderowaniu
// treści doklejanych dynamicznie (AJAX), gdzie data-i18n nie
// zdąży zadziałać, bo element jeszcze nie istnieje w DOM.
var CURRENT_LANG = 'en';

function t(key, fallback) {
    var dict = I18N[CURRENT_LANG] || {};
    if (dict[key] !== undefined) return dict[key];
    return fallback !== undefined ? fallback : key;
}
(function initLang() {
    function run() {
        var saved = null;
        try { saved = localStorage.getItem(I18N_KEY); } catch (e) {}
        var cfg = window.DASH_CONFIG || {};
        applyLang(saved || cfg.default_lang || 'en');
    }

    // Skrypt bywa wczytywany w <head>, zanim <body> istnieje —
    // poczekaj na DOMContentLoaded zamiast szukać elementów od razu
    // (dokładnie tak jak robi to już main.js).
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', run);
    } else {
        run();
    }
})();
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
        'nav.dashboard':      'Dashboard',
        'nav.nodes':          'Nody',
        'nav.talkgroups':     'Grupy',
        'nav.logs':           'Logi',
        'echolink.title':     'Informacje EchoLink NODE',

        'nodes.title':        'Podłączone nody do SVXReflector',
        'nodes.empty':        'Brak podłączonych nodów',

        'tg.page_title':      'Grupy',
        'tg.total_label':     'Łączna liczba grup',
        'tg.active_label':    'Ostatnia aktywna grupa',
        'tg.no_active':       'Brak aktywnej TG',
        'tg.search_placeholder': '🔍 Szukaj po numerze lub nazwie TG...',
        'tg.col_index':       '#',
        'tg.col_number':      'Numer TG',
        'tg.col_name':        'Nazwa grupy',
        'tg.empty_defined':   'Brak zdefiniowanych grup',
        'tg.empty_format':    'Format:',
        'tg.note_intro':      '💡 Każda zmiana w pliku',
        'tg.note_outro':      'zostanie automatycznie odzwierciedlona w tej tabeli. Wystarczy odświeżyć stronę klawiszem',
        'tg.no_results':      '🔍 Nie znaleziono grup',

        'log.filter_type':      'Typ',
        'log.filter_callsign':  'Znak wywoławczy',
        'log.filter_tg':        'TG #',
        'log.filter_date':      'Data',
        'log.placeholder_cs':   'np: SP2ABC',
        'log.placeholder_tg':   'np: 604',
        'log.btn_filter':       '🔍 Filtruj',
        'log.btn_reset':        '✕ Resetuj',
        'log.live':              'Live',
        'log.paused':            'Wstrzymane',
        'log.autorefresh_off_filtered': 'Auto-odświeżanie WYŁ — widok filtrowany',
        'log.autorefresh_off_page':     'Auto-odświeżanie WYŁ — strona',
        'log.total_entries':    'Łączna liczba wpisów',
        'log.entries_title':    'Wpisy logu',
        'log.entries_word':     'wpisów',
        'log.filtered_badge':   'Filtrowane',
        'log.page_label':       'Strona',
        'log.lines_on':         'linii z',
        'log.displaying':       'wyświetlanie',
        'log.to':                'do',
        'log.col_date':          'Data',
        'log.col_time':          'Godzina',
        'log.col_type':          'Typ',
        'log.col_callsign':      'Znak wywoławczy',
        'log.col_tg':            'TG #',
        'log.col_message':       'Wiadomość',
        'log.col_raw':           'Surowa linia',
        'log.no_entries':        'Nie znaleziono wpisów logu.',
        'log.prev':              '« Poprzednia',
        'log.next':              'Następna »',

        'header.dashboard_for':  'SvxLink Dashboard dla',
        'header.node':           'Node:',
        'header.qth':            'QTH:',
        'header.status_active':  'AKTYWNY',
        'header.status_stopped': 'ZATRZYMANY',
        'header.status_idle':    'BEZCZYNNY',
        'header.status_unknown': 'NIEZNANY',
        'header.local_time':     'Czas lokalny',

	'index.freq_title':              'Częstotliwość RX / TX',
	'index.offset':                  'Offset',
	'index.trx_status':              'Status TRX',
	'index.modules_title':           'Moduły',
	'index.no_modules':              'Brak załadowanych modułów',
	'index.uptime_title':            'Czas pracy komputera',
	'index.last_reboot':             'Ostatni restart',
	'index.cpu_temp_title':          'Temperatura CPU',
	'index.cpu_for_sbc':             'Temperatura CPU komputera',
	'index.activity_title':          'Aktywność SVXReflector',
	'index.reflector_not_configured':'SVXReflector nie skonfigurowany w',
	'index.no_activity':             'Brak aktywności w logu.',
	'index.col_time':                'Data / Czas',
	'index.col_tg':                  'TG #',
	'index.col_tgname':              'Nazwa TG',
	'index.col_duration':            'Czas TX',
	'index.reflector_tg_title':      'Reflektor i grupy (TG)',
	'index.callsign_on_reflector':   'Znak wywoławczy na reflektorze',
	'index.tg_default_label':        'TG domyślna',
	'index.tg_not_defined':          'Nie zdefiniowano',
	'index.tg_monitor_label':        'TG monitorowana',
	'index.no_monitored_tg':         'Brak monitorowanej TG',
	'index.last_active_tg_label':    'Aktywna TG',
	'common.link_status':            'Status łącza',
	'index.node_callsign_label':     'Znak wywoławczy na EL',
	'index.node_location_label':     'INFO /QTH',
	'index.node_sysop_label':        'Operator (Sysop)',
	'index.nodes_connected_label':   'Podłączone nody',
	'index.connections_count_label': 'Liczba połączeń',
	'index.connected_mode_label':    'Typ połączenie',
	'index.no_node_connected':       'BRAK PODŁĄCZONYCH NODÓW',
	'index.hw_title':                'Informacje sprzętowe',
	'index.hostname':                'Nazwa hosta',
	'index.local_ip':                'Lokalne IP',
	'index.architecture':            'Architektura',
	'index.kernel':                  'Kernel',
	'index.linux':                   'System Linux',
	'index.svxlink_label':           'SvxLink wersja',
	'index.last_svx_restart':        'Ostatni restart SvxLink',
	'index.cpu_cores':               'Rdzenie CPU',
	'index.cores_suffix':            'rdzenie',
	'index.cpu_usage_label':         'Wykorzystanie CPU',
	'index.mem_usage_label':         'Wykorzystanie pamięci',
	'index.disk_usage_label':        'Wykorzystanie dysku',
	'index.logics_title':            'Logiki',
	'index.no_logics':               'Brak skonfigurowanych logik',
	'rs.log_not_found':      'Nasłuch — plik logu nie znaleziony',
	'rs.no_log_data':        'Nasłuch — brak danych w logu',
	'rs.no_recent_activity': 'Nasłuch — brak ostatniej aktywności',
	'rs.rx_local':           'RX — odbiór lokalnego sygnału RF',
	'rs.idle':               'Oczekiwanie na aktywność',
	'rs.tx_network':         'TX — retransmisja audio z sieci',
	'rs.tx_local':           'TX — nadawanie',
       }

    // en: not need to create here  - english is in HTML code

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

    document.querySelectorAll('[data-i18n-placeholder]').forEach(function (el) {
        if (el.dataset.i18nPlaceholderOrig === undefined) {
            el.dataset.i18nPlaceholderOrig = el.getAttribute('placeholder') || '';
        }

        var key = el.getAttribute('data-i18n-placeholder');
        el.setAttribute('placeholder',
            (dict[key] !== undefined) ? dict[key] : el.dataset.i18nPlaceholderOrig);
    });

    document.querySelectorAll('.lang-flag').forEach(function (btn) {
        btn.classList.toggle('active', btn.dataset.lang === lang);
    });
    document.documentElement.setAttribute('lang', lang);
    try { localStorage.setItem(I18N_KEY, lang); } catch (e) {}
}
// Strony/skrypty mogą się tu zarejestrować, żeby dostać sygnał
// "język się zmienił" i odświeżyć dynamicznie renderowaną treść,
// której applyLang() nie dotyka (bo nie ma data-i18n, tylko t()
// wywoływane wewnątrz callbacków AJAX).
var LANG_CHANGE_HOOKS = [];

function onLangChange(fn) {
    if (typeof fn === 'function') LANG_CHANGE_HOOKS.push(fn);
}

function setLang(lang) {
    applyLang(lang);
    LANG_CHANGE_HOOKS.forEach(function (fn) {
        try { fn(); } catch (e) { /* jeden zepsuty hook nie blokuje reszty */ }
    });
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
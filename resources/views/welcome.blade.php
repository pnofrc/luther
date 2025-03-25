<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Luther Lexicon Map</title>
    <link rel="icon" type="image/x-icon" href="/icon-dot-black.svg">


    <style>
        @font-face {
            font-family: "orpheus";
            src: url('/Orpheus-Pro.ttf');
        }

        @font-face {
            font-family: "Fraktur";
            src: url('/EskapadeFraktur-RegularItalic.ttf');
        }

        body,
        html {
            font-family: "orpheus";
            width: 100vw;
            height: 100vh;
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-size: 1em;
        }

        #map {
            width: 100%;
            height: 110%;
            position: absolute;
            z-index: 0;
            top: 0;
        }

        /* Nascondo i controlli standard di Leaflet */
        .leaflet-control-container {
            display: none !important;
        }

        .padding {
            padding: .3vw .3vw 0 .4vw;
        }

        .pointer {
            cursor: pointer;
        }

        .selected {
            border-bottom: 1px solid;
            padding-bottom: 1px;
            width: max-content;
        }

        #coord {
            border-left: 1px solid;
            border-right: 1px solid;
            padding: 0 10px;
        }

        #header {
            z-index: 3;
            position: relative;
            /* font-size: 1.1rem; */
            border-top: solid 1px;
            border-right: solid 1px;
            border-left: solid 1px;
            display: flex;
            justify-content: space-between;
            margin: 1.5vw;
            z-index: 999999999;
            align-items: baseline;
        }

        #bottom {
            z-index: 2;
            /* font-size: 1em; */
            display: flex;
            justify-content: space-between;
            margin: 1.5vw;
            position: absolute;
            bottom: .5rem;
            width: 97vw;
        }

        .right {
            display: flex;
            width: 35%;
            justify-content: space-around;
        }

        .about {
            border-left: 1px solid;
            width: 100%;

        }
    #aboutBox{
    background-color: rgb(238, 235, 226);
    display:none;
    width: 70vw;
    max-width: 70vw;
    max-height: 60vh;
  overflow-y: scroll;
  
    }

    #aboutBox span{
        margin-top: 5vh;
  display: block;
    }
        #lang span {
            cursor: pointer;
        }

        #listPlaces, #listKeywords {
            display: none;
            position: absolute;
            flex-direction: column;
            gap: 0;
            padding-top: 5vh;
            width: max-content;
        }


        .places {
            display: flex;
            width: 100%;
            border-left: solid 1px;
            flex-direction: column;
        }

        /* Stile per ogni voce del menu dei luoghi */
        .placeItem, .keywordItem {
            padding: 0.2vw;
            display: inline;
            cursor: pointer;
        }

        .placeItem:hover,.keywordItem:hover {
            border-bottom: 1px solid #333;
        }

    
        .my-div-icon {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid #333;
            padding: 2px 5px;
            border-radius: 3px;
            text-align: center;
            white-space: nowrap;
        }

        .my-div-icon .map-label {
            /* font-size: 0.9rem; */
            font-weight: bold;
        }

        #infoBoxes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            pointer-events: none;
            z-index: 1000;
        }

        .infoBox {
            position: absolute;
            background-color: #b1b1b1;
            padding: 20px;
            cursor: grab;
            width: 24vw;
            border: 1px solid #000;
            max-width: 300px;
            /* font-size: 1.2vw; */
            line-height: 1.2em;
            z-index: 9999;
            pointer-events: all
        }

        .closeBtn {
            position: absolute;
            top: 3vmin;
            right: 3vmin;
            width: 3vmin;
            cursor: pointer !important;
            z-index: 99999999999;
        }

        .marker-label {
            font-size: 1.5em;
            font-family: "Fraktur";

        }


        .title-box {
            margin-top: 1rem;
            display: block;
        }

        .keywords {
            margin: 1rem 0;
            display: flex;
            flex-direction: column;
        }

        .keywords p {
            margin: 0;
        }

        .title-box,
        .keywords span {
            font-family: "Fraktur";
            /* font-size: 1.4vw; */
        }

        .mobile{
            display: none;
        }
   
         @media screen and (max-width: 800px) {
            
            .mobile{
            display: block;
        }

        #header{
            display: flex;
            flex-direction: column;
            border-left: unset;
            border-right: unset;
            border-top:unset;
        }
            #year{ display: none;}

            .left{
                display: flex;
                justify-content: space-between;
                border-bottom:1px solid
            }

            #descWebsite{
                width: 80%;
            }
            .right{
                width: 100%;
                margin-top: .5rem;
            }

            .right div:nth-child(2){
                align-items: start;
                border-left: unset;
            }
            .right div:nth-child(3){
                align-items: center;
            }
            .right div:nth-child(4){
                align-items: flex-end;
                display: flex;
                flex-direction: column;
            }


            .infoBox{
                line-height: 5vmin;
                font-size: 5vmin;
                width: 50%;
                z-index: 2;
                overflow-y: scroll;
                max-height: 50vh;
            }

    }

    .nested{
        border-bottom: 1px solid;
    }
    </style>
</head>

<body>
    <div id="infoBoxes"></div>

    
    {{-- <div class="mobile">
        <span class="padding" id="descWebsiteMobile">
            Mappa digitale del lessico politico-religioso di Lutero in Europa
        </span>
        <span class="padding" id="yearMobile">16C. 1521</span>
    </div>

    <div class="mobile">
        <div class="padding pointer places" id="titleKeywordMobile">
            <span id="titleKeywordText">Parole Chiave</span>
            <div id="listKeywords">
                @foreach ($keywords as $keyword)
                <span class="keywordItem" data-id="{{ $keyword->id }}">
                    {{ $keyword->title_de }}
                </span>
                @endforeach
            </div>
        </div>

        <div class="padding pointer places" id="titlePlaceMobile">
            <span id="titlePlaceText">Luoghi</span>
            <div id="listPlaces">
                @foreach ($places as $place)
                <span class="placeItem" data-id="{{ $place->id }}" data-lat="{{ $place->latitude }}"
                    data-lng="{{ $place->longitude }}">
                    {{ $place->title_it }}
                </span>
                @endforeach
            </div>
        </div>
        
        <span class="padding pointer" onclick="openAbout()">
            <span class="attribute" id="titleAbout">About</span>
        </span>
    </div> --}}


    <div id="header">
        <div class="left">
            
            <span class="padding" id="descWebsite">
                Mappa digitale del lessico politico-religioso di Lutero in Europa
            </span>

            <span class="padding mobile">16C. 1521</span>

        </div>

        <div class="right">
            <span id="year" class="padding">16C. 1521</span>

            <div class="padding pointer places" id="titleKeyword">
                <span id="titleKeywordText">Parole Chiave</span>
                <div id="listKeywords">
                    @foreach ($keywords as $keyword)
                    <span class="keywordItem" data-id="{{ $keyword->id }}">
                        {{ $keyword->title_de }}
                    </span>
                    @endforeach
                </div>
            </div>

            <div class="padding pointer places" id="titlePlace">
                <span id="titlePlaceText">Luoghi</span>
                <div id="listPlaces">
                    @foreach ($places as $place)
                    <span class="placeItem" data-id="{{ $place->id }}" data-lat="{{ $place->latitude }}"
                        data-lng="{{ $place->longitude }}">
                        {{ $place->title_it }}
                    </span>
                    @endforeach
                </div>
            </div>
            
            <div class="padding pointer about" onclick="openAbout()">
                <span class="attribute" id="titleAbout">About</span>
            </div>
        </div>
    </div>

    <div id="aboutBox" class="infoBox">

    </div>

    
    <div id="bottom">
        <div id="coord" class="selected"></div>
        <div id="lang" class="selected">
            <span onclick="changeLang('IT')">IT</span>/<span onclick="changeLang('DE')">DE</span>/<span
                onclick="changeLang('EN')">EN</span>
        </div>
    </div>

    <div id="map"></div>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCzFoo_5mTjJGFO7RO8FzPZXKBher0uH0k&callback=initMap"
        async defer></script>
    <script src="https://unpkg.com/@googlemaps/markerwithlabel/dist/index.min.js"></script>
    <script>
        var isMobile = false; // Inizialmente, assumiamo che non siamo su un dispositivo mobile
        if (window.innerWidth <= 960) {
            isMobile = true;
        }
        var centerCoordinates;
        if (isMobile) {
            centerCoordinates = {
                lat: 47.47007,
                lng: 10.1032
            };
        } else {
            centerCoordinates = {
                lat: 45.6650,
                lng: 9.852218
            };
        }
        let map;
        let markers = {};
        let currentLang = "IT";
        let connectionLine = null; // Per la linea tratteggiata
        let about = {!!json_encode($about -> toArray())!!}
        let places = {!!json_encode($places -> toArray())!!}; // Dati dai backend
        let keywords = {!!json_encode($keywords -> toArray())!!}; // Dati dai backend
        // Imposta manualmente le coordinate di Wittenberg
        const wittenberg = {
            lat: 51.90123, // Imposta la latitudine corretta
            lng: 12.60321 // Imposta la longitudine corretta
        };
        let connectionLines = [];
        let lineMapping = {};

        function toggleMarkersByZoom(zoomLevel) {
                places.forEach((place) => {
                    let marker = markers[place.id];
                    if (zoomLevel <= 4) {
                        // Nascondi i marker e i label tranne quello di Wittenberg
                        if (place.title_it !== "Wittenberg" && place.title_de !== "Wittenberg" && place
                            .title_en !==
                            "Wittenberg") {
                            marker.setMap(null); // Rimuovi il marker dalla mappa
                        } else {
                            marker.setMap(map); // Mostra il marker di Wittenberg
                        }
                    } else {
                        // Mostra tutti i marker e i label
                        marker.setMap(map);
                    }
                });
            }

        function initMap() {
            // Inizializzazione della mappa
            map = new google.maps.Map(document.getElementById("map"), {
                center: centerCoordinates, // Assicurati che 'centerCoordinates' sia definito
                zoom: 4,
                minZoom: 4,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: false,
                disableDefaultUI: true,
                gestureHandling: "greedy",
                styles: [{
                        "featureType": "all",
                        "elementType": "labels.text",
                        "stylers": [{
                            "visibility": "off"
                        }]
                    },
                    {
                        "featureType": "all",
                        "elementType": "labels.icon",
                        "stylers": [{
                            "visibility": "off"
                        }]
                    },
                    {
                        "featureType": "administrative",
                        "elementType": "all",
                        "stylers": [{
                            "lightness": "0"
                        }, {
                            "saturation": "28"
                        }]
                    },
                    {
                        "featureType": "administrative",
                        "elementType": "labels.text",
                        "stylers": [{
                            "visibility": "off"
                        }]
                    },
                    {
                        "featureType": "administrative.country",
                        "elementType": "geometry",
                        "stylers": [{
                            "visibility": "off"
                        }]
                    },
                    {
                        "featureType": "administrative.province",
                        "elementType": "all",
                        "stylers": [{
                            "lightness": "-100"
                        }, {
                            "visibility": "off"
                        }]
                    },
                    {
                        "featureType": "administrative.province",
                        "elementType": "geometry",
                        "stylers": [{
                            "visibility": "off"
                        }]
                    },
                    {
                        "featureType": "landscape.natural",
                        "elementType": "all",
                        "stylers": [{
                            "lightness": "100"
                        }, {
                            "color": "#eeebe2"
                        }]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "all",
                        "stylers": [{
                            "visibility": "off"
                        }]
                    },
                    {
                        "featureType": "poi.sports_complex",
                        "elementType": "all",
                        "stylers": [{
                            "lightness": "0"
                        }]
                    },
                    {
                        "featureType": "road",
                        "elementType": "all",
                        "stylers": [{
                            "lightness": "0"
                        }, {
                            "visibility": "off"
                        }]
                    },
                    {
                        "featureType": "transit",
                        "elementType": "all",
                        "stylers": [{
                            "visibility": "off"
                        }]
                    },
                    {
                        "featureType": "water",
                        "elementType": "all",
                        "stylers": [{
                            "color": "#aed6df"
                        }, {
                            "lightness": "0"
                        }]
                    },
                    {
                        "featureType": "water",
                        "elementType": "labels.text",
                        "stylers": [{
                            "visibility": "off"
                        }]
                    }
                ]
            });
            document.getElementById("coord").innerHTML = wittenberg.lat.toFixed(5) + ", " + wittenberg.lng.toFixed(5)
            google.maps.event.addListener(map, 'center_changed', function() {
                let center = map.getCenter();
                document.getElementById("coord").innerHTML = center.lat().toFixed(5) + ", " + center.lng()
                    .toFixed(5)
            });
            // Renderizzazione delle info box
            renderInfoBoxes();
            // Icona personalizzata per il marker (per esempio, un punto nero)
            var customIconDot = {
                url: 'https://lutherlexiconmap.education/wp-content/themes/lutherlexiconmap/images/icon-dot-black.svg', // URL dell'immagine
                scaledSize: new google.maps.Size(20, 20), // Dimensioni dell'icona
                origin: new google.maps.Point(0, 0), // Punto di origine
                anchor: new google.maps.Point(10, 10) // Punto di ancoraggio
            };
            // Variabili per i marker e per la mappa
            let bounds = new google.maps.LatLngBounds(); // Per calcolare la bounding box
            places.forEach((place) => {
                let marker;
                // Creazione del marker, personalizzato per Wittenberg
                if (place.title_it === "Wittenberg" || place.title_de === "Wittenberg" || place.title_en ===
                    "Wittenberg") {
                    marker = new markerWithLabel.MarkerWithLabel({
                        position: {
                            lat: parseFloat(place.latitude),
                            lng: parseFloat(place.longitude),
                        },
                        map: map,
                        labelContent: "Wittenberg", // Etichetta
                        labelAnchor: new google.maps.Point(-40, -40), // Posizione dell'etichetta
                        labelClass: "marker-label", // Classe CSS per l'etichetta
                        icon: customIconDot, // Icona personalizzata
                    });
                } else {
                    marker = new markerWithLabel.MarkerWithLabel({
                        position: {
                            lat: parseFloat(place.latitude),
                            lng: parseFloat(place.longitude),
                        },
                        map: map,
                        labelContent: getLabel(place), // Etichetta dinamica
                        labelAnchor: new google.maps.Point(-50, 0), // Posizione dell'etichetta
                        labelClass: "marker-label", // Classe CSS per l'etichetta
                        icon: {
                            url: "", // Nessuna icona personalizzata
                            size: new google.maps.Size(0, 0), // La dimensione è nulla
                            anchor: new google.maps.Point(0, 0), // Ancoraggio
                            scaledSize: new google.maps.Size(0, 0), // Nessuna scala
                        },
                    });
                }
                // Aggiungi i marker alla bounding box per il calcolo del livello di zoom
                bounds.extend(marker.getPosition());
                // Memorizza il marker
                markers[place.id] = marker;
                // Aggiungi l'evento di clic per il marker
                marker.addListener("click", () => {
                    if (getLabel(place) !== "Wittenberg") {
                        let destination = {
                            lat: parseFloat(place.latitude),
                            lng: parseFloat(place.longitude)
                        };
                        drawVShape(destination, place.id); // Disegna la forma V
                    } else {
                        if (lineMapping[place.id]) {
                            lineMapping[place.id].forEach(line => line.setMap(
                                null)); // Rimuovi le linee esistenti
                            delete lineMapping[place.id]; // Elimina la mappatura delle linee
                        }
                    }
                    showInfoBox(place.id); // Mostra la info box
                });
            });
            // map.fitBounds(bounds);
            // Funzione per mostrare o nascondere i marker in base al livello di zoom
            
            // Applicare la visibilità iniziale dei marker in base al livello di zoom
            toggleMarkersByZoom(map.getZoom());
            // Listener per il cambio di livello di zoom
            google.maps.event.addListener(map, 'zoom_changed', function() {
                let zoomLevel = map.getZoom(); // Ottieni il livello di zoom attuale
                toggleMarkersByZoom(zoomLevel); // Applica la logica per i marker in base al nuovo zoom
            });
        }

        let zIndexInc = 0;

function dragElement(elmnt) {
    var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;

    // Gestisci eventi per il mouse
    elmnt.onmousedown = dragMouseDown;
    
    // Gestisci eventi per il touch
    elmnt.ontouchstart = dragTouchStart;

    function dragMouseDown(e) {
        zIndexInc++;
        elmnt.style.zIndex = zIndexInc;

        e = e || window.event;
        // e.preventDefault();
        pos3 = e.clientX;
        pos4 = e.clientY;
        document.onmouseup = closeDragElement;
        document.onmousemove = elementDrag;
    }

    function dragTouchStart(e) {
        zIndexInc++;
        elmnt.style.zIndex = zIndexInc;

        e = e.touches[0] || e.changedTouches[0]; // Prendi il primo touch
        e.preventDefault();
        pos3 = e.clientX;
        pos4 = e.clientY;
        document.ontouchend = closeDragElement;
        document.ontouchmove = elementDrag;
    }

    function elementDrag(e) {
        e = e || window.event;
        e.preventDefault();

        // Per il mouse
        if (e.clientX && e.clientY) {
            pos1 = pos3 - e.clientX;
            pos2 = pos4 - e.clientY;
            pos3 = e.clientX;
            pos4 = e.clientY;
        }

        // Per il touch
        if (e.touches) {
            pos1 = pos3 - e.touches[0].clientX;
            pos2 = pos4 - e.touches[0].clientY;
            pos3 = e.touches[0].clientX;
            pos4 = e.touches[0].clientY;
        }

        elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
        elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
    }

    function closeDragElement() {
        document.onmouseup = null;
        document.onmousemove = null;

        document.ontouchend = null;
        document.ontouchmove = null;
    }
}

        function drawVShape(destination, placeId) {
            // Rimuovi eventuali linee esistenti per il marker corrente
            if (lineMapping[placeId]) {
                lineMapping[placeId].forEach(line => line.setMap(null));
            }
            // Crea i punti per la linea "V"
            let mid = {
                lat: (wittenberg.lat + destination.lat) / 2,
                lng: (wittenberg.lng + destination.lng) / 2
            };
            let dx = destination.lng - wittenberg.lng;
            let dy = destination.lat - wittenberg.lat;
            let offsetFactor = 0.2;
            let offset = {
                lat: -dy * offsetFactor,
                lng: dx * offsetFactor
            };
            let leftPoint = {
                lat: mid.lat + offset.lat,
                lng: mid.lng + offset.lng
            };
            let rightPoint = {
                lat: mid.lat - offset.lat,
                lng: mid.lng - offset.lng
            };
            // Crea le linee "V"
            let lineLeft = new google.maps.Polyline({
                path: [wittenberg, leftPoint],
                strokeOpacity: 0,
                icons: [{
                    icon: {
                        path: "M 0,-3 0,3",
                        strokeOpacity: 1,
                        scale: 1.5
                    },
                    offset: "0",
                    repeat: "20px"
                }],
                map: map,
            });
            let lineRight = new google.maps.Polyline({
                path: [wittenberg, rightPoint],
                strokeOpacity: 0,
                icons: [{
                    icon: {
                        path: "M 0,-3 0,3",
                        strokeOpacity: 1,
                        scale: 1.5
                    },
                    offset: "0",
                    repeat: "20px"
                }],
                map: map,
            });
            // Memorizza le linee "V" in lineMapping
            lineMapping[placeId] = [lineLeft, lineRight];
        }
        // Pre-renderizza gli infoBox (una volta sola) per ogni luogo, in base all'ordine dei dati
        function renderInfoBoxes() {
            let infoBoxesContainer = document.getElementById("infoBoxes");
            places.forEach((place, index) => {
                let box = document.getElementById("infoBox-" + place.id);
                if (!box) { // Se la infoBox non esiste, creiamo una nuova box
                    box = document.createElement("div");
                    dragElement(box)
                    box.className = "infoBox";
                    box.id = "infoBox-" + place.id;
                    box.style.display = "none"; // Impostiamo che sia inizialmente nascosto
                    if (place.title_it == "Wittenberg") {
                        box.style.backgroundColor = "#eeebe2";
                    }
                    // Posizione fissa in base all'ordine dei dati
                    let topOffset = 10 + index * 2;
                    let leftOffset = 10 + index * 2;
                    box.style.top = `${topOffset}vh`;
                    box.style.left = `${leftOffset}vw`;
                    // Impostiamo il contenuto per la lingua attuale
                    box.innerHTML =
                        `<img src="/icon-close.svg" class="closeBtn" onclick="closeInfoBox('${place.id}')"> ${getPopupContent(place)}`;
                    infoBoxesContainer.appendChild(box);
                } else {
                    // Se l'infoBox esiste già, aggiorniamo solo il contenuto
                    box.innerHTML =
                        `<img src="/icon-close.svg" class="closeBtn" onclick="closeInfoBox('${place.id}')"> ${getPopupContent(place)}`;
                }
            });
        }
        // Mostra l'infoBox per un luogo (non duplicandolo)
        function showInfoBox(placeId) {
            let box = document.getElementById("infoBox-" + placeId);
            if (box) {
                box.style.display = "block";
            }
            // Mostra la linea associata a questo luogo
            if (lineMapping[placeId]) {
                lineMapping[placeId].forEach(line => line.setMap(map));
            }
        }

        function closeInfoBox(placeId) {
            let box = document.getElementById("infoBox-" + placeId);
            if (box) {
                box.style.display = "none";
            }
            // Se esistono linee "V" per questo placeId, rimuovile
            if (lineMapping[placeId]) {
                // Rimuovi le linee "V" dalla mappa
                lineMapping[placeId].forEach(line => line.setMap(null));
                // Elimina il mapping per evitare che venga disegnato di nuovo
                delete lineMapping[placeId];
            }
        }
        // Ritorna il testo dell'etichetta del marker in base alla lingua corrente
        function getLabel(place) {
            if (currentLang === "IT") return place.title_it;
            if (currentLang === "DE") return place.title_de;
            if (currentLang === "EN") return place.title_en;
            return "";
        }
        // Ritorna il contenuto dell'infoBox in base alla lingua corrente
        function getPopupContent(place) {
            if (place.title_it === "Wittenberg") {
                if (currentLang === "IT")
                return `<span class="title-box">${place.title_it}</span></div> <p>${place.content_it}</p>`;
                if (currentLang === "DE")
                return `<span class="title-box">${place.title_de}</span></div> <p>${place.content_de}</p>`;
                if (currentLang === "EN")
                return `<span class="title-box">${place.title_en}</span></div> <p>${place.content_en}</p>`;
            } else {
                
                    if(place.file){
                        if (currentLang === "IT")
                        return `<span class="title-box">${place.title_it}</span> <div class="keywords"><span>Keyword</span><p>${place.keyword.title_de}</p></div> <p>${place.content_it}</p> <a href="/storage/${place.file}" download>Download del pdf</a>`;
                        if (currentLang === "DE")
                        return `<span class="title-box">${place.title_de}</span> <div class="keywords"><span>Keyword</span><p>${place.keyword.title_de}</p></div> <p>${place.content_de}</p> <a href="/storage/${place.file}" download>Download the pdf</a>`;
                        if (currentLang === "EN")
                        return `<span class="title-box">${place.title_en}</span> <div class="keywords"><span>Keyword</span><p>${place.keyword.title_de}</p></div> <p>${place.content_en}</p> <a href="/storage/${place.file}" download>PDF herunterladen</a>`;
            
                    } else {
                        if (currentLang === "IT")
                        return `<span class="title-box">${place.title_it}</span> <div class="keywords"><span>Keyword</span><p>${place.keyword.title_de}</p></div> <p>${place.content_it}</p>`;
                        if (currentLang === "DE")
                        return `<span class="title-box">${place.title_de}</span> <div class="keywords"><span>Keyword</span><p>${place.keyword.title_de}</p></div> <p>${place.content_de}</p>`;
                        if (currentLang === "EN")
                        return `<span class="title-box">${place.title_en}</span> <div class="keywords"><span>Keyword</span><p>${place.keyword.title_de}</p></div> <p>${place.content_en}</p>`;
            
                    }
                     }
            return "";
        }
        // Cambia lingua e aggiorna marker, infoBox e menu
        // Cambia lingua e aggiorna marker, infoBox e menu
        function changeLang(lang) {
            var customIconDot = {
                url: '/icon-dot-black.svg', // URL dell'immagine
                scaledSize: new google.maps.Size(20, 20), // Dimensioni dell'icona
                origin: new google.maps.Point(0, 0), // Punto di origine
                anchor: new google.maps.Point(10, 10) // Punto di ancoraggio
            };
            if (currentLang !== lang) {
                currentLang = lang;
                // Rimuovi tutti i marker esistenti dalla mappa
                for (let placeId in markers) {
                    markers[placeId].setMap(null); // Rimuove il marker dalla mappa
                }
                // Re-crea i marker per ogni luogo con la lingua selezionata
                places.forEach((place) => {
                    let marker;
                    let label = getLabel(place); // Etichetta dinamica per la lingua selezionata
                    // Creazione del marker, personalizzato per Wittenberg
                    if (label === "Wittenberg") {
                        marker = new markerWithLabel.MarkerWithLabel({
                            position: {
                                lat: parseFloat(place.latitude),
                                lng: parseFloat(place.longitude),
                            },
                            map: map,
                            labelContent: "Wittenberg", // Etichetta
                            labelAnchor: new google.maps.Point(-40, -40), // Posizione dell'etichetta
                            labelClass: "marker-label", // Classe CSS per l'etichetta
                            icon: customIconDot, // Icona personalizzata
                        });
                    } else {
                        marker = new markerWithLabel.MarkerWithLabel({
                            position: {
                                lat: parseFloat(place.latitude),
                                lng: parseFloat(place.longitude),
                            },
                            map: map,
                            labelContent: label, // Etichetta dinamica
                            labelAnchor: new google.maps.Point(-50, 0), // Posizione dell'etichetta
                            labelClass: "marker-label", // Classe CSS per l'etichetta
                            icon: {
                                url: "", // Nessuna icona personalizzata
                                size: new google.maps.Size(0, 0), // La dimensione è nulla
                                anchor: new google.maps.Point(0, 0), // Ancoraggio
                                scaledSize: new google.maps.Size(0, 0), // Nessuna scala
                            },
                        });
                    }
                    // Memorizza il nuovo marker
                    markers[place.id] = marker;

                    // Aggiungi l'evento di clic per il marker
                    marker.addListener("click", () => {
                        if (label !== "Wittenberg") {
                            let destination = {
                                lat: parseFloat(place.latitude),
                                lng: parseFloat(place.longitude)
                            };
                            drawVShape(destination, place.id); // Disegna la forma V
                        } else {
                            if (lineMapping[place.id]) {
                                lineMapping[place.id].forEach(line => line.setMap(
                                null)); // Rimuovi le linee esistenti
                                delete lineMapping[place.id]; // Elimina la mappatura delle linee
                            }
                        }
                        showInfoBox(place.id); // Mostra la info box

                    });
                });
                // Cambia il titolo della sezione "Luoghi"
                document.getElementById("titlePlaceText").innerText =
                    currentLang === "IT" ? "Luoghi" : currentLang === "DE" ? "Orte" : "Locations";
               
                document.getElementById("titleKeywordText").innerText =
                    currentLang === "IT" ? "Parole Chiave" : currentLang === "DE" ? "Schlüsselwörter" : "Keywords";
               
                    toggleMarkersByZoom(map.getZoom());
                    // Re-renderizza le infoBox con i nuovi contenuti
                renderInfoBoxes();
            }

         

           
        }

        function openBoxFromKeyword(item){
            const keywordId = item.getAttribute("data-id");
        // Filtra tutte le places che hanno questa keyword
        let matchedPlaces = places.filter(place => place.keyword.id == keywordId);
        
        if (matchedPlaces.length > 0) {
            // Per comodità, centra la mappa sulla prima place trovata
            let firstPlace = matchedPlaces[0];
            map.setZoom(5);
            map.panTo({
                lat: parseFloat(firstPlace.latitude),
                lng: parseFloat(firstPlace.longitude)
            });
            
            // Per ogni place corrispondente, disegna la "V" e mostra l'infoBox
            matchedPlaces.forEach(place => {
                let destination = {
                    lat: parseFloat(place.latitude),
                    lng: parseFloat(place.longitude)
                };
                drawVShape(destination, place.id);
                showInfoBox(place.id);
            });
        }
        }
       
        document.querySelectorAll(".placeItem").forEach((item) => {

            item.addEventListener("click", function() {
                const lat = parseFloat(item.getAttribute("data-lat"));
                const lng = parseFloat(item.getAttribute("data-lng"));
                const id = item.getAttribute("data-id");
                map.setZoom(5)
                map.panTo({
                    lat,
                    lng
                });
                // Chiamata per disegnare la "V" per il luogo
                let destination = {
                    lat,
                    lng
                };
                drawVShape(destination, id);
                showInfoBox(id);
            });
        });
        

        document.querySelectorAll(".keywordItem").forEach((item) => {
    item.addEventListener("click", function() {
        openBoxFromKeyword(item)
    });
});




function setupDropdown(title, list) {
    if (window.matchMedia("(max-width: 800px)").matches) {
        // Se è mobile, aggiungiamo il toggle con il click
        title.addEventListener("click", () => {
            const isVisible = list.style.display === "flex";
            list.style.display = isVisible ? "none" : "flex";
            title.classList.toggle("selected", !isVisible);
        });

        // Chiudiamo il menu se si clicca fuori
        document.addEventListener("click", (event) => {
            if (!title.contains(event.target) && !list.contains(event.target)) {
                list.style.display = "none";
                title.classList.remove("selected");
            }
        });
    } else {
        // Manteniamo il comportamento originale su desktop
        title.addEventListener("mouseover", () => {
            list.style.display = "flex";
            title.classList.add("selected");
        });

        title.addEventListener("mouseout", () => {
            list.style.display = "none";
            title.classList.remove("selected");
        });
    }
}

const titlePlace = document.getElementById("titlePlace");
const listPlaces = document.getElementById("listPlaces");
const titleKeyword = document.getElementById("titleKeyword");
const listKeywords = document.getElementById("listKeywords");

setupDropdown(titlePlace, listPlaces);
setupDropdown(titleKeyword, listKeywords);

function closeAbout(){
    document.getElementById("aboutBox").style.display = 'none'
}
function openAbout(){
let box = document.getElementById("aboutBox");
    dragElement(box)
    let topOffset = 7;
    let leftOffset = 15;
    box.style.top = `${topOffset}vh`;
    box.style.left = `${leftOffset}vw`;
    // Impostiamo il contenuto per la lingua attuale
    box.innerHTML =
        `<img src="/icon-close.svg" class="closeBtn" onclick="closeAbout()"> <span>${about.about_it}</span>`;
    box.style.display ="block"
}
    </script>

</body>

</html>
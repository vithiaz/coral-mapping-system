@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/coral-lists.css') }}">
@endpush

<div class="add-coral-points-page">
    <section class="coordinate-list">
        <div class="container">
            <div class="section-title-wrapper">
                <h1><i class="fa-solid fa-table-list"></i> Koordinat Terumbu Karang</h1>
                <div class="button-wrapper">
                    <button onclick="location.href='{{ route('admin.add-coral-points') }}'" class="btn add-button ico">
                        <i class="fa-solid fa-plus"></i>
                        <span>Tambah Koordinat</span>
                    </button>
                </div>
            </div>
            <div class="section-body-wrapper">
                
                <div class="card-item-container">
                    <div class="condition-wrapper">
                        <div class="condition-card-wrapper">
                            <div wire:click="toggle_filter('good')" class="condition-card @if($this->filterGood) active @endif">
                                <div class="status-wrapper">
                                    <div class="status-circle good"></div>
                                </div>
                                <div class="status-container">
                                    <span class="status">Baik</span>
                                </div>
                            </div>
                            <div wire:click="toggle_filter('bad')" class="condition-card @if($this->filterBad) active @endif">
                                <div class="status-wrapper">
                                    <div class="status-circle"></div>
                                </div>
                                <div class="status-container">
                                    <span class="status">Rusak</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="location-card-wrapper">
                        @forelse ($storedCorals as $coral)
                            <div wire:key='coral-card-{{ $coral['id'] }}' class="location-card @if($this->selectedPointId == $coral['id']) active @endif" onclick="setMapCenter({{ $coral['id'] }}, {{ $coral['long'] }}, {{ $coral['lat'] }})">
                                <div class="status-wrapper">
                                    <div class="status-circle @if($coral['condition'] == 'baik') good @endif"></div>
                                </div>
                                <div class="loc-wrapper">
                                    <span>{{ $coral['long'] }}</span>
                                    <span>{{ $coral['lat'] }}</span>
                                </div>
                                @auth
                                    <div class="icon-wrapper">
                                        <button wire:click="delete_cord({{ $coral['id'] }})" class="btn delete-button ico">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                @endauth
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="fa-solid fa-circle-info"></i>
                                <span>belum ada koordinat</span>
                            </div>
                        @endforelse
                    </div>
                </div>
                
                {{-- <div class="form-container">
                    <div class="details-input-wrapper form-floating">
                        <select wire:model.live='condition' class="form-select default-input" aria-label="pilih kondisi terumbu karang" id="condition-input">
                            <option value="baik" selected>baik</option>
                            <option value="rusak">rusak</option>
                        </select>
                        <label for="condition-input">Kondisi</label>
                    </div>
                </div> --}}

                <div class="map-item-wrapper">
                    <div class="map-container">
                        <div wire:ignore id="map" class="map-canvas"></div>
                    </div>
                    <div class="option-container">
                        <div class="form-check">
                            <label class="form-check-label" for="heatmap-togler">
                              tampilkan heatmap
                            </label>
                            <input class="form-check-input" type="checkbox" checked id="heatmap-togler">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


</div>


@push('script')
<script>
    let storedCorals = []
    let db_heatMapDataGreen = []
    let db_heatMapDataWhite = []
    let db_scatterPlotData = []
    
    function getHeatMapData() {
        storedCorals = @this.storedCorals
        storedCorals.forEach(d => {
            if (d.condition == 'baik') {
                db_heatMapDataGreen.push({location: new google.maps.LatLng(d.lat, d.long), weight: 0.01 * d.radius})
            } else {
                db_heatMapDataWhite.push({location: new google.maps.LatLng(d.lat, d.long), weight: 0.01 * d.radius})
            }
    
            db_scatterPlotData.push({position: [d.long, d.lat], color: (d.condition == 'baik' ? [9,218,150] : [255,255,255]), radius: d.radius})
        })
    }
    
    $( document ).ready(function () {
        getHeatMapData()
    })

    
    const GeoJsonLayer = deck.GeoJsonLayer;
    const GoogleMapsOverlay = deck.GoogleMapsOverlay;

    let map, heatmapGreenLayer, heatmapWhiteLayer
    function initMap() {                
        const centerLatLong = { lat: 1.794427, lng: 125.146919 };
        // const centerLatLong = { lat: 1.8065609052563818, lng: 125.11308183829424 };

        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 13,
            center: centerLatLong,
            mapTypeId: 'satellite',
        });

        let infoWindow = new google.maps.InfoWindow({
            content: "Click di map untuk menentukan long/lat",
            position: centerLatLong,
        });

        // Map Click Events
        map.addListener("click", (mapsMouseEvent) => {
            let latVal = mapsMouseEvent.latLng.toJSON().lat
            let longVal = mapsMouseEvent.latLng.toJSON().lng
            
            infoWindow.close();
            // infoWindow = new google.maps.InfoWindow({
            // position: mapsMouseEvent.latLng,
            // });
            // infoWindow.setContent(
            //     '<span>lng: '+longVal+'</span><br>'+
            //     '<span>lat: '+latVal+'</span>'
            // );
            // infoWindow.open(map);
        });

        const greenGradient = [
            "rgba(116, 235, 213, 0)",
            "rgba(116, 235, 213, 1)",
            "rgba(116, 235, 213, 1)",
            "rgba(116, 235, 213, 1)",
            "rgba(116, 235, 213, 1)",
            "rgba(116, 235, 213, 1)",
            "rgba(147, 249, 185, 1)",
            "rgba(147, 249, 185, 1)",
            "rgba(147, 249, 185, 1)",
            "rgba(147, 249, 185, 1)",
            "rgba(147, 249, 185, 1)",
            "rgba(0, 143, 255, 1)",
            "rgba(27, 117, 188, 1)",
        ];
        const whiteGradient = [
            "rgba(196, 224, 229, 0)",
            "rgba(196, 224, 229, 1)",
            "rgba(196, 224, 229, 1)",
            "rgba(196, 224, 229, 1)",
            "rgba(196, 224, 229, 1)",
            "rgba(196, 224, 229, 1)",
            "rgba(76, 161, 175, 1)",
            "rgba(76, 161, 175, 1)",
            "rgba(76, 161, 175, 1)",
            "rgba(76, 161, 175, 1)",
            "rgba(76, 161, 175, 1)",
            // "rgba(127, 0, 63, 1)",
            // "rgba(191, 0, 31, 1)",
            "rgba(255, 0, 0, 1)",
            "rgba(255, 0, 0, 1)",
            "rgba(255, 0, 0, 1)",
        ];

        heatmapGreenLayer = new google.maps.visualization.HeatmapLayer({
            map: map,
            data: db_heatMapDataGreen,
            gradient: greenGradient
        });
        heatmapWhiteLayer = new google.maps.visualization.HeatmapLayer({
            map: map,
            data: db_heatMapDataWhite,
            gradient: whiteGradient
        });

        $(window).on('reset-heatmap', function() {
            db_heatMapDataGreen = []
            db_heatMapDataWhite = []
            getHeatMapData()
            heatmapGreenLayer.set("data", db_heatMapDataGreen);
            heatmapWhiteLayer.set("data", db_heatMapDataWhite);
        })

        
        const deckOverlay = new GoogleMapsOverlay({})
        const db_scatterLayer = new deck.ScatterplotLayer({
            id: 'stored-scatter-layer',
            data: db_scatterPlotData,
            getPosition: d => d.position,
            getFillColor: d => d.color,
            getRadius: d => d.radius,
            opacity: 0.8,
        })
        deckOverlay.setProps({
            layers: [
                db_scatterLayer,
            ],
        });

        deckOverlay.setMap(map);

        $( window ).on('add-map-marker', function (e) {
            let storedCordData = e.detail.storedCordData
            let storedScatterLayerData = []
            storedCordData.forEach(cord => {
                storedScatterLayerData.push({
                    position: [cord.long, cord.lat], color: (cord.condition == 'baik' ? [9,218,150] : [255,255,255]), radius: cord.radius
                })
            });
            let storedScatterLayerMarker = new deck.ScatterplotLayer({
                id: 'scatter-layer-stored',
                data: storedScatterLayerData,
                getPosition: d => d.position,
                getFillColor: d => d.color,
                getRadius: d => d.radius,
                opacity: 0.6,
            })
                         
            let cordData = e.detail.cordData
            let currentScatterLayerData = [
                    {position: [cordData.long, cordData.lat], color: [27, 117, 188], radius: cordData.radius},
            ]
            let currentScatterLayerMarker = new deck.ScatterplotLayer({
                id: 'scatter-layer-marker',
                data: currentScatterLayerData,
                getPosition: d => d.position,
                getFillColor: d => d.color,
                getRadius: d => d.radius,
                opacity: 0.6
            })

            deckOverlay.setProps({
                layers: [
                    // db_scatterLayer,
                    storedScatterLayerMarker,
                    currentScatterLayerMarker,
                ],
            });
            deckOverlay.setMap(map);
        })

    }
    window.initMap = initMap;

    function toggleHeatMap() {
        heatmapGreenLayer.setMap(heatmapGreenLayer.getMap() ? null : map);
        heatmapWhiteLayer.setMap(heatmapWhiteLayer.getMap() ? null : map);
    }
    $('#heatmap-togler').click(function () {
        toggleHeatMap()
    })

    function setMapCenter(coralId, coralLong, coralLat) {
        map.setCenter({ lat: coralLat, lng: coralLong })
        map.setZoom(17)
        @this.hover_location(coralId)
    }
    


    
    
</script>
@endpush
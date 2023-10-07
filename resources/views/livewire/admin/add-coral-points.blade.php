@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/admin-add-coral-points.css') }}">
@endpush

<div class="add-coral-points-page">
    <section class="add-coordinates">
        <div class="container">
            <div class="section-title-wrapper">
                <h1><i class="fa-solid fa-plus"></i> Koordinat Terumbu Karang</h1>
            </div>
            <div class="section-body-wrapper">
                <div class="form-container">
                    <div class="details-input-wrapper form-floating">
                        <select wire:model.live='condition' class="form-select default-input" aria-label="pilih kondisi terumbu karang" id="condition-input">
                            <option value="baik" selected>baik</option>
                            <option value="rusak">rusak</option>
                        </select>
                        <label for="condition-input">Kondisi</label>
    
                        <div class="range-input">
                            <div class="label-wrapper">
                                <label for="radius-input" class="form-label">Radius</label>
                                <span wire:ignore id="radius-label-values" class="range-values"></span>
                            </div>
                            <input wire:model.live='radius' type="range" class="form-range" id="radius-input" min="1" max="100" step="1" onchange="setRadiusLabel(this.value)">
                        </div>
                    </div>
                    <div class="coordinates-input-wrapper">
                        <div class="form-check">
                            <input wire:model.live='direct_save' class="form-check-input" type="checkbox" value="" id="store-direct-toggler">
                            <label class="form-check-label" for="store-direct-toggler">
                              klik pada map langsung menambahkan koordinat
                            </label>
                        </div>
                        <div class="form-floating">
                            <input wire:model.live='long' type="number" step="0.1" class="form-control default-input @if(!$this->direct_save) @error('long') is-invalid @enderror @endif " @if($this->direct_save) disabled @endif placeholder="Longtitude" id="long-input">
                            <label for="long-input">Longtitude</label>
                        </div>
                        <div class="form-floating">
                            <input wire:model.live='lat' type="number" step="0.1" class="form-control default-input @if(!$this->direct_save) @error('lat') is-invalid @enderror @endif " @if($this->direct_save) disabled @endif placeholder="Lattitude" id="lat-input">
                            <label for="lat-input">Lattitude</label>
                        </div>
                        <div class="button-wrapper">
                            <button wire:click='add_coord' class="btn button-default ico hovered">
                                <i class="fa-solid fa-plus"></i>
                                <span>Tambah</span>
                            </button>
                        </div>
                    </div>
                </div>

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

    <section class="listed-coordinates">
        <div class="container">
            <div class="section-title-wrapper">
                <h1>Koordinat yang ditambahkan</h1>
                <div class="button-wrapper">
                    <button wire:click='empty_coordinates' class="btn reset-button ico hovered">
                        <i class="fa-solid fa-arrow-rotate-left"></i>
                        <span>Reset</span>
                    </button>
                    <button type="button" wire:click='store_coordinates' class="btn button-default ico">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Simpan</span>
                    </button>
                </div>
            </div>
            <div class="section-body-wrapper">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Longtitude</th>
                            <th>Lattitude</th>
                            <th>Radius</th>
                            <th>Kondisi</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->cord_data as $index=>$cord)
                            <tr wire:key='stored-cord-{{ $index }}'>
                                <td>{{ $cord['long'] }}</td>
                                <td>{{ $cord['lat'] }}</td>
                                <td>{{ $cord['radius'] }}</td>
                                <td>{{ $cord['condition'] }}</td>
                                <td>
                                    <button wire:click='delete_coord({{ $index }})' class="btn table-btn delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

</div>


@push('script')
<script>

    function setRadiusLabel(values) {
        $('#radius-label-values').text(values)
    }

    let storedCorals = []
    let db_heatMapDataGreen = []
    let db_heatMapDataWhite = []
    let db_scatterPlotData = []
    
    $( document ).ready(function () {
        setRadiusLabel(@this.radius)
        storedCorals = @this.storedCorals

        storedCorals.forEach(d => {
            if (d.condition == 'baik') {
                db_heatMapDataGreen.push({location: new google.maps.LatLng(d.lat, d.long), weight: 0.01 * d.radius})
            } else {
                db_heatMapDataWhite.push({location: new google.maps.LatLng(d.lat, d.long), weight: 0.01 * d.radius})
            }

            db_scatterPlotData.push({position: [d.long, d.lat], color: (d.condition == 'baik' ? [9,218,150] : [255,255,255]), radius: d.radius})
        })
    })

    
    const GeoJsonLayer = deck.GeoJsonLayer;
    const GoogleMapsOverlay = deck.GoogleMapsOverlay;

    let map, heatmapGreenLayer
    function initMap() {                
        // const centerLatLong = { lat: 1.794427, lng: 125.146919 };
        const centerLatLong = { lat: 1.8065609052563818, lng: 125.11308183829424 };

        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 15,
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

            @this.set_cord_data(longVal, latVal)
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
        
        const deckOverlay = new GoogleMapsOverlay({})
        const db_scatterLayer = new deck.ScatterplotLayer({
            id: 'stored-scatter-layer',
            data: db_scatterPlotData,
            getPosition: d => d.position,
            getFillColor: d => d.color,
            getRadius: d => d.radius,
            opacity: 0.2,
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
                    db_scatterLayer,
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
    


    
    
</script>
@endpush
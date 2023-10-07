@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/homepage.css') }}">
@endpush

<div class="homepage">
    <div class="map-container">
        <div id="map"></div>
    </div>


    
</div>

@push('script')
<script>
    const GeoJsonLayer = deck.GeoJsonLayer;
    const GoogleMapsOverlay = deck.GoogleMapsOverlay;

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
        setMapHeight()
    })

    $(window).on('scroll', function () {
        setMapHeight()
    })
    $(window).on('resize', function () {
        setMapHeight()
    })


    function initMap() {                
        const centerLatLong = { lat: 1.794427, lng: 125.146919 };
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 13,
            center: centerLatLong,
            mapTypeId: 'satellite',
        });

        // let infoWindow = new google.maps.InfoWindow({
        //     content: "Click di map untuk menentukan long/lat",
        //     position: centerLatLong,
        // });
        // infoWindow.open(map);

        // map.addListener("click", (mapsMouseEvent) => {
        //     infoWindow.close();
        //     infoWindow = new google.maps.InfoWindow({
        //     position: mapsMouseEvent.latLng,
        //     });
        //     infoWindow.setContent(
        //         '<span>lng: '+mapsMouseEvent.latLng.toJSON().lng+'</span><br>'+
        //         '<span>lat: '+mapsMouseEvent.latLng.toJSON().lat+'</span>'
        //     );
        //     infoWindow.open(map);
            
        //     console.log(
        //         mapsMouseEvent.latLng.toJSON().lat + ', ' +
        //         mapsMouseEvent.latLng.toJSON().lng,
        //     )
        // });

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


        // --------------------
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
        // --------------------

        
        // const lineData = [];
        // for (let i = 0; i < scatterplotData.length - 1; i++) {
        //     for (let j = i + 1; j < scatterplotData.length; j++) {
        //         lineData.push({
        //             sourcePosition: scatterplotData[i].position,
        //             targetPosition: scatterplotData[j].position,
        //             phase: Math.random() * Math.PI * 2,
        //         });
        //     }
        // }
        // lineData = [
        //     {sourcePosition: scatterplotData[0].position, targetPosition: scatterplotData[1].position},
        //     {sourcePosition: scatterplotData[0].position, targetPosition: scatterplotData[9].position},
        // ];

        
        // waypointsData = [
        //     {
        //         waypoints: [],
        //     },
        // ]
        // let timestamps = 0
        // for (let i = 0; i < scatterplotData.length; i++) {
        //     timestamps += 10
        //     waypointsData[0].waypoints.push({coordinates: scatterplotData[i].position, timestamp: timestamps})
        // }
        // waypointsData = [
        //     {
        //       waypoints: [
        //        {coordinates: scatterplotData[0].position, timestamp: 1554772579000},
        //        {coordinates: scatterplotData[1].position, timestamp: 1554772579010},
        //        {coordinates: scatterplotData[9].position, timestamp: 1554772580200},
        //       ],
        //     }
        // ]


        const deckOverlay = new GoogleMapsOverlay({})
        const db_scatterLayer = new deck.ScatterplotLayer({
            id: 'stored-scatter-layer',
            data: db_scatterPlotData,
            getPosition: d => d.position,
            getFillColor: d => d.color,
            getRadius: d => d.radius,
            opacity: 0.6,
        })
        deckOverlay.setProps({
            layers: [
                db_scatterLayer,
            ],
        });
        deckOverlay.setMap(map);

        // let currentTime = 0
        // const animate = () => {
        //     currentTime = (currentTime + 1) % 150;

        //     const scatterLayer = new deck.ScatterplotLayer({
        //         id: 'scatter-layer',
        //         data: scatterplotData,
        //         getPosition: d => d.position,
        //         getFillColor: d => d.color,
        //         getRadius: d => d.radius,
        //     })

        //     const tripsLayer = new deck.TripsLayer({
        //         id: 'trips-layer',
        //         data: waypointsData,
        //         getPath: d => d.waypoints.map(p => p.coordinates),
        //         getTimestamps: d => d.waypoints.map(p => p.timestamp),
        //         getColor: [253, 128, 93],
        //         opacity: 0.8,
        //         widthMinPixels: 2,
        //         rounded: true,
        //         fadeTrail: true,
        //         trailLength: 200,
        //         currentTime: currentTime
        //     })
        //     const tripsLayer2 = new deck.TripsLayer({
        //         id: 'trips-layer',
        //         data: waypointsData,
        //         getPath: d => d.waypoints.map(p => p.coordinates),
        //         getTimestamps: d => d.waypoints.map(p => p.timestamp),
        //         getColor: [0, 165, 255],
        //         opacity: 0.5,
        //         widthMinPixels: 2,
        //         rounded: true,
        //         fadeTrail: true,
        //         trailLength: 200,
        //         currentTime: currentTime - 60
        //     })

        //     const lineLayer = new deck.LineLayer({
        //         id: 'line-layer',
        //         data: lineData,
        //         getSourcePosition: d => d.sourcePosition,
        //         getTargetPosition: d => d.targetPosition,
        //         getColor: [0, 255, 0], // Green color for the line
        //         getWidth: 5, // Adjust the line width as needed
        //     })

        //     deckOverlay.setProps({
        //         layers: [
        //             scatterLayer,
        //             tripsLayer,
        //             tripsLayer2,
        //             // lineLayer,
        //         ],
        //     });

        //     window.requestAnimationFrame(animate);
        // };
        // window.requestAnimationFrame(animate);


        // map.addListener("click", (mapsMouseEvent) => {
        //     deckOverlay.setMap(map);
        // });


    }
    window.initMap = initMap;

    function setMapHeight() {
        let navbarHeight = $('#main-navbar').height()
        let CalculatedHeight = window.innerHeight - navbarHeight - 20;
    
        $('.map-container').css('height', CalculatedHeight + "px")
    }
    




    
</script>


@endpush
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
import $ from 'jquery';
require('bootstrap');

import '../scss/global.scss';

//Hoodtrooper map
const projects = [
    {
        "position": {
            "lat": -7.057282,
            "lng": -69.790649
        },
        "id": "cat_180",
        "filled": true,
        "color": "#6A1E74",
        "tooltip": {
            "title": "Bioverse Labs",
            "linkLabel": "Read more",
            "linkDirection": "/portfolio/bioverse-labs-using-drones-and-ai-support-sustainability-amazonian-ecosystem",
            "items": [
                {
                    "label": "Funding status",
                    "content": "Active"
                },
                {
                    "label": "Funding amount",
                    "content": "$74,171"
                },
                {
                    "label": "Founding year",
                    "content": "2018 "
                }
            ]
        }
    },
    {
        "position": {
            "lat": 15.057282,
            "lng": 22.790649
        },
        "id": "cat_200",
        "filled": false,
        "color": "#6A1E74",
        "tooltip": {
            "title": "Architecture 1",
            "linkLabel": "Read more",
            "linkDirection": "/art1",
            "items": [
                {
                    "label": "Funding status",
                    "content": "Active"
                },
                {
                    "label": "Funding amount",
                    "content": "$74,171"
                },
                {
                    "label": "Founding year",
                    "content": "2018 "
                }
            ]
        }
    }
];

const categories = [
    {
        "id": "all",
        "label": "All investments",
        "summaries": [
            {
                "label": "Projects",
                "value": "85"
            },
            {
                "label": "Countries",
                "value": "55"
            }
        ],
        "color": "#FFD740"
    },
    {
        "id": "cat_180",
        "label": "180",
        "summaries": [
            {
                "label": "Cats",
                "value": "11"
            },
            {
                "label": "Dogs",
                "value": "44"
            }
        ],
        "color": "#FF00FF"
    },
    {
        "id": "cat_200",
        "label": "200",
        "summaries": [
            {
                "label": "Cats",
                "value": "112"
            },
            {
                "label": "Dogs",
                "value": "443"
            }
        ],
        "color": "#00FF00"
    }
];

const types = [
    'Natural place',
    'Architecture'
];

const body = $('body');
const mapClose = $('#mapClose');
const mapModal = $('#mapModal');
const menuToggler = $('#toggleMapMenu');
const mapMenu = $('#mapMenu');

mapClose.click(function () {
    // mapModal.removeClass('showModal');
    // body.attr('overflow-y', 'auto')
    window.location.href = '/';
});

menuToggler.click(function () {
    mapMenu.toggleClass('opened');
    $(this).toggleClass('opened')
})

function reloadPage(){
    window.location.reload();
}

$(document).ready(function(){
    mapModal.addClass('showModal');
    initMap();
    body.attr('overflow-y', 'hidden')

    $('button[data-toggle=\'modal\']').on('click', function(){
        let loadUrl = $(this).data('modal-content');
        console.log('loading...');
        $('#popup .modal-title').html(loadUrl);
        $('#popup .inside').html('loading...');
        $('#popup .inside').load('/' + loadUrl);
    });

    $(document).on('submit', '#loginForm', function(e) {
        e.preventDefault();

        const form = $(this);

        $.ajax({
            url : form.attr('action'),
            type: form.attr('method'),
            data : form.serialize(),
            success: function(data) {
                if(data.logged_in_auth || data.logged_in){
                    console.log('reloading');
                    reloadPage();
                // }else if(data.failed_login){
                //     console.log('failed login');
                //     $('#popup .inside').html('loading failed login...');
                //     $('#popup .inside').html(data.failed_login);
                }else{
                    $('#popup .inside').html('elsee...');
                    $('#popup .inside').html(data);
                }
            }
        });
    });
});

// globals - they should be initialized inside view (refactor)
const currentCategoryElem = document.getElementById('current-category');
const expandedCategoryListElem = document.getElementById('category-expanded-list');
const categoryListElem = document.getElementById('category-list');
const typeListElem = document.getElementById('type-list');
const summaryListElem = document.getElementById('summary-list');
let map;
let tooltip;

// generate basic nodes
let expandedCategoryListNode = document.createElement('ul');
let summaryListNode = document.createElement('ul');
let categoryListNode = document.createElement('ul');
let typesListNode = document.createElement('ul');

expandedCategoryListNode.classList.add('expanded__categories__list');
summaryListNode.classList.add('summary__list', 'list_type--row');
categoryListNode.classList.add('category__list');
typesListNode.classList.add('type__list');
// end

const mapModel = {
    selectedCategory: null,
    projects: [],
    categories: [],
    types: [],
    markers: [],
    tempMarker: {
        marker: false,
        tooltip: false
    },
    init: function () {
        this.projects = projects;
        this.categories = categories;
        this.types = types;

        mapController.renderView()
    },
    setMarkers: function (markers) {
        this.markers = markers
    },
    closeTempMarker: function() {
        if(this.tempMarker.marker){
            this.tempMarker.marker.setMap(null);
        }
        if(this.tempMarker.tooltip){
            this.tempMarker.tooltip.close();
        }
    },
    setTempMarker: function (markerData) {
        //close temp marker
        this.closeTempMarker();

        //create new marker
        this.tempMarker.marker = new google.maps.Marker(markerData);

        //tooltip window
        this.tempMarker.tooltip = new google.maps.InfoWindow();
        let content = `
          <div class="tooltip__container">
            <span class="border__top" style="background-color: #a35256"></span>
            <div class="tooltip__header">
              <p>head</p>
            </div>
            <div class="tooltip__body">
              <p>brzuszek</p>
            </div>
            <div class="tooltip__footer">
              <p>footer</p>
            </div>
          </div>`;

        this.tempMarker.tooltip.setContent(content);
        this.tempMarker.tooltip.open(map, this.tempMarker.marker);
    }
};


const mapView = {
    generateElements: function (categories, types) {
        /**
         * Generate initial UI.
         */
        currentCategoryElem.addEventListener('click', function ()  {
            expandedCategoryListElem.classList.toggle('show--list');
            currentCategoryElem.classList.toggle('displayed');
        });
        this.generateFilterList(categories);
        this.generateStaticLists(categories, types);
    },
    renderNodes: function (nodes, container) {
        /**
         * Render initial UI.
         */
        container.appendChild(nodes)
    },
    generateFilterList: function (categories) {
        categories.forEach(category => {
            /**
             * Create expandable categories list.
             * Refactor is welcome here.
             */
            const listItem = this.generateListElement(category.label);
            const selectedCategory = mapController.getCurrentCategory();
            if (selectedCategory.id === category.id) {
                // set class for initial selected
                listItem.classList.add('current--category')
            }
            listItem.setAttribute('data-category', category.id);
            listItem.onclick = () => {
                const allItems = [...expandedCategoryListElem.getElementsByTagName('li')]; // get all items
                allItems.forEach(item => {
                    // set current class
                    const id = item.getAttribute('data-category');
                    if (id === category.id) {
                        item.classList.add('current--category')
                    } else {
                        item.classList.remove('current--category')
                    }
                });
                expandedCategoryListElem.classList.toggle('show--list'); // show list
                currentCategoryElem.classList.toggle('displayed'); // change mark
                tooltip.close();
                mapModel.closeTempMarker();
                this.filterCategory(category);
            };
            expandedCategoryListNode.appendChild(listItem);

            this.renderNodes(expandedCategoryListNode, expandedCategoryListElem)
        });
    },
    filterCategory: function (category) {
        /**
         * Filter selected category.
         * @param {Object} category
         */

        mapController.setCurrentCategory(category);
        this.setSelectedCategory(category);
        this.generateSummaryList(category);
        this.filterMarkers(category.id);
    },
    filterMarkers: function (id) {
        /**
         * Display / hide markers
         */
        const markers = mapController.getMarkers();

        markers.forEach(marker => {
            if (id === 'all') {
                marker.setVisible(true)
            } else if (marker.category === id) {
                marker.setVisible(true)
            } else {
                marker.setVisible(false)
            }
        })
    },
    generateListElement: function (template) {
        /**
         * Generate simple list element.
         */
        const newItem = document.createElement('li');
        newItem.innerHTML = template;
        newItem.classList.add('list__item');

        return newItem
    },
    generateStaticLists: function (categories, types) {
        /**
         * Any refactor is welcome here.
         */
        categories.forEach(category => {
            if (category.id !== 'all') {
                const template = `<span class="category--color" style="background-color: ${category.color}"></span> ${category.label}`;
                const listItem = this.generateListElement(template);
                categoryListNode.appendChild(listItem);

                this.renderNodes(categoryListNode, categoryListElem)
            }
        });
        types.forEach(type => {
            const listItem = this.generateListElement(type);
            typesListNode.appendChild(listItem);

            this.renderNodes(typesListNode, typeListElem)
        })
    },
    generateSummaryList: function (category) {
        /**
         * Generate summary list based on selected category.
         */
        summaryListNode.innerHTML = '';

        category.summaries.forEach(summary => {
            const innerTemplate = `<span class="summary__value">${summary.value}</span><span class="summary__label">${summary.label}</span>`;
            const listItem = this.generateListElement(innerTemplate);
            summaryListNode.appendChild(listItem)
        });

        this.renderNodes(summaryListNode, summaryListElem);
    },
    setSelectedCategory: function (category) {
        /**
         * Display selected category label.
         */
        currentCategoryElem.innerHTML = `<span>${category.label}</span>`
    },
    renderMarkers: function (projects) {
        /**
         * Create all markers.
         * Markers can have theirs own view - refactor.
         */
        tooltip = new google.maps.InfoWindow();
        let markers = [];

        projects.forEach(project => {
            const marker = new google.maps.Marker({
                position: project.position,
                category: project.id,
                map: map,
                icon: {
                    path: google.maps.SymbolPath.CIRCLE,
                    scale: 3,
                    strokeColor: project.color,
                    strokeWeight: 2,
                    fillColor: project.color,
                    fillOpacity: project.filled ? 1 : 0
                },
            });

            marker.addListener('click', function () {
                mapModel.closeTempMarker();

                const tooltipContent = project.tooltip;
                let content = `
          <div class="tooltip__container">
            <span class="border__top" style="background-color: ${project.color}"></span>
            <div class="tooltip__header">
              <span>${tooltipContent.title}</span>
            </div>
            <div class="tooltip__body">
              <ul>
          `;
                tooltipContent.items.forEach(item => {
                    content += `<li><span class="content__label">${item.label}</span><span class="content__content">${item.content}</span></li>`
                });
                content += `
              </ul>
            </div>
            <div class="tooltip__footer">
              <a href="${tooltipContent.linkDirection}" target="_blank">${tooltipContent.linkLabel}</a>
            </div>
          </div>`;

                tooltip.setContent(content);
                tooltip.open(map, marker);
            });

            markers.push(marker)
        });

        mapController.setMarkers(markers);

        google.maps.event.addListener(map, "click", function (e) {
            expandedCategoryListElem.classList.remove('show--list'); // hide list
            currentCategoryElem.classList.remove('displayed'); // change mark
            tooltip.close();

            //place temp marker
            const tempMarker = {
                position: e.latLng,
                map: map,
                icon: {
                    path: google.maps.SymbolPath.BACKWARD_CLOSED_ARROW,
                    scale: 5,
                    strokeColor: "#000",
                    strokeWeight: 2,
                    fillColor: "#ffc213",
                    fillOpacity: 1
                },
            };
            mapController.setTempMarker(tempMarker);
        });
    },
};


const mapController = {
    setCurrentCategory: function (category) {
        mapModel.selectedCategory = category
    },
    getMarkers: function () {
        return mapModel.markers
    },
    getCurrentCategory: function () {
        return mapModel.selectedCategory
    },
    setMarkers (markers) {
        mapModel.setMarkers(markers)
    },
    setTempMarker (marker) {
        mapModel.setTempMarker(marker)
    },
    renderView: function () {
        /**
         * Initialize all layout.
         */
        const categories = mapModel.categories;
        const defaultCategoryIndex = categories.findIndex(category => category.id === 'all');

        mapView.filterCategory(categories[defaultCategoryIndex]);
        mapView.renderMarkers(mapModel.projects);
        mapView.generateElements(categories, mapModel.types);
    }
};

function initMap () {
    /**
     * Initialize simple map.
     * @type {google.maps.Map}
     */
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 3,
        minZoom: 3,
        cursor: 'default',
        draggableCursor: 'default',
        center: { lat: 28.306877, lng: 1.746233 },
        fullscreenControl: false,
        mapTypeControl: false,
        streetViewControl: false,
        zoomControlOptions: {
            position: google.maps.ControlPosition.LEFT_BOTTOM
        },
        // resetBoundsOnResize: true,
        restriction: {
            latLngBounds: { north: 85, south: -85, west: -180, east: 180 },
        },
        styles: [
            {
                featureType: "all",
                elementType: "labels",
                stylers: [
                    {visibility: "off"}
                ]
            },
            {elementType: 'geometry', stylers: [{color: '#232329'}]},
            {elementType: 'labels.text.stroke', stylers: [{color: '#242f3e'}]},
            {elementType: 'labels.text.fill', stylers: [{color: '#746855'}]},
            {
                featureType: 'administrative.locality',
                elementType: 'labels.text.fill',
                stylers: [{color: '#d59563'}]
            },
            {
                featureType: 'administrative.country',
                stylers: [{color: '#2c2d35'}]
            },
            {
                featureType: 'poi.park',
                elementType: 'geometry',
                stylers: [{visibility: "off"}]
            },
            {
                featureType: 'road',
                elementType: 'geometry',
                stylers: [{visibility: "off"}]
            },
            {
                featureType: 'transit',
                elementType: 'geometry',
                stylers: [{visibility: "off"}]
            },
            {
                featureType: 'water',
                elementType: 'geometry',
                stylers: [{color: '#2c2d35'}]
            },
            {
                featureType: 'water',
                elementType: 'labels.text.fill',
                stylers: [{color: '#515c6d'}]
            },
            {
                featureType: 'water',
                elementType: 'labels.text.stroke',
                stylers: [{color: '#17263c'}]
            }
        ]
    });

    mapModel.init();
}

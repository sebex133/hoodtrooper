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

var hoodtrooper_places = [];

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

var categories = [];

const types = [
    'Place with image',
    'No image places'
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

    //Hide show
    $('#hoodtrooperModal').on('hidden.bs.modal', function (e) {
        $('#hoodtrooperModal .modal-title').html('');
        $('#hoodtrooperModal .inside').html('');
        $('#hoodtrooperModal .modal-loader').addClass('d-none');
    });

    //Modal show
    $('#hoodtrooperModal').on('show.bs.modal', function (e) {
        const loadUrl = $(e.relatedTarget).data('modal-url');
        const title = $(e.relatedTarget).data('modal-title');

        $('#hoodtrooperModal .modal-title').html(title);
        $('#hoodtrooperModal .inside').html('');
        $('#hoodtrooperModal .modal-loader').removeClass('d-none');

        $.get(loadUrl, function(e) {
        })
        .done(function(data) {
            $('#hoodtrooperModal .inside').html(data);
            $('#hoodtrooperModal .modal-loader').addClass('d-none');
        })
        .fail(function() {
            $('#hoodtrooperModal .inside').html('an error occured - try again');
            $('#hoodtrooperModal .modal-loader').addClass('d-none');
        })
        .always(function() {
        });
    });

    $(document).on('click', '.close-tooltip', function(e) {
        mapModel.closeTempMarker();
    });

    $(document).on('click', '.reload-modal-content', function(e) {
        const loadUrl = $(this).data('modal-url');
        const title = $(this).data('modal-title');

        $('#hoodtrooperModal .modal-title').html(title);
        $('#hoodtrooperModal .inside').html('');
        $('#hoodtrooperModal .modal-loader').removeClass('d-none');

        $.get(loadUrl, function(e) {
        })
        .done(function(data) {
            $('#hoodtrooperModal .inside').html(data);
            $('#hoodtrooperModal .modal-loader').addClass('d-none');
        })
        .fail(function() {
            $('#hoodtrooperModal .inside').html('an error occured - try again');
            $('#hoodtrooperModal .modal-loader').addClass('d-none');
        })
        .always(function() {
        });
    });

    $(document).on('change', '.custom-file-input', function(e) {
        const fileInput = e.target;
        const label = $(fileInput).siblings('label[for="' + fileInput.id + '"');
        const filename = fileInput.files[0].name;
        label.html(filename);
    });

    $(document).on('submit', '.ajax-form', function(e) {
        e.preventDefault();
        const form = $(this);
        const formWrap = $(this).parent();

        formWrap.html('<p>Sending form...</p>');

        //enable disabled elements before serialization
        const disabledElements = form.find(':disabled').removeAttr('disabled');

        //special serialization to also serialize File inputs
        let formData = new FormData()
        let formParams = form.serializeArray();

        $.each(form.find('input[type="file"]'), function(i, tag) {
            $.each($(tag)[0].files, function(i, file) {
                formData.append(tag.name, file);
            });
        });

        $.each(formParams, function(i, val) {
            formData.append(val.name, val.value);
        });

        //disable elements again
        disabledElements.attr('disabled','disabled');

        $.ajax({
            url : form.attr('action'),
            type: form.attr('method'),
            data : formData,
            processData: false,
            contentType: false,
            success: function(data) {
                if(data.success_ajax_form){
                    formWrap.html('<p>Success! Reloading page...</p>');
                    reloadPage();
                }else{
                    formWrap.html(data);
                }
            }
        });
    });

    $(document).on('submit', '.ajax-comment-form', function(e) {
        e.preventDefault();
        const form = $(this);
        const formWrap = $(this).parent();
        const wholeFormSection = $(this).closest('.place_comments');

        if(form.hasClass('delete-comment')){
            formWrap.html('<p>Deleting...</p>');
        }else{
            formWrap.html('<p>Sending comment...</p>');
        }

        //enable disabled elements before serialization
        const disabledElements = form.find(':disabled').removeAttr('disabled');

        //special serialization to also serialize File inputs
        let formData = new FormData()
        let formParams = form.serializeArray();

        $.each(form.find('input[type="file"]'), function(i, tag) {
            $.each($(tag)[0].files, function(i, file) {
                formData.append(tag.name, file);
            });
        });

        $.each(formParams, function(i, val) {
            formData.append(val.name, val.value);
        });

        //disable elements again
        disabledElements.attr('disabled','disabled');

        $.ajax({
            url : form.attr('action'),
            type: form.attr('method'),
            data : formData,
            processData: false,
            contentType: false,
            success: function(data) {
                if(data){
                    wholeFormSection.html(data);
                }else{
                    wholeFormSection.html('There was a problem during comments retrieve from database...');
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

const tempTooltipMarkerContent = document.getElementById('add-new-place-content');

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
        // this.projects = projects;
        this.projects = hoodtrooper_places;
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
    // fillTempMarker: function () {
    //     this.tempMarker.tooltip.setContent(content);
    //     this.tempMarker.tooltip.open(map, this.tempMarker.marker);
    // }
    setTempMarker: function (markerData) {
        //close temp marker
        this.closeTempMarker();

        //create new marker
        this.tempMarker.marker = new google.maps.Marker(markerData);

        //tooltip window
        this.tempMarker.tooltip = new google.maps.InfoWindow();

        //loading markup
        let contentLoading = `
          <div class="tooltip__container text-center">
            <span class="border__top" style="background-color: #a35256"></span>
            <div class="tooltip__header">
                <p>Loading...</p>
            </div>
            <div class="tooltip__body">
            </div>
            <div class="tooltip__footer">
            </div>
          </div>`;

        this.tempMarker.tooltip.setContent(contentLoading);
        this.tempMarker.tooltip.open(map, this.tempMarker.marker);

        const markerTooltip = this.tempMarker.tooltip;

        $.get('/hoodtrooper/place_tooltip?lat=' + this.tempMarker.marker.position.lat() + '&lng=' + this.tempMarker.marker.position.lng(), function(e) {
        })
        .done(function(data) {
            let content = data;

            markerTooltip.setContent(content);
        })
        .fail(function() {
        })
        .always(function() {
        });
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

                //off render
                // this.renderNodes(categoryListNode, categoryListElem)
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
                  <div class="tooltip__container text-center">
                        <span class="border__top" style="background-color: ${project.color}"></span>
                        <div class="tooltip__header">
                          <span>${tooltipContent.title}</span>
                        </div>
                        <div class="tooltip__body">`;

                    // tooltipContent.items.forEach(item => {
                    //     content += `<li><span class="content__label">${item.label}</span><span class="content__content">${item.content}</span></li>`
                    // });

                    content +=
                        `</div>
                        <div class="tooltip__footer">
                            <button class="btn btn-sm btn-dark" 
                            data-toggle="modal" 
                            data-target="#hoodtrooperModal" 
                            data-modal-title="${tooltipContent.title}" 
                            data-modal-url="${tooltipContent.linkDirection}">
                                ${tooltipContent.linkLabel}
                            </button>
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

        mapMenu.toggleClass('opened');
    }
};

function initMap () {

    //get all places
    $.get('/hoodtrooper/place/places_json', function(e) {
    })
    .done(function(data) {
        if(data.places_json){
            hoodtrooper_places = data.places_json;

            categories = [
                {
                    "id": "all",
                    "label": "All places",
                    "summaries": [
                        {
                            "label": "Places",
                            "value": hoodtrooper_places.length ? hoodtrooper_places.length : 0,
                        },
                    ],
                    "color": "#FFD740"
                },
                {
                    "id": "with_image",
                    "label": "Place with image",
                    "summaries": [
                        {
                            "label": "Places",
                            "value": "33"
                        },
                    ],
                    "color": "#FF00FF"
                },
                {
                    "id": "no_image",
                    "label": "No image places",
                    "summaries": [
                        {
                            "label": "Places",
                            "value": "77"
                        },
                    ],
                    "color": "#00FF00"
                }
            ];
            createMap();
        }
    })
    .fail(function() {
    });
}

function createMap() {
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

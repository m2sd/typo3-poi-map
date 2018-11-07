/**
 * Module: TYPO3/CMS/PoiMap/PositionPicker
 *
 * JavaScript to handle position picker using google maps
 */
define(['jquery', 'TYPO3/CMS/Backend/FormEngine', 'TYPO3/CMS/PoiMap/GoogleMapsLoader'], function ($, FormEngine, GoogleMapsLoader) {
    'use strict';

    function getLatLngFromString(input) {
        input = input.split(',');
        return {
            lat: parseFloat(input[0]),
            lng: parseFloat(input[1])
        };
    }

    /**
     * @exports TYPO3/CMS/PoiMap/PositionPicker
     */
    var MapsPositionPicker = function (container) {
        this.$container = $(container);
        this.$view = $(this.$container.data('view'));
        this.$outputActual = FormEngine.getFieldElement(this.$container.data('output-name'));
        this.$outputVisual = this.$container.closest('form').find(':input[data-formengine-input-name="' + this.$container.data('output-name') + '"]');

        if (typeof window.google === 'undefined') {
            var self = this;
            GoogleMapsLoader.KEY = this.$container.data('api-key');
            GoogleMapsLoader.load(function (google) {
                // jQuery wrapper for google maps
                if (typeof $.fn.googleMaps !== 'function') {
                    $.fn.googleMaps = function (config) {
                        config = (config || {});
                        var maps = [];

                        this.each(function () {
                            var $this = $(this),
                                mapOptions = $.extend({},
                                    {
                                        zoom: 1,
                                        center: { lat: 0.0, lng: 0.0 },
                                        mapTypeId: google.maps.MapTypeId.ROADMAP,
                                        mapTypeControl: false,
                                        streetViewControl: false
                                    },
                                    config
                                ),
                                map = ($this.data('googleMap') || new google.maps.Map(this));

                            map.setOptions(mapOptions);

                            maps.push(map);
                            $this.data('googleMap', map);
                        });

                        if (maps.length === 1) {
                            return maps[0];
                        } else {
                            return maps;
                        }
                    };
                }

                self.init();
            });
        } else {
            this.init();
        }
    };

    MapsPositionPicker.prototype.init = function () {
        var config = {},
            value;

        if (value = (this.$outputActual.val() || this.$container.data('center'))) {
            if (typeof value === 'string') {
                value = getLatLngFromString(value);
            }

            config.center = value;
        }

        if (value = this.$container.data('zoom')) {
            config.zoom = value;
        }
        if (value = this.$container.data('type')) {
            config.mapTypeId = value;
        }

        this.map = this.$view.googleMaps(config);

        this.setMarkerPosition(
            this.$outputActual.val() || config.center || '0.0,0.0'
        );
    };

    MapsPositionPicker.prototype.setMarkerPosition = function (position) {
        if (typeof position === 'string' && position) {
            position = getLatLngFromString(position);
        }

        if (this.marker) {
            this.marker.setMap(null);
        }

        if (position) {
            this.marker = new google.maps.Marker({
                position: position,
                map: this.map,
                draggable: true
            });

            var self = this;
            this.marker.addListener('drag', function () {
                self.$outputVisual.val(self.getMarkerPosition());
            });
            this.marker.addListener('dragend', function () {
                self.$outputActual.val(self.getMarkerPosition());
            })
        }
    };

    MapsPositionPicker.prototype.getMarkerPosition = function () {
        var position = this.marker.getPosition().toJSON();
        return position.lat + ',' + position.lng;
    };

    $('.t3js-poimap-position-picker').each(function () {
        new MapsPositionPicker(this);
    });

    return MapsPositionPicker;
});
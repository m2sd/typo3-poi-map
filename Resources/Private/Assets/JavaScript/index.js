import GoogleMapsLoader from '@/Resources/Public/JavaScript/GoogleMapsLoader.js';

class PoiMap {
  constructor(options = {}) {
    this.options = options;
    this.maps = [];

    if (this.options.apiKey) {
      GoogleMapsLoader.KEY = this.options.apiKey;
    }

    if (this.options.language) {
      GoogleMapsLoader.LANGUAGE = this.options.language;
    }

    if (options.callbacks instanceof Array && options.callbacks.length) {
      this.init(options.callbacks);
    }
  }

  init(callbacks) {
    GoogleMapsLoader.load(() => {
      window.SnazzyInfoWindow = require('snazzy-info-window');
      if (!this.options.excludeCss) {
        require('snazzy-info-window/dist/snazzy-info-window.min.css');
      }

      callbacks.forEach(callback => {
        this.maps.push(callback(this));
      });
    });
  }
}

const ns = (window.M2S = window.M2S || {});

ns.PoiMap = PoiMap;
export default PoiMap;

const loaded = new Event('m2s:poi-map-loaded');
document.dispatchEvent(loaded);

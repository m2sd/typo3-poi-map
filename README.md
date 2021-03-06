# typo3-poi-map

A Typo3 extensions which adds categorized points of interest with GoogleMaps and SnazzyInfoWindow integration.

## Installation

The extension is tested in composer mode but, like all composer extensions, should work in classic mode without any hassle.

### Composer mode

Just require the extension from the command line.

```bash
composer require m2s/typo3-poi-map
```

### Classic mode

1. Download the latest release and extract it into a folder named `poi_map` in your extension folder.
2. Go to the extension manager in the typo3 backend and enable the extension.

## Setup

### TypoScript

Include the static typoscirpt in your root template or an extension template of your choice.

### Extension configuration

#### Basic settings

| Setting            | Type        | Description                                                                                                                                                                                          | Default     |
| ------------------ | ----------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ----------- |
| maps.api_key       | `text`      | **[`Maps JavaScript API` key](https://developers.google.com/maps/documentation/javascript/get-api-key):** If not set the maps implementations will be rendered in development mode                   | `''`        |
| maps.default_type  | `select`    | **[`mapTypeId`](https://developers.google.com/maps/documentation/javascript/reference/map#MapOptions.mapTypeId):** The default display type for google maps rendered by the plugin                   | `'roadmap'` |
| maps.default_style | `text/json` | **[`styles`](https://developers.google.com/maps/documentation/javascript/reference/map#MapOptions.styles):** A json string that defines how the google map is rendered, only works if api key is set | `''`        |

**Hint:** Visit [SnazzyMaps](https://snazzymaps.com/) for some awesome styles.

#### Content settings

| Setting                      | Type       | Description                                                                                                                                                                                         | Default |
| ---------------------------- | ---------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ------- |
| maps.use_typoscript_language | `checkbox` | **Use Typo3 language:** If set maps will be loaded using the language code from the typoscript configuration (by default google maps use the browser preferences to determine the display language) | `false` |

**ATTENTION:** Please be sure to set the [`config.language` property in your Typoscript](https://docs.typo3.org/typo3cms/TyposcriptReference/Setup/Config/Index.html#language) appropiately, if you want to use this setting

#### Advanced Settings

| Setting                 | Type        | Description                                                                                                                                                                                                                 | Default     |
| ----------------------- | ----------- | --------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- | ----------- |
| maps.default_options    | `text/json` | **Default [maps options](https://developers.google.com/maps/documentation/javascript/reference/map#MapOptions):** The `styles` option is overwritten by the `maps.default_style` setting if set to anything other than `''` | `''`        |
| wizard.api_key          | `text`      | **[`Maps JavaScript API` key](https://developers.google.com/maps/documentation/javascript/get-api-key) for the backend wizard:** Is ignored if `wizard.use_frontend_api` is set                                             | `''`        |
| wizard.use_frontend_api | `checkbox`  | **Use frontend API key for backend wizard:** If set the API key defined for the frontend implementation is used for the backend                                                                                             | `false`     |
| wizard.center           | `text/csv`  | **Default [center](https://developers.google.com/maps/documentation/javascript/reference/map#MapOptions.center) for backend wizard:** Commaseparated geo coordinates                                                        | `'0.0,0.0'` |
| wizard.zoom             | `int+`      | **Default [zoom](https://developers.google.com/maps/documentation/javascript/reference/map#MapOptions.zoom) for backend wizard:** A value between `1` and `18`                                                              | `1`         |

In most cases it is advisable to not use an api key for the backend to conserve quota, because you will be [billed](https://developers.google.com/maps/billing/understanding-cost-of-use#dynamic-maps) after 28,000 loads per day.  
With that said however it can be useful to use a different api key for the backend to track the usage separately.

### Constants

These settings won't be available if you did not include the static typoscript in your template ([see above](#TypoScript)).

#### Template settings

As is common with plugin extensions you can customize the templates by adding your own root paths and overwriting the templates.

| Setting          | Type   | Desciption        | Default |
| ---------------- | ------ | ----------------- | ------- |
| templateRootPath | `text` | Path to templates | `''`    |
| partialRootPath  | `text` | Path to partials  | `''`    |
| layoutRootPath   | `text` | Path to layouts   | `''`    |

#### Persitence settings

This setting does not have any effect at the moment as data insertion is done via the List module.  
However it is recommended to set this to the UID of the page in which you keep your [Place](#Places) records.

| Setting    | Type   | Description         | Default |
| ---------- | ------ | ------------------- | ------- |
| storagePid | `int+` | Default storage PID | `0`     |
  
#### Additional settings

These settings provide defaults for all maps rendered by the plugin.

| Setting              | Type        | Description                                                                                                                                                                                                                                                        | Default   |
| -------------------- | ----------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | --------- |
| default_type         | `select`    | **[`mapTypeId`](https://developers.google.com/maps/documentation/javascript/reference/map#MapOptions.mapTypeId):** The default display type for google maps rendered by the plugin, will overwrite extension setting if set to anything but `inherit`              | `inherit` |
| default_marker_icon  | `text/url`  | **Default marker icon:** An absolute URL either to an external resource (including schema and host) or to a local resource (starting with `/`) to use as default custom marker                                                                                     | `''`      |
| default_info_options | `text/json` | **[SnazzyInfoWindow options](https://github.com/atmist/snazzy-info-window#options):** A json string that provides the default options for info windows rendered by the plugin                                                                                      | `''`      |
| default_style        | `text/json` | **[`styles`](https://developers.google.com/maps/documentation/javascript/reference/map#MapOptions.styles):** A json string that defines how the google map is rendered, only works if api key is set, will overwrite extension setting if set to anything but `''` | `''`      |

## Places

The extension adds a new record type.  
Place records can be added from the List module in the typo3 backend.  
Places can be used as markers for the maps.

## Maps

The extension adds a new list type (plugin).  
The plugin has to layouts:

* **List:** Lists all Places corresponding to the filter settings
* **Map:** Generates a google map with all Places corresponding to the filter settings

## ViewHelper

The extension adds a new ViewHelper.  
The map view helper can be used to render a google map.

### Maps specific attributes

| Property         | Type      | Description                                                     | Default                |
| ---------------- | --------- | --------------------------------------------------------------- | ---------------------- |
| places           | `array`   | A set of places to be placed on the map as markers              | `[]`                   |
| as               | `string`  | The name for the iteration variable in the info window template | `'place'`              |
| options          | `array`   | Configuration array for the map itself                          | `default_options`      |
| mapStyles        | `array`   | Configuration array for the styles option of the map            | `default_style`        |
| type             | `string`  | The type of map that should be displayed                        | `default_type`         |
| zoom             | `integer` | The zoom of the map (between `1` and `18`)                      | `1`                    |
| center           | `string`  | The center of the map (comma separated geo coordinates)         | `'0.0,0.0'`            |
| enableInfo       | `boolean` | Enables/disables on click info windows                          | `true`                 |
| enableInfoSingle | `boolean` | Enables/disables multiple open info windows                     | `true`                 |
| infoOptions      | `array`   | Additional configuration for SnazzyInfoWindow instances         | `default_info_options` |
| markerIcon       | `string`  | Sets a custom marker icon for all markers on this map           | `default_marker_icon`  |

### Default tag attributes

| Property             | Description                                                                                         |
| -------------------- | --------------------------------------------------------------------------------------------------- |
| class                | CSS class(es) for this element                                                                      |
| dir                  | Text direction for this HTML element. Allowed strings: "ltr" (left to right), "rtl" (right to left) |
| lang                 | Language for this element. Use short names specified in RFC 1766                                    |
| style                | Individual CSS styles for this element                                                              |
| title                | Tooltip text of element                                                                             |
| accessKey            | Keyboard shortcut to access this element                                                            |
| tabindex             | Specifies the tab order of this element                                                             |
| onclick              | JavaScript evaluated for the onclick event                                                          |
| additionalAttributes | An array of additional attributes for the maps container                                            |

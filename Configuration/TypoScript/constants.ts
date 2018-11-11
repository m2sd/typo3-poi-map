plugin.tx_poimap_places {
  view {
    # cat=poi_map/file/a1; type=string; label=Path to template root(FE)
    templateRootPath =
    # cat=poi_map/file/a2; type=string; label=Path to template partials(FE)
    partialRootPath =
    # cat=poi_map/file/a3; type=string; label=Path to template layouts(FE)
    layoutRootPath =
    }
  persistence {
    # cat=poi_map/links/a1; type=int; label=Default storage PID
    storagePid =
    }
  settings {
    # cat=poi_map/typo/a1; type=options[inherit=,ROADMAP=roadmap,SATELLITE=satellite,HYBRID=hybrid,TERRAIN=terrain]; label=GoogleMaps type:The display type for the google map(inherit = use extension settings)
    default_type =
    # cat=poi_map/typo/a3; type=string; label=SnazzyInfoWindow options:A json string that specifies custom options for the info windows
    default_info_options =
    # cat=poi_map/color/a1; type=string; label=GoogleMaps styles:A json string that defines how the google map is rendered, only works if api key is set(empty = use extension settings)
    default_style =
  }
}

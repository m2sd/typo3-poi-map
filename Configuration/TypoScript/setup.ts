plugin.tx_poimap_places {
    persistence {
        storagePid = {$plugin.tx_poimap_places.persistence.storagePid}

        classes {
            M2S\PoiMap\Domain\Model\Place {
                newRecordStoragePid = {$plugin.tx_poimap_places.persistence.storagePid}
            }
            M2S\PoiMap\Domain\Model\Category {
                mapping {
                    tableName = sys_category
                }
            }
        }
    }

    view {
        layoutRootPaths {
            0 = EXT:poi_map/Resources/Private/Layouts/
            1 = {$plugin.tx_poimap_places.view.layoutRootPath}
        }
        partialRootPaths {
            1 = {$plugin.tx_poimap_places.view.partialRootPath}
        }
        templateRootPaths {
            0 = EXT:poi_map/Resources/Private/Templates/
            1 = {$plugin.tx_poimap_places.view.templateRootPath}
        }
    }

    settings {
        default_style = {$plugin.tx_poimap_places.settings.default_style}
        default_marker_icon = {$plugin.tx_poimap_places.settings.default_marker_icon}
        default_info_options = {$plugin.tx_poimap_places.settings.default_info_options}
        default_type = {$plugin.tx_poimap_places.settings.default_type}
    }
}

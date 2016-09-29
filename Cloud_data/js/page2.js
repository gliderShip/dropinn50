function clean_up_and_submit_search_request() {
    $("#search_type_list").trigger("click");
    CogzidelSearch.loadNewResults();
    return false
}

function display_search_type(e, t) {
    var n, r;
    $("#map_message").hide();
    t = typeof t === "undefined" ? true : t;
    var i = CogzidelSearch.currentViewType;
    if (i === e.replace("search_type_", "")) {
        return false
    }
    $(".search_type_option").removeClass("search_type_option_active");
    $("#" + e).addClass("search_type_option_active");
    CogzidelSearch.changing_display_type = true;
    if (e === "search_type_photo") {
        SS.initOnce();
        CogzidelSearch.currentViewType = "photo";
        $("#Search_Main").removeClass("list_view map_view");
        $("#list_view_loading").show();
        $("#small_map_loading").show();
        setTimeout(function() {
            $("#list_view_loading").hide();
            $("#small_map_loading").hide();
            $("#Search_Main").addClass("photo_view")
        }, 100);
        r = "small";
        $("#search_type_photo").trigger("click");
        CogzidelSearch.loadNewResults();
        $("#search_filters_wrapper").appendTo("#Search_Main");
        $("#map_wrapper").prependTo("#search_filters");
        $("#map_options").prependTo("#search_filters");
        return false
    } else {
        if (e === "search_type_list") {
            CogzidelSearch.currentViewType = "list";
            $("#Search_Main").removeClass("map_view photo_view").addClass("list_view");
            r = "x_small";
            $("#search_type_list").trigger("click");
            CogzidelSearch.loadNewResults();
            $("#search_filters_wrapper").appendTo("#Search_Main");
            $("#map_wrapper").prependTo("#search_filters");
            $("#map_options").prependTo("#search_filters");
            AMM.zoom_control();
            return false
        } else {
            if (e === "search_type_map") {
                SS.initOnce();
                CogzidelSearch.currentViewType = "map";
                CogzidelSearch.hideBannerForRemainderOfSession = true;
                var s = AMM.map.getCenter();
                var o = AMM.map.getZoom();
                if (o < 13) {
                    o = o + 2
                }
                $("#search_type_map").trigger("click");
                CogzidelSearch.loadNewResults();
                $("#cc_attribution_link").addClass("force_hide");
                $("#Search_Main").removeClass("list_view photo_view").addClass("map_view condensed_header_view");
                google.maps.event.addListenerOnce(AMM.map, "resize", function() {
                    AMM.map.setCenter(s);
                    AMM.map.setZoom(o)
                });
                google.maps.event.trigger(AMM.map, "resize");
                jQuery("#results_filters").insertAfter("#standby_action_area");
                jQuery("#results_save").insertAfter("#applied_filters");
                jQuery("#map_wrapper").appendTo("#Search_Main");
                jQuery("#map_options").prependTo("#search_filters");
                jQuery("#map_wrapper").append(jQuery("#search_filters_wrapper")).append(jQuery("#search_filters_toggle"));
                AMM.clearOverlays();
                jQuery.each(CogzidelSearch.resultsJson.properties, function(e, t) {
                    AMM.queue.push(t.id)
                });
                AMM.showOverlays();
                AMM.initMapOnce("search_map")
            }
        }
    }
    if (e === "search_type_list" || e === "search_type_wishlist") {
        if (t) {
            AMM.closeInfoWindow();
            n = CogzidelSearch.thumbnailStyles[r];
            $(".search_thumbnail").each(function(e, t) {
                t.src = t.src;
                t.height = n[0];
                t.width = n[1]
            })
        }
        var u = AMM.map.getCenter();
        var a = AMM.map.getZoom();
        if (a > 10) {
            a = a - 2
        }
        google.maps.event.addListenerOnce(AMM.map, "resize", function() {
            AMM.map.setCenter(u);
            AMM.map.setZoom(a)
        });
        if (i === "map") {
            google.maps.event.trigger(AMM.map, "resize");
            $("#results_filters").insertAfter("#results_header");
            $("#results_save").appendTo("#results_header");
            if (t && !redoSearchInMapIsChecked()) {
                $("#search_type_list").trigger("click");
                CogzidelSearch.loadNewResults()
            }
        }
        $("#search_filters_wrapper").appendTo("#Search_Main");
        $("#map_wrapper").prependTo("#search_filters");
        $("#map_options").prependTo("#search_filters")
    }
    if (i === "map" && (e === "list" || e === "photo") || i !== "map" && e !== "map") {
        CogzidelSearch.loadNewResultsWithNoResponse()
    }
    if (i === "map") {
        $("#map_message").width(507);
        $("#search_filters_toggle").addClass("search_filters_toggle_off").removeClass("search_filters_toggle_on");
        $("#search_filters").show()
    } else {
        if ($("#search_filters").is(":visible")) {
            $("#search_filters_toggle").addClass("search_filters_toggle_on").removeClass("search_filters_toggle_off")
        } else {
            $("#search_filters_toggle").addClass("search_filters_toggle_off").removeClass("search_filters_toggle_on")
        }
    }
    CogzidelSearch.$.trigger("finishedrendering");
    CogzidelSearch.changing_display_type = false;
    return false
}

function reset_params_to_defaults() {
    CogzidelSearch.newSearch = true;
    CogzidelSearch.locationHasChanged = false;
    CogzidelSearch.results_changed_by_map_action = false;
    $("#page").val("1")
}

function redoSearchInMapIsChecked() {
    return $("#redo_search_in_map").is(":checked")
}

function showLoadingOverlays() {
    clearTimeout(CogzidelSearch.loadingMessageTimeout);
    CogzidelSearch.loadingMessageTimeout = setTimeout(function() {
        if (window.google && window.google.maps) {}
    }, 250)
}

function hideLoadingOverlays() {
    clearTimeout(CogzidelSearch.loadingMessageTimeout);
    $("#results_header, #results_filters, #results, #results_footer").removeClass("search_grayed")
}

function clearResultsList() {
    $("#results").empty()
}

function setBannerImage(e) {
    if (e.url === undefined) {
        $("#Search_Main").addClass("condensed_header_view")
    } else {
        var t = new Image;
        t.src = e.url;
        if (t.complete) {
            bannerImageLoadComplete(e);
            t.onload = function() {}
        } else {
            t.onload = function() {
                bannerImageLoadComplete(e);
                t.onload = function() {}
            }
        }
    }
    setAirtvVideo(e)
}

function setAirtvVideo(e) {
    $("#airtv_promo").remove();
    if (e.airtv_url !== undefined && CogzidelSearch.resultsJson.show_airtv_in_search_results && CogzidelSearch.resultsJson.show_airtv_in_search_results === "true") {
        var t = {};
        t.airtv_url = e.airtv_url;
        t.airtv_headline = e.airtv_headline || "Check Out AirTV!";
        t.airtv_description = e.airtv_description || "A video from nearby!";
        $("#results").before($("#list_view_airtv_template").jqote(t, "*"));
        initAirtvSearchVideoLightBox("#airtv_promo", e.airtv_url, e.airtv_headline)
    }
}

function initAirtvSearchVideoLightBox(e, t, n) {
    if (jQuery("#video_lightbox_content").length === 0) {
        jQuery("body").append('<div id="video_lightbox_content"></div>')
    }
    jQuery(e).colorbox({
        inline: true,
        href: "#video_lightbox_content",
        onLoad: function() {
            var e = ['<object id="video" width="764" height="458"><param name="movie" value="', t, '"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="', t, '" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="764" height="458"></embed></object>'].join("");
            jQuery("#video_lightbox_content").html(e)
        },
        onComplete: function() {
            jQuery("#cboxTitle").html(n)
        },
        onCleanup: function() {
            jQuery("#video_lightbox_content").html("");
            jQuery("#cboxTitle").html("")
        }
    })
}

function bannerImageLoadComplete(e) {
    jQuery("#search_header").css("background-image", ["url(", e.url, ")"].join(""));
    if (e.height) {
        jQuery("#search_header").css("height", e.height)
    } else {
        jQuery("#search_header").css("height", 150)
    }
    jQuery("#Search_Main").removeClass("condensed_header_view");
    var t = e.attribution_text || "CC licensed photo from Flickr";
    var n = e.attribution_url || "http://www.flickr.com";
    jQuery("#cc_attribution_link").html(t).attr("href", n).show();
    CogzidelSearch.$.trigger("finishedrendering")
}

function render_results_oncomplete(e) {
    var t;
    if (e.banner_info && CogzidelSearch.hideBannerForRemainderOfSession === false) {
        setBannerImage(e.banner_info)
    } else {
        jQuery("#cc_attribution_link").hide()
    }
    if (!(e.banner_info && e.banner_info.airtv_url)) {
        Connections.init()
    }
    AMM.centerLat = false;
    AMM.centerLng = false;
    AMM.geocodePrecision = false;
    if (e.center_lat && e.center_lng) {
        AMM.centerLat = e.center_lat;
        AMM.centerLng = e.center_lng;
        if (e.geocode_precision) {
            AMM.geocodePrecision = e.geocode_precision
        }
    }
    AMM.drawCenterMarker();
    reset_params_to_defaults();
    setTimeout(function() {
        AMM.turnMapListenersOn()
    }, 1e3);
    CogzidelSearch.markViewedPageLinks();
    CogzidelSearch.trackSearch();
    CogzidelSearch.activeAjaxRequest = null;
    CogzidelSearch.initialLoadComplete = true;
    CogzidelSearch.$.trigger("finishedrendering")
}

function render_results(e, t) {
    function n(e, t, n) {
        return f.jqote({
            badge_type: e,
            badge_text: t,
            badge_name: n
        }, "*")
    }
    var r, i, s, o, u;
    var a, f, l;
    var c = false;
    AMM.turnMapListenersOff();
    clearResultsList();
    if (window.google && window.google.maps && (arguments.length == 1 || t.ea === undefined)) {
        r = new google.maps.LatLngBounds
    } else {
        r = t
    }
    $(".results_count").html(e.results_count_top_html);
    $("#results_count_html").html(e.results_count_html);
    $("#results_pagination").html(e.results_pagination_html);
    AMM.clearQueue();
    s = false;
    if (CogzidelSearch.forcedViewType !== false && (CogzidelSearch.initialLoadComplete === false || CogzidelSearch.searchHasBeenModified() === false)) {
        s = CogzidelSearch.forcedViewType
    } else {
        if (e.view_type) {
            s = e.view_type
        }
    }
    if (s !== false) {
        display_search_type("search_type_" + CogzidelSearch.viewTypes[s], false);
        CogzidelSearch.currentViewType = CogzidelSearch.viewTypes[s];
        CogzidelSearch.params.search_view = s
    }
    if (e.present_standby_option && e.present_standby_option === true && e.standby_url) {
        $("#standby_link").attr("href", e.standby_url);
        CogzidelSearch.presentStandbyOption()
    } else {
        $("#standby_link").attr("href", base_url + "messaging/standby");
        CogzidelSearch.hideStandbyOption()
    }
    i = document.createDocumentFragment();
    f = $("#badge_template");
    l = $("#list_view_item_template");
    if (CogzidelSearch.currentViewType === "list") {
        u = "x_small"
    } else {
        u = "small"
    }
    o = CogzidelSearch.thumbnailStyles[u];
    $.each(e.properties, function(e, s) {
        var u, a, f;
        var h;
        var p = s.hosting_thumbnail_url;
        if (window.google && window.google.maps) {
            f = new google.maps.LatLng(s.lat, s.lng);
            AMM.add(f, s);
            if (r !== t) {
                r.extend(f)
            }
        }
        if (SS) {
            if (s.picture_ids) {
                SS.addHostingAndIds(s.id, s.picture_ids);
                if (SS.pictureArrays && SS.pictureArrays[s.id][0] !== undefined) {
                    s.smallThumbnail = SS.fullImageUrl(SS.pictureArrays[s.id][0])
                } else {
                    SS.pictureArrays[s.id] = []
                }
            }
        }
        if (CogzidelSearch.currentViewType === "list" || CogzidelSearch.currentViewType === "photo") {
          var review = s.review_count == 1  ? Translations.review : Translations.reviews ;
        	var count_re = s.review_count ;
        	if(s.review_count == 0)
        	{
        		var overall = "" ;	
        	}else{
        		var overall = count_re+" "+review ;	
        	}        
            a = {
                hosting_name: s.name,
                user_name: s.user_name,
                user_id: s.user_id,
                hosting_id: s.id,
                result_number: e + 1,
                address: s.address,
                price: s.price,
                symbol: s.symbol,
                views: s.page_viewed,
               // wishlit count 1 start
               // wishlit count 1 end
                
             
                 // Discount label 1 start 
                  discount: s.discount,
                 // Discount label 1 end
                staggered: s.staggered,
                short_listed: s.short_listed,
                hosting_thumbnail_url: p,
                hosting_thumbnail_width: o[1],
                hosting_thumbnail_height: o[0],
                connections: s.relationships || [],
                hasVideo: s.has_video,
                instant_book:s.instant_book,
                isNewHosting: s.is_new_hosting && s.is_new_hosting === true,
                hasInstantBook: s.instant_book && s.instant_book === true,
                distance: s.distance,
                review_count: overall,
                review_rating:s.review_rating
            };
            if (s.user_thumbnail_url && s.user_thumbnail_url.charAt(0) !== "/") {
                a.user_thumbnail_url = s.user_thumbnail_url
            }
            if (s.price > 999 && CogzidelSearch.currencySymbolRight !== "" || s.staggered) {
                c = true
            }
            h = $(l.jqote(a, "*"));
            u = "";
            /*
            if (s.review_count > 0) {
                u += n("reviews", s.review_count, s.review_count == 1 ? Translations.review : Translations.reviews)
            }
            if (s.recommendation_count > 0) {
                u += n("friends", s.recommendation_count, s.recommendation_count == 1 ? Translations.friend : Translations.friends)
            }
            if (s.user_is_superhost > 0) {
                u += n("superhost", "", Translations.superhost)
            }
            if (u) {
                h.find("ul.reputation").append(u)
            }
            */
            i.appendChild(h[0])
        }
    });
    $("#results").toggleClass("mini_prices", c).append(i.cloneNode(true));
    a = $("#map_message");
    if (CogzidelSearch.currentViewType === "map") {
        if (e.properties && e.properties.length == CogzidelSearch.params.per_page || !redoSearchInMapIsChecked()) {
            if (redoSearchInMapIsChecked()) {
                a.html(['<span class="zoom_in_to_see_more_properties">', Translations.zoom_in_to_see_more_properties, "</span>"].join(""))
            } else {
                a.html(["<h3>", Translations.zoom_in_to_see_more_properties, "</h3>", '<span id="redo_search_in_map_tip">', Translations.redo_search_in_map_tip, "</span>"].join(""))
            }
            a.removeClass("tall_message").addClass("short_message").show()
        } else {
            if (!e.properties || e.properties.length === 0) {
                a.html(["<h3>", Translations.your_search_was_too_specific, "</h3>", "<p>", Translations.we_suggest_unchecking_a_couple_filters, "</p>"].join(""));
                a.removeClass("short_message").addClass("tall_message").show()
            } else {
                a.hide()
            }
        }
    } else {
        a.hide()
    }
    AMM.currentBounds = r;
    AMM.clearOverlays(true);
    AMM.showOverlays();
    if (e.properties && e.properties.length > 0 && (CogzidelSearch.results_changed_by_map_action === false || CogzidelSearch.changing_display_type === true) && (!redoSearchInMapIsChecked() || CogzidelSearch.locationHasChanged)) {
        AMM.fitBounds(r)
    }
    if (e.properties && e.properties.length > 0) {
        $("#results_footer").show()
    } else {
        $("#results_footer").show();
        CogzidelSearch.showBlankState();
        AMM.mapLoaded = "first";
        if (time != 1) {
            AMM.initMapOnce("search_map")
        } else {
            time = 2
        }
    }
    hideLoadingOverlays();
    return true
}

function killActiveAjaxRequest() {
    if (CogzidelSearch.activeAjaxRequest) {
        CogzidelSearch.activeAjaxRequest.abort();
        CogzidelSearch.activeAjaxRequest = null;
        hideLoadingOverlays()
    }
}(function(e, t, n) {
    function r(e) {
        e = e || location.href;
        return "#" + e.replace(/^[^#]*#?(.*)$/, "$1")
    }
    var i = "hashchange",
        s = document,
        o, u = e.event.special,
        a = s.documentMode,
        f = "on" + i in t && (a === n || a > 7);
    e.fn[i] = function(e) {
        return e ? this.bind(i, e) : this.trigger(i)
    };
    e.fn[i].delay = 50;
    u[i] = e.extend(u[i], {
        setup: function() {
            if (f) {
                return false
            }
            e(o.start)
        },
        teardown: function() {
            if (f) {
                return false
            }
            e(o.stop)
        }
    });
    o = function() {
        function o() {
            var n = r(),
                s = p(l);
            if (n !== l) {
                h(l = n, s);
                e(t).trigger(i)
            } else {
                if (s !== l) {
                    location.href = location.href.replace(/#.*/, "") + s
                }
            }
            a = setTimeout(o, e.fn[i].delay)
        }
        var u = {},
            a, l = r(),
            c = function(e) {
                return e
            },
            h = c,
            p = c;
        u.start = function() {
            a || o()
        };
        u.stop = function() {
            a && clearTimeout(a);
            a = n
        };
        e.browser.msie && !f && function() {
            var t, n;
            u.start = function() {
                if (!t) {
                    n = e.fn[i].src;
                    n = n && n + r();
                    t = e('<iframe tabindex="-1" title="empty"/>').hide().one("load", function() {
                        n || h(r());
                        o()
                    }).attr("src", n || "javascript:0").insertAfter("body")[0].contentWindow;
                    s.onpropertychange = function() {
                        try {
                            if (event.propertyName === "title") {
                                t.document.title = s.title
                            }
                        } catch (e) {}
                    }
                }
            };
            u.stop = c;
            p = function() {
                return r(t.location.href)
            };
            h = function(n, r) {
                var o = t.document,
                    u = e.fn[i].domain;
                if (n !== r) {
                    o.title = s.title;
                    o.open();
                    u && o.write('<script>document.domain="' + u + '"</script>');
                    o.close();
                    t.location.hash = n
                }
            }
        }();
        return u
    }()
})(jQuery, this);
var Translations = {
    clear_dates: "Clear Dates",
    entire_place: "Entire Place",
    friend: "friend",
    friends: "friends",
    loading: "Loading",
    neighborhoods: "Neighborhoods",
    private_room: "Private Room",
    review: "review",
    reviews: "reviews",
    superhost: "superhost",
    shared_room: "Shared Room",
    today: "Today",
    you_are_here: "You are Here",
    a_friend: "a friend",
    distance_away: "away",
    instant_book: "Instant Book",
    social_connections: "Social Connections",
    show_more: "Show More...",
    learn_more: "Learn More",
    amenities: "Amenities",
    room_type: "Room Type",
    price: "Price",
    keywords: "Keywords",
    property_type: "Property Type",
    bedrooms: "Bedrooms",
    bathrooms: "Bathrooms",
    beds: "Beds",
    languages: "Languages",
    collection: "Collection",
    host: "Host",
    group: "Group",
    connections: "Connections",
    night_writer: "NightWriter",
    redo_search_in_map_tip: '"Redo search in map" must be checked to see new results as you move the map',
    zoom_in_to_see_more_properties: "Zoom in to see more properties",
    your_search_was_too_specific: "Your search was a little too specific.",
    we_suggest_unchecking_a_couple_filters: "We suggest unchecking a couple filters, zooming out, or searching for a different city."
};
var MapIcons = {
    centerPoint: false,
    numbered: [],
    numberedHover: [],
    numberedStarred: [],
    numberedStarredHover: [],
    numberedVisited: [],
    numberedVisitedHover: [],
    numberedVisitedStarred: [],
    numberedVisitedStarredHover: [],
    small: false,
    smallHover: false,
    smallStarred: false,
    smallStarredHover: false,
    smallVisited: false,
    smallVisitedHover: false,
    smallVisitedStarred: false,
    smallVisitedStarredHover: false,
    shadowStandard: false,
    shadowSmall: false,
    shadowCenterPoint: false,
    init: function() {
        MapIcons.centerPoint = new google.maps.MarkerImage(cdn_img_url + "images/map_icons/icon_center_point.png", new google.maps.Size(15, 36), new google.maps.Point(0, 0));
        MapIcons.small = new google.maps.MarkerImage(cdn_img_url + "images/map_icons/small_pins.png", new google.maps.Size(9, 9), new google.maps.Point(0, 0));
        MapIcons.smallHover = new google.maps.MarkerImage(cdn_img_url + "images/map_icons/small_pins.png", new google.maps.Size(9, 9), new google.maps.Point(9, 0));
        MapIcons.smallStarred = new google.maps.MarkerImage(cdn_img_url + "images/map_icons/small_pins.png", new google.maps.Size(9, 9), new google.maps.Point(0, 9));
        MapIcons.smallStarredHover = new google.maps.MarkerImage(cdn_img_url + "images/map_icons/small_pins.png", new google.maps.Size(9, 9), new google.maps.Point(9, 9));
        MapIcons.smallVisited = new google.maps.MarkerImage(cdn_img_url + "images/map_icons/small_pins.png", new google.maps.Size(9, 9), new google.maps.Point(18, 0));
        MapIcons.smallVisitedHover = new google.maps.MarkerImage(cdn_img_url + "images/map_icons/small_pins.png", new google.maps.Size(9, 9), new google.maps.Point(27, 0));
        MapIcons.smallVisitedStarred = new google.maps.MarkerImage(cdn_img_url + "images/map_icons/small_pins.png", new google.maps.Size(9, 9), new google.maps.Point(18, 9));
        MapIcons.smallVisitedStarredHover = new google.maps.MarkerImage(cdn_img_url + "images/map_icons/small_pins.png", new google.maps.Size(9, 9), new google.maps.Point(27, 9));
        for (var e = 0; e < 20; e++) {
            MapIcons.numbered[e + 1] = new google.maps.MarkerImage(cdn_img_url + "images/map_icons/map_pins_sprite_001.png", new google.maps.Size(22, 34), new google.maps.Point(0, e * 34));
            MapIcons.numberedHover[e + 1] = new google.maps.MarkerImage(cdn_img_url + "images/map_icons/map_pins_sprite_001.png", new google.maps.Size(22, 34), new google.maps.Point(44, e * 34));
            MapIcons.numberedStarred[e + 1] = new google.maps.MarkerImage(cdn_img_url + "images/map_icons/map_pins_sprite_001.png", new google.maps.Size(22, 34), new google.maps.Point(22, e * 34));
            MapIcons.numberedStarredHover[e + 1] = new google.maps.MarkerImage(cdn_img_url + "images/map_icons/map_pins_sprite_001.png", new google.maps.Size(22, 34), new google.maps.Point(66, e * 34));
            MapIcons.numberedVisited[e + 1] = new google.maps.MarkerImage(cdn_img_url + "images/map_icons/map_pins_sprite_001.png", new google.maps.Size(22, 34), new google.maps.Point(88, e * 34));
            MapIcons.numberedVisitedHover[e + 1] = new google.maps.MarkerImage(cdn_img_url + "images/map_icons/map_pins_sprite_001.png", new google.maps.Size(22, 34), new google.maps.Point(132, e * 34));
            MapIcons.numberedVisitedStarred[e + 1] = new google.maps.MarkerImage(cdn_img_url + "images/map_icons/map_pins_sprite_001.png", new google.maps.Size(22, 34), new google.maps.Point(110, e * 34));
            MapIcons.numberedVisitedStarredHover[e + 1] = new google.maps.MarkerImage(cdn_img_url + "images/map_icons/map_pins_sprite_001.png", new google.maps.Size(22, 34), new google.maps.Point(154, e * 34))
        }
        MapIcons.shadowCenterPoint = new google.maps.MarkerImage(cdn_img_url + "images/map_icons/icon_center_point_shadow.png", new google.maps.Size(35, 27), new google.maps.Point(0, 0), new google.maps.Point(4, 27));
        MapIcons.shadowSmall = new google.maps.MarkerImage(cdn_img_url + "images/map_icons/icon_small_dot_shadow.png", new google.maps.Size(11, 11), new google.maps.Point(0, 0), new google.maps.Point(5, 9));
        MapIcons.shadowStandard = new google.maps.MarkerImage(cdn_img_url + "images/map_icons/default_shadow.png", new google.maps.Size(33, 26), new google.maps.Point(0, 0), new google.maps.Point(5, 23))
    }
};
var CogzidelSearch = {
    thumbnailRegex: /\/[^\/]*\.jpg$/,
    thumbnailStyles: {
        x_small: [74, 114],
        small: [144, 216]
    },
    hideBannerForRemainderOfSession: false,
    forcedViewType: false,
    code: false,
    eventId: false,
    hostId: false,
    hostName: "",
    forceHideHost: false,
    groupId: false,
    groupName: "",
    forceHideGroup: false,
    isViewingStarred: false,
    collectionId: false,
    collectionName: "",
    forceHideCollection: false,
    viewTypes: {
        1: "list",
        2: "photo",
        3: "map"
    },
    activeAjaxRequest: null,
    loadingMessageTimeout: false,
    newSearch: false,
    currentViewType: "list",
    results_changed_by_map_action: false,
    changing_display_type: false,
    shareLightbox: false,
    params: {},
    currencySymbolLeft: "",
    currencySymbolRight: "",
    initialLoadComplete: false,
    resultsJson: false,
    locationHasChanged: false,
    viewedIds: [],
    updateFacebookBannerText: function() {
        if (CogzidelSearch.params.location) {
            jQuery("#connect_banner .general").hide();
            jQuery("#connect_banner .specific").find("span.place").text(CogzidelSearch.params.location).end().show()
        }
    },
    initFacebookBannerTooltip: function() {
        var e = jQuery("#what_does_this_do_tooltip");
        if (!e.length) {
            return
        }
        var t = jQuery("#connect_banner a.what_does_this_do"),
            n = e.css("width").split("px")[0];
        e.appendTo("body");
        t.hover(function() {
            var r = t.width(),
                i = t.offset(),
                s = i.left + r / 2 - n / 2;
            e.css({
                left: s + "px",
                top: i.top + 23 + "px"
            }).fadeIn("fast")
        }, function() {
            e.fadeOut("fast")
        })
    },
    init: function(e) {
        function t() {
            var e = jQuery(window).scrollTop();
            var t = o.position().top;
            if (e >= a && f < s) {
                if (!o.hasClass("fixed")) {
                    o.addClass("fixed")
                }
                if (f + t >= i && e >= t) {
                    o.css({
                        position: "absolute",
                        top: i - f + 1 + "px"
                    })
                } else {
                    if (o.css("position") === "absolute") {
                        o.css({
                            position: "",
                            top: "0"
                        })
                    }
                }
            } else {
                n()
            }
        }

        function n() {
            if (o.hasClass("fixed")) {
                o.removeClass("fixed")
            }
            if (o.css("position") === "absolute") {
                o.css({
                    position: "",
                    top: "0"
                })
            }
        }

        function r() {
            f = o.height();
            t();
            t()
        }
        CogzidelSearch.viewedIds = CogzidelSearch.getViewedPage3Ids();
        e = e || {};
        if (e.min_bathrooms) {
            $("#min_bathrooms").val(e.min_bathrooms)
        }
        if (e.min_bedrooms) {
            $("#min_bedrooms").val(e.min_bedrooms)
        }
        if (e.min_beds) {
            $("#min_beds").val(e.min_beds)
        }
        if (e.min_bed_type) {
            $("#min_bed_type").val(e.min_bed_type)
        }
        if (e.page) {
            $("#page").val(e.page);
            CogzidelSearch.params.page = e.page
        }
        CogzidelSearch.params.lat = $("#lat").val();
        CogzidelSearch.params.lng = $("#lng").val();
        if (e.sort) {
            CogzidelSearch.params.sort = e.sort
        }
        if (e.neighborhoods) {
            CogzidelSearch.params.neighborhoods = e.neighborhoods
        }
        if (e.hosting_amenities) {
            CogzidelSearch.params.hosting_amenities = e.hosting_amenities
        }
        if (e.room_types) {
            CogzidelSearch.params.room_types = e.room_types
        }
         if (e.instance_book) {
            CogzidelSearch.params.instance_book = e.instance_book
        }
        if (e.property_type_id) {
            CogzidelSearch.params.property_type_id = e.property_type_id
        }
        if (e.connected) {
            CogzidelSearch.params.connected = "true"
        }
        if (e.night_writer) {
            CogzidelSearch.params.night_writer = "true"
        }
        if (e.guests) {
            $("#number_of_guests").val(e.guests);
            CogzidelSearch.params.guests = e.guests
        }
        if (e.price_min) {
            CogzidelSearch.params.price_min = e.price_min
        }
        if (e.price_max) {
            CogzidelSearch.params.price_max = e.price_max
        }
        $("#search_type_toggle").each(function() {
            $(this).delegate(".search_type_option", "click", function() {
                display_search_type(this.id)
            });
            $(this).delegate(".search_type_option", "hover", function(e) {
                $(this).toggleClass("search_type_option_hover", e.type === "mouseenter")
            })
        });
        $("#reinstate_collections").live("click", function() {
            SearchFilters.reinstateCollections();
            $(this).remove()
        });
        $("#reinstate_user").live("click", function() {
            SearchFilters.reinstateHost();
            $(this).remove()
        });
        $("#reinstate_group").live("click", function() {
            SearchFilters.reinstateGroup();
            $(this).remove()
        });
        $("#share_results_link").colorbox({
            inline: true,
            width: 500,
            href: "#share_lightbox",
            onComplete: function() {
                var e = $("#share_url").val([Translations.loading, "..."].join(""));
                CogzidelSearch.setParamsFromDom();
                CogzidelSearch.activeAjaxRequest = $.getJSON(base_url + "search/create", CogzidelSearch.params, function(t) {
                    e.val([base_url + "search?code=", t.search.code].join("")).select()
                })
            }
        });
        $("#keywords").live("keyup", function(e) {
            var t = e.keyCode ? e.keyCode : e.which;
            if (t == 13) {
                var n = jQuery("#keywords");
                if (n.attr("defaultValue") !== n.val()) {
                    $("#search_type_list").trigger("click");
                    CogzidelSearch.loadNewResults()
                }
            }
        });
         $(".close_but").live("click", function(e) {
         	AMM.closeInfoWindow();
         });
        $("#redo_search_in_map_link_on").live("click", function(e) {
            jQuery("#redo_search_in_map").attr("checked", true);
            if (AMM.redoSearchPromptTimeout) {
                clearTimeout(AMM.redoSearchPromptTimeout);
                AMM.redoSearchPromptTimeout = false
            }
            jQuery("#first_time_map_question").fadeOut(500);
            AMM.closeInfoWindow();
            CogzidelSearch.results_changed_by_map_action = true;
            $("#search_type_list").trigger("click");
            CogzidelSearch.loadNewResults();
            return false
        });
        jQuery("#redo_search_in_map_link_off").live("click", function(e) {
            if (AMM.redoSearchPromptTimeout) {
                clearTimeout(AMM.redoSearchPromptTimeout);
                AMM.redoSearchPromptTimeout = false
            }
            jQuery("#first_time_map_question").fadeOut(500);
            return false
        });
        jQuery("#share_url").live("focus", function() {
            jQuery(this).select()
        });
        jQuery(".pagination a").live("click", function() {
            var e = jQuery(this);
            var t = e.html();
            if (e.attr("rel") == "next") {
                t = parseInt(jQuery("div.pagination span.current").html(), 10) + 1
            } else {
                if (e.attr("rel") == "prev") {
                    t = parseInt(jQuery("div.pagination span.current").html(), 10) - 1
                } else {
                    t = parseInt(t, 10)
                }
            }
            if (e.attr("class") == "last") {
                t = e.attr("rel")
            }
            if (isNaN(t) || t < 1) {
                t = 1
            }
            jQuery("#page").val(t);
            $("#search_type_list").trigger("click");
            CogzidelSearch.loadNewResults();
            return false
        });
        var i, s;
        var o = jQuery("#search_filters");
        var u = jQuery("#search_body");
        var a = o.position().top;
        var f = o.height();
        CogzidelSearch.$.bind("finishedrendering", function() {
            a = u.position().top;
            f = o.height();
            s = u.height();
            i = a + s;
            if (s > f && CogzidelSearch.currentViewType !== "map") {
                jQuery(window).scroll(t).scroll();
                CogzidelSearch.$.bind("filtertoggle", r)
            } else {
                jQuery(window).unbind("scroll", t);
                CogzidelSearch.$.unbind("filtertoggle", r);
                n()
            }
        });
        jQuery("#search_filters_toggle").live("click", function() {
            var e = jQuery(this);
            if (e.hasClass("search_filters_toggle_off")) {
                jQuery("#Mab_Big_Main").width(699);
                jQuery("#map_message").width(507);
                jQuery("#search_map").width(2e3)
            } else {
                jQuery("#Mab_Big_Main").width(958);
                jQuery("#search_map").width(2e3);
                jQuery("#map_message").width(752)
            }
            e.toggleClass("search_filters_toggle_on search_filters_toggle_off");
            jQuery("#search_filters").toggle();
            google.maps.event.trigger(AMM.map, "resize")
        });
        $("#results_filters").delegate(".filter_x_container", "click", function() {
            SearchFilters.appliedFilterXCallback(this)
        });
        $.each($(".inner_text"), function(e, t) {
            var n = $(t).next("input");
            var r = n.val();
            n.attr("defaultValue", t.innerHTML);
            n.val(t.innerHTML);
            if (r.length > 0) {
                n.val(r);
                n.addClass("active")
            }
            n.bind("focus", function() {
                if ($(n).val() == n.attr("defaultValue")) {
                    $(n).val("");
                    $(n).addClass("active")
                }
                $(n).removeClass("error");
                return true
            });
            n.bind("blur", function() {
                if ($(n).val() === "") {
                    $(n).removeClass("active");
                    $(n).val(n.attr("defaultValue"))
                } else {
                    $(n).removeClass("error")
                }
            });
            $(t).remove()
        });
        $("#location_label").show();
        if (e.location) {
            $("#location").val(e.location).addClass("active")
        }
        if (CogzidelSearch.initialLoadComplete === false) {
            var l = $.datepicker._defaults.dateFormat;
            var c = {
                minDate: 0,
                maxDate: "+2Y",
                nextText: "",
                prevText: "",
                numberOfMonths: 1,
                closeText: Translations.clear_dates,
                currentText: Translations.today,
                showButtonPanel: true
            };
            var h = jQuery.extend(true, {}, c);
            var p = jQuery.extend(true, {}, c);
            if (typeof e.checkin !== "undefined" && typeof e.checkout !== "undefined" && e.checkin !== l && e.checkout !== l) {
                jQuery("#checkin").val(e.checkin);
                jQuery("#checkout").val(e.checkout);
                h = jQuery.extend(h, {
                    defaultDate: e.checkin
                });
                p = jQuery.extend(p, {
                    defaultdate: e.checkout
                })
            } else {
                jQuery("#checkin").val("Check In");
                jQuery("#checkout").val("Check Out");
                jQuery("#search_inputs").css("background-color", "#ffe75f")
            }
            jQuery("#search_form").cogzidelInputDateSpan({
                defaultsCheckin: h,
                defaultsCheckout: p,
                onSuccess: function() {
                    $("#search_type_list").trigger("click");
                    CogzidelSearch.loadNewResults()
                }
            });
            jQuery("ul.collapsable li input:button, ul#lightbox_filters input:button").live("click", function() {
                var e = false;
                var t = jQuery(this).attr("id");
                var n = jQuery(this).attr("name");
                var r = jQuery(this).attr("value");
                if (t.indexOf("lightbox") === -1) {
                    t = ["#lightbox_", t].join("");
                    e = true
                } else {
                    t = ["#", t.replace("lightbox_", "")].join("")
                }
                if (jQuery(t)) {
                    if (jQuery(this).is(":checked")) {
                        jQuery(['input:checkbox[name="', n, '"][value="', r, '"]'].join("")).attr("checked", true)
                    } else {
                        jQuery(['input:checkbox[name="', n, '"][value="', r, '"]'].join("")).attr("checked", false)
                    }
                }
                if (e === true) {
                    $("#search_type_list").trigger("click");
                    CogzidelSearch.loadNewResults()
                }
            });
            jQuery("ul.collapsable_filters li input:checkbox, ul#lightbox_filters input:checkbox").live("click", function() {
                var e = false;
                var t = jQuery(this).attr("id");
                var n = jQuery(this).attr("name");
                var r = jQuery(this).attr("value");
                if (t.indexOf("lightbox") === -1) {
                    t = ["#lightbox_", t].join("");
                    e = true
                } else {
                    t = ["#", t.replace("lightbox_", "")].join("")
                }
                if (jQuery(t)) {
                    if (jQuery(this).is(":checked")) {
                        jQuery(['input:checkbox[name="', n, '"][value="', r, '"]'].join("")).attr("checked", true)
                    } else {
                        jQuery(['input:checkbox[name="', n, '"][value="', r, '"]'].join("")).attr("checked", false)
                    }
                }
                if (e === true) {
                    $("#search_type_list").trigger("click");
                    CogzidelSearch.loadNewResults()
                }
            });
            jQuery("a.show_more_link").live("click", function(e) {
                $("#search_type_list").trigger("click");
                SearchFilters.openFiltersLightbox();
                var t = jQuery(this).closest(".search_filter").attr("id").replace("_container", "");
                SearchFilters.selectLightboxTab(t)
            });
            jQuery("#min_bathrooms").live("change", function() {
                $.colorbox.close();
                $("#search_type_list").trigger("click");
                CogzidelSearch.loadNewResults()
            });
            jQuery("#min_beds").live("change", function() {
                $.colorbox.close();
                $("#search_type_list").trigger("click");
                CogzidelSearch.loadNewResults()
            });
            jQuery(".filters_lightbox_nav_element").live("click", function() {
                $("#search_type_list").trigger("click");
                var e = jQuery(this).attr("id").replace("lightbox_nav_", "");
                SearchFilters.selectLightboxTab(e)
            });
            jQuery("#min_bedrooms").change(function() {
                $("#search_type_list").trigger("click");
                CogzidelSearch.loadNewResults()
            });
            jQuery("#number_of_guests").change(function() {
                $("#search_type_list").trigger("click");
                CogzidelSearch.loadNewResults()
            });
            jQuery("#lightbox_filter_content_property_type_id .clearfix_type input:checkbox").live("click", function() {
                var e = false;
                var t = jQuery(this).attr("id");
                var n = jQuery(this).attr("name");
                var r = jQuery(this).attr("value");
                $("#search_type_list").trigger("click");
                CogzidelSearch.loadNewResults()
            });
            jQuery("#lightbox_filter_content_property_type_id input:checkbox").live("click", function() {
                var e = false;
                var t = jQuery(this).attr("id");
                var n = jQuery(this).attr("name");
                var r = jQuery(this).attr("value");
                $("#search_type_list").trigger("click");
                CogzidelSearch.loadNewResults()
            });
            jQuery("#lightbox_container_amenities input:checkbox").live("click", function() {
                var e = false;
                var t = jQuery(this).attr("id");
                var n = jQuery(this).attr("name");
                var r = jQuery(this).attr("value");
                $("#search_type_list").trigger("click");
                CogzidelSearch.loadNewResults()
            });
            jQuery("a.filter_header, a.filter_toggle").live("click", function() {
                jQuery(this).closest(".search_filter").toggleClass("closed open");
                CogzidelSearch.$.trigger("filtertoggle")
            });
            jQuery(".search_result").live("mouseenter", function(e) {
                CogzidelSearch.hoverListResult(e.currentTarget.id.split("_")[1])
            });
            jQuery(".search_result").live("mouseleave", function(e) {
                CogzidelSearch.unHoverListResult(e.currentTarget.id.split("_")[1])
            });
            jQuery("#slider-range").slider({
                range: true,
                min: min_price,
                max: max_price,
                step: 5,
                values: [6, 100],
                slide: function(e, t) {
                    SearchFilters.applyPriceSliderChanges(t)
                },
                change: function(e) {
                    if (e && e.originalEvent && e.originalEvent.type === "mouseup") {
                        $("#search_type_list").trigger("click");
                        CogzidelSearch.loadNewResults()
                    }
                }
            });
            SearchFilters.applyPriceSliderChanges();
            if (window.google && window.google.maps && e.search_by_map && e.ne_lng && e.ne_lat && e.sw_lng && e.sw_lat) {
                AMM.initMapOnce("search_map");
                var d = {
                    sw_lat: e.sw_lat,
                    sw_lng: e.sw_lng,
                    ne_lat: e.ne_lat,
                    ne_lng: e.ne_lng
                };
                CogzidelSearch.params.forceBounds = d;
                var v = new google.maps.LatLng(d.sw_lat, d.sw_lng);
                var m = new google.maps.LatLng(d.ne_lat, d.ne_lng);
                AMM.fitBounds(new google.maps.LatLngBounds(v, m));
                jQuery("#redo_search_in_map").attr("checked", true);
                CogzidelSearch.params = e
            }
            $("#search_type_list").trigger("click");
            CogzidelSearch.loadNewResults(true);
            CogzidelSearch.params = e;
            jQuery("#redo_search_in_map").bind("change", function() {
                if (AMM.redoSearchPromptTimeout) {
                    clearTimeout(AMM.redoSearchPromptTimeout);
                    AMM.redoSearchPromptTimeout = false;
                    jQuery("#first_time_map_question").fadeOut(250)
                }
                if (redoSearchInMapIsChecked()) {
                    AMM.closeInfoWindow();
                    CogzidelSearch.results_changed_by_map_action = true;
                    $("#search_type_list").trigger("click");
                    CogzidelSearch.loadNewResults()
                } else {
                    AMM.turnMapListenersOff()
                }
            })
        }
        CogzidelSearch.initFacebookBannerTooltip()
    },
    performNewSearch: function() {
        return CogzidelSearch.newSearch || CogzidelSearch.initialLoadComplete
    },
    searchHasBeenModified: function() {
        try {
            var e = window.location.hash;
            if (e) {
                var t = e.split("#")[1].split("&")[0].split("modified=")[1];
                if (t === "true") {
                    return true
                }
            }
        } catch (n) {}
        return false
    },
    setParamsFromDom: function() {
        var e;
        var t = CogzidelSearch.params;
        CogzidelSearch.params = {};
        if (CogzidelSearch.initialLoadComplete === false && CogzidelSearch.code && CogzidelSearch.searchHasBeenModified() === false) {
            CogzidelSearch.params.code = CogzidelSearch.code
        }
        if (CogzidelSearch.eventId && CogzidelSearch.searchHasBeenModified() === false) {
            CogzidelSearch.params.event_id = CogzidelSearch.eventId
        }
        if (CogzidelSearch.performNewSearch()) {
            CogzidelSearch.params.new_search = true
        }
        AMM.new_bounds = AMM.mapLoaded ? AMM.map.getBounds() || false : false;
        switch (CogzidelSearch.currentViewType) {
            case "list":
                CogzidelSearch.params.search_view = 1;
                break;
            case "photo":
                CogzidelSearch.params.search_view = 2;
                break;
            case "map":
                CogzidelSearch.params.search_view = 3;
                break;
            default:
                CogzidelSearch.params.search_view = 1
        }
        CogzidelSearch.params.min_bedrooms = $("#min_bedrooms").val() || "0";
        CogzidelSearch.params.min_bathrooms = $("#min_bathrooms").val() || "0";
        CogzidelSearch.params.min_beds = $("#min_beds").val() || "0";
        CogzidelSearch.params.min_bed_type = $("#min_bed_type").val() || "0";
        CogzidelSearch.params.page = $("#page").val() || "1";
        var n = jQuery("#location");
        var r = n.val();
        if (r !== n.attr("defaultValue")) {
            CogzidelSearch.params.location = r || ""
        }
        if (!t || !t.location || t.location != CogzidelSearch.params.location) {
            CogzidelSearch.locationHasChanged = true;
            CogzidelSearch.hideBannerForRemainderOfSession = false
        }
        if (CogzidelSearch.includeCollectionParam()) {
            CogzidelSearch.params.collection_id = CogzidelSearch.collectionId
        } else {
            SearchFilters.clearCollections(false)
        }
        if (CogzidelSearch.includeHostParam()) {
            CogzidelSearch.params.host_id = CogzidelSearch.hostId
        } else {
            SearchFilters.clearHost()
        }
        if (CogzidelSearch.includeGroupParam()) {
            CogzidelSearch.params.group_id = CogzidelSearch.groupId
        } else {
            SearchFilters.clearGroup()
        }
        var i = $("#checkin").val();
        var s = $("#checkout").val();
        if (i != "mm/dd/yyyy") {
            CogzidelSearch.params.checkin = jQuery("#checkin").val() || ""
        }
        if (s != "mm/dd/yyyy") {
            CogzidelSearch.params.checkout = jQuery("#checkout").val() || ""
        }
        CogzidelSearch.params.guests = jQuery("#number_of_guests").val() || "1";
        CogzidelSearch.params.room_types = [];
        jQuery("input[name='room_types']").each(function(e, t) {
            if (jQuery(t).is(":checked")) {
                CogzidelSearch.params.room_types.push(jQuery(t).val())
            }
        });
        //
        CogzidelSearch.params.instance_book = [];
        jQuery("input[name='instance_book']").each(function(e, t) {
            if (jQuery(t).is(":checked")) {
                CogzidelSearch.params.instance_book.push(jQuery(t).val())
            }
        });
        //
        CogzidelSearch.params.property_type_id = [];
        jQuery("input[name='property_type_id']").each(function(e, t) {
            if (jQuery(t).is(":checked")) {
                CogzidelSearch.params.property_type_id.push(jQuery(t).val())
            }
        });
        CogzidelSearch.params.hosting_amenities = [];
        jQuery("input[name='amenities']").each(function(e, t) {
            if (jQuery(t).is(":checked")) {
                CogzidelSearch.params.hosting_amenities.push(jQuery(t).val())
            }
        });
        if (CogzidelSearch.isViewingStarred) {
            CogzidelSearch.params.starred = true
        }
        if (jQuery("input[name='connected']").is(":checked")) {
            CogzidelSearch.params.connected = true
        }
        if (jQuery("input[name='night_writer']").is(":checked")) {
            CogzidelSearch.params.night_writer = true
        }
        CogzidelSearch.params.languages = [];
        jQuery("input[name='languages']").each(function(e, t) {
            if (jQuery(t).is(":checked")) {
                CogzidelSearch.params.languages.push(jQuery(t).val())
            }
        });
        CogzidelSearch.params.neighborhoods = [];
        if (CogzidelSearch.initialLoadComplete === false) {
            e = ["neighborhoods", "room_types", "instance_book", "min_bedrooms", "price_min", "price_max", "guests", "property_type_id", "connected"];
            jQuery(e).each(function(e, n) {
                if (t[n]) {
                    CogzidelSearch.params[n] = t[n]
                }
            })
        }
        CogzidelSearch.params.hosting_amenities = CogzidelSearch.params.hosting_amenities.unique();
        CogzidelSearch.params.neighborhoods = CogzidelSearch.params.neighborhoods.unique();
        CogzidelSearch.params.room_types = CogzidelSearch.params.room_types.unique();
         CogzidelSearch.params.instance_book = CogzidelSearch.params.instance_book.unique();
        var o = jQuery("#keywords");
        if (o.attr("defaultValue") !== o.val()) {
            CogzidelSearch.params.keywords = o.val()
        }
        var u = $("#slider-range");
        var a = u.slider("option", "min");
        var f = u.slider("option", "max");
        var l = u.slider("values", 0);
        var c = u.slider("values", 1);
        if (f !== c || a !== l) {
            CogzidelSearch.params.price_min = l;
            CogzidelSearch.params.price_max = c
        }
        if (redoSearchInMapIsChecked()) {
            if (AMM.new_bounds && (!CogzidelSearch.locationHasChanged || CogzidelSearch.results_changed_by_map_action)) {
                CogzidelSearch.params.sw_lat = AMM.new_bounds.getSouthWest().lat();
                CogzidelSearch.params.sw_lng = AMM.new_bounds.getSouthWest().lng();
                CogzidelSearch.params.ne_lat = AMM.new_bounds.getNorthEast().lat();
                CogzidelSearch.params.ne_lng = AMM.new_bounds.getNorthEast().lng();
                CogzidelSearch.params.search_by_map = true
            } else {
                if (t && t.forceBounds) {
                    CogzidelSearch.params.sw_lat = t.forceBounds.sw_lat;
                    CogzidelSearch.params.sw_lng = t.forceBounds.sw_lng;
                    CogzidelSearch.params.ne_lat = t.forceBounds.ne_lat;
                    CogzidelSearch.params.ne_lng = t.forceBounds.ne_lng;
                    var h = new google.maps.LatLng(t.forceBounds.sw_lat, t.forceBounds.sw_lng);
                    var p = new google.maps.LatLng(t.forceBounds.ne_lat, t.forceBounds.ne_lng);
                    AMM.new_bounds = new google.maps.LatLngBounds(h, p);
                    AMM.fitBounds(AMM.new_bounds);
                    CogzidelSearch.params.search_by_map = true
                }
            }
        }
        if (CogzidelSearch.currentViewType === "photo") {
            CogzidelSearch.params.per_page = 20
        } else {
            if (CogzidelSearch.currentViewType === "list") {
                CogzidelSearch.params.per_page = 20
            } else {
                if (CogzidelSearch.currentViewType === "map") {
                    CogzidelSearch.params.per_page = 40
                }
            }
        }
        return CogzidelSearch.params
    },
    markUrlAsModified: function() {
        try {
            window.location.hash = "modified=true"
        } catch (e) {}
    },
    loadNewResultsWithNoResponse: function() {
        CogzidelSearch.setParamsFromDom();
        CogzidelSearch.params.suppress_response = true;
        CogzidelSearch.markUrlAsModified();
        CogzidelSearch.activeAjaxRequest = jQuery.getJSON(base_url + "search/ajax_get_results", CogzidelSearch.params, function(e) {
            CogzidelSearch.params.suppress_response = false
        })
    },
    loadNewResultsCallback: function(e) {
    	jQuery("#search_body").css("opacity","1");
        if (!e) {
            CogzidelSearch.resultsJson = false;
            hideLoadingOverlays();
            CogzidelSearch.trackSearch();
            return false
        }
        CogzidelSearch.resultsJson = e;
        if (render_results(e, AMM.new_bounds)) {
            if (e.params) {
                SearchFilters.update(e.params)
            } else {
                if (e.facets) {
                    SearchFilters.update(e.facets)
                }
            }
            if (SearchFilters.per_month !== e.per_month) {
                SearchFilters.per_month = e.per_month;
                var t = SearchFilters.per_month === true && (CogzidelSearch.params.price_min < SearchFilters.minPriceMonthly || CogzidelSearch.params.price_max < SearchFilters.minPriceMonthly);
                var n = SearchFilters.per_month !== true && (CogzidelSearch.params.price_min > SearchFilters.maxPrice || CogzidelSearch.params.price_max > SearchFilters.maxPrice);
                var r = t || n;
                if (r) {
                    jQuery("#applied_filter_price").remove();
                    if (jQuery("#applied_filters").html() === "") {
                        jQuery("#results_filters").hide()
                    }
                    SearchFilters.setPriceSliderLimits(SearchFilters.per_month, true)
                } else {
                    SearchFilters.setPriceSliderLimits(SearchFilters.per_month, false)
                }
            }
            render_results_oncomplete(e)
        }
    },
    loadNewResults: function(e) {
        if (CogzidelSearch.initialLoadComplete === true) {
            CogzidelSearch.markUrlAsModified()
        }
        var t = e || false;
        AMM.initMapOnce("search_map");
        if (CogzidelSearch.results_changed_by_map_action === true && !redoSearchInMapIsChecked() && t === false) {
            reset_params_to_defaults();
            setTimeout(function() {
                AMM.turnMapListenersOn()
            }, 1e3);
            return true
        }
        killActiveAjaxRequest();
        var n = jQuery("#search_header").is(":visible");
        var r = jQuery(window).scrollTop();
        if (n === true && r > 275 || n === false && r > 129) {
/*
            jQuery("html, body").animate({
                scrollTop: jQuery("#Selsearch_params").offset().top
            }, "fast")
*/
        }
        jQuery("#search_body").css("opacity","0.2");
        
        showLoadingOverlays();
        CogzidelSearch.setParamsFromDom();
        CogzidelSearch.activeAjaxRequest = jQuery.getJSON(base_url + "search/ajax_get_results", CogzidelSearch.params, CogzidelSearch.loadNewResultsCallback);
        if (CogzidelSearch.isViewingStarred) {
            jQuery("#share_results_link").hide()
        } else {
            jQuery("#share_results_link").show()
        }
        return true
    },
    hoverListResult: function(e) {
        var t;
        var n = ["#room_", e].join("");
        var r = AMM.markers[e];
        jQuery(n).addClass("hover");
        if (SS.initialized === true) {
            SS.show(n, e)
        }
        if (r) {
            if (r.numbered_pin !== false) {
                if (jQuery.inArray(e.toString(), CogzidelSearch.viewedIds) !== -1) {
                    t = MapIcons.numberedVisitedHover[r.numbered_pin + 1]
                } else {
                    t = MapIcons.numberedHover[r.numbered_pin + 1]
                }
                r.marker.setIcon(t)
            }
        }
    },
    unHoverListResult: function(e) {
        var t;
        var n = AMM.markers[e];
        jQuery(["#room_", e].join("")).removeClass("hover");
        if (SS.initialized === true) {
            SS.reset()
        }
        if (n) {
            if (n.numbered_pin !== false) {
                if (jQuery.inArray(e.toString(), CogzidelSearch.viewedIds) !== -1) {
                    t = MapIcons.numberedVisited[n.numbered_pin + 1]
                } else {
                    t = MapIcons.numbered[n.numbered_pin + 1]
                }
                n.marker.setIcon(t)
            }
        }
    },
    is_map_search: function() {
        return CogzidelSearch.results_changed_by_map_action && !CogzidelSearch.changing_display_type
    },
    showBlankState: function() {
        jQuery("#results").html(jQuery("#blank_state_content").html())
    },
    markViewedPageLinks: function() {
        if (CogzidelSearch.viewedIds === false || CogzidelSearch.viewedIds.size === 0) {
            return
        }
        jQuery("#results .search_result").each(function(e, t) {
            t = jQuery(t);
            var n = t.attr("id").replace("room_", "");
            if (jQuery.inArray(n, CogzidelSearch.viewedIds) !== -1) {
                t.addClass("visited")
            }
        })
    },
    getViewedPage3Ids: function() {
        var e = jQuery.cookie("viewed_page3_ids");
        if (e !== null) {
            var t = e.split(",");
            t = t.unique();
            return t
        }
        return []
    },
    trackSearch: function() {
        TrackingPixel.track()
    },
    presentStandbyOption: function() {
        $("#standby_action_area").show()
    },
    hideStandbyOption: function() {
        $("#standby_action_area").hide()
    },
    includeCollectionParam: function() {
        return CogzidelSearch.collectionId !== false && CogzidelSearch.forceHideCollection === false && (CogzidelSearch.searchHasBeenModified() === false || !CogzidelSearch.params.location || CogzidelSearch.params.location === "")
    },
    includeHostParam: function() {
        return CogzidelSearch.hostId !== false && CogzidelSearch.forceHideHost === false && (CogzidelSearch.searchHasBeenModified() === false || !CogzidelSearch.params.location || CogzidelSearch.params.location === "")
    },
    includeGroupParam: function() {
        return CogzidelSearch.groupId !== false && CogzidelSearch.forceHideGroup === false && (CogzidelSearch.searchHasBeenModified() === false || !CogzidelSearch.params.location || CogzidelSearch.params.location === "")
    },
    $: jQuery(this)
};
var TrackingPixel = {
    params: {
        uuid: "",
        user: "",
        af: "",
        c: "",
        pg: "",
        checkin: "",
        ngt: "",
        gc: "1",
        bc: "",
        lat: "",
        lng: ""
    },
    imgId: "#page2_v3_tp",
    BASE_URL: "http://pluto.cogzidel.com/t/t.php?",
    updateParamsFromDom: function() {
        if (AMM.map) {
            var e = AMM.map.getCenter();
            TrackingPixel.params.lat = e.lat();
            TrackingPixel.params.lng = e.lng()
        }
        var t = jQuery("#checkin").val();
        var n = jQuery("#checkout").val();
        if (t == "mm/dd/yyyy" || n == "mm/dd/yyyy") {
            TrackingPixel.params.checkin = "";
            TrackingPixel.params.checkout = ""
        } else {
            TrackingPixel.params.checkin = t;
            var r = new Date(t);
            var i = new Date(n);
            var s = 864e5;
            var o = Math.abs(r.getTime() - i.getTime());
            var u = Math.round(o / s);
            TrackingPixel.params.ngt = u || ""
        }
        TrackingPixel.params.gc = jQuery("#number_of_guests").val() || "1"
    },
    serializedParams: function() {
        return jQuery.param(TrackingPixel.params)
    },
    updateImgSrc: function() {
        var e = jQuery(TrackingPixel.imgId);
        if (e) {
            var t = [TrackingPixel.BASE_URL, TrackingPixel.serializedParams()].join("");
            e.attr("src", t)
        }
    },
    track: function() {
        TrackingPixel.updateParamsFromDom();
        TrackingPixel.updateImgSrc()
    }
};
var AMM = {
    map: "",
    isFirstMapInteraction: true,
    redoSearchPromptTimeout: false,
    overlay: false,
    mapLoaded: false,
    new_bounds: false,
    currentBounds: false,
    currentHighestZIndex: 0,
    activeInfoWindow: null,
    activeInfoWindowMarker: false,
    queue: [],
    activeHostingIds: [],
    markers: {},
    defaultMapOptions: {},
    centerLat: false,
    centerLng: false,
    centerMarker: false,
    geocodePrecision: false,
    initMapOnce: function(e) {
       /* if (AMM.mapLoaded == "first") {
            lat = $("#lat").val();
            lng = $("#lng").val();
            AMM.defaultMapOptions = {
                zoom: 6,
                center: new google.maps.LatLng(lat, lng),
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                disableDefaultUI: true,
                navigationControl: true,
                navigationControlOptions: {
                    position: google.maps.ControlPosition.LEFT
                },
                scaleControl: true,
                scrollwheel: false
            };
            AMM.map = new google.maps.Map(document.getElementById(e), AMM.defaultMapOptions);
            AMM.overlay = new google.maps.OverlayView;
            AMM.overlay.draw = function() {};
            AMM.overlay.setMap(AMM.map);
            MapIcons.init();
            AMM.mapLoaded = true
        }*/
        if (AMM.mapLoaded === false) {
            if (window.google && window.google.maps) {
                $("#map_options").show();
                $("#map_wrapper").show();
                AMM.defaultMapOptions = {
                    zoom: 6,
                    center: new google.maps.LatLng(lat, lng),
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    disableDefaultUI: false,
                    mapTypeControl: false,
                    streetViewControl: false,
                    navigationControl: false,
                    rotateControl: false,
                    panControl: false,
                    zoomControlOptions: {
                        style: google.maps.ZoomControlStyle.SMALL,
                        position: google.maps.ControlPosition.LEFT_TOP
                    },
                    navigationControlOptions: {
                        position: google.maps.ControlPosition.LEFT
                    },
                    scaleControl: false,
                    scrollwheel: true
                };
                AMM.map = new google.maps.Map(document.getElementById(e), AMM.defaultMapOptions);
                AMM.overlay = new google.maps.OverlayView;
                AMM.overlay.draw = function() {};
                AMM.overlay.setMap(AMM.map);
                MapIcons.init();
                AMM.mapLoaded = true
            } else {
                $("#map_options").hide();
                $("#map_wrapper").hide()
            }
        }
    },
    add: function(e, t) {
        if (!AMM.markers[t.id]) {
            AMM.markers[t.id] = {
                location: e,
                details: t,
                active: false
            }
        }
        AMM.queue.push(t.id)
    },
    drawCenterMarker: function() {
        AMM.clearCenterMarker();
        if (AMM.mapLoaded && AMM.centerLat && AMM.centerLng) {
            var e = 1;
            if (AMM.geocodePrecision) {
                if (AMM.geocodePrecision == "address") {
                    e = 100
                }
            }
            var t = new google.maps.LatLng(AMM.centerLat, AMM.centerLng);
            var n = new google.maps.Marker({
                position: t,
                map: AMM.map,
                icon: MapIcons.centerPoint,
                shadow: MapIcons.shadowCenterPoint,
                title: Translations.you_are_here,
                zIndex: e
            });
            AMM.centerMarker = n;
            var r = AMM.currentBounds;
            if (r === false) {
                r = new google.maps.LatLngBounds
            }
            r.extend(t)
        }
    },
    clearCenterMarker: function() {
        if (AMM.centerMarker !== false) {
            AMM.centerMarker.setMap(null);
            AMM.centerMarker = false
        }
    },
    clearOverlays: function(e) {
        if (AMM.markers) {
            jQuery.each(AMM.markers, function(t, n) {
                if (jQuery.inArray(parseInt(t, 10), AMM.queue) === -1 || e === true) {
                    AMM.removeOverlay(t)
                }
            })
        }
    },
    openInfoWindow: function(e, t, n) {
        var r = AMM.activeInfoWindow;
        AMM.activeInfoWindowMarker = t;
        if (r) {
            r.setContent(e);
            r.open(AMM.map, t)
        } else {
            r = AMM.activeInfoWindow = new google.maps.InfoWindow({
                content: e,
                maxWidth: 300
            });
            google.maps.event.addListenerOnce(r, "closeclick", function() {
                r = AMM.activeInfoWindow = AMM.activeInfoWindowMarker = null
            });
            google.maps.event.addListener(r, 'domready', function(){
 $("#slider1").responsiveSlides({
       						 auto: false,
        					 pager: false,
      						 nav: true,
        					 speed: 500,
       						 namespace: "callbacks",
       						 prevText: "<i class='fa fa-angle-left slider-arrow-left' style='display:none;'></i>",
       						 nextText: "<i class='fa fa-angle-right slider-arrow-right' style='display:none;'></i>",
      						 before: function () {
         					 $('.events').append("<li>before event fired.</li>");
       						 },
      						  after: function () {
        						}
     						 });
});
            r.open(AMM.map, t)
        }
        if (SS.initialized === true) {
            SS.reset();
            google.maps.event.addListenerOnce(r, "domready", function() {
                if (typeof SS.pictureArrays[n] !== "undefined") {
                    jQuery(".map_info_window").find("img").attr("src", SS.fullImageUrl(SS.pictureArrays[n][0]))
                }
                SS.show(".map_info_window", n)
            })
        }
    },
    openInfoWindow1: function(e, t, n) {
    	
        var r = AMM.activeInfoWindow;
        AMM.activeInfoWindowMarker = t;
        if (r) {
            r.setContent(e);
            google.maps.event.addListener(r, 'domready', function(){
 					$("#slider"+n).responsiveSlides({
       						 auto: false,
        					 pager: false,
      						 nav: true,
        					 speed: 500,
       						 namespace: "callbacks",
       						 prevText: "<i class='fa fa-angle-left slider-arrow-left' style='display:none;'></i>",
       						 nextText: "<i class='fa fa-angle-right slider-arrow-right' style='display:none;'></i>",
      						 before: function () {
         					 $('.events').append("<li>before event fired.</li>");
       						 },
      						  after: function () {
        						}
     						 });
});
            r.open(AMM.map, t);
        } else {
            r = AMM.activeInfoWindow = new google.maps.InfoWindow({
                content: e,
                maxWidth: 300,
                maxHeight: 40
            });
            google.maps.event.addListenerOnce(r, "closeclick", function() {
                r = AMM.activeInfoWindow = AMM.activeInfoWindowMarker = null
            });
            google.maps.event.addListener(r, 'domready', function(){
 					$("#slider"+n).responsiveSlides({
       						 auto: false,
        					 pager: false,
      						 nav: true,
        					 speed: 500,
       						 namespace: "callbacks",
       						 prevText: "<i class='fa fa-angle-left slider-arrow-left' style='display:none;'></i>",
       						 nextText: "<i class='fa fa-angle-right slider-arrow-right' style='display:none;'></i>",
      						 before: function () {
         					 $('.events').append("<li>before event fired.</li>");
       						 },
      						  after: function () {
        						}
     						 });
});
            r.open(AMM.map, t)
        }
        if (SS.initialized === true) {
            SS.reset();
            google.maps.event.addListenerOnce(r, "domready", function() {
                if (typeof SS.pictureArrays[n] !== "undefined") {
                    jQuery(".map_info_window").find("img").attr("src", SS.fullImageUrl(SS.pictureArrays[n][0]))
                }
                SS.show(".map_info_window", n)
            })
        }
    },
    closeInfoWindow: function() {
        if (AMM.activeInfoWindow) {
            google.maps.event.clearInstanceListeners(AMM.activeInfoWindow);
            AMM.activeInfoWindow.close();
            AMM.activeInfoWindow = AMM.activeInfoWindowMarker = null;
            if (SS.initialized === true) {
                SS.hide()
            }
            return true
        } else {
            return false
        }
    },
    removeOverlay: function(e) {
        var t = AMM.markers[e];
        if (t.active === true) {
            if (t.infoWindow) {
                google.maps.event.clearInstanceListeners(t.infoWindow);
                t.infoWindow = null
            }
            google.maps.event.clearInstanceListeners(t.marker);
            t.marker.setMap(null);
            t.marker = null;
            t.active = false
        }
    },
    showOverlays: function() {
        var e = 20;
        var t = AMM.queue.length;
        jQuery.each(AMM.queue, function(n, r) {
            var i, s, o, u;
            var a = AMM.markers[r];
            if (a && !a.active) {
                i = a.details;
                u = jQuery.inArray(r.toString(), CogzidelSearch.viewedIds) !== -1;
                if (n < e) {
                    if (u) {
                        s = MapIcons.numberedVisited[n + 1]
                    } else {
                        s = MapIcons.numbered[n + 1]
                    }
                    a.numbered_pin = n;
                    o = new google.maps.Marker({
                        position: a.location,
                        map: AMM.map,
                        icon: s,
                        shadow: MapIcons.shadowStandard,
                        title: [n + 1, ". ", i.name].join(""),
                        zIndex: t - n
                    })
                } else {
                    if (u) {
                        s = MapIcons.smallVisited
                    } else {
                        s = MapIcons.small
                    }
                    a.numbered_pin = false;
                    o = new google.maps.Marker({
                        position: a.location,
                        map: AMM.map,
                        icon: s,
                        shadow: MapIcons.shadowSmall,
                        title: i.name,
                        zIndex: t - n
                    })
                }
                if (CogzidelSearch.currentViewType === "map") {
                    var f = i.review_count === 1 ? Translations.review : Translations.reviews;
                    var l = i.smallThumbnail;
                    if (l == undefined) {
                        l = cdn_img_url + "images/no_image.jpg"
                    }
                    var c = ['<div class="map_info_window">', '<a class="map_info_window_link_image" href="' + base_url + "rooms/", r, '" />', '<img width="210" height="140" class="map_info_window_thumbnail" src="', l, '" />', "</a>", '<p class="map_info_window_details">', '<a class="map_info_window_link" href="' + base_url + "rooms/", r, '" />', i.name, "</a>", '<span class="map_info_window_review_count">', i.review_count, " ", f, "</span>", '<span class="map_info_window_price">', i.symbol, i.price, CogzidelSearch.currencySymbolRight, "</span>", "</p>", "</div>"].join("");
                    google.maps.event.addListener(o, "click", function(e) {
                        AMM.openInfoWindow(c, o, r)
                    })
                } else {
                    google.maps.event.addListener(o, "mouseover", function() {
                        CogzidelSearch.hoverListResult(r)
                    });
                    google.maps.event.addListener(o, "mouseout", function() {
                        CogzidelSearch.unHoverListResult(r)
                    });
                    google.maps.event.addListener(o, "click", function() {
                        var e = i.smallThumbnail;
                        if (e == undefined) {
                            e = cdn_img_url + "images/no_image.jpg"
                        }
                        
                        if(i.city == '')
                        var address = i.city;
                        else
                        var address = i.state;
                        
                        if(i.short_listed == 1)
                        {
                        var wishlist_img = cdn_img_url+'images/heart_rose.png';
                        }
                        else
                        {
                        var wishlist_img = cdn_img_url+'images/search_heart_hover.png';
                        }
                                              
                     var slider_html = '<div class="listing-slideshow"><ul id="slider'+r+'" class="rslides" style="overflow: visible ! important;">';
                      $.each(i.picture_ids,function(key,value)
                      {
                      	slider_html += '<li data="'+r+'"><img height="144" width="216" style="position: absolute; top: 0px; left: 0px; z-index: 2; opacity: 1;height:175px !important" src="'+cdn_img_url+'images/'+value+'" alt="" data="'+r+'"><div class="panel-overlay-bottom-left panel-overlay-label panel-overlay-listing-label" style=""><span class="h3 price-amount" style="font-size:24px;">'+i.price+'</span> <span class="h6 text-contrast" style="font-size:14px;top: -0.5em;font-weight:bold;">'+i.currency_code+'</span>    </div></li>';
                      	
                      })
                      slider_html += '</ul></div><a class="photo-paginate prev" href="javascript:void(0);"><i></i></a><a class="photo-paginate next" href="javascript:void(0);"><i></i></a>';
                                  
                        var t = ['<div class="infoBox" style="position: absolute; visibility: visible; z-index: 330; width: 280px; left: -18.698px; bottom: -13.217px;"><button class="close close_but">×</button><div class="listing-map-popover" style="left: 497.302px; top: 200.217px;"><div class=""><div class="panel-image listing-img img-large"> ',slider_html,' <div class="panel-overlay-top-right wl-social-connection-panel"> <span class="rich-toggle wish_list_button wishlist-button saved"> <label for="">  <a style="background-image:url('+wishlist_img+');z-index:3;background-repeat: no-repeat;left: 276px;padding: 16px;top:10px;" class="search_heart_normal search_heart" id="'+r+'" style="position: absolute;cursor:pointer;cursor: hand;" id="my_shortlist" onclick="add_shortlist('+r+',this);" href="#map_view"> </a>     </label>      </span>    </div>  </div>  <div class="panel-body panel-card-section">     <div class="media">   <a class="text-normal" title="'+i.name+'" href="'+base_url+'rooms/'+r+'" style="">         <div class="h5 listing-name text-truncate row-space-top-1">'+i.name+'</div> </a><a style="text-decoration:none;" href="'+base_url+'rooms/'+r+'">       <div style="color: #82888a;" class="text-muted listing-location text-truncate">'+i.room_type+' - '+i.review_count+' reviews - '+address+' </div> </a> </div>   </div> </div> </div></div>'].join(""); 
                        AMM.openInfoWindow1(t, o, r)
                    })
                }
                a.marker = o;
                a.active = true
            }
        });
        AMM.clearQueue()
    },
    clearQueue: function() {
        AMM.queue = []
    },
    turnMapListenersOn: function() {
        AMM.listenForMapChanges()
    },
    turnMapListenersOff: function() {
        if (AMM.mapLoaded) {
            google.maps.event.clearListeners(AMM.map, "idle")
        }
    },
    listenForMapChanges: function() {
        if (AMM.mapLoaded) {
            google.maps.event.addListener(AMM.map, "idle", function() {
                AMM.mapListenerCallback()
            })
        }
    },
    fitBounds: function(e) {
        if (AMM.mapLoaded) {
            AMM.map.fitBounds(e)
        }
    },
    mapListenerCallback: function() {
        if (AMM.isFirstMapInteraction === true) {
            AMM.isFirstMapInteraction = false;
            var e = jQuery("#first_time_map_question");
            if (!redoSearchInMapIsChecked()) {
                AMM.redoSearchPromptTimeout = setTimeout(function() {
                    e.fadeOut(2e3)
                }, 14e3);
                e.show();
                return false
            }
        }
        if (AMM.activeInfoWindow && AMM.activeInfoWindowMarker) {
            var t = AMM.overlay.getProjection().fromLatLngToContainerPixel(AMM.activeInfoWindowMarker.getPosition());
            var n = t.x;
            var r = t.y;
            var i = 82;
            var s = jQuery("#search_map");
            var o = s.width();
            var u = s.height();
            var a = 260;
            var f = 250;
            var l = a / 2;
            var c = f / 2;
            if (redoSearchInMapIsChecked()) {
                if (n < c || r < l || n > o - c + i / 2 || r > u + l * 1.33) {
                    AMM.closeInfoWindow()
                }
            } else {
                if (n < 0 || r < 0 || n > o + i || r > u + a) {
                    AMM.closeInfoWindow()
                }
            }
        }
        if (!AMM.activeInfoWindow) {
            CogzidelSearch.results_changed_by_map_action = true;
            CogzidelSearch.loadNewResults()
        }
    }
};
var Connections = {
    COOKIE_NAME: "cogzidel_connect_banner",
    COOKIE_HIDE_VALUE: "hide",
    init: function() {
        var e = $("#connect_banner");
        var t = $("#airtv_promo");
        var n = jQuery.cookie(Connections.COOKIE_NAME);
        if (t.length < 1 && e.length > 0 && n !== Connections.COOKIE_HIDE_VALUE) {
            $("#connect_banner_close").click(Connections.closeClickHandler);
            $("#fb-connect-banner-button").click(Connections.connectButtonClickHandler);
            e.show()
        }
    },
    closeClickHandler: function(e) {
        $.cookie(Connections.COOKIE_NAME, Connections.COOKIE_HIDE_VALUE, {
            expires: 90,
            path: "/"
        });
        $b.remove();
        $b = null;
        Connections.trackEvent("hidePage2Banner");
        e.preventDefault()
    },
    connectButtonClickHandler: function() {
        var e = "loading";
        var t = $(this).addClass(e);
        Cogzidel.FBConnect.startLoginFlow();
        Cogzidel.FBConnect.one("connect_success", function() {
            Connections.trackEvent("page2FbConnect")
        });
        Cogzidel.FBConnect.one("connect_cancel", function() {
            Connections.trackEvent("page2FbCancel");
            t.removeClass(e)
        });
        Cogzidel.FBConnect.one("bail", function() {
            Connections.trackEvent("page2FbBail");
            t.removeClass(e)
        });
        Cogzidel.FBConnect.one("complete", function() {
            Connections.trackEvent("page2FbComplete")
        });
        return false
    },
    trackEvent: function(e) {
        _gaq.push(["_trackEvent", "SocialConnections", e])
    }
};
var SearchFilters = {
    defaults: {
        callbackFunction: "CogzidelSearch.loadNewResults",
        maxFilters: 4
    },
    has_photo: [],
    host_has_photo: [],
    languages: [],
    property_type_id: [],
    top_neighborhoods: [],
    neighborhoods: [],
    top_amenities: [],
    amenities: [],
    min_bedrooms: [],
    min_beds: [],
    min_bathrooms: [],
    min_bed_type: [],
    group_ids: [],
    room_types: [
        [0, [Translations.private_room, 0]],
        [1, [Translations.shared_room, 0]],
        [2, [Translations.entire_place, 0]]
    ],
    instance_book:[],
    minPrice: min_price,
    maxPrice: max_price,
    minPriceMonthly: 150,
    maxPriceMonthly: 5e3,
    per_month: false,
    filtersLightbox: false,
    applyPriceSliderChanges: function(e) {
        var t = jQuery("#slider-range").slider("option", "max");
        if (e !== undefined) {
            jQuery("#slider_user_min").html([symbol, e.values[0]].join(""));
            jQuery("#slider_user_max").html([symbol, e.values[1], e.values[1] === t ? "+ " : ""].join(""))
        } else {
            jQuery("#slider_user_min").html([jQuery("#slider-range").slider("values")[0], symbol].join(""));
            jQuery("#slider_user_max").html([jQuery("#slider-range").slider("values")[1], jQuery("#slider-range").slider("values")[1] === t ? "+ " : "", symbol].join(""))
        }
    },
    setPriceSliderLimits: function(e, t) {
        var n, r, i;
        if (e === true) {
            n = SearchFilters.minPriceMonthly;
            r = SearchFilters.maxPriceMonthly
        } else {
            n = SearchFilters.minPrice;
            r = SearchFilters.maxPrice
        }
        n = parseInt(min_price);
        r = parseInt(max_price);
        jQuery("#slider-range").slider("option", "min", n);
        jQuery("#slider-range").slider("option", "max", r);
        i = "+ ";
        if (t === false && CogzidelSearch.params.price_min && CogzidelSearch.params.price_max) {
            n = CogzidelSearch.params.price_min;
            r = CogzidelSearch.params.price_max;
            i = ""
        }
        jQuery("#slider-range").slider("values", 0, n);
        jQuery("#slider-range").slider("values", 1, r);
        jQuery("#slider_user_min").html([symbol, n].join(""));
        jQuery("#slider_user_max").html([symbol, r, i].join(""))
    },
    update: function(e) {
        SearchFilters.setFacets(e);
        SearchFilters.render()
    },
    setFacets: function(e) {
        SearchFilters.connected = e.connected || [];
        SearchFilters.room_types = e.room_type || [];
        SearchFilters.instance_book = e.instance_book || [];
        SearchFilters.top_neighborhoods = e.top_neighborhoods || [];
        SearchFilters.neighborhoods = e.neighborhood_facet || [];
        SearchFilters.top_amenities = e.top_amenities || [];
        SearchFilters.amenities = e.hosting_amenity_ids || [];
        SearchFilters.has_photo = e.has_photo || [];
        SearchFilters.host_has_photo = e.host_has_photo || [];
        SearchFilters.languages = e.languages || [];
        SearchFilters.property_type_id = e.property_type_id || [];
        SearchFilters.group_ids = e.group_ids || [];
        SearchFilters.night_writer = e.night_writer || []
    },
    render: function(e) {
        SearchFilters.renderSocialConnections();
        SearchFilters.renderNightWriter();
        SearchFilters.renderRoomTypes();
        SearchFilters.renderAmenities();
        SearchFilters.renderNeighborhoods();
        SearchFilters.renderGenericLightboxFacet("property_type_id");
        SearchFilters.renderGenericLightboxFacet("languages");
        SearchFilters.renderGenericLightboxFacet("group_ids");
        SearchFilters.renderAppliedFilters();
        return true
    },
    APPLIED_FILTER_NAMES: {
        neighborhoods: Translations.neighborhoods,
        hosting_amenities: Translations.amenities,
        room_types: Translations.room_type,
        room_types: Translations.instance_book,
        price: Translations.price,
        keywords: Translations.keywords,
        property_type_id: Translations.property_type,
        min_bedrooms: Translations.bedrooms,
        min_bathrooms: Translations.bathrooms,
        min_beds: Translations.beds,
        min_bed_type: Translations.bed_type,
        languages: Translations.languages,
        collection: Translations.collection,
        starred: "Starred Items",
        host: Translations.host,
        group: Translations.group,
        connections: Translations.connections,
        night_writer: Translations.night_writer
    },
    renderAppliedFilters: function() {
        var e;
        jQuery("#applied_filters").empty();
        if (CogzidelSearch.params.neighborhoods && CogzidelSearch.params.neighborhoods.length > 0) {
            SearchFilters.renderOneAppliedFilter("neighborhoods", SearchFilters.APPLIED_FILTER_NAMES.neighborhoods)
        }
        if (CogzidelSearch.params.price_max || CogzidelSearch.params.price_min) {
            SearchFilters.renderOneAppliedFilter("price", SearchFilters.APPLIED_FILTER_NAMES.price)
        }
        if (CogzidelSearch.params.hosting_amenities && CogzidelSearch.params.hosting_amenities.length > 0) {
            SearchFilters.renderOneAppliedFilter("hosting_amenities", SearchFilters.APPLIED_FILTER_NAMES.hosting_amenities)
        }
        if (CogzidelSearch.params.room_types && CogzidelSearch.params.room_types.length > 0) {
            SearchFilters.renderOneAppliedFilter("room_types", SearchFilters.APPLIED_FILTER_NAMES.room_types)
        }
        if (CogzidelSearch.params.instance_book && CogzidelSearch.params.instance_book.length > 0) {
            SearchFilters.renderOneAppliedFilter("instance_book", SearchFilters.APPLIED_FILTER_NAMES.instance_book)
        }
        if (CogzidelSearch.params.keywords && CogzidelSearch.params.keywords.length > 0) {
            SearchFilters.renderOneAppliedFilter("keywords", SearchFilters.APPLIED_FILTER_NAMES.keywords)
        }
        if (CogzidelSearch.params.property_type_id && CogzidelSearch.params.property_type_id.length > 0) {
            SearchFilters.renderOneAppliedFilter("property_type_id", SearchFilters.APPLIED_FILTER_NAMES.property_type_id)
        }
        if (CogzidelSearch.params.min_bedrooms && CogzidelSearch.params.min_bedrooms > 0) {
            SearchFilters.renderOneAppliedFilter("min_bedrooms", SearchFilters.APPLIED_FILTER_NAMES.min_bedrooms)
        }
        if (CogzidelSearch.params.min_beds && CogzidelSearch.params.min_beds > 0) {
            SearchFilters.renderOneAppliedFilter("min_beds", SearchFilters.APPLIED_FILTER_NAMES.min_beds)
        }
        if (CogzidelSearch.params.min_beds && CogzidelSearch.params.min_beds > 0) {
            SearchFilters.renderOneAppliedFilter("min_bed_type", SearchFilters.APPLIED_FILTER_NAMES.min_bed_type)
        }
        if (CogzidelSearch.params.min_bathrooms && CogzidelSearch.params.min_bathrooms > 0) {
            SearchFilters.renderOneAppliedFilter("min_bathrooms", SearchFilters.APPLIED_FILTER_NAMES.min_bathrooms)
        }
        if (CogzidelSearch.params.languages && CogzidelSearch.params.languages.length > 0) {
            SearchFilters.renderOneAppliedFilter("languages", SearchFilters.APPLIED_FILTER_NAMES.languages)
        }
        if (CogzidelSearch.params.connected) {
            SearchFilters.renderOneAppliedFilter("connections", SearchFilters.APPLIED_FILTER_NAMES.connections)
        }
        if (CogzidelSearch.params.night_writer) {
            SearchFilters.renderOneAppliedFilter("night_writer", SearchFilters.APPLIED_FILTER_NAMES.night_writer)
        }
        if (CogzidelSearch.includeCollectionParam() === true) {
            e = CogzidelSearch.isViewingStarred ? SearchFilters.APPLIED_FILTER_NAMES.starred : SearchFilters.APPLIED_FILTER_NAMES.collection;
            if (CogzidelSearch.collectionName && CogzidelSearch.collectionName !== "") {
                e = [e, CogzidelSearch.collectionName].join(": ")
            }
            SearchFilters.renderOneAppliedFilter("collections", e)
        }
        if (CogzidelSearch.isViewingStarred) {
            e = SearchFilters.APPLIED_FILTER_NAMES.starred;
            SearchFilters.renderOneAppliedFilter("starred", e)
        }
        if (CogzidelSearch.includeHostParam()) {
            var t = SearchFilters.APPLIED_FILTER_NAMES.host;
            if (CogzidelSearch.hostName && CogzidelSearch.hostName !== "") {
                t = [t, CogzidelSearch.hostName].join(": ")
            }
            SearchFilters.renderOneAppliedFilter("host", t)
        }
        if (CogzidelSearch.includeGroupParam()) {
            var n = SearchFilters.APPLIED_FILTER_NAMES.group;
            if (CogzidelSearch.groupName && CogzidelSearch.groupName !== "") {
                n = [n, CogzidelSearch.groupName].join(": ")
            }
            SearchFilters.renderOneAppliedFilter("group", n)
        }
        if (jQuery("#applied_filters").html() === "") {
            jQuery("#results_filters").hide()
        } else {
            jQuery("#results_filters").show()
        }
    },
    appliedFilterXCallback: function(e) {
        var t = true;
        var n = $(e).closest("li");
        var r = $(n).attr("id").replace("applied_filter_", "");
        switch (r) {
            case "neighborhoods":
                SearchFilters.clearNeighborhoods();
                break;
            case "price":
                SearchFilters.clearPrice();
                break;
            case "hosting_amenities":
                SearchFilters.clearAmenities();
                break;
            case "room_types":
                SearchFilters.clearRoomTypes();
                break;
            case "instance_book":
                SearchFilters.clearinstance_book();
                break;    
            case "keywords":
                SearchFilters.clearKeywords();
                break;
            case "property_type_id":
                SearchFilters.clearPropertyTypes();
                break;
            case "min_bedrooms":
                SearchFilters.clearMinBedrooms();
                break;
            case "min_bathrooms":
                SearchFilters.clearMinBathrooms();
                break;
            case "min_beds":
                SearchFilters.clearMinBeds();
                break;
            case "min_bed_type":
                SearchFilters.clearMinBedType();
                break;
            case "languages":
                SearchFilters.clearLanguages();
                break;
            case "collections":
                SearchFilters.clearCollections();
                break;
            case "starred":
                SearchFilters.clearStarred();
                break;
            case "host":
                SearchFilters.clearHost();
                break;
            case "group":
                SearchFilters.clearGroup();
                break;
            case "connections":
                SearchFilters.clearConnections();
                break;
            case "night_writer":
                SearchFilters.clearNightWriter();
                break;
            default:
        }
        n.remove();
        if (t === true) {
            $("#search_type_list").trigger("click");
            CogzidelSearch.loadNewResults()
        }
    },
    clearStarred: function() {
        CogzidelSearch.isViewingStarred = false
    },
    clearCollections: function(e) {
        e = e || true;
        if (CogzidelSearch.collectionId !== false && jQuery.trim(CogzidelSearch.collectionName).length !== 0) {
            setTimeout(function() {
                if (jQuery("#reinstate_collections").length === 0) {
                    jQuery(["<a class='rounded_more reinstate_button' id='reinstate_collections'>Back to the  \"", CogzidelSearch.collectionName, '" Collection</a>'].join("")).insertBefore("#Search_Main")
                }
            }, 1e3)
        }
        CogzidelSearch.forceHideCollection = e
    },
    reinstateCollections: function() {
        CogzidelSearch.forceHideCollection = false;
        jQuery("#location").val("");
        $("#search_type_list").trigger("click");
        CogzidelSearch.loadNewResults()
    },
    clearHost: function() {
        if (CogzidelSearch.hostId !== false && CogzidelSearch.hostName !== "") {
            setTimeout(function() {
                if (jQuery("#reinstate_user").length === 0) {
                    jQuery(["<a class='rounded_more reinstate_button' id='reinstate_user'>Back to properties from ", CogzidelSearch.hostName, "</a>"].join("")).insertBefore("#Search_Main")
                }
            }, 1e3)
        }
        CogzidelSearch.forceHideHost = true
    },
    reinstateHost: function() {
        CogzidelSearch.forceHideHost = false;
        jQuery("#location").val("");
        $("#search_type_list").trigger("click");
        CogzidelSearch.loadNewResults()
    },
    clearGroup: function() {
        if (CogzidelSearch.groupId !== false && CogzidelSearch.groupName !== "") {
            setTimeout(function() {
                if (jQuery("#reinstate_group").length === 0) {
                    jQuery(["<a class='rounded_more reinstate_button' id='reinstate_group'>Back to properties from ", CogzidelSearch.groupName, "</a>"].join("")).insertBefore("#Search_Main")
                }
            }, 1e3)
        }
        CogzidelSearch.forceHideGroup = true
    },
    reinstateGroup: function() {
        CogzidelSearch.forceHideGroup = false;
        jQuery("#location").val("");
        $("#search_type_list").trigger("click");
        CogzidelSearch.loadNewResults()
    },
    clearNeighborhoods: function() {
        jQuery("input[name='neighborhoods']").each(function(e, t) {
            jQuery(t).attr("checked", false)
        })
    },
    clearAmenities: function() {
        jQuery("input[name='amenities']").each(function(e, t) {
            $(t).removeAttr("checked")
        })
    },
    clearLanguages: function() {
        $("input[name='languages']").each(function(e, t) {
            $(t).removeAttr("checked")
        })
    },
    clearConnections: function() {
        $("input[name='connected']").removeAttr("checked")
    },
    clearNightWriter: function() {
        $("input[name='night_writer']").removeAttr("checked")
    },
    clearKeywords: function() {
        var e = $("#keywords");
        delete CogzidelSearch.params.keywords;
        e.val(e.attr("defaultValue"))
    },
    clearRoomTypes: function() {
        $("input[name='room_types']").each(function(e, t) {
            $(t).removeAttr("checked")
        })
    },
    clearinstance_book: function() {
        $("input[name='instance_book']").each(function(e, t) {
            $(t).removeAttr("checked")
        })
    },
    clearPropertyTypes: function() {
        $("input[name='property_type_id']").each(function(e, t) {
            $(t).removeAttr("checked")
        })
    },
    clearMinBedrooms: function() {
        jQuery("#min_bedrooms").val("")
    },
    clearMinBathrooms: function() {
        jQuery("#min_bathrooms").val("")
    },
    clearMinBeds: function() {
        jQuery("#min_beds").val("")
    },
    clearMinBedType: function() {
        jQuery("#min_bed_type").val("")
    },
    clearPrice: function() {
        SearchFilters.setPriceSliderLimits(SearchFilters.per_month, true);
        SearchFilters.applyPriceSliderChanges()
    },
    renderOneAppliedFilter: function(e, t) {
        $("#applied_filters").append($("#applied_filters_template").jqote({
            filter_id: e,
            filter_display_name: t
        }, "*"))
    },
    renderNightWriter: function() {
        var e = "#night_writer_container .search_filter_content";
        $(e).empty();
        SearchFilters.buildCheckbox({
            elementId: "night_writer",
            elementName: "night_writer",
            htmlValue: "night_writer",
            label: Translations.night_writer,
            forceActive: true,
            appendToElementSelector: e,
            checked: CogzidelSearch.params.night_writer
        })
    },
    renderSocialConnections: function() {
        var e = SearchFilters.connected && SearchFilters.connected[0];
        var t = "#social_connections_container .search_filter_content";
        $(t).empty();
        if (e) {
            SearchFilters.buildCheckbox({
                elementId: "connected",
                elementName: "connected",
                htmlValue: "connected",
                label: Translations.social_connections,
                facetCount: e[1],
                forceActive: true,
                appendToElementSelector: t,
                checked: CogzidelSearch.params.connected
            })
        }
        $(t).append('<li><a href="/social/" target="_blank">' + Translations.learn_more + "!</a></li>")
    },
    renderRoomTypes: function() {
        jQuery("#room_type_container ul.search_filter_content").empty();
        jQuery("#lightbox_filter_content_room_type").empty();
        jQuery.each(SearchFilters.room_types, function(e, t) {
            var n;
            if (CogzidelSearch.params.room_types && jQuery.inArray(t[0], CogzidelSearch.params.room_types) > -1) {
                n = true
            } else {
                n = false
            }
            SearchFilters.buildCheckbox({
                elementId: ["room_type_", e].join(""),
                elementName: "room_types",
                htmlValue: t[0],
                label: t[1][0],
                facetCount: t[1][1],
                forceActive: true,
                appendToElementSelector: "#room_type_container ul.search_filter_content",
                checked: n
            });
            SearchFilters.buildCheckbox({
                elementId: ["lightbox_room_type_", e].join(""),
                elementName: "room_types",
                htmlValue: t[0],
                label: t[1][0],
                forceActive: true,
                facetCount: t[1][1],
                appendToElementSelector: "#lightbox_filter_content_room_type",
                checked: n
            })
        });
        SearchFilters.appendShowMoreLink("#room_type_container ul.search_filter_content")
    },
     renderinstance_book: function() {
        jQuery("#instance_book_con ul.search_filter_content").empty();
        jQuery("#instance_book_con").empty();
        jQuery.each(SearchFilters.room_types, function(e, t) {
            var n;
            if (CogzidelSearch.params.room_types && jQuery.inArray(t[0], CogzidelSearch.params.room_types) > -1) {
                n = true
            } else {
                n = false
            }
            SearchFilters.buildCheckbox({
                elementId: ["instance_book", e].join(""),
                elementName: "instance_book",
                htmlValue: t[0],
                label: t[0],
                facetCount: t[0],
                forceActive: true,
                appendToElementSelector: "#instance_book_con ul.search_filter_content",
                checked: n
            });
            SearchFilters.buildCheckbox({
                elementId: ["instance_book", e].join(""),
                elementName: "instance_book",
                htmlValue: t[0],
                label: t[0],
                forceActive: true,
                facetCount: t[1],
                appendToElementSelector: "#instance_book_con",
                checked: n
            })
        });
        SearchFilters.appendShowMoreLink("#instance_book_con ul.search_filter_content")
    },
    renderAmenities: function() {
        var e = 0;
        var t;
        jQuery("#amenities_container ul.search_filter_content").empty();
        jQuery("#lightbox_container_amenities ul.search_filter_content").empty();
        if (parseInt(SearchFilters.top_amenities.length, 10) > 0) {
            jQuery.each(SearchFilters.top_amenities, function(n, r) {
                if (CogzidelSearch.params && CogzidelSearch.params.hosting_amenities && jQuery.inArray(r[0].toString(), CogzidelSearch.params.hosting_amenities) > -1) {
                    t = true
                } else {
                    t = false
                }
                if (n < SearchFilters.defaults.maxFilters) {
                    SearchFilters.buildCheckbox({
                        elementId: "amenity_" + n,
                        elementName: "amenities",
                        htmlValue: r[0],
                        label: r[1][0],
                        facetCount: r[1][1],
                        checked: t,
                        appendToElementSelector: "#amenities_container ul.search_filter_content"
                    })
                }
                e++
            })
        }
        if (parseInt(SearchFilters.amenities.length, 10) > 0 && parseInt(SearchFilters.amenities.length, 10) > e) {
            jQuery.each(SearchFilters.amenities, function(n, r) {
                if (CogzidelSearch.params && CogzidelSearch.params.hosting_amenities && jQuery.inArray(r[0].toString(), CogzidelSearch.params.hosting_amenities) > -1) {
                    t = true
                } else {
                    t = false
                }
                if (e === 0) {
                    SearchFilters.buildCheckbox({
                        elementId: "amenity_" + e,
                        elementName: "amenities",
                        htmlValue: r[0],
                        label: r[1][0],
                        facetCount: r[1][1],
                        checked: t,
                        appendToElementSelector: "#amenities_container ul.search_filter_content"
                    })
                }
                SearchFilters.buildCheckbox({
                    elementId: "lightbox_amenity_" + e,
                    elementName: "amenities",
                    htmlValue: r[0],
                    label: r[1][0],
                    facetCount: r[1][1],
                    checked: t,
                    appendToElementSelector: "#lightbox_container_amenities ul.search_filter_content"
                });
                e++
            });
            if (SearchFilters.amenities.length > SearchFilters.defaults.maxFilters) {
                SearchFilters.appendShowMoreLink("#amenities_container ul.search_filter_content")
            }
        } else {
            jQuery("#amenities_container").hide()
        }
        if (e > 0) {
            jQuery("#amenities_container").show()
        }
        return true
    },
    renderNeighborhoods: function() {
        var e = 0;
        var t;
        var n = true;
        jQuery("#neighborhood_container ul.search_filter_content").empty();
        jQuery("#lightbox_container_neighborhood ul.search_filter_content").empty();
        if (SearchFilters.top_neighborhoods && parseInt(SearchFilters.top_neighborhoods.length, 10) > 0) {
            jQuery.each(SearchFilters.top_neighborhoods, function(r, i) {
                n = true;
                if (i[0].indexOf("'") > -1) {
                    n = false
                }
                if (CogzidelSearch.params && CogzidelSearch.params.neighborhoods && jQuery.inArray(i[0], CogzidelSearch.params.neighborhoods) > -1) {
                    t = true
                } else {
                    t = false
                }
                if (r < SearchFilters.defaults.maxFilters && n) {
                    SearchFilters.buildCheckbox({
                        elementId: "neighborhood_" + r,
                        elementName: "neighborhoods",
                        htmlValue: i[0],
                        label: i[1][0],
                        facetCount: i[1][1],
                        checked: t,
                        appendToElementSelector: "#neighborhood_container ul.search_filter_content"
                    })
                }
                e++
            })
        }
        if (SearchFilters.neighborhoods && parseInt(SearchFilters.neighborhoods.length, 10) > 0 && parseInt(SearchFilters.neighborhoods.length, 10) > e) {
            jQuery.each(SearchFilters.neighborhoods, function(r, i) {
                n = true;
                if (i[0].indexOf("'") > -1) {
                    n = false
                }
                if (CogzidelSearch.params && CogzidelSearch.params.neighborhoods && jQuery.inArray(i[0], CogzidelSearch.params.neighborhoods) > -1) {
                    t = true
                } else {
                    t = false
                }
                if (e === 0 && n) {
                    SearchFilters.buildCheckbox({
                        elementId: "neighborhood_" + e,
                        elementName: "neighborhoods",
                        htmlValue: i[0],
                        label: i[1][0],
                        facetCount: i[1][1],
                        checked: t,
                        appendToElementSelector: "#neighborhood_container ul.search_filter_content"
                    })
                }
                if (n) {
                    SearchFilters.buildCheckbox({
                        elementId: "lightbox_neighborhood_" + e,
                        elementName: "neighborhoods",
                        htmlValue: i[0],
                        label: i[1][0],
                        facetCount: i[1][1],
                        checked: t,
                        appendToElementSelector: "#lightbox_container_neighborhood ul.search_filter_content"
                    })
                }
                e++
            });
            if (SearchFilters.neighborhoods.length > SearchFilters.defaults.maxFilters) {
                SearchFilters.appendShowMoreLink("#neighborhood_container ul.search_filter_content")
            }
        } else {
            jQuery("#neighborhood_container").hide()
        }
        if (e > 0) {
            jQuery("#neighborhood_container").show()
        }
        return true
    },
    renderGenericLightboxFacet: function(e) {
        var t;
        jQuery(["#lightbox_filter_content_", e].join("")).empty();
        jQuery.each(SearchFilters[e], function(n, r) {
            if (CogzidelSearch.params && CogzidelSearch.params[e] && CogzidelSearch.params[e] !== undefined && jQuery.inArray(r[0].toString(), CogzidelSearch.params[e]) > -1) {
                t = true
            } else {
                t = false
            }
            SearchFilters.buildCheckbox({
                elementId: ["lightbox_", e, "_", n].join(""),
                elementName: e,
                htmlValue: r[0],
                label: r[1][0],
                forceActive: true,
                facetCount: r[1][1],
                appendToElementSelector: ["#lightbox_filter_content_", e].join(""),
                checked: t
            })
        })
    },
    buildCheckbox: function(e) {
        e = e || {};
        var t = !!e.checked,
            n = e.elementName || "",
            r = e.elementId || "",
            i = e.appendToElementSelector || "",
            s = e.label || "",
            o = e.htmlValue.toString() || "",
            u = e.facetCount,
            a = e.forceActive,
            f = e.onChangecallbackFunction || SearchFilters.defaults.callbackFunction,
            l = u > 0 || t || a;
        var c = ["<li class='clearfix'>", u > 0 ? ["<span class='facet_count'>", u, "</span>"].join("") : "", "<input type='checkbox' id='", r, "' name='", n, "' value='", o, "'", l ? "" : " disabled='disabled'", t ? " checked='checked'" : "", " /> <label ", l ? "" : ' class="disabled" ', "for='", r, "'>", s, "</label>", "</li>"].join("");
        if (i) {
            $(i).append(c)
        }
        return false
    },
    appendShowMoreLink: function(e) {
        return $(e).append("<li><a href='javascript:void(0);' class='show_more_link'>" + Translations.show_more + "</a></li>")
    },
    openFiltersLightbox: function() {
        $.colorbox({
            inline: true,
            height: 480,
            width: 600,
            href: "#filters_lightbox"
        })
    },
    closeFiltersLightbox: function() {
        SearchFilters.clearRoomTypes();
        SearchFilters.clearPropertyTypes();
        SearchFilters.clearAmenities();
        SearchFilters.clearMinBedrooms();
        SearchFilters.clearMinBathrooms();
        SearchFilters.clearMinBeds();
        $.colorbox.close();
        $("#search_type_list").trigger("click");
        CogzidelSearch.loadNewResults()
    },
    selectLightboxTab: function(e) {
        var t = e || "room_type";
        $(".filters_lightbox_nav_element").removeClass("active");
        $(".lightbox_filter_container").hide();
        $("#lightbox_nav_" + t).addClass("active");
        $("#lightbox_container_" + t).show()
    }
};
var SS = {
    initialized: false,
    SHORT_TIMEOUT: 50,
    LONG_TIMEOUT: 675,
    FADE_DURATION: 250,
    containerEl: jQuery("#page2_inline_slideshow"),
    imgEl: jQuery("#page2_inline_slideshow_img"),
    isFirstHover: true,
    cloudFrontUrl: cdn_img_url + "images/",
    hoverTimeout: false,
    pictureArrays: {},
    currentUrls: [],
    currentParentDivId: undefined,
    currentHostingId: undefined,
    currentPosition: 0,
    addHostingAndIds: function(e, t) {
        if (SS.pictureArrays[e] === undefined) {
            SS.pictureArrays[e] = t
        }
    },
    fullImageUrl: function(e) {
        var t = [SS.cloudFrontUrl, e].join("");
        return t
    },
    initOnce: function() {
        if (SS.initialized === false) {
            SS.init()
        }
    },
    init: function() {
        jQuery("#page2_inline_slideshow, .map_info_window_link_image").live("hover", function() {
            if (SS.hoverTimeout) {
                clearTimeout(SS.hoverTimeout)
            }
            if (SS.isFirstHover === true) {
                SS.hoverTimeout = setTimeout(function() {
                    SS.next()
                }, SS.SHORT_TIMEOUT);
                SS.isFirstHover = false
            } else {
                SS.hoverTimeout = setTimeout(function() {
                    SS.next()
                }, SS.LONG_TIMEOUT)
            }
        }, function() {
            SS.reset()
        });
        SS.initialized = true
    },
    next: function() {
        var e, t;
        if (SS.totalPicturesSize() <= 1) {
            return
        }
        e = SS.fullImageUrl(SS.pictureArrays[SS.currentHostingId][0]);
        SS.pictureArrays[SS.currentHostingId].push(SS.pictureArrays[SS.currentHostingId].shift());
        t = SS.fullImageUrl(SS.pictureArrays[SS.currentHostingId][0]);
        SS.imgEl.attr("src", e);
        SS.imgEl.show();
        SS.preloadImage(t, function(e) {
            return function() {
                if (e === SS.currentHostingId) {
                    if (CogzidelSearch.currentViewType === "map") {
                        jQuery(".map_info_window").find("img").attr("src", t)
                    } else {
                        jQuery(SS.currentParentDivId).find(".search_thumbnail").attr("src", t)
                    }
                    SS.imgEl.fadeOut(SS.FADE_DURATION, function() {
                        if (SS.hoverTimeout === null) {
                            SS.imgEl.attr("src", CogzidelSearch.PIXEL_PATH);
                            SS.hoverTimeout = setTimeout(function() {
                                SS.next()
                            }, SS.LONG_TIMEOUT)
                        }
                    })
                }
            }
        }(SS.currentHostingId));
        SS.hoverTimeout = null
    },
    show: function(e, t) {
        SS.currentParentDivId = e;
        SS.currentHostingId = t;
        SS.attachToParent();
        SS.containerEl.show()
    },
    hide: function() {
        SS.containerEl.hide()
    },
    attachToParent: function() {
        if (SS.currentParentDivId) {
            if (CogzidelSearch.currentViewType == "map") {
                SS.containerEl.appendTo(SS.currentParentDivId);
                SS.containerEl.attr("href", jQuery(SS.currentParentDivId).find("a.image_link").attr("href"))
            } else {
                SS.containerEl.appendTo(SS.currentParentDivId);
                SS.containerEl.attr("href", jQuery(SS.currentParentDivId).find("a.image_link").attr("href"))
            }
        }
    },
    reset: function() {
        SS.hide();
        SS.imgEl.hide();
        clearTimeout(SS.hoverTimeout);
        SS.hoverTimeout = false;
        SS.isFirstHover = true
    },
    totalPicturesSize: function() {
        return SS.pictureArrays[SS.currentHostingId].length
    },
    preloadImage: function(e, t) {
        var n = new Image;
        n.src = e;
        if (n.complete) {
            if (SS.hoverTimeout !== false) {
                t()
            }
            n.onload = function() {}
        } else {
            n.onload = function() {
                if (SS.hoverTimeout !== false) {
                    t()
                }
                n.onload = function() {}
            }
        }
    }
};
CogzidelSearch.$.bind("finishedrendering", function() {
    CogzidelSearch.updateFacebookBannerText()
});
(function(e, t) {
    var n = "has-mini-profile",
        r = "mini-profile-container",
        i = "/users/mini_profile/%USER_ID%",
        s = 400;
    t.miniprofile = function() {
        t("body").delegate("." + n, "hover", function(e) {
            if (e.type === "mouseenter" || e.type === "mouseover") {
                new o(t(this), e)
            }
        })
    };
    var o = function(e, t) {
        this.$t = e;
        this.userId = e.data("user_id");
        this.pageX = t.pageX;
        this.pageY = t.pageY;
        this.padding = 10;
        this.buffer = 16;
        this.guid = (new Date).getTime();
        this.init()
    };
    o.fn = o.prototype;
    o.fn.init = function() {
        var e = this;
        if (!this.$t.data("mini-profile-attached")) {
            this.$t.data("mini-profile-attached", true);
            t(document).bind("mousemove.mini_profile" + this.guid, function(t) {
                e.pageX = t.pageX;
                e.pageY = t.pageY;
                if (!e.isInHitbox()) {
                    e.kill()
                }
            });
            setTimeout(function() {
                if (e.isInHitbox()) {
                    e.show()
                } else {
                    e.kill()
                }
            }, 500)
        }
    };
    o.fn.isInHitbox = function() {
        if (!this.tCoords) {
            var e = this.$t.offset();
            this.tCoords = {
                top: e.top,
                left: e.left,
                width: this.$t.width(),
                height: this.$t.height()
            }
        }
        if (this.inCoords(this.tCoords)) {
            return true
        }
        if (this.$el) {
            if (!this.elCoords || this.elCoords.width !== this.$el.width()) {
                var t = this.$el.offset();
                this.elCoords = {
                    top: t.top,
                    left: t.left,
                    width: this.$el.width(),
                    height: this.$el.height()
                }
            }
            if (this.inCoords(this.elCoords)) {
                return true
            }
            if (this.inBufferTriangle()) {
                return true
            }
        }
        return false
    };
    o.fn.inCoords = function(e) {
        e = this.pad(e);
        return this.pageX >= e.left && this.pageX <= e.left + e.width && this.pageY >= e.top && this.pageY <= e.top + e.height
    };
    o.fn.pad = function(e) {
        return t.extend({}, e, {
            top: e.top - this.padding,
            left: e.left - this.padding,
            width: e.width + 2 * this.padding,
            height: e.height + 2 * this.padding
        })
    };
    o.fn.inBufferTriangle = function() {
        if (!this.triCoords) {
            this.triCoords = {
                top: this.tCoords.top,
                left: this.tCoords.left + this.tCoords.width,
                height: this.tCoords.height,
                width: this.elCoords.width - this.tCoords.width,
                sideTop: this.elCoords.top < this.tCoords.top
            }
        }
        if (!this.inCoords(this.triCoords, false)) {
            return false
        }
        var e = this.pad(this.triCoords),
            t = e.height / (e.width - this.padding * 2),
            n = this.pageX - e.left - this.padding * 2,
            r = this.pageY - e.top;
        if (r >= t * n) {
            return this.triCoords.sideTop
        } else {
            return !this.triCoords.sideTop
        }
        return true
    };
    o.fn.show = function() {
        this.build();
        this.position();
        this.$el.css("visibility", "visible").hide().fadeIn(100);
        this.loadContent()
    };
    o.fn.build = function() {
        this.$el = t('<div><div class="arrow"></div></div>').attr("id", r).addClass("loading").appendTo("body")
    };
    o.fn.position = function(e) {
        var t = this.$t.offset(),
            n = this.$el.height(),
            r = e || t.top - (n + this.buffer);
        if (this.showBelow(r)) {
            r += n + this.buffer * 2 + this.$t.height();
            this.$el.addClass("below")
        }
        this.$el.css({
            left: t.left + "px",
            top: r + "px"
        });
        return r
    };
    o.fn.kill = function() {
        if (this.$el) {
            this.$el.fadeOut(100, function() {
                t(this).remove()
            })
        }
        t(document).unbind("mousemove.mini_profile" + this.guid);
        this.$t.data("mini-profile-attached", null)
    };
    o.fn.loadContent = function() {
        var e = this,
            n, r, i, s = this.$el.offset(),
            o = this.$el.height(),
            u = this.$el.width(),
            a;
        t.get(this.getUrl(), function(u) {
            n = t(u).css("visibility", "hidden").appendTo("body");
            r = n.width();
            i = n.height();
            n.hide().css("visibility", "visible").appendTo(e.$el);
            a = s.top + (o - i) * (e.showBelow(s.top) ? 0 : 1);
            if (e.showBelow(a)) {
                a = e.$t.offset().top + e.$t.height() + e.buffer;
                e.$el.css("top", a + "px");
                e.$el.addClass("below")
            }
            e.$el.animate({
                top: a,
                width: r,
                height: i
            }, 200, function() {
                n.fadeIn(200);
                e.$el.removeClass("loading")
            })
        })
    };
    o.fn.showBelow = function(e) {
        return e <= t(document).scrollTop()
    };
    o.fn.getUrl = function() {
        return i.replace("%USER_ID%", this.userId)
    };
    e.MiniProfile = o
})(Cogzidel, jQuery)
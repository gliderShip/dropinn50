/* List Your Space */
jQuery(document).ready(function() {
	jQuery("#loading").hide();
	
    function e(e, t) {
        jQuery("#small_length").text("Your price is too low. The minimum is " + t + e + ".");
        jQuery("#large_length").text("Your price is too long. The maximum is " + t + e * 1e3 + ".");
        jQuery("#small_week_length").text("Your price is too low. The minimum is " + t + e * 7 + ".");
        jQuery("#large_week_length").text("Your price is too long. The maximum is " + t + e * 1400 + ".");
        jQuery("#large_month_length").text("Your price is too long. The maximum is " + t + e * 6e3 + ".");
        jQuery("#small_month_length").text("Your price is too low. The minimum is " + t + e * 30 + ".");
        jQuery("#small_clean_length").text("Your price is too low. The minimum is " + t + e + ".");
        jQuery("#large_clean_length").text("Your price is too long. The maximum is " + t + e * 60 + ".");
        jQuery("#small_security_length").text("Your price is too low. The minimum is " + t + e + ".");
        jQuery("#large_security_length").text("Your price is too long. The maximum is " + t + e * 60 + ".");
        jQuery("#small_additional_length").text("Your price is too low. The minimum is " + t + e + ".");
        jQuery("#large_additional_length").text("Your price is too long. The maximum is " + t + e * 60 + ".")
    }

    function t(e) {
        var t = e.which ? e.which : event.keyCode;
        if (t != 46 && t > 31 && (t < 48 || t > 57)) return false;
        return true
    }

    function n() {
        var e = new google.maps.LatLng(jQuery("#hidden_lat").val(), jQuery("#hidden_lng").val());
        var t;
        var n;
        var r;
        var i;
        var s = [{
            featureType: "water",
            elementType: "geometry.fill",
            stylers: [{
                weight: 3.3
            }, {
                hue: "#00aaff"
            }, {
                lightness: 100
            }, {
                saturation: 93
            }, {
                gamma: .01
            }, {
                color: "#5cb8e4"
            }]
        }];
        var o = new google.maps.StyledMapType(s, {
            name: "Styled Map"
        });
        var u = {
            zoom: 13,
            center: e,
            scrollwheel: false
        };
        n = new google.maps.Map(document.getElementById("map-canvas1"), u);
        n.setCenter(e);
        t = new google.maps.Marker({
            map: n,
            draggable: true,
            position: e
        });
        n.mapTypes.set("map-canvas1", o);
        n.setMapTypeId("map-canvas1");
        google.maps.event.addListener(t, "dragend", function(e) {
            n.setCenter(t.getPosition());
            r = t.getPosition().lat();
            i = t.getPosition().lng();
            jQuery.getJSON("https://maps.googleapis.com/maps/api/geocode/json?latlng=" + r + "," + i + "&sensor=true&key="+places_API+"&language=en", function(e) {
                if (e.status == "OK") {
                    jQuery(".disable_finish").hide();
                    jQuery(".enable_finish").show();
                    jQuery("#hidden_lat").val(r);
                    jQuery("#hidden_lng").val(i);
                    address = e.results[0].formatted_address;
                    jQuery("#hidden_address").val(address);
                    var t = {};
                    for (var n = 0; n < e.results[0].address_components.length; n++) {
                        var s = route = street = city = state = zipcode = country = formatted_address = "";
                        var o = e.results[0].address_components[n].types.join(",");
                        if (o == "street_number") {
                            t.street_number = e.results[0].address_components[n].long_name
                        }
                        if (o == "route" || o == "point_of_interest,establishment") {
                            t.route = e.results[0].address_components[n].long_name;
                           jQuery("#lys_street_address").val(t.route);
                            if (t.route == "[object HTMLInputElement]") {
                                jQuery("#lys_street_address").val("")
                            }
                        }
                        if (o == "sublocality,political" || o == "locality,political" || o == "neighborhood,political" || o == "administrative_area_level_3,political") {
                            t.city = city == "" || o == "locality,political" ? e.results[0].address_components[n].long_name : city;
                            jQuery("#city").val(t.city);
                            if (t.city == "[object HTMLInputElement]") {
                                jQuery("#city").val("")
                            }
                        }
                        if (o == "administrative_area_level_1,political") {
                            t.state = e.results[0].address_components[n].long_name;
                            jQuery("#state").val(t.state);
                            if (t.state == "[object HTMLInputElement]") {
                                jQuery("#state").val("")
                            }
                        }
                        if (o == "postal_code" || o == "postal_code_prefix,postal_code") {
                            t.zipcode = e.results[0].address_components[n].long_name;
                            jQuery("#zipcode").val(t.zipcode);
                            if (t.zipcode == "[object HTMLInputElement]") {
                                jQuery("#zipcode").val("")
                            }
                        }
                        if (o == "country,political") {
                            t.country = e.results[0].address_components[n].long_name;
                            jQuery("#country option").each(function() {
                                if (jQuery(this).text() == jQuery.trim(t.country)) {
                                    jQuery("#country").val(t.country)
                                }
                            })
                        }
                    }
                } else {
                    jQuery.ajax({
                        url: base_url + "rooms/get_address",
                        type: "POST",
                        dataType: "json",
                        data: {
                            room_id: room_id
                        },
                        success: function(e) {
                            jQuery.each(e, function(e, t) {
                                city = t["city"];
                                jQuery("#city").val(city);
                                state = t["state"];
                                jQuery("#state").val(state);
                                country = t["country"];
                                jQuery("#country").val(country);
                                jQuery("#hidden_lat").val(t["lat"]);
                                jQuery("#hidden_lng").val(t["long"]);
                                jQuery("#zipcode").val(t["zip_code"])
                            })
                        }
                    })
                }
            })
        })
    }
    var r = "";
    var i = "";
    var s = "";
    var o = 0;
    var u = "";
    var a = "";
    var f = "";
    var l = 0;
    var c = 0;
    var h = 0;
    var p = "";
    var d = "";
    var v = 0;
    var m = 0;
    var g = 0;
    jQuery(document).mousemove(function(e) {
        m = e.pageX;
        g = e.pageY;
        jQuery("#mouse_x").val(m);
        jQuery("#mouse_y").val(g)
    });
    if (house_rule == "") {
        var y = 0
    } else {
        var y = 1
    }
    if (calendar_type == 1) {
        jQuery("#calendar_first").hide();
        jQuery("#always").show();
        jQuery("#cal_plus").hide();
        jQuery("#cal_plus_after").show();
        o = 1
    }
    if (calendar_type == 2) {
        jQuery("#calendar_first").hide();
        if (some_times_cal == 1) {
            jQuery("#some_times_cal").show()
        } else {
            jQuery("#some_times").show()
        }
        jQuery("#cal_plus").hide();
        jQuery("#cal_plus_after").show();
        o = 1
    }
    if (edit_photo == 1) {
        jQuery("#cal").hide();
        jQuery("#photo").hide();
        jQuery("#photo_after").show();
        if (calendar_type == 1 || calendar_type == 2 || calendar_type == 3) {
            jQuery("#cal_after").show()
        } else {
            jQuery("#cal1").show()
        }
        if (photos_count != 0) {
            jQuery("#photo_plus_white").hide();
            jQuery("#photo_grn_white").show();
            jQuery("#container_photo").hide();
            jQuery(".container_add_photo").show()
        } else {
            jQuery("#container_photo").show()
        }
        jQuery("#cal_container").hide();
        jQuery("#photos_container").show();
        jQuery("#price-right-hover").hide();
        jQuery("#overview-textbox-hover").hide();
        jQuery("#photo_ul").show()
    }
    if (calendar_type == 3) {
        jQuery("#calendar_first").hide();
        jQuery("#one_time").show();
        jQuery("#cal_plus").hide();
        jQuery("#cal_plus_after").show();
        o = 1
    }
    jQuery("#home-1").mouseover(function() {
        jQuery("#home-2").css("opacity", "0.4");
        jQuery("#home-3").css("opacity", "0.4")
    });
    jQuery("#home-1").mouseleave(function() {
        jQuery("#home-2").css("opacity", "1");
        jQuery("#home-3").css("opacity", "1")
    });
    jQuery("#home-2").mouseover(function() {
        jQuery("#home-1").css("opacity", "0.4");
        jQuery("#home-3").css("opacity", "0.4")
    });
    jQuery("#home-2").mouseleave(function() {
        jQuery("#home-1").css("opacity", "1");
        jQuery("#home-3").css("opacity", "1")
    });
    jQuery("#home-3").mouseover(function() {
        jQuery("#home-1").css("opacity", "0.4");
        jQuery("#home-2").css("opacity", "0.4")
    });
    jQuery("#home-3").mouseleave(function() {
        jQuery("#home-1").css("opacity", "1");
        jQuery("#home-2").css("opacity", "1")
    });
    jQuery("#home-1").click(function() {
        jQuery("#calendar_first").hide();
        jQuery("#always").show();
        r = 1;
        jQuery.ajax({
            url: base_url + "rooms/calendar_type",
            type: "POST",
            data: {
                type: r,
                room_id: room_id
            },
            success: function(e) {
                jQuery("#cal_plus").hide();
                jQuery("#cal_plus_after").show();
                o = 1;
                var t = 0;
                t = o + price_status + address_status + listing_status + photo_status + overview_status;
                var n = 6 - t;
                jQuery("#steps").replaceWith('<span id="steps">' + n + " steps</span>");
                if (n == 0) {
                    jQuery.ajax({
                        url: base_url + "rooms/final_step",
                        type: "POST",
                        data: {
                            room_id: room_id
                        },
                        success: function(e) {
                            jQuery("#steps_count").hide();
                            jQuery("#list_space").show();
                            jQuery("#list-button").rotate3Di(720, 750)
                        }
                    })
                }
            }
        })
    });
    jQuery("#home-2").click(function() {
        jQuery("#calendar_first").hide();
        jQuery("#some_times").show();
        i = 2;
        jQuery.ajax({
            url: base_url + "rooms/calendar_type",
            type: "POST",
            data: {
                type: i,
                room_id: room_id
            },
            success: function(e) {
                jQuery("#cal_plus").hide();
                jQuery("#cal_plus_after").show();
                o = 1;
                var t = 0;
                t = o + price_status + address_status + listing_status + photo_status + overview_status;
                var n = 6 - t;
                jQuery("#steps").replaceWith('<span id="steps">' + n + " steps</span>");
                if (n == 0) {
                    jQuery.ajax({
                        url: base_url + "rooms/final_step",
                        type: "POST",
                        data: {
                            room_id: room_id
                        },
                        success: function(e) {
                            jQuery("#steps_count").hide();
                            jQuery("#list_space").show();
                            jQuery("#list-button").rotate3Di(720, 750)
                        }
                    })
                }
            }
        })
    });
    jQuery("#home-3").click(function() {
        jQuery("#calendar_first").hide();
        jQuery("#one_time").show();
        s = 3;
        jQuery.ajax({
            url: base_url + "rooms/calendar_type",
            type: "POST",
            data: {
                type: s,
                room_id: room_id
            },
            success: function(e) {
                jQuery("#cal_plus").hide();
                jQuery("#cal_plus_after").show();
                o = 1;
                var t = 0;
                t = o + price_status + address_status + listing_status + photo_status + overview_status;
                var n = 6 - t;
                jQuery("#steps").replaceWith('<span id="steps">' + n + " steps</span>");
                if (n == 0) {
                    jQuery.ajax({
                        url: base_url + "rooms/final_step",
                        type: "POST",
                        data: {
                            room_id: room_id
                        },
                        success: function(e) {
                            jQuery("#steps_count").hide();
                            jQuery("#list_space").show();
                            jQuery("#list-button").rotate3Di(720, 750)
                        }
                    })
                }
            }
        })
    });
    jQuery("#calendar_always").click(function() {
        jQuery("#always").hide();
        jQuery("#one_time").hide();
        jQuery("#some_times").hide();
        jQuery("#calendar_first").show();
        jQuery("#home-1 .myButtonLink").css("background-image", "url(" + cdn_url_images + "images/tick-hover.png)")
    });
    jQuery("#calendar_one").click(function() {
        jQuery("#always").hide();
        jQuery("#one_time").hide();
        jQuery("#some_times").hide();
        jQuery("#calendar_first").show();
        jQuery("#home-3 .myButtonLink").css("background-image", "url(" + cdn_url_images + "images/tick-hover.png")
    });
    jQuery("#calendar_some").click(function() {
        jQuery("#always").hide();
        jQuery("#one_time").hide();
        jQuery("#some_times").hide();
        jQuery("#calendar_first").show();
        jQuery("#home-2 .myButtonLink").css("background-image", "url(" + cdn_url_images + "images/cal-hover.png)")
    });
    jQuery("#back_always").click(function() {
        jQuery("#always").hide();
        jQuery("#calendar_first").show();
        jQuery("#home-1 .myButtonLink").css("background-image", "url(" + cdn_url_images + "images/tick-hover.png)")
    });
    jQuery("#back_one").click(function() {
        jQuery("#one_time").hide();
        jQuery("#calendar_first").show();
        jQuery("#home-3 .myButtonLink").css("background-image", "url(" + cdn_url_images + "images/tick-hover.png)")
    });
    jQuery("#back_some").click(function() {
        jQuery("#some_times").hide();
        jQuery("#calendar_first").show();
        jQuery("#home-2 .myButtonLink").css("background-image", "url(" + cdn_url_images + "images/cal-hover.png)");
        jQuery("#home-2 .myButtonLink").css("height", "99");
        jQuery("#home-2 .myButtonLink").css("width", "97")
    });
    jQuery("#price").click(function() {
        jQuery("#price-right-hover").show();
        jQuery("#overview-textbox-hover").hide();
        jQuery("#ded").hide();
        if (jQuery.trim(jQuery("#title").val()).length == 0) {
            jQuery.ajax({
                url: base_url + "rooms/change_title_status",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                    title_status = 0
                }
            });
            title_status = 0;
            overview_status = 0;
            var e = 0;
            e = o + price_status + address_status + listing_status + photo_status + overview_status;
            var t = 6 - e;
            if (t != 0) {
                jQuery("#steps").replaceWith('<span id="steps">' + t + " steps</span>");
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#list_space").hide();
                        jQuery("#steps_count").show()
                    }
                })
            } else if (t == 0) {
                jQuery("#list_space").show();
                jQuery("#steps_count").hide()
            }
        }
        if (jQuery.trim(jQuery("#summary").val()).length == 0) {
            jQuery.ajax({
                url: base_url + "rooms/change_summary_status",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                    summary_status = 0
                }
            });
            summary_status = 0;
            overview_status = 0;
            var e = 0;
            e = o + price_status + address_status + listing_status + photo_status + overview_status;
            var t = 6 - e;
            if (t != 0) {
                jQuery("#steps").replaceWith('<span id="steps">' + t + " steps</span>");
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#list_space").hide();
                        jQuery("#steps_count").show()
                    }
                })
            } else if (t == 0) {
                jQuery("#list_space").show();
                jQuery("#steps_count").hide()
            }
        }
        if (o == 1) {
            jQuery("#cal").hide();
            jQuery("#cal_after").show();
            jQuery("#cal1").hide()
        } else {
            jQuery("#cal").hide();
            jQuery("#cal1").show()
        }
        if (price_status == 0) {
            jQuery("#price").hide();
            jQuery("#price_after").show();
            jQuery("#price_plus_after").hide();
            jQuery("#price_plus").show()
        } else {
            jQuery("#price").hide();
            jQuery("#price_after").show();
            jQuery("#price_plus").hide();
            jQuery("#price_plus_after").show();
            jQuery("#large_length").hide();
            jQuery("#small_length").hide()
        }
        if (v == 1) {
            jQuery("#amenities").show();
            jQuery("#amenities_after").hide()
        }
        if (beds_status == 1 && bathrooms_status == 1 && bedscount_status == 1 && bedtype_status == 1) {
            jQuery("#listing").show();
            jQuery("#listing_after").hide();
            jQuery("#list_plus").hide();
            jQuery("#list_plus_after").show()
        } else {
            jQuery("#listing").show();
            jQuery("#listing_after").hide()
        }
        if (title_status == 1 && summary_status == 1) {
            jQuery("#overview_after").hide();
            jQuery("#overview").show();
            jQuery("#over_plus").hide();
            jQuery("#over_plus_after1").hide();
            jQuery("#over_plus_after").show()
        } else {
            jQuery("#overview_after").hide();
            jQuery("#overview").show();
            jQuery("#over_plus_after").hide();
            jQuery("#over_plus").show()
        }
        if (photo_status == 1) {
            jQuery("#photo_after").hide();
            jQuery("#photo").show();
            jQuery("#photo_plus").hide();
            jQuery("#photo_grn").show()
        } else {
            jQuery("#photo_after").hide();
            jQuery("#photo").show();
            jQuery("#photo_grn").hide();
            jQuery("#photo_plus").show()
        }
        if (address_status == 1) {
            jQuery("#address_after").hide();
            jQuery("#address_side").show();
            jQuery("#addr_plus_after_grn").show();
            jQuery("#address_before").hide();
            jQuery("#addr_plus").hide()
        } else {
            jQuery("#address_after").hide();
            jQuery("#address_side").show()
        }
        jQuery("#cal_container").hide();
        jQuery("#overview_entire").hide();
        jQuery("#amenities_entire").hide();
        jQuery("#listing_entire").hide();
        jQuery("#price_container").show();
        jQuery("#photos_container").hide();
        jQuery("#address_entire").hide();
        jQuery("#address_right").hide();
        jQuery("#static_circle_map").hide();
        jQuery("#detail_container").hide();
        jQuery("#terms_container").hide();
        jQuery("#cleaning-price-right").hide();
        jQuery("#additional-price-right").hide();
        jQuery("#terms_side").show();
        jQuery("#terms_side_after").hide();
        if (y == 1) {
            jQuery("#detail_side").show();
            jQuery("#detail_side_after").hide();
            jQuery("#detail_plus").hide()
        } else {
            jQuery("#detail_side_after").hide();
            jQuery("#detail_side").show();
            jQuery("#detail_plus").show()
        }
    });
    jQuery("#cal1").click(function() {
        jQuery("#price-right-hover").hide();
        jQuery("#overview-textbox-hover").hide();
        jQuery("#ded").hide();
        if (jQuery.trim(jQuery("#title").val()).length == 0) {
            jQuery.ajax({
                url: base_url + "rooms/change_title_status",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                    title_status = 0
                }
            });
            title_status = 0;
            overview_status = 0;
            var e = 0;
            e = o + price_status + address_status + listing_status + photo_status + overview_status;
            var t = 6 - e;
            if (t != 0) {
                jQuery("#steps").replaceWith('<span id="steps">' + t + " steps</span>");
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#list_space").hide();
                        jQuery("#steps_count").show()
                    }
                })
            } else if (t == 0) {
                jQuery("#list_space").show();
                jQuery("#steps_count").hide()
            }
        }
        if (jQuery.trim(jQuery("#summary").val()).length == 0) {
            jQuery.ajax({
                url: base_url + "rooms/change_summary_status",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                    summary_status = 0
                }
            });
            summary_status = 0;
            overview_status = 0;
            var e = 0;
            e = o + price_status + address_status + listing_status + photo_status + overview_status;
            var t = 6 - e;
            if (t != 0) {
                jQuery("#steps").replaceWith('<span id="steps">' + t + " steps</span>");
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#list_space").hide();
                        jQuery("#steps_count").show()
                    }
                })
            } else if (t == 0) {
                jQuery("#list_space").show();
                jQuery("#steps_count").hide()
            }
        }
        if (o == 1) {
            jQuery("#cal").hide();
            jQuery("#cal_after").show();
            jQuery("#cal1").hide()
        } else {
            jQuery("#cal").show();
            jQuery("#cal1").hide()
        }
        if (title_status == 1 && summary_status == 1) {
            jQuery("#overview_after").hide();
            jQuery("#overview").show();
            jQuery("#over_plus").hide();
            jQuery("#over_plus_after1").hide();
            jQuery("#over_plus_after").show()
        } else {
            jQuery("#overview_after").hide();
            jQuery("#overview").show();
            jQuery("#over_plus_after").hide();
            jQuery("#over_plus").show()
        }
        if (price_status == 1) {
            jQuery("#price_after").hide();
            jQuery("#price").show();
            jQuery("#des_plus").hide();
            jQuery("#des_plus_after").show()
        } else {
            jQuery("#price_after").hide();
            jQuery("#price").show()
        }
        if (v == 1) {
            jQuery("#amenities").show();
            jQuery("#amenities_after").hide()
        }
        if (beds_status == 1 && bathrooms_status == 1 && bedscount_status == 1 && bedtype_status == 1) {
            jQuery("#listing").show();
            jQuery("#listing_after").hide();
            jQuery("#list_plus").hide();
            jQuery("#list_plus_after").show()
        } else {
            jQuery("#listing").show();
            jQuery("#listing_after").hide()
        }
        if (photo_status == 1) {
            jQuery("#photo_after").hide();
            jQuery("#photo").show();
            jQuery("#photo_plus").hide();
            jQuery("#photo_grn").show()
        } else {
            jQuery("#photo_after").hide();
            jQuery("#photo").show()
        }
        if (address_status == 1) {
            jQuery("#address_after").hide();
            jQuery("#address_side").show();
            jQuery("#addr_plus_after_grn").show();
            jQuery("#address_before").hide();
            jQuery("#addr_plus").hide()
        } else {
            jQuery("#address_after").hide();
            jQuery("#address_side").show()
        }
        jQuery("#overview_after").hide();
        jQuery("#overview").show();
        jQuery("#cal_container").show();
        jQuery("#price_container").hide();
        jQuery("#loading").hide();
        jQuery("#overview_entire").hide();
        jQuery("#amenities_entire").hide();
        jQuery("#listing_entire").hide();
        jQuery("#photos_container").hide();
        jQuery("#address_entire").hide();
        jQuery("#address_right").hide();
        jQuery("#static_circle_map").hide();
        jQuery("#detail_container").hide();
        jQuery("#terms_container").hide();
        jQuery("#cleaning-price-right").hide();
        jQuery("#additional-price-right").hide();
        jQuery("#terms_side").show();
        jQuery("#terms_side_after").hide()
    });
    jQuery("#cal_after").click(function() {
        jQuery("#price-right-hover").hide();
        jQuery("#ded").hide();
        jQuery("#overview-textbox-hover").show();
        if (jQuery.trim(jQuery("#title").val()).length == 0) {
            jQuery.ajax({
                url: base_url + "rooms/change_title_status",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                    title_status = 0
                }
            });
            title_status = 0;
            overview_status = 0;
            var e = 0;
            e = o + price_status + address_status + listing_status + photo_status + overview_status;
            var t = 6 - e;
            if (t != 0) {
                jQuery("#steps").replaceWith('<span id="steps">' + t + " steps</span>");
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#list_space").hide();
                        jQuery("#steps_count").show()
                    }
                })
            } else if (t == 0) {
                jQuery("#list_space").show();
                jQuery("#steps_count").hide()
            }
        }
        if (jQuery.trim(jQuery("#summary").val()).length == 0) {
            jQuery.ajax({
                url: base_url + "rooms/change_summary_status",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                    summary_status = 0
                }
            });
            summary_status = 0;
            overview_status = 0;
            var e = 0;
            e = o + price_status + address_status + listing_status + photo_status + overview_status;
            var t = 6 - e;
            if (t != 0) {
                jQuery("#steps").replaceWith('<span id="steps">' + t + " steps</span>");
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#list_space").hide();
                        jQuery("#steps_count").show()
                    }
                })
            } else if (t == 0) {
                jQuery("#list_space").show();
                jQuery("#steps_count").hide()
            }
        }
        if (o == 1) {
            jQuery("#cal").show();
            jQuery("#cal_after").hide();
            jQuery("#cal1").hide()
        } else {
            jQuery("#cal").show();
            jQuery("#cal1").hide()
        }
        if (price_status == 1) {
            jQuery("#price_after").hide();
            jQuery("#price").show();
            jQuery("#des_plus").hide();
            jQuery("#des_plus_after").show();
            u = jQuery("#night_price").val();
            var e = 0;
            e = o + price_status + address_status + listing_status + photo_status + overview_status;
            var t = 6 - e;
            if (t != 0) {
                jQuery("#steps").replaceWith('<span id="steps">' + t + " steps</span>");
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#list_space").hide();
                        jQuery("#steps_count").show()
                    }
                })
            } else if (t == 0) {
                jQuery("#list_space").show();
                jQuery("#steps_count").hide()
            }
        } else {
            jQuery("#price_after").hide();
            jQuery("#price").show();
            jQuery("#des_plus_after").hide();
            jQuery("#des_plus").show()
        }
        if (v == 1) {
            jQuery("#amenities").show();
            jQuery("#amenities_after").hide()
        }
        if (photo_status == 1) {
            jQuery("#photo_after").hide();
            jQuery("#photo").show();
            jQuery("#photo_plus").hide();
            jQuery("#photo_grn").show()
        } else {
            jQuery("#photo_after").hide();
            jQuery("#photo").show();
            jQuery("#photo_grn").hide();
            jQuery("#photo_plus").show()
        }
        if (beds_status == 1 && bathrooms_status == 1 && bedscount_status == 1 && bedtype_status == 1) {
            jQuery("#listing").show();
            jQuery("#listing_after").hide();
            jQuery("#list_plus").hide();
            jQuery("#list_plus_after").show()
        } else {
            jQuery("#listing").show();
            jQuery("#listing_after").hide()
        }
        if (address_status == 1) {
            jQuery("#address_after").hide();
            jQuery("#address_side").show();
            jQuery("#addr_plus_after_grn").show();
            jQuery("#address_before").hide();
            jQuery("#addr_plus").hide()
        } else {
            jQuery("#address_after").hide();
            jQuery("#address_side").show()
        }
        if (title_status == 1 && summary_status == 1) {
            jQuery("#overview_after").hide();
            jQuery("#overview").show();
            jQuery("#over_plus").hide();
            jQuery("#over_plus_after1").hide();
            jQuery("#over_plus_after").show()
        } else {
            jQuery("#overview_after").hide();
            jQuery("#overview").show();
            jQuery("#over_plus_after").hide();
            jQuery("#over_plus").show()
        }
        jQuery("#cal_container").show();
        jQuery("#price_container").hide();
        jQuery("#overview_entire").hide();
        jQuery("#amenities_entire").hide();
        jQuery("#listing_entire").hide();
        jQuery("#photos_container").hide();
        jQuery("#address_entire").hide();
        jQuery("#address_right").hide();
        jQuery("#static_circle_map").hide();
        jQuery("#detail_container").hide();
        jQuery("#terms_container").hide();
        jQuery("#cleaning-price-right").hide();
        jQuery("#additional-price-right").hide();
        jQuery("#terms_side").show();
        jQuery("#terms_side_after").hide();
        if (y == 1) {
            jQuery("#detail_side").show();
            jQuery("#detail_side_after").hide();
            jQuery("#detail_plus").hide()
        } else {
            jQuery("#detail_side_after").hide();
            jQuery("#detail_side").show();
            jQuery("#detail_plus").show()
        }
    });
    var b = 0;
    jQuery("#advance_price").click(function() {
        jQuery("#advance_price").hide();
        jQuery("#advance_price1").hide();
        jQuery("#advance_price_after").show();
        jQuery("#advance_price_after1").show()
    });
    if (cleaning_fee != 0) {
        jQuery("#listing_cleaning_fee_native_checkbox").prop("checked", true)
    }
    if (jQuery("#listing_cleaning_fee_native_checkbox").prop("checked") == true) {
        jQuery("#clean_textbox").show();
        jQuery.ajax({
            url: base_url + "rooms/get_cleaning_price",
            type: "POST",
            data: {
                room_id: room_id
            },
            success: function(e) {
                jQuery("#clean_textbox").val(e)
            }
        })
    }
    if (extra_guest_price != 0) {
        jQuery("#price_for_extra_person_checkbox").prop("checked", true)
    }
    if (jQuery("#price_for_extra_person_checkbox").prop("checked") == true) {
        jQuery("#additional_textbox").show()
    }
    if (security != 0) {
        jQuery("#listing_security_deposit_native_checkbox").prop("checked", true)
    }
    if (jQuery("#listing_security_deposit_native_checkbox").prop("checked") == true) {
        jQuery("#security_textbox").show()
    }
    jQuery("#night_price").bind("keypress", function(e) {
        var t = new RegExp("^[a-zA-Z]+$");
        var n = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (!t.test(n)) {} else {
            e.preventDefault();
            return false
        }
    });
    jQuery("#seasonal_price").keyup(function(e) {
    //   var t = jQuery("#currency_symbol").text();
    // var t = jQuery("#currency_drop").text();
      //  w("USD", t, "10");
        var n = parseInt(jQuery("#currency_hidden").val());
        if (isNaN(jQuery(this).val() / 1) == false) {
            u = parseInt(jQuery(this).val());
            u = parseInt(jQuery("#seasonal_price").val());
            if (e.keyCode == 13) {
                if (u < n) {
                    jQuery("#small_length").fadeIn();
                    jQuery("#large_length").hide()
                } else if (u <= n * 1e3) {
                    jQuery("#small_length").fadeOut();
                    jQuery("#large_length").fadeOut()
                } else if (u > n * 1e3) {
                    jQuery("#large_length").fadeIn();
                    jQuery("#small_length").hide()
                } else {
                    jQuery("#small_length").fadeIn();
                    jQuery("#large_length").hide()
                }
            } else if (u < n) {
                jQuery("#small_length").fadeIn();
                jQuery("#large_length").hide()
            } else if (u <= n * 1e3) {
                jQuery("#small_length").fadeOut();
                jQuery("#large_length").fadeOut()
            } else if (u > n * 1e3) {
                jQuery("#large_length").fadeIn();
                jQuery("#small_length").hide()
            } else {
                jQuery("#small_length").fadeIn();
                jQuery("#large_length").hide()
            }
        } else {
            b = 1
        }
    });
    
    
    //edit price
  
  /*  jQuery("#currency_drop").change(function(e) {
    	var e=parseInt(jQuery("#night_price").val());
     
      var t = jQuery("#currency_drop option:selected").text();
     // alert(t);
       w("USD", t, "10");
       var n = parseInt(jQuery("#currency_hidden").val());
      // alert(n);
       // if (isNaN(jQuery(this).val() / 1) == false) {
         //   u = parseInt(jQuery(this).val());
         
         // var u = parseInt(jQuery("#night_price").val());
         
                //    jQuery("#night_price").val(e)
                
           
            if (e.keyCode == 13) {
                if (e < n) {
                   
                    jQuery("#price_plus").show();
                    jQuery("#price_plus_after").hide();
                   
                   
                    jQuery("#small_length").fadeIn();
                    jQuery("#large_length").hide()
                } else if (e <= n * 1e3) {
                   
                   jQuery("#price_plus").show();
                    jQuery("#price_plus_after").hide();
                   
                    jQuery("#small_length").fadeOut();
                    jQuery("#large_length").fadeOut()
                } else if (e > n * 1e3) {
                	
                	jQuery("#price_plus").show();
                    jQuery("#price_plus_after").hide();
                	
                    jQuery("#large_length").fadeIn();
                    jQuery("#small_length").hide()
                } else {
                	
                	jQuery("#price_plus").show();
                    jQuery("#price_plus_after").hide();
                	
                    jQuery("#small_length").fadeIn();
                    jQuery("#large_length").hide()
                }
            } else if (e < n) {
            	
            	jQuery("#price_plus").show();
                    jQuery("#price_plus_after").hide();
            	
                jQuery("#small_length").fadeIn();
                jQuery("#large_length").hide()
            } else if (e <= n * 1e3) {
            	
            	jQuery("#price_plus").show();
                    jQuery("#price_plus_after").hide();
            	
                jQuery("#small_length").fadeOut();
                jQuery("#large_length").fadeOut()
            } else if (e > n * 1e3) {
            	
            	jQuery("#price_plus").show();
                    jQuery("#price_plus_after").hide();
            	
                jQuery("#large_length").fadeIn();
                jQuery("#small_length").hide()
            } else {
            	
            	jQuery("#price_plus").show();
                    jQuery("#price_plus_after").hide();
            	
            	
                jQuery("#small_length").fadeIn();
                jQuery("#large_length").hide()
            }
       
     //  }
        
      /* else {
            b = 1
        }
        
    
    	
    });*/
    
    
    //edit price
    
    jQuery("#night_price").keyup(function(e) {
    	
        var t = jQuery("#currency_drop option:selected").text();
        w("USD", t, "10");
        var n = parseInt(jQuery("#currency_hidden").val());
        if (isNaN(jQuery(this).val() / 1) == false) {
            u = parseInt(jQuery(this).val());
            u = parseInt(jQuery("#night_price").val());
           if (e.keyCode == 13) {
                if (u < n) {
                    jQuery("#small_length").fadeIn();
                    jQuery("#large_length").hide()
                } else if (u <= n * 1e3) {
                    jQuery("#small_length").fadeOut();
                    jQuery("#large_length").fadeOut()
                } else if (u > n * 1e3) {
                    jQuery("#large_length").fadeIn();
                    jQuery("#small_length").hide()
                } else {
                    jQuery("#small_length").fadeIn();
                    jQuery("#large_length").hide()
                }
            } else if (u < n) {
                jQuery("#small_length").fadeIn();
                jQuery("#large_length").hide()
            } else if (u <= n * 1e3) {
                jQuery("#small_length").fadeOut();
                jQuery("#large_length").fadeOut()
            } else if (u > n * 1e3) {
                jQuery("#large_length").fadeIn();
                jQuery("#small_length").hide()
            } else {
                jQuery("#small_length").fadeIn();
                jQuery("#large_length").hide()
            }
        } else {
            b = 1
        }
    });
    var w = function(t, n, r) {
      // alert(r);
        jQuery.ajax({
            url: base_url + "rooms/currency_converter",
            type: "POST",
            data: {
                from: t,
                to: n,
                amount: r
            },
            success: function(t) {
                jQuery("#currency_hidden").val(t);
               jQuery.ajax({
                    url: base_url + "rooms/get_currency",
                    type: "POST",
                    data: {
                        currency: n
                    },
                    success: function(n) {
                        jQuery("#currency_symbol_hidden").val(n);
                        e(t, n)
                      //  alert(t);alert(n);
                    }
                })
            }
        })
    };
    w("USD", currency, "10");
    jQuery.ajax({
        url: base_url + "rooms/get_currency",
        type: "POST",
        data: {
            currency: currency,
            room_id: room_id
        },
        success: function(e) {
            jQuery("#price_container .js-standard-price .center_night .input-addon #currency_symbol").replaceWith('<span id="currency_symbol" class="input-prefix-curency"><b>' + e + "</b></span>");
            jQuery("#advance_price_after1 .input-addon #currency_symbol").replaceWith('<span id="currency_symbol" class="input-prefix-curency"><b>' + e + "</b></span>")
        	 jQuery("#clean_textbox .row .col-4 .input-addon #clean_currency").replaceWith('<span class="input-prefix" id="clean_currency">' + e +"</b></span>");
                jQuery("#additional_textbox .row .col-4 .input-addon #additional_currency").replaceWith('<span class="input-prefix" id="additional_currency">' + e + "</b></span>");
                jQuery("#security_textbox .row .col-4 .input-addon #security_currency").replaceWith('<span class="input-prefix" id="security_currency">' + e + "</b></span>")

        
        }
    });
    var E = 0;
    jQuery("#night_price").focusout(function() {
    
        var e = jQuery("#currency_drop option:selected").text();
     
        w("USD", e, "10");
        var t = parseInt(jQuery("#currency_hidden").val());
        
        if (b == 1) {
            return false
        }
        u = parseInt(jQuery("#night_price").val());
        if (u > t * 1e3) {
        	
           jQuery("#night_price").val(jQuery("#hidden_price").val());
            jQuery("#price_plus").hide();
            jQuery("#price_plus_after").show();
            jQuery.ajax({
                url: base_url + "rooms/get_price",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
              
              
                    jQuery("#night_price").val(e)
                }
            })
        }
       
        if (u == 0 || u == "") {
        	
            jQuery("#night_price").val(jQuery("#hidden_price").val());
            l = 0;
            E = 1;
            jQuery.ajax({
                url: base_url + "rooms/replace_price",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                    var t = 0;
                    t = o + l + address_status + listing_status + photo_status + overview_status;
                    var n = 6 - t;
                    if (n != 0) {
                        jQuery("#steps").replaceWith('<span id="steps">' + n + " steps</span>");
                        jQuery.ajax({
                            url: base_url + "rooms/final_step",
                            type: "POST",
                            data: {
                                room_id: room_id
                            },
                            success: function(e) {
                            	  jQuery("#loading").hide();
                                jQuery("#list_space").hide();
                                jQuery("#steps_count").show()
                            }
                        })
                    }
                }
            })
        }
        var n = 0;
        jQuery.ajax({
            url: base_url + "rooms/get_price",
            type: "POST",
            data: {
                room_id: room_id
            },
            success: function(e) {
            
                if (e > 1 || e < t * 1e3 || e != t * 10) {
                    n = 0
                } else {
                    n = 1
                }
            }
        });
        
        if (u < t) {
        
            jQuery("#small_length").fadeIn();
  
        jQuery("#night_price").val(jQuery("#hidden_price").val());
            u = parseInt(jQuery("#night_price").val());
            jQuery.ajax({
                url: base_url + "rooms/min_price",
                type: "POST",
                data: {
                    price: u,
                    room_id: room_id
                },
                success: function(e) {
                    jQuery("#price_plus").show();
                    jQuery("#price_plus_after").hide();
                    jQuery("#price_saving").fadeOut()
                }
            })
        }
        if (u <= t * 1e3 && u >= t) {
            jQuery("#small_length").fadeOut();
            jQuery("#large_length").fadeOut();
            jQuery("#price_saving").fadeIn();
            jQuery("#hidden_price").val(u);
           // var t = parseInt(jQuery("#currency_hidden").val());
            jQuery.ajax({
                url: base_url + "rooms/add_price",
                type: "POST",
                data: {
                    price: u,
                    room_id: room_id
                   
                },
                success: function(e) {
                    jQuery("#price_plus").hide();
                    jQuery("#price_plus_after").show();
                    jQuery("#des_plus").hide();
                    jQuery("#des_plus_after").show();
                    l = 1;
                    price_status = 1;
                    var t = 0;
                    t = o + l + address_status + listing_status + photo_status + overview_status;
                    var n = 6 - t;
                    jQuery("#steps").replaceWith('<span id="steps">' + n + " steps</span>");
                    if (n == 0) {
                        jQuery.ajax({
                            url: base_url + "rooms/final_step",
                            type: "POST",
                            data: {
                                room_id: room_id
                            },
                            success: function(e) {
                                jQuery("#steps_count").hide();
                                jQuery("#list_space").show();
                                if (E == 1) {
                                    E = 0;
                                    jQuery("#list-button").rotate3Di(720, 750)
                                }
                            }
                        })
                    }
                    jQuery("#price_saving").fadeOut()
                }
            })
        }
        if (isNaN(u)) {
            u = 0
        }
        if (u == 0) {
            jQuery.ajax({
                url: base_url + "rooms/get_price",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                   //alert(e);
                    jQuery("#night_price").val(e)
                }
            });
            jQuery("#small_length").fadeIn();
            jQuery("#large_length").hide();
            jQuery("#price_plus").show();
            jQuery("#price_plus_after").hide();
            l = 1;
            E = 1;
            jQuery.ajax({
                url: base_url + "rooms/replace_price",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                    var t = 0;
                    t = o + l + address_status + listing_status + photo_status + overview_status;
                    var n = 6 - t;
                    if (n != 0) {
                        jQuery("#steps").replaceWith('<span id="steps">' + n + " steps</span>");
                        jQuery.ajax({
                            url: base_url + "rooms/final_step",
                            type: "POST",
                            data: {
                                room_id: room_id
                            },
                            success: function(e) {
                                jQuery("#list_space").hide();
                                jQuery("#steps_count").show()
                            }
                        })
                    }
                }
            })
        }
    });
    
    



    jQuery("#currency_drop").change(function() {
    	
    	  jQuery("#loading").show();
    	 
        w("USD", jQuery(this).val(), "10");
      var t = jQuery("#currency_drop option:selected").text();
            var e=parseInt(jQuery("#night_price").val());
            
        if (isNaN(e)) {
            e = 0
        }

       jQuery.ajax({
            url: base_url + "rooms/add_currency",
            type: "POST",
            dataType: 'json',
            data: {
                currency:jQuery(this).val(),
                //currency: jQuery(this).val(),
                room_id: room_id,
                to  :t, 
              // from  :s,
                price :e
               
            },
            success: function(result) {
           
            
              jQuery("#loading").hide();
               
               var e = result['currency'] ;
              
                var a = result['amount'] ;
              
      			 jQuery("#night_price").val(a);
               
            //edit price
           
            
            //edit price
            
                jQuery("#price_container .js-standard-price .center_night .input-addon #currency_symbol").replaceWith('<span id="currency_symbol" class="input-prefix-curency"><b>' + e + "</b></span>");
                jQuery("#advance_price_after1 .input-addon #currency_symbol").replaceWith('<span id="currency_symbol" class="input-prefix-curency"><b>' + e+ "</b></span>");
                jQuery("#clean_textbox .row .col-4 .input-addon #clean_currency").replaceWith('<span class="input-prefix" id="clean_currency">' + e +"</b></span>");
                jQuery("#additional_textbox .row .col-4 .input-addon #additional_currency").replaceWith('<span class="input-prefix" id="additional_currency">' + e + "</b></span>");
                jQuery("#security_textbox .row .col-4 .input-addon #security_currency").replaceWith('<span class="input-prefix" id="security_currency">' + e + "</b></span>")

           
             
          
            //jQuery("#night_price").val(e.$amount2);
           
            }
          
        })
        
       
    });
    
    
    
    //change
//(function () {


    
  // }
    
    
    
    
    
    
    //change
    
    
    //change to month function
    

    //change to month function
    
    
    
    
    
   jQuery("#week_price").keyup(function(e) { 
   	
   	  var e = jQuery("#currency_drop option:selected").text();
        
      //  alert(e);

        var t = parseInt(jQuery("#currency_hidden").val());
          
          a = jQuery("#week_price").val();
      
          var w = function(e, t, a) {
      // alert(r);
        jQuery.ajax({
            url: base_url + "rooms/currency_converter",
            type: "POST",
            data: {
                from: e,
                to: t,
                amount: a
            },
            success: function(t) {
            	 // alert(t);
         a = jQuery("#week_price").val();
               var pre = t;
           /*            if (a < pre * 7 && a !=0) { 
            jQuery("#small_week_length").fadeIn();
         //  jQuery("#large_week_length").fadeOut();
           
        jQuery.ajax({
                url: base_url + "rooms/get_week_price",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                	
                jQuery("#loading").hide();
                   // jQuery("#week_price").val(e)
                }
            })
        }*/
       
   //   alert(pre);
   //alert(jQuery.trim(a));alert(jQuery.trim(pre) * 7);
   //alert(jQuery.trim(a) >= jQuery.trim(pre) * 7);
 
   if (jQuery.trim(a) <= (jQuery.trim(pre) * 1400 ) && jQuery.trim(a) >= jQuery.trim(pre) * 7 || jQuery.trim(a)==0 )  {
        	
            jQuery("#small_week_length").fadeOut();
            jQuery("#large_week_length").fadeOut();
            jQuery("#advance_price_saving").fadeIn();
            jQuery.ajax({
                url: base_url + "rooms/add_price",
                type: "POST",
                data: {
                    week_price: a,
                    room_id: room_id
                },
                success: function(e) {
                  jQuery("#loading").hide();
                   jQuery("#price_plus").hide();
                  jQuery("#price_plus_after").show();
                    l = 1;
                    jQuery("#advance_price_saving").fadeOut()
                }
            })
           
            
       }
          else {
            jQuery.ajax({
                url: base_url + "rooms/get_week_price",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                	
                	  jQuery("#loading").hide();
                   // jQuery("#week_price").val(e)
                }
            });
            jQuery("#small_week_length").fadeIn();
            jQuery("#large_week_length").fadeIn()
        }
        
        if (a == "" || a == 0) { 
        	
        	//jQuery("#loading").show();
        	
            jQuery.ajax({
                url: base_url + "rooms/get_week_price",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                	  jQuery("#loading").hide();
                  //  jQuery("#week_price").val(e)
                }
            });
           jQuery("#small_week_length").show();
            jQuery("#large_week_length").hide()
        }
            }
        })
    };
      
   
         w("USD", e, "10");
        
        
        if (isNaN(a)) {
            a = 0
        }
        
    });
    
    // week validation
    
    jQuery("#currency_drop").change(function() {
    	
    	  	        var e = jQuery("#currency_drop option:selected").text();
      
        
        var t = parseInt(jQuery("#currency_hidden").val());
          
          a = jQuery("#week_price").val();
     
          var w = function(e, t, a) {
      // alert(r);
        jQuery.ajax({
            url: base_url + "rooms/currency_converter",
            type: "POST",
            data: {
                from: e,
                to: t,
                amount: a
            },
            success: function(t) {
            	 // alert(t);
         a = jQuery("#week_price").val();
               var pre = t;
               
                  if (a == "" || a == 0) { 
        	
        	jQuery("#loading").show();
        	
            jQuery.ajax({
                url: base_url + "rooms/get_week_price",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                	  jQuery("#loading").hide();
                   // jQuery("#week_price").val(e)
                }
            });
           jQuery("#small_week_length").show();
            jQuery("#large_week_length").hide()
        }
                   else if (a < pre * 7 && a !=0) { 
            jQuery("#small_week_length").fadeIn();
           jQuery("#large_week_length").fadeOut();
           
           jQuery.ajax({
                url: base_url + "rooms/get_week_price",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                	
                jQuery("#loading").hide();
                   // jQuery("#week_price").val(e)
                }
            })
        }
     else if (jQuery.trim(a) <= (jQuery.trim(pre) * 1400 ) && (jQuery.trim(a) >= jQuery.trim(pre) * 7) || jQuery.trim(a)==0 )  {
        	
            jQuery("#small_week_length").fadeOut();
            jQuery("#large_week_length").fadeOut();
            jQuery("#advance_price_saving").fadeIn();
            jQuery.ajax({
                url: base_url + "rooms/add_price",
                type: "POST",
                data: {
                    week_price: a,
                    room_id: room_id
                },
                success: function(e) {
                  jQuery("#loading").hide();
            
                    l = 1;
                    jQuery("#advance_price_saving").fadeOut()
                }
            })
           
            
       }
          else {
            jQuery.ajax({
                url: base_url + "rooms/get_week_price",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                	
                	  jQuery("#loading").hide();
                //    jQuery("#week_price").val(e)
                }
            });
            jQuery("#small_week_length").fadeIn();
            jQuery("#large_week_length").fadeIn()
        }
        
     
            }
        })
    };
      
   
         w("USD", e, "10");
        
        
        if (isNaN(a)) {
            a = 0
        }
       	
    });
    
  //month  
    
       
    jQuery("#currency_drop").change(function() {
    	
    	  	        var e = jQuery("#currency_drop option:selected").text();
      
        
        var t = parseInt(jQuery("#currency_hidden").val());
          
          a = jQuery("#month_price").val();
      
          var w = function(e, t, a) {
      // alert(r);
        jQuery.ajax({
            url: base_url + "rooms/currency_converter",
            type: "POST",
            data: {
                from: e,
                to: t,
                amount: a
            },
            success: function(t) {
            	
         a = jQuery("#month_price").val();
               var pre = t;
        
     if (a < pre * 30) { 
                jQuery("#small_month_length").fadeIn();
                jQuery("#large_month_length").fadeOut();
                  
                jQuery.ajax({
                    url: base_url + "rooms/get_month_price",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                      //  jQuery("#month_price").val(e)
                    }
                })
                
          } else if ((jQuery.trim(a) <= (jQuery.trim(pre) * 6e3 ) ) || jQuery.trim(a)==0) { 
                jQuery("#small_month_length").fadeOut();
                jQuery("#large_month_length").fadeOut();
                jQuery("#advance_price_saving").fadeIn();
                jQuery.ajax({
                    url: base_url + "rooms/add_price",
                    type: "POST",
                    data: {
                        month_price: a,
                        room_id: room_id
                    },
                    success: function(e) {
                    	
                     
                        l = 1
                        jQuery("#advance_price_saving").fadeOut();
                    }
                })
           } else if (a == "" || a == 0) { 
                jQuery.ajax({
                    url: base_url + "rooms/get_month_price",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        //jQuery("#month_price").val(e)
                    }
                });
                jQuery("#small_month_length").show();
                jQuery("#large_month_length").hide()
            }
           else if (a > pre * 6e3) { 
                jQuery.ajax({
                    url: base_url + "rooms/get_month_price",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        //jQuery("#month_price").val(e)
                    }
                });
                jQuery("#small_month_length").hide();
                jQuery("#large_month_length").fadeIn()
          } else {
                jQuery.ajax({
                    url: base_url + "rooms/get_month_price",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                       // jQuery("#month_price").val(e)
                    }
                });
                jQuery("#small_month_length").fadeIn();
                jQuery("#large_month_length").hide()
            }
            }
        })
    };
      
   
         w("USD", e, "10");
        
        
        if (isNaN(a)) {
            a = 0
        }
       	
    }); 
    
    
    //month
     jQuery("#currency_drop").mouseleave(function() {
    	
    	        var e = jQuery("#currency_drop option:selected").text();
        w("USD", e, "10");
     
    	
    });
    jQuery("#week_price").focusout(function() {
   
       	  var e = jQuery("#currency_drop option:selected").text();
        
      //  alert(e);

        var t = parseInt(jQuery("#currency_hidden").val());
          
          a = jQuery("#week_price").val();
      
          var w = function(e, t, a) {
      // alert(r);
        jQuery.ajax({
            url: base_url + "rooms/currency_converter",
            type: "POST",
            data: {
                from: e,
                to: t,
                amount: a
            },
            success: function(t) {
            	 // alert(t);
         a = jQuery("#week_price").val();
               var pre = t;
           /*            if (a < pre * 7 && a !=0) { 
            jQuery("#small_week_length").fadeIn();
         //  jQuery("#large_week_length").fadeOut();
           
        jQuery.ajax({
                url: base_url + "rooms/get_week_price",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                	
                jQuery("#loading").hide();
                   // jQuery("#week_price").val(e)
                }
            })
        }*/
       
      // alert(jQuery.trim(pre) * 1400 );
        
   if (jQuery.trim(a) <= (jQuery.trim(pre) * 1400 )  && (jQuery.trim(a) >= jQuery.trim(pre) * 7) || jQuery.trim(a)==0 )  {
        	
            jQuery("#small_week_length").fadeOut();
            jQuery("#large_week_length").fadeOut();
            jQuery("#advance_price_saving").fadeIn();
            jQuery.ajax({
                url: base_url + "rooms/add_price",
                type: "POST",
                data: {
                    week_price: a,
                    room_id: room_id
                },
                success: function(e) {
                  jQuery("#loading").hide();
    
                    l = 1;
                    jQuery("#advance_price_saving").fadeOut()
                }
            })
           
            
       }
          else {
            jQuery.ajax({
                url: base_url + "rooms/get_week_price",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                	
                	  jQuery("#loading").hide();
                   // jQuery("#week_price").val(e)
                }
            });
            jQuery("#small_week_length").fadeIn();
            jQuery("#large_week_length").fadeIn()
        }
        
        if (a == "" || a == 0) { 
        	
        	//jQuery("#loading").show();
        	
            jQuery.ajax({
                url: base_url + "rooms/get_week_price",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                	  jQuery("#loading").hide();
                  //  jQuery("#week_price").val(e)
                }
            });
           jQuery("#small_week_length").show();
            jQuery("#large_week_length").hide()
        }
            }
        })
    };
      
   
         w("USD", e, "10");
        
        
        if (isNaN(a)) {
            a = 0
        }
        
    
    });
   jQuery("#month_price").keyup(function(e) {

 var e = jQuery("#currency_drop option:selected").text();
      
        
        var t = parseInt(jQuery("#currency_hidden").val());
          
          a = jQuery("#month_price").val();
      
          var w = function(e, t, a) {
      // alert(r);
        jQuery.ajax({
            url: base_url + "rooms/currency_converter",
            type: "POST",
            data: {
                from: e,
                to: t,
                amount: a
            },
            success: function(t) {
            	
         a = jQuery("#month_price").val();
               var pre = t;
               
                    if (a < pre * 30 && jQuery.trim(a)!=0) { 
                jQuery("#small_month_length").fadeIn();
                jQuery("#large_month_length").fadeOut();
                  
                jQuery.ajax({
                    url: base_url + "rooms/get_month_price",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                      //  jQuery("#month_price").val(e)
                    }
                })
                
          }
 else if ((jQuery.trim(a) <= (jQuery.trim(pre) * 6e3 ) ) || jQuery.trim(a)==0) {
                jQuery("#small_month_length").fadeOut();
                jQuery("#large_month_length").fadeOut();
                jQuery("#advance_price_saving").fadeIn();
                jQuery.ajax({
                    url: base_url + "rooms/add_price",
                    type: "POST",
                    data: {
                        month_price: a,
                        room_id: room_id
                    },
                    success: function(e) {
                    	
                        jQuery("#price_plus").hide();
                        jQuery("#price_plus_after").show();
                        jQuery("#advance_price_saving").fadeOut();
                        l = 1
                    }
                })
           } else if (a == "" || a == 0) { 
                jQuery.ajax({
                    url: base_url + "rooms/get_month_price",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#month_price").val(e)
                    }
                });
                jQuery("#small_month_length").show();
                jQuery("#large_month_length").hide()
            }
           else if (a > pre * 6e3) { 
                jQuery.ajax({
                    url: base_url + "rooms/get_month_price",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        //jQuery("#month_price").val(e)
                    }
                });
                jQuery("#small_month_length").hide();
                jQuery("#large_month_length").fadeIn()
          } else {
                jQuery.ajax({
                    url: base_url + "rooms/get_month_price",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                       // jQuery("#month_price").val(e)
                    }
                });
                jQuery("#small_month_length").fadeIn();
                jQuery("#large_month_length").hide()
            }
            }
        })
    };
      
   
         w("USD", e, "10");
        
        
        if (isNaN(a)) {
            a = 0
        }
        
    });
    
    
      jQuery("#month_price").focusout(function(e) {

 var e = jQuery("#currency_drop option:selected").text();
      
        
        var t = parseInt(jQuery("#currency_hidden").val());
          
          a = jQuery("#month_price").val();
      
          var w = function(e, t, a) {
      // alert(r);
        jQuery.ajax({
            url: base_url + "rooms/currency_converter",
            type: "POST",
            data: {
                from: e,
                to: t,
                amount: a
            },
            success: function(t) {
            	
         a = jQuery("#month_price").val();
               var pre = t;
               
                    if (a < pre * 30 && jQuery.trim(a)!=0) { 
                jQuery("#small_month_length").fadeIn();
                jQuery("#large_month_length").fadeOut();
                  
                jQuery.ajax({
                    url: base_url + "rooms/get_month_price",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                      //  jQuery("#month_price").val(e)
                    }
                })
                
          }
 else if ((jQuery.trim(a) <= (jQuery.trim(pre) * 6e3 ) ) || jQuery.trim(a)==0) { 
                jQuery("#small_month_length").fadeOut();
                jQuery("#large_month_length").fadeOut();
                jQuery("#advance_price_saving").fadeIn();
                jQuery.ajax({
                    url: base_url + "rooms/add_price",
                    type: "POST",
                    data: {
                        month_price: a,
                        room_id: room_id
                    },
                    success: function(e) {
                    	
                        jQuery("#price_plus").hide();
                        jQuery("#price_plus_after").show();
                        jQuery("#advance_price_saving").fadeOut();
                        l = 1
                    }
                })
           } else if (a == "" || a == 0) { 
                jQuery.ajax({
                    url: base_url + "rooms/get_month_price",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#month_price").val(e)
                    }
                });
                jQuery("#small_month_length").show();
                jQuery("#large_month_length").hide()
            }
           else if (a > pre * 6e3) { 
                jQuery.ajax({
                    url: base_url + "rooms/get_month_price",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        //jQuery("#month_price").val(e)
                    }
                });
                jQuery("#small_month_length").hide();
                jQuery("#large_month_length").fadeIn()
          } else {
          	
                jQuery.ajax({
                    url: base_url + "rooms/get_month_price",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                       // jQuery("#month_price").val(e)
                    }
                });
                jQuery("#small_month_length").fadeIn();
                jQuery("#large_month_length").hide()
            }
            
              if (a == "" || a == 0) { 
                jQuery.ajax({
                    url: base_url + "rooms/get_month_price",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#month_price").val(e)
                    }
                });
                jQuery("#small_month_length").show();
                jQuery("#large_month_length").hide()
            }
            
            
            }
        })
    };
      
   
         w("USD", e, "10");
        
        
        if (isNaN(a)) {
            a = 0
        }
        
    });
    
    
    
    
    
    
    
    
    
    jQuery("#night_price").keydown(function(e) {
        if (e.shiftKey || e.ctrlKey || e.altKey) {
            e.preventDefault()
        } else {
            var t = e.keyCode;
            if (!(t >= 48 && t <= 57 || t >= 96 && t <= 105 || t == 8 || t == 110 || t == 190)) {
                e.preventDefault()
            }
        }
    });
    jQuery("#week_price").keydown(function(e) {
        if (e.shiftKey || e.ctrlKey || e.altKey) {
            e.preventDefault()
        } else {
            var t = e.keyCode;
            if (!(t >= 48 && t <= 57 || t >= 96 && t <= 105 || t == 8 || t == 110 || t == 190)) {
                e.preventDefault()
            }
        }
    });
    jQuery("#month_price").keydown(function(e) {
        if (e.shiftKey || e.ctrlKey || e.altKey) {
            e.preventDefault()
        } else {
            var t = e.keyCode;
            if (!(t >= 48 && t <= 57 || t >= 96 && t <= 105 || t == 8 || t == 110 || t == 190)) {
                e.preventDefault()
            }
        }
    });
    jQuery("#price_index").keydown(function(e) {
        if (e.shiftKey || e.ctrlKey || e.altKey) {
            e.preventDefault()
        } else {
            var t = e.keyCode;
            if (!(t >= 48 && t <= 57 || t >= 96 && t <= 105 || t == 8 || t == 110 || t == 190)) {
                e.preventDefault()
            }
        }
    });
    jQuery("#cleaning_price").keydown(function(e) {
        if (e.shiftKey || e.ctrlKey || e.altKey) {
            e.preventDefault()
        } else {
            var t = e.keyCode;
            if (!(t >= 48 && t <= 57 || t >= 96 && t <= 105 || t == 8 || t == 110 || t == 190)) {
                e.preventDefault()
            }
        }
    });
    jQuery("#month_price").keydown(function(e) {
        if (e.shiftKey || e.ctrlKey || e.altKey) {
            e.preventDefault()
        } else {
            var t = e.keyCode;
            if (!(t >= 48 && t <= 57 || t >= 96 && t <= 105 || t == 8 || t == 110 || t == 190)) {
                e.preventDefault()
            }
        }
    });
   /* jQuery("#title").keydown(function(e) {
        if (e.shiftKey || e.ctrlKey || e.altKey) {
            e.preventDefault()
        } else {
            var t = e.keyCode;
            if (!(t == 8 || t >= 65 && t <= 90 || t == 32 || t == 190)) {
                e.preventDefault()
            }
        }
    });*/
    /*jQuery("#summary").keydown(function(e) {
        if (e.shiftKey || e.ctrlKey || e.altKey) {
            e.preventDefault()
        } else {
            var t = e.keyCode;
            if (!(t == 8 || t >= 65 && t <= 90 || t == 32 || t == 190)) {
                e.preventDefault()
            }
        }
    });*/
   
   // month validation
   

    jQuery("#listing_cleaning_fee_native_checkbox").change(function() {
        if (this.checked) { 
            jQuery("#clean_textbox").show();
            jQuery.ajax({
                url: base_url + "rooms/get_cleaning_price",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                    jQuery("#cleaning_price").val(e)
                }
            })
        } else {
            jQuery("#clean_textbox").hide();
            jQuery.ajax({
                url: base_url + "rooms/cleaning_price",
                type: "POST",
                data: {
                    room_id: room_id,
                    cleaning_price: 0
                },
                success: function(e) {}
            })
        }
    });
    jQuery("#price_for_extra_person_checkbox").change(function() {
        if (this.checked) {
            jQuery("#additional_textbox").show();
            jQuery.ajax({
                url: base_url + "rooms/get_guest_count",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                    jQuery("#extra_guest_count").val(e)
                }
            });
            jQuery.ajax({
                url: base_url + "rooms/get_extra_guest_price",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                    jQuery("#extra_guest_price").val(e)
                }
            })
        } else {
            jQuery("#additional_textbox").hide();
            jQuery.ajax({
                url: base_url + "rooms/guest_count",
                type: "POST",
                data: {
                    room_id: room_id,
                    guest_count: 1
                },
                success: function(e) {}
            });
            jQuery.ajax({
                url: base_url + "rooms/extra_guest_price",
                type: "POST",
                data: {
                    room_id: room_id,
                    guest_price: 0
                },
                success: function(e) {}
            })
        }
    });
    jQuery("#listing_security_deposit_native_checkbox").change(function() {
        if (this.checked) {
            jQuery("#security_textbox").show();
            jQuery.ajax({
                url: base_url + "rooms/get_security_price",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                    jQuery("#security_price_textbox").val(e)
                }
            })
        } else {
            jQuery("#security_textbox").hide();
            jQuery.ajax({
                url: base_url + "rooms/security_price",
                type: "POST",
                data: {
                    room_id: room_id,
                    security_price: 0
                },
                success: function(e) {
                    jQuery("#clean_price_saving").fadeIn();
                    jQuery("#clean_price_saving").fadeOut()
                }
            })
        }
    });
    jQuery("#cleaning_price").bind("paste", function(e) {
        e.preventDefault()
    });
    jQuery("#security_price_textbox").bind("paste", function(e) {
        e.preventDefault()
    });
    jQuery("#extra_guest_price").bind("paste", function(e) {
        e.preventDefault()
    });
    jQuery("#week_price").bind("paste", function(e) {
        e.preventDefault()
    });
    jQuery("#month_price").bind("paste", function(e) {
        e.preventDefault()
    });
    jQuery("#night_price").bind("paste", function(e) {
        e.preventDefault()
    });
    jQuery("#cleaning_price").keyup(function() {
        var e = jQuery.trim(jQuery(this).val());
        var t = jQuery("#currency_drop option:selected").text();
        w("USD", t, "5");
        setTimeout(function() {
            var t = parseInt(jQuery("#currency_hidden").val());
            if (e < t) {
                jQuery("#large_clean_length").hide();
                jQuery("#small_clean_length").show()
            }
            if (e > t * 60) {
                jQuery("#small_clean_length").hide();
                jQuery("#large_clean_length").show()
            }
            if (e >= t && e <= t * 60) {
                jQuery("#small_clean_length").hide();
                jQuery("#large_clean_length").hide();
                jQuery.ajax({
                    url: base_url + "rooms/cleaning_price",
                    type: "POST",
                    data: {
                        room_id: room_id,
                        cleaning_price: e
                    },
                    success: function(e) {
                        jQuery("#clean_price_saving").fadeIn();
                        jQuery("#clean_price_saving").fadeOut()
                    }
                })
            }
        }, 500)
    });
    jQuery("#security_price_textbox").keyup(function() {
        var e = jQuery.trim(jQuery(this).val());
       
        var t = jQuery("#currency_drop option:selected").text();
        w("USD", t, "10");
        setTimeout(function() {
            var t = parseInt(jQuery("#currency_hidden").val());
            if (e < t) {
                jQuery("#large_security_length").hide();
                jQuery("#small_security_length").show()
            }
            if (e > t * 60) {
                jQuery("#small_security_length").hide();
                jQuery("#large_security_length").show()
            }
            if (e >= t && e <= t * 60) {
                jQuery("#small_security_length").hide();
                jQuery("#large_security_length").hide();
                jQuery.ajax({
                    url: base_url + "rooms/security_price",
                    type: "POST",
                    data: {
                        room_id: room_id,
                        security_price: e
                    },
                    success: function(e) {
                        jQuery("#clean_price_saving").fadeIn();
                        jQuery("#clean_price_saving").fadeOut()
                    }
                })
            }
        }, 500)
    });
    jQuery("#extra_guest_count").change(function() {
        jQuery.ajax({
            url: base_url + "rooms/guest_count",
            type: "POST",
            data: {
                room_id: room_id,
                guest_count: jQuery(this).val()
            },
            success: function(e) {}
        })
    });
    jQuery("#extra_guest_price").keyup(function() {
        var e = jQuery.trim(jQuery(this).val());
        var t = jQuery("#currency_drop option:selected").text();
        w("USD", t, "5");
        setTimeout(function() {
            var t = parseInt(jQuery("#currency_hidden").val());
            if (e < t) {
                jQuery("#large_additional_length").hide();
                jQuery("#small_additional_length").show()
            }
            if (e > t * 60) {
                jQuery("#small_additional_length").hide();
                jQuery("#large_additional_length").show()
            }
            if (e >= t && e <= t * 60) {
                jQuery("#small_additional_length").hide();
                jQuery("#large_additional_length").hide();
                jQuery.ajax({
                    url: base_url + "rooms/extra_guest_price",
                    type: "POST",
                    data: {
                        room_id: room_id,
                        guest_price: e
                    },
                    success: function(e) {
                        jQuery("#clean_price_saving").fadeIn();
                        jQuery("#clean_price_saving").fadeOut()
                    }
                });
                var n = jQuery("#extra_guest_count").val();
                jQuery.ajax({
                    url: base_url + "rooms/guest_count",
                    type: "POST",
                    data: {
                        room_id: room_id,
                        guest_count: n
                    },
                    success: function(e) {}
                })
            }
        }, 500)
    });
    jQuery("#overview").click(function() {
     
        if (jQuery.trim(jQuery("#title").val()).length == 0) {
            jQuery.ajax({
                url: base_url + "rooms/change_title_status",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                   title_status = 0
                }
            });
            title_status = 0;
            overview_status = 0;
            var e = 0;
            e = o + price_status + address_status + listing_status + photo_status + overview_status;
            var t = 6 - e;
            if (t != 0) {
                jQuery("#steps").replaceWith('<span id="steps">' + t + " steps</span>");
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#list_space").hide();
                        jQuery("#steps_count").show()
                    }
                })
            } else if (t == 0) {
                jQuery("#list_space").show();
                jQuery("#steps_count").hide()
            }
        }
        if (jQuery.trim(jQuery("#summary").val()).length == 0) {
            jQuery.ajax({
                url: base_url + "rooms/change_summary_status",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                    summary_status = 0
                }
            });
            summary_status = 0;
            overview_status = 0;
            var e = 0;
            e = o + price_status + address_status + listing_status + photo_status + overview_status;
            var t = 6 - e;
            if (t != 0) {
                jQuery("#steps").replaceWith('<span id="steps">' + t + " steps</span>");
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#list_space").hide();
                        jQuery("#steps_count").show()
                    }
                })
            } else if (t == 0) {
                jQuery("#list_space").show();
                jQuery("#steps_count").hide()
            }
        }
        jQuery("#price-right-hover").hide();
        jQuery("#ded").hide();
        jQuery("#overview-textbox-hover").show();
        if (o == 0) {
            jQuery("#cal").hide();
            jQuery("#cal1").show();
            if (overview_status == 0) {
                jQuery("#overview").hide();
                jQuery("#overview_after").show()
            } else if (overview_status == 1) {
                jQuery("#overview").hide();
                jQuery("#overview_after").show();
                jQuery("#over_plus1").hide();
                jQuery("#over_plus_after1").show();
                jQuery("#over_plus_after").hide()
            }
        } else {
            jQuery("#cal").hide();
            jQuery("#cal_after").show();
            jQuery("#cal1").hide()
        }
        if (photo_status == 1) {
            jQuery("#photo_after").hide();
            jQuery("#photo").show();
            jQuery("#photo_plus").hide();
            jQuery("#photo_grn").show()
        } else {
            jQuery("#photo_after").hide();
            jQuery("#photo").show();
            jQuery("#photo_grn").hide();
            jQuery("#photo_plus").show()
        }
        if (price_status == 0) {
            jQuery("#price_after").hide();
            jQuery("#price").show();
            jQuery("#des_plus_after").hide();
            jQuery("#des_plus").show()
        } else {
            jQuery("#price_after").hide();
            jQuery("#price").show();
            jQuery("#price_plus_after").hide();
            jQuery("#des_plus_after").show();
            jQuery("#des_plus").hide();
            u = jQuery("#night_price").val();
            var e = 0;
            e = o + price_status + address_status + listing_status + photo_status + overview_status;
            var t = 6 - e;
            if (t != 0) {
                jQuery("#steps").replaceWith('<span id="steps">' + t + " steps</span>");
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#list_space").hide();
                        jQuery("#steps_count").show()
                    }
                })
            } else if (t == 0) {
                jQuery("#list_space").show();
                jQuery("#steps_count").hide()
            }
        }
        if (overview_status == 1) {
            jQuery("#overview").hide();
            jQuery("#overview_after").show();
            jQuery("#over_plus1").hide();
            jQuery("#over_plus_after1").show()
        }
        if (overview_status == 0) {
            jQuery("#overview").hide();
            jQuery("#overview_after").show()
        }
        if (v == 1) {
            jQuery("#amenities").show();
            jQuery("#amenities_after").hide()
        }
        if (beds_status == 1 && bathrooms_status == 1 && bedscount_status == 1 && bedtype_status == 1) {
            jQuery("#listing").show();
            jQuery("#listing_after").hide();
            jQuery("#list_plus").hide();
            jQuery("#list_plus_after").show()
        } else {
            jQuery("#listing").show();
            jQuery("#listing_after").hide()
        }
        if (address_status == 1) {
            jQuery("#address_after").hide();
            jQuery("#address_side").show();
            jQuery("#addr_plus_after_grn").show();
            jQuery("#address_before").hide();
            jQuery("#addr_plus").hide()
        } else {
            jQuery("#address_after").hide();
            jQuery("#address_side").show()
        }
        if (title_status == 1 && summary_status == 1) {
            jQuery("#overview_after").show();
            jQuery("#overview").hide();
            jQuery("#over_plus_after1").show()
        } else {
            jQuery("#overview").hide();
            jQuery("#overview_after").show();
            jQuery("#over_plus_after1").hide();
            jQuery("#over_plus1").show()
        }
        jQuery("#cal_container").hide();
        jQuery("#price_container").hide();
        jQuery("#overview_entire").show();
        jQuery("#amenities_entire").hide();
        jQuery("#listing_entire").hide();
        jQuery("#photos_container").hide();
        jQuery("#address_entire").hide();
        jQuery("#address_right").hide();
        jQuery("#static_circle_map").hide();
        jQuery("#detail_container").hide();
        jQuery("#terms_container").hide();
        jQuery("#cleaning-price-right").hide();
        jQuery("#additional-price-right").hide();
        jQuery("#terms_side").show();
        jQuery("#terms_side_after").hide();
        if (y == 1) {
            jQuery("#detail_side").show();
            jQuery("#detail_side_after").hide();
            jQuery("#detail_plus").hide()
        } else {
            jQuery("#detail_side_after").hide();
            jQuery("#detail_side").show();
            jQuery("#detail_plus").show()
        }
    });
    jQuery("#amenities").click(function() {
        if (jQuery.trim(jQuery("#title").val()).length == 0) {
            jQuery.ajax({
                url: base_url + "rooms/change_title_status",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                    title_status = 0
                }
            });
            title_status = 0;
            overview_status = 0;
            var e = 0;
            e = o + price_status + address_status + listing_status + photo_status + overview_status;
            var t = 6 - e;
            if (t != 0) {
                jQuery("#steps").replaceWith('<span id="steps">' + t + " steps</span>");
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#list_space").hide();
                        jQuery("#steps_count").show()
                    }
                })
            } else if (t == 0) {
                jQuery("#list_space").show();
                jQuery("#steps_count").hide()
            }
        }
        if (jQuery.trim(jQuery("#summary").val()).length == 0) {
            jQuery.ajax({
                url: base_url + "rooms/change_summary_status",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                    summary_status = 0
                }
            });
            summary_status = 0;
            overview_status = 0;
            var e = 0;
            e = o + price_status + address_status + listing_status + photo_status + overview_status;
            var t = 6 - e;
            if (t != 0) {
                jQuery("#steps").replaceWith('<span id="steps">' + t + " steps</span>");
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#list_space").hide();
                        jQuery("#steps_count").show()
                    }
                })
            } else if (t == 0) {
                jQuery("#list_space").show();
                jQuery("#steps_count").hide()
            }
        }
        jQuery("#price-right-hover").hide();
        jQuery("#ded").hide();
        jQuery("#overview-textbox-hover").hide();
        v = 1;
        jQuery("#cal_container").hide();
        jQuery("#price_container").hide();
        jQuery("#overview_entire").hide();
        jQuery("#photo_entire").hide();
        jQuery("#amenities").hide();
        jQuery("#amenities_after").show();
        jQuery("#amenities_entire").show();
        jQuery("#listing_entire").hide();
        jQuery("#photos_container").hide();
        jQuery("#address_entire").hide();
        jQuery("#address_right").hide();
        jQuery("#static_circle_map").hide();
        jQuery("#detail_container").hide();
        jQuery("#terms_container").hide();
        jQuery("#cleaning-price-right").hide();
        jQuery("#additional-price-right").hide();
        jQuery("#terms_side").show();
        jQuery("#terms_side_after").hide();
        if (o == 0) {
            jQuery("#cal").hide();
            jQuery("#cal1").show()
        } else {
            jQuery("#cal").hide();
            jQuery("#cal_after").show();
            jQuery("#cal1").hide()
        }
        if (overview_status == 0) {
            jQuery("#overview").show();
            jQuery("#overview_after").hide()
        } else {
            jQuery("#overview_after").hide();
            jQuery("#overview").show();
            jQuery("#over_plus").hide();
            jQuery("#over_plus_after1").hide();
            jQuery("#over_plus_after").show()
        }
        if (photo_status == 1) {
            jQuery("#photo_after").hide();
            jQuery("#photo").show();
            jQuery("#photo_plus").hide();
            jQuery("#photo_grn").show()
        } else {
            jQuery("#photo_after").hide();
            jQuery("#photo").show();
            jQuery("#photo_grn").hide();
            jQuery("#photo_plus").show()
        }
        if (price_status == 1) {
            jQuery("#price_after").hide();
            
            jQuery("#price").show();
            jQuery("#des_plus").hide();
            jQuery("#des_plus_after").show();
            u = jQuery("#night_price").val();
            var e = 0;
            e = o + price_status + address_status + listing_status + photo_status + overview_status;
            var t = 6 - e;
            if (t != 0) {
                jQuery("#steps").replaceWith('<span id="steps">' + t + " steps</span>");
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#list_space").hide();
                        jQuery("#steps_count").show()
                    }
                })
            } else if (t == 0) {
                jQuery("#list_space").show();
                jQuery("#steps_count").hide()
            }
        } else {
            jQuery("#price_after").hide();
            jQuery("#price").show();
            jQuery("#des_plus_after").hide();
            jQuery("#des_plus").show()
        }
        if (title_status == 1 && summary_status == 1) {
            jQuery("#overview_after").hide();
            jQuery("#overview").show();
            jQuery("#over_plus").hide();
            jQuery("#over_plus_after1").hide();
            jQuery("#over_plus_after").show()
        } else {
            jQuery("#overview_after").hide();
            jQuery("#overview").show();
            jQuery("#over_plus_after").hide();
            jQuery("#over_plus").show()
        }
        if (address_status == 1) {
            jQuery("#address_after").hide();
            jQuery("#address_side").show();
            jQuery("#addr_plus_after_grn").show();
            jQuery("#address_before").hide();
            jQuery("#addr_plus").hide()
        } else {
            jQuery("#address_after").hide();
            jQuery("#address_side").show()
        }
        if (y == 1) {
            jQuery("#detail_side").show();
            jQuery("#detail_side_after").hide();
            jQuery("#detail_plus").hide()
        } else {
            jQuery("#detail_side_after").hide();
            jQuery("#detail_side").show();
            jQuery("#detail_plus").show()
        }
        if (beds_status == 1 && bathrooms_status == 1 && bedscount_status == 1 && bedtype_status == 1) {
            jQuery("#listing").show();
            jQuery("#listing_after").hide();
            jQuery("#list_plus").hide();
            jQuery("#list_plus_after").show()
        } else {
            jQuery("#listing").show();
            jQuery("#listing_after").hide()
        }
    });
    jQuery("input:checkbox").change(function() {
        jQuery("#amenities_saving").fadeIn();
        if (this.checked) {
            jQuery.ajax({
                url: base_url + "rooms/add_amenities",
                type: "POST",
                data: {
                    amenity: jQuery(this).val(),
                    room_id: room_id
                },
                success: function(e) {
                    amenities_status = 1
                }
            })
        } else {
            var e = [];
            jQuery("input:checkbox").each(function() {
                if (this.checked) {
                    e.push(jQuery(this).val())
                }
            });
            jQuery.ajax({
                url: base_url + "rooms/delete_amenities",
                type: "POST",
                data: {
                    amenity: e,
                    room_id: room_id
                },
                success: function(e) {
                    amenities_status = 1
                }
            })
        }
        jQuery("#amenities_saving").fadeOut()
    });
    jQuery("#listing").click(function() {
        if (jQuery.trim(jQuery("#title").val()).length == 0) {
            jQuery.ajax({
                url: base_url + "rooms/change_title_status",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                    title_status = 0
                }
            });
            title_status = 0;
            overview_status = 0;
            var e = 0;
            e = o + price_status + address_status + listing_status + photo_status + overview_status;
            var t = 6 - e;
            if (t != 0) {
                jQuery("#steps").replaceWith('<span id="steps">' + t + " steps</span>");
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#list_space").hide();
                        jQuery("#steps_count").show()
                    }
                })
            } else if (t == 0) {
                jQuery("#list_space").show();
                jQuery("#steps_count").hide()
            }
        }
        if (jQuery.trim(jQuery("#summary").val()).length == 0) {
            jQuery.ajax({
                url: base_url + "rooms/change_summary_status",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                    summary_status = 0
                }
            });
            summary_status = 0;
            overview_status = 0;
            var e = 0;
            e = o + price_status + address_status + listing_status + photo_status + overview_status;
            var t = 6 - e;
            if (t != 0) {
                jQuery("#steps").replaceWith('<span id="steps">' + t + " steps</span>");
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#list_space").hide();
                        jQuery("#steps_count").show()
                    }
                })
            } else if (t == 0) {
                jQuery("#list_space").show();
                jQuery("#steps_count").hide()
            }
        }
        jQuery("#price-right-hover").hide();
        jQuery("#ded").hide();
        jQuery("#overview-textbox-hover").hide();
        jQuery("#cal_container").hide();
        jQuery("#price_container").hide();
        jQuery("#overview_entire").hide();
        jQuery("#photo_entire").hide();
        jQuery("#amenities_entire").hide();
        jQuery("#listing_entire").show();
        jQuery("#photos_container").hide();
        jQuery("#address_entire").hide();
        jQuery("#address_right").hide();
        jQuery("#static_circle_map").hide();
        jQuery("#detail_container").hide();
        jQuery("#terms_container").hide();
        jQuery("#cleaning-price-right").hide();
        jQuery("#additional-price-right").hide();
        jQuery("#terms_side").show();
        jQuery("#terms_side_after").hide();
        if (o == 0) {
            jQuery("#cal").hide();
            jQuery("#cal1").show()
        } else {
            jQuery("#cal").hide();
            jQuery("#cal_after").show();
            jQuery("#cal1").hide()
        }
        if (beds_status == 1 && bathrooms_status == 1 && bedscount_status == 1 && bedtype_status == 1) {
            jQuery("#listing").hide();
            jQuery("#listing_after").show();
            jQuery("#listing_plus1").hide();
            jQuery("#listing_plus_after1").show()
        } else {
            jQuery("#listing").hide();
            jQuery("#listing_after").show()
        }
        if (price_status == 1) {
            jQuery("#price_after").hide();
            jQuery("#price").show();
            jQuery("#des_plus").hide();
            jQuery("#des_plus_after").show();
            u = jQuery("#night_price").val();
            var e = 0;
            e = o + price_status + address_status + listing_status + photo_status + overview_status;
            var t = 6 - e;
            if (t != 0) {
                jQuery("#steps").replaceWith('<span id="steps">' + t + " steps</span>");
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#list_space").hide();
                        jQuery("#steps_count").show()
                    }
                })
            } else if (t == 0) {
                jQuery("#list_space").show();
                jQuery("#steps_count").hide()
            }
        } else {
            jQuery("#price_after").hide();
            jQuery("#price").show();
            jQuery("#des_plus_after").hide();
            jQuery("#des_plus").show()
        }
        if (address_status == 1) {
            jQuery("#address_after").hide();
            jQuery("#address_side").show();
            jQuery("#addr_plus_after_grn").show();
            jQuery("#address_before").hide();
            jQuery("#addr_plus").hide()
        } else {
            jQuery("#address_after").hide();
            jQuery("#address_side").show()
        }
        if (photo_status == 1) {
            jQuery("#photo_after").hide();
            jQuery("#photo").show();
            jQuery("#photo_plus").hide();
            jQuery("#photo_grn").show()
        } else {
            jQuery("#photo_after").hide();
            jQuery("#photo").show();
            jQuery("#photo_grn").hide();
            jQuery("#photo_plus").show()
        }
        if (title_status == 1 && summary_status == 1) {
            jQuery("#overview_after").hide();
            jQuery("#overview").show();
            jQuery("#over_plus").hide();
            jQuery("#over_plus_after1").hide();
            jQuery("#over_plus_after").show()
        } else {
            jQuery("#overview_after").hide();
            jQuery("#overview").show();
            jQuery("#over_plus_after").hide();
            jQuery("#over_plus").show()
        }
        if (y == 1) {
            jQuery("#detail_side").show();
            jQuery("#detail_side_after").hide();
            jQuery("#detail_plus").hide()
        } else {
            jQuery("#detail_side_after").hide();
            jQuery("#detail_side").show();
            jQuery("#detail_plus").show()
        }
        jQuery("#amenities").show();
        jQuery("#amenities_after").hide();
    });
    if (price_status == 1) {
        jQuery("#des_plus").hide();
        jQuery("#des_plus_after").show();
    }
    var S = 0;
    S = calendar_status + price_status + address_status + listing_status + photo_status + overview_status;
    var x = 6 - S;
    jQuery("#steps").replaceWith('<span id="steps">' + x + " steps</span>");
    if (x == 0) {
        jQuery.ajax({
            url: base_url + "rooms/final_step",
            type: "POST",
            data: {
                room_id: room_id
            },
            success: function(e) {
                jQuery("#steps_count").hide();
                jQuery("#list_space").show();
            }
        })
    }
    
   jQuery("#summary").bind("cut copy paste", function(e) {
          	var n = 250 - jQuery(this).val().length;
                if (jQuery(this).val().length < 256) {
                    if (n <= 5) {
                        jQuery("#display_count").replaceWith('<span id="display_count" style="color:#959595;float:right;text-align:right;font-weight:bold;font-size:12px;text-rendering:optimizelegibility;margin-right:-13px;"><span style="color:red;padding-bottom: 2px;">' + n + "</span> CHARACTERS LEFT</span>")
                    } else {
                        jQuery("#display_count").html(n + " CHARACTERS LEFT")
                    }
                }
    });
  
    jQuery("#summary").keyup(function(e) {
    	
    	 var t = e.which ? e.which : e.keyCode;
        var n;
        n = e.keyCode;
        jQuery("#summary").keydown(function(e) {
            var t = e.which ? e.which : e.keyCode;
            if (e.keyCode == 46 || e.keyCode == 88 || e.keyCode == 8) {
                var n = 250 - jQuery(this).val().length;
                if (jQuery(this).val().length < 256) {
                    if (n <= 5) {
                        jQuery("#display_count").replaceWith('<span id="display_count" style="color:#959595;float:right;text-align:right;font-weight:bold;font-size:12px;text-rendering:optimizelegibility;margin-right:-13px;"><span style="color:red;padding-bottom: 2px;">' + n + "</span> CHARACTERS LEFT</span>")
                    } else {
                        jQuery("#display_count").html(n + " CHARACTERS LEFT")
                    }
                }
                
            }
        });
        var r = 250 - jQuery(this).val().length;
        if (jQuery(this).val().length < 256) {
            if (r <= 5) {
                jQuery("#display_count").replaceWith('<span id="display_count" style="color:#959595;float:right;text-align:right;font-weight:bold;font-size:12px;text-rendering:optimizelegibility;margin-right:-13px;"><span style="color:red;padding-bottom: 2px;">' + r + "</span> CHARACTERS LEFT</span>")
            } else {
                jQuery("#display_count").html(r + " CHARACTERS LEFT")
            }
        }
    	
    	
        p = jQuery(this).val();
        p = jQuery.trim(p);
        if (p.length == 0) {
            jQuery("#over_plus_after1").hide();
            jQuery("#over_plus1").show();
            summary_status = 0;
            overview_status = 0;
            h = 0;
            t = 0 + price_status + address_status + listing_status + photo_status + overview_status;
            var e = 6 - t;
            if (e == 0) {
                jQuery("#list_space").show();
                jQuery("#list-button").rotate3Di(720, 750);
                jQuery("#steps_count").hide()
            } else {
                jQuery("#steps").replaceWith('<span id="steps">' + e + " steps</span>");
                jQuery("#list_space").hide();
                jQuery("#steps_count").show()
            }
            jQuery.ajax({
                url: base_url + "rooms/add_desc",
                type: "POST",
                data: {
                    desc: p,
                    room_id: room_id,
                    summary_index: h
                },
                success: function(e) {}
            })
        } else {
            summary_status = 1
        }
        if (p.length >= 1 && title_status == 1) {
            summary_status = 1;
            overview_status = 1;
            var t = 0;
            t = 1 + price_status + address_status + listing_status + photo_status + overview_status;
            var e = 6 - t;
            if (e == 0) {
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {}
                })
            }
        }
    });
    var T = 0;
    jQuery("#summary").focusout(function() {
        if (p.length == 0) {
            T = 1
        }
        if (d.length > 0 && p.length > 0) {
            jQuery("#over_plus_after1").show();
            jQuery("#over_plus1").hide()
        }
        d = jQuery.trim(jQuery("#title").val());
        if (p.length > 0) {
            jQuery.ajax({
                url: base_url + "rooms/add_desc",
                type: "POST",
                data: {
                    desc: p,
                    room_id: room_id,
                    summary_index: 1
                },
                success: function(e) {
                    jQuery("#overview_saving").fadeIn();
                    var t = 0;
                    t = 1 + price_status + address_status + listing_status + photo_status + overview_status;
                    var n = 6 - t;
                    jQuery("#steps").replaceWith('<span id="steps">' + n + " steps</span>");
                    if (n == 0) {
                        jQuery.ajax({
                            url: base_url + "rooms/final_step",
                            type: "POST",
                            data: {
                                room_id: room_id
                            },
                            success: function(e) {
                                jQuery("#steps_count").hide();
                                jQuery("#list_space").show();
                                if (T == 1) {
                                    T = 0;
                                    jQuery("#list-button").rotate3Di(720, 750)
                                }
                            }
                        })
                    }
                    jQuery("#overview_saving").fadeOut()
                }
            })
        }
    });
    jQuery("#title").bind("cut copy paste", function(e) {
    	var n = 35 - jQuery(this).val().length;
                if (jQuery(this).val().length < 36) {
                    if (n <= 5) {
                        jQuery("#chars_count").replaceWith('<span id="chars_count" style="color:#959595;float:right;text-align:right;font-weight:bold;font-size:12px;text-rendering:optimizelegibility;margin-right:-13px;"><span style="color:red;padding-bottom: 2px;">' + n + "</span> CHARACTERS LEFT</span>")
                    } else {
                        jQuery("#chars_count").html(n + " CHARACTERS LEFT")
                    }
                }
    });
    jQuery("#title").keyup(function(e) {
        var t = e.which ? e.which : e.keyCode;
        var n;
        n = e.keyCode;
        jQuery("#title").keydown(function(e) {
            var t = e.which ? e.which : e.keyCode;
            if (e.keyCode == 46 || e.keyCode == 88 || e.keyCode == 8) {
                var n = 35 - jQuery(this).val().length;
                if (jQuery(this).val().length < 36) {
                    if (n <= 5) {
                        jQuery("#chars_count").replaceWith('<span id="chars_count" style="color:#959595;float:right;text-align:right;font-weight:bold;font-size:12px;text-rendering:optimizelegibility;margin-right:-13px;"><span style="color:red;padding-bottom: 2px;">' + n + "</span> CHARACTERS LEFT</span>")
                    } else {
                        jQuery("#chars_count").html(n + " CHARACTERS LEFT")
                    }
                }
                
            }
        });
        var r = 35 - jQuery(this).val().length;
        if (jQuery(this).val().length < 36) {
            if (r <= 5) {
                jQuery("#chars_count").replaceWith('<span id="chars_count" style="color:#959595;float:right;text-align:right;font-weight:bold;font-size:12px;text-rendering:optimizelegibility;margin-right:-13px;"><span style="color:red;padding-bottom: 2px;">' + r + "</span> CHARACTERS LEFT</span>")
            } else {
                jQuery("#chars_count").html(r + " CHARACTERS LEFT")
            }
        }
        
        var i = jQuery(this).val();
        i = jQuery.trim(i);
        if (i.length == 0) {
            i = room_type_org;
            jQuery("#title_header").replaceWith('<span id="title_header">' + i + "</span>");
            c = 0;
            title_status = 0
        } else {
            jQuery("#title_header").replaceWith('<span id="title_header">' + i + "</span>");
            c = 1;
            title_status = 1
        }
    });
    jQuery("#title").keyup(function() {
        d = jQuery(this).val();
        d = jQuery.trim(d);
        if (d.length == 0) {
            jQuery("#over_plus_after1").hide();
            jQuery("#over_plus1").show();
            overview_status = 0;
            t = o + price_status + address_status + listing_status + photo_status + overview_status;
            var e = 6 - t;
            if (e == 0) {
                jQuery("#list_space").show();
                jQuery("#list-button").rotate3Di(720, 750);
                jQuery("#steps_count").hide()
            } else {
                jQuery("#steps").replaceWith('<span id="steps">' + e + " steps</span>");
                jQuery("#list_space").hide();
                jQuery("#steps_count").show()
            }
        }
        if (d.length >= 1 && summary_status == 1) {
            var t = 0;
            overview_status = 1;
            t = o + price_status + address_status + listing_status + photo_status + overview_status;
            var e = 6 - t;
            if (e == 0) {
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {}
                })
            }
        }
    });
    var N = 0;
    jQuery("#title").focusout(function() {
        if (d == "") {
            d = room_type_org
        }
        d = jQuery.trim(jQuery("#title").val());
        if (d.length == 0) {
            c = 0;
            N = 1;
            jQuery.ajax({
                url: base_url + "rooms/add_title_zero",
                type: "POST",
                data: {
                    title: room_type_org,
                    room_id: room_id,
                    title_index: c
                },
                success: function(e) {}
            })
        }
        p = jQuery.trim(jQuery("#summary").val());
        if (d.length > 0 && p.length > 0) {
            jQuery("#over_plus_after1").show();
            jQuery("#over_plus1").hide();
        }
        if (d.length > 0) {
            jQuery("#overview_saving").fadeIn();
            jQuery.ajax({
                url: base_url + "rooms/add_title",
                type: "POST",
                data: {
                    title: d,
                    room_id: room_id,
                    title_index: c
                },
                success: function(e) {
                    var t = 0;
                    t = o + price_status + address_status + listing_status + photo_status + overview_status;
                    var n = 6 - t;
                    jQuery("#steps").replaceWith('<span id="steps">' + n + " steps</span>");
                    if (n == 0) {
                        jQuery.ajax({
                            url: base_url + "rooms/final_step",
                            type: "POST",
                            data: {
                                room_id: room_id
                            },
                            success: function(e) {
                                jQuery("#steps_count").hide();
                                jQuery("#list_space").show();
                                if (N == 1) {
                                    N = 0;
                                    jQuery("#list-button").rotate3Di(720, 750)
                                }
                            }
                        })
                    }
                    jQuery("#overview_saving").fadeOut()
                }
            })
        }
    });
    jQuery("#bedrooms").change(function() {
        var e = 0;
        jQuery.ajax({
            url: base_url + "rooms/get_bedrooms",
            type: "POST",
            data: {
                room_id: room_id
            },
            success: function(t) {
                if (t == 0) {
                    e = 1
                } else {
                    e = 0
                }
            }
        });
        jQuery("#listing_saving").fadeIn();
        jQuery.ajax({
            url: base_url + "rooms/add_bedrooms",
            type: "POST",
            data: {
                bedrooms: jQuery("#bedrooms :selected").text(),
                room_id: room_id
            },
            success: function(t) {
                beds_status = 1;
                if (beds_status == 1 && bathrooms_status == 1 && bedscount_status == 1 && bedtype_status == 1) {
                    jQuery("#listing").hide();
                    jQuery("#listing_after").show();
                    jQuery("#listing_plus1").hide();
                    jQuery("#listing_plus_after1").show();
                    var n = 0;
                    listing_status = 1;
                    n = o + price_status + address_status + listing_status + photo_status + overview_status;
                    var r = 6 - n;
                    jQuery("#steps").replaceWith('<span id="steps">' + r + " steps</span>");
                    if (r == 0) {
                        jQuery.ajax({
                            url: base_url + "rooms/final_step",
                            type: "POST",
                            data: {
                                room_id: room_id
                            },
                            success: function(t) {
                                jQuery("#steps_count").hide();
                                jQuery("#list_space").show();
                                if (e == 1) {
                                    jQuery("#list-button").rotate3Di(720, 750)
                                }
                            }
                        })
                    }
                }
            }
        });
        jQuery("#listing_saving").fadeOut()
    });
    jQuery("#hosting_bed_type").change(function() {
        jQuery.ajax({
            url: base_url + "rooms/add_bed_type",
            type: "POST",
            data: {
                room_id: room_id,
                bed_type: jQuery(this).val()
            },
            success: function(e) {
                bedtype_status = 1;
                if (beds_status == 1 && bathrooms_status == 1 && bedscount_status == 1 && bedtype_status == 1) {
                    jQuery("#listing").hide();
                    jQuery("#listing_after").show();
                    jQuery("#listing_plus1").hide();
                    jQuery("#listing_plus_after1").show();
                    var t = 0;
                    listing_status = 1;
                    t = o + price_status + address_status + listing_status + photo_status + overview_status;
                    var n = 6 - t;
                    jQuery("#steps").replaceWith('<span id="steps">' + n + " steps</span>");
                    if (n == 0) {
                        jQuery.ajax({
                            url: base_url + "rooms/final_step",
                            type: "POST",
                            data: {
                                room_id: room_id
                            },
                            success: function(e) {
                                jQuery("#steps_count").hide();
                                jQuery("#list_space").show();
                                if (beds_val == 1) {
                                    jQuery("#list-button").rotate3Di(720, 750)
                                }
                            }
                        })
                    }
                }
            }
        })
    });
    jQuery("#beds").change(function() {
        var e = 0;
        jQuery.ajax({
            url: base_url + "rooms/get_beds",
            type: "POST",
            data: {
                room_id: room_id
            },
            success: function(t) {
                if (t == 0) {
                    e = 1
                } else {
                    e = 0
                }
            }
        });
        jQuery("#listing_saving").fadeIn();
        jQuery.ajax({
            url: base_url + "rooms/add_beds",
            type: "POST",
            data: {
                beds: jQuery("#beds :selected").text(),
                room_id: room_id
            },
            success: function(t) {
                bedscount_status = 1;
                if (beds_status == 1 && bathrooms_status == 1 && bedscount_status == 1 && bedtype_status == 1) {
                    jQuery("#listing").hide();
                    jQuery("#listing_after").show();
                    jQuery("#listing_plus1").hide();
                    jQuery("#listing_plus_after1").show();
                    var n = 0;
                    listing_status = 1;
                    n = o + price_status + address_status + listing_status + photo_status + overview_status;
                    var r = 6 - n;
                    jQuery("#steps").replaceWith('<span id="steps">' + r + " steps</span>");
                    if (r == 0) {
                        jQuery.ajax({
                            url: base_url + "rooms/final_step",
                            type: "POST",
                            data: {
                                room_id: room_id
                            },
                            success: function(t) {
                                jQuery("#steps_count").hide();
                                jQuery("#list_space").show();
                                if (e == 1) {
                                    jQuery("#list-button").rotate3Di(720, 750)
                                }
                            }
                        })
                    }
                }
            }
        });
        jQuery("#listing_saving").fadeOut()
    });
    jQuery("#bathrooms").change(function() {
        var e = 0;
        jQuery.ajax({
            url: base_url + "rooms/get_bath",
            type: "POST",
            data: {
                room_id: room_id
            },
            success: function(t) {
                if (t == "") {
                    e = 1
                } else {
                    e = 0
                }
            }
        });
        jQuery("#listing_saving").fadeIn();
        jQuery.ajax({
            url: base_url + "rooms/add_bathrooms",
            type: "POST",
            data: {
                bathrooms: jQuery("#bathrooms :selected").text(),
                room_id: room_id
            },
            success: function(t) {
                bathrooms_status = 1;
                if (beds_status == 1 && bathrooms_status == 1 && bedscount_status == 1 && bedtype_status == 1) {
                    jQuery("#listing").hide();
                    jQuery("#listing_after").show();
                    jQuery("#listing_plus1").hide();
                    jQuery("#listing_plus_after1").show();
                    var n = 0;
                    listing_status = 1;
                    n = o + price_status + address_status + listing_status + photo_status + overview_status;
                    var r = 6 - n;
                    jQuery("#steps").replaceWith('<span id="steps">' + r + " steps</span>");
                    if (r == 0) {
                        jQuery.ajax({
                            url: base_url + "rooms/final_step",
                            type: "POST",
                            data: {
                                room_id: room_id
                            },
                            success: function(t) {
                                jQuery("#steps_count").hide();
                                jQuery("#list_space").show();
                                if (e == 1) {
                                    jQuery("#list-button").rotate3Di(720, 750)
                                }
                            }
                        })
                    }
                }
            }
        });
        jQuery("#listing_saving").fadeOut()
    });
    jQuery("#home_type_drop").change(function() {
        jQuery("#listing_saving").fadeIn();
        jQuery.ajax({
            url: base_url + "rooms/add_hometype",
            type: "POST",
            data: {
                hometype: jQuery(this).val(),
                room_id: room_id
            },
            success: function(e) {}
        });
        jQuery("#listing_saving").fadeOut()
    });
    //
     jQuery("#instance_book").change(function() {
        //jQuery("#listing_saving").fadeIn();
        jQuery.ajax({
            url: base_url + "rooms/instance_book",
            type: "POST",
            data: {
                instance_book: jQuery(this).val(),
                room_id: room_id
            },
            success: function(e) {}
        });
        jQuery("#listing_saving").fadeOut()
    });
    jQuery("#instance_booka").change(function() {
        //jQuery("#listing_saving").fadeIn();
        jQuery.ajax({
            url: base_url + "rooms/instance_book",
            type: "POST",
            data: {
                instance_book: jQuery(this).val(),
                room_id: room_id
            },
            success: function(e) {}
        });
        jQuery("#listing_saving").fadeOut()
    });
    //
    jQuery("#room_type_drop").change(function() {
        jQuery("#listing_saving").fadeIn();
        jQuery.ajax({
            url: base_url + "rooms/add_roomtype",
            type: "POST",
            data: {
                roomtype: jQuery(this).val(),
                room_id: room_id
            },
            success: function(e) {}
        });
        jQuery("#listing_saving").fadeOut()
    });
    jQuery("#accommodates_drop").change(function() {
        jQuery("#listing_saving").fadeIn();
        jQuery.ajax({
            url: base_url + "rooms/add_accommodates",
            type: "POST",
            data: {
                accommodates: jQuery(this).val(),
                room_id: room_id
            },
            success: function(e) {}
        });
        jQuery("#listing_saving").fadeOut()
    });
    if (beds_status == 1 && bathrooms_status == 1 && bedscount_status == 1 && bedtype_status == 1) {
        jQuery("#list_plus").hide();
        jQuery("#list_plus_after").show()
    }
    if (overview_status == 1) {
        if (title_status == 1 && summary_status == 1) {
            jQuery("#over_plus").hide();
            jQuery("#over_plus_after").show()
        }
        jQuery("#overview_after").hide();
        jQuery("#overview").show()
    }
    jQuery("#photo").click(function() {
        if (jQuery.trim(jQuery("#title").val()).length == 0) {
            jQuery.ajax({
                url: base_url + "rooms/change_title_status",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                    title_status = 0
                }
            });
            title_status = 0;
            overview_status = 0;
            var e = 0;
            e = o + price_status + address_status + listing_status + photo_status + overview_status;
            var t = 6 - e;
            if (t != 0) {
                jQuery("#steps").replaceWith('<span id="steps">' + t + " steps</span>");
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#list_space").hide();
                        jQuery("#steps_count").show()
                    }
                })
            } else if (t == 0) {
                jQuery("#list_space").show();
                jQuery("#steps_count").hide()
            }
        }
        if (jQuery.trim(jQuery("#summary").val()).length == 0) {
            jQuery.ajax({
                url: base_url + "rooms/change_summary_status",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                    summary_status = 0
                }
            });
            summary_status = 0;
            overview_status = 0;
            var e = 0;
            e = o + price_status + address_status + listing_status + photo_status + overview_status;
            var t = 6 - e;
            if (t != 0) {
                jQuery("#steps").replaceWith('<span id="steps">' + t + " steps</span>");
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#list_space").hide();
                        jQuery("#steps_count").show()
                    }
                })
            } else if (t == 0) {
                jQuery("#list_space").show();
                jQuery("#steps_count").hide()
            }
        }
        jQuery("#cal_container").hide();
        jQuery("#overview_entire").hide();
        jQuery("#amenities_entire").hide();
        jQuery("#listing_entire").hide();
        jQuery("#price_container").hide();
        jQuery("#amenities_after").hide();
        jQuery("#amenities").show();
        jQuery("#photos_container").show();
        jQuery("#overview-textbox-hover").hide();
         jQuery("#price-right-hover").hide();
        jQuery("#ded").hide();
        jQuery("#photo").hide();
        jQuery("#photo_after").show();
        jQuery("#address_entire").hide();
        jQuery("#address_right").hide();
        jQuery("#static_circle_map").hide();
        jQuery("#detail_container").hide();
        jQuery("#terms_container").hide();
        jQuery("#cleaning-price-right").hide();
        jQuery("#additional-price-right").hide();
        jQuery("#terms_side").show();
        jQuery("#terms_side_after").hide();
        jQuery.ajax({
            url: base_url + "rooms/photo_check",
            type: "POST",
            data: {
                room_id: room_id
            },
            success: function(e) {
                if (e == 1) {
                    jQuery("#container_photo").hide();
                    jQuery(".container_add_photo").show();
                    jQuery("#photo_ul").show()
                }
            }
        });
        if (o == 0) {
            jQuery("#cal").hide();
            jQuery("#cal1").show()
        } else {
            jQuery("#cal").hide();
            jQuery("#cal_after").show();
            jQuery("#cal1").hide()
        }
        if (overview_status == 0) {
            jQuery("#overview").show();
            jQuery("#overview_after").hide()
        } else {
            jQuery("#overview_after").hide();
            jQuery("#overview").show();
            jQuery("#over_plus").hide();
            jQuery("#over_plus_after1").hide();
            jQuery("#over_plus_after").show()
        }
        if (price_status == 1) {
            jQuery("#price_after").hide();
            jQuery("#price").show();
            jQuery("#des_plus").hide();
            jQuery("#des_plus_after").show();
            u = jQuery("#night_price").val();
            var e = 0;
            e = o + price_status + address_status + listing_status + photo_status + overview_status;
            var t = 6 - e;
            if (t != 0) {
                jQuery("#steps").replaceWith('<span id="steps">' + t + " steps</span>");
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#list_space").hide();
                        jQuery("#steps_count").show()
                    }
                })
            } else if (t == 0) {
                jQuery("#list_space").show();
                jQuery("#steps_count").hide()
            }
        } else {
            jQuery("#price_after").hide();
            jQuery("#price").show();
            jQuery("#des_plus_after").hide();
            jQuery("#des_plus").show()
        }
        if (address_status == 1) {
            jQuery("#address_after").hide();
            jQuery("#address_side").show();
            jQuery("#addr_plus_after_grn").show();
            jQuery("#address_before").hide();
            jQuery("#addr_plus").hide()
        } else {
            jQuery("#address_after").hide();
            jQuery("#address_side").show()
        }
        if (title_status == 1 && summary_status == 1) {
            jQuery("#overview_after").hide();
            jQuery("#overview").show();
            jQuery("#over_plus").hide();
            jQuery("#over_plus_after1").hide();
            jQuery("#over_plus_after").show()
        } else {
            jQuery("#overview_after").hide();
            jQuery("#overview").show();
            jQuery("#over_plus_after").hide();
            jQuery("#over_plus").show()
        }
        if (beds_status == 1 && bathrooms_status == 1 && bedscount_status == 1 && bedtype_status == 1) {
            jQuery("#listing").show();
            jQuery("#listing_after").hide();
            jQuery("#list_plus").hide();
            jQuery("#list_plus_after").show()
        } else {
            jQuery("#listing").show();
            jQuery("#listing_after").hide()
        }
        if (photo_status == 1) {
            jQuery("#photo_plus_white").hide();
            jQuery("#photo_grn_white").show()
        }
        if (y == 1) {
            jQuery("#detail_side").show();
            jQuery("#detail_side_after").hide();
            jQuery("#detail_plus").hide()
        } else {
            jQuery("#detail_side_after").hide();
            jQuery("#detail_side").show();
            jQuery("#detail_plus").show()
        }
    });
    if (total_status_php == 1) {
        jQuery("#seelist_container").hide()
    }
     jQuery("#list-button").click(function() {
      var edit_list=jQuery("#edit-list").val();
       jQuery.ajax({
            url: base_url + "rooms/get_lys_status",
            type: "POST",
            data: {
                room_id: room_id
            },
            success: function(e) {
                if (e == 6) {
                    jQuery.ajax({
                        url: base_url + "rooms/list_pay",
                        type: "POST",
                        data: {
                            room_id: room_id
                        },
                        success: function(e) {
                            if (e == "1") {
                                jQuery.ajax({
                                    url: base_url + "rooms/list_pay_status",
                                    type: "POST",
                                    data: {
                                        room_id: room_id
                                    },
                                    success: function(e) {
                                        if (e == 1) {
                                            window.location.href = base_url + "rooms/" + room_id
                                        } else {
                                            window.location.href = base_url + "rooms/listpay/" + room_id
                                        }
                                    }
                                })
                            } else {
                                jQuery.ajax({
                                    type: "POST",
                                    url: base_url + "rooms/final_photo",                                   
                                    data: {
                                        room_id: room_id,
                                        edit_list:edit_list
                                    },
                                    success: function(e) {
                                        if (e != "no_image.jpg") {
                                            jQuery("#final_photo").replaceWith('<div  class="modal-seelist-img modal-body-list modal-body-picture-list" id="final_photo" style="background: url(' + cdn_url_images + "images/" + room_id + "/" + e + '); background-repeat:no-repeat; background-size:577px"></div>')
                                        } else {
                                            jQuery("#final_photo").replaceWith('<div  class="modal-seelist-img modal-body-list modal-body-picture-list" id="final_photo" style="background: url(' + cdn_url_images + "images/" + e + '); background-repeat:no-repeat; background-size:577px"></div>')
                                        }
                                    }
                                });
                                jQuery.ajax({
                                    url: base_url + "rooms/get_lys_status",
                                    type: "POST",
                                    data: {
                                        room_id: room_id
                                    },
                                    success: function(e) {
                                        if (e == 6) {
                                            jQuery("#terms_side_2").show();
                                            jQuery("#detail_side_2").show();
                                            jQuery("#seelist_container").fadeIn()
                                        } else {
                                            window.location.href = base_url + "rooms/lys_next/edit/" + room_id
                                        }
                                    }
                                })
                            }
                        }
                    })
                } else {
                    window.location.href = base_url + "rooms/lys_next/edit/" + room_id
                }
            }
        })
    });
    jQuery("#close_list").click(function() {
        jQuery("#seelist_container").fadeOut();
        window.location.href = base_url + "rooms/lys_next/edit/" + room_id
    });
    jQuery("#see_list").click(function() {
        window.location.href = base_url + "rooms/" + room_id
    });
    jQuery("#finish_list").click(function() {
        jQuery("#my_contain").fadeOut();
        jQuery.ajax({
            url: base_url + "rooms/first_popup",
            type: "POST",
            data: {
                room_id: room_id
            },
            success: function(e) {}
        })
    });
    jQuery("#upload_file").live("change", function() {
        jQuery("#upload_file1").attr("disabled", "disabled");
        var e = base_url + "rooms/add_photo_user_login";
        jQuery.ajax({
            url: e,
            success: function(e) {
                if (!e) {
                    window.location.assign(base_url + "users/signin")
                }
            }
        });
        if (jQuery("#upload_file").val() == "") {
            alert("No file choosed");
            return false
        }
        var t = 0;
        var n = 0;
        jQuery.each(jQuery("#upload_file")[0].files, function(e, r) {
            var i = r.name.toLowerCase();
            var s = i.split(".").pop();
            if (!(s && /^(jpg|png|jpeg|gif)$/.test(s))) {} else {
                t = t + 1
            }
            n = e
        });
        if (n == 0) {
            var r = jQuery("#upload_file").val().toLowerCase();
            var i = r.split(".").pop();
            if (!(i && /^(jpg|png|jpeg|gif)$/.test(i))) {
                return false
            }
        }
        jQuery("#container_photo").hide();
        jQuery(".container_add_photo").show();
        var s = false;
        if (!s) {
            s = true;
            jQuery.ajaxFileUpload({
                url: base_url + "rooms/add_photo/" + room_id,
                secureuri: false,
                fileElementId: "upload_file",
                dataType: "text",
                async: false,
                success: function(e) {
                    if (e == "users/signin") {
                        window.location.href = base_url + e
                    } else if (e != "no") {
                        jQuery("#container_photo").hide();
                        jQuery(".container_add_photo").show();
                        jQuery("#photos_count").hide();
                        jQuery("#content").show();
                        for (var n = 0; n < 50; n++) {
                            jQuery(".expand").css("width", n + "%")
                        }
                        jQuery("#upload_file_btn1").show();
                        jQuery("#upload_file_btn1_dis").hide();
                        setTimeout(function() {
                            jQuery("#container_photo").hide();
                            jQuery(".container_add_photo").show();
                            jQuery("#photo_ul").show();
                            jQuery("#photo_ul").replaceWith(e);
                            for (var n = 50; n < 100; n++) {
                                jQuery(".expand").css("width", n + "%")
                            }
                            photo_status = 1;
                            photos_count = photos_count + t;
                            jQuery("#content").hide();
                            jQuery("#photos_count").show();
                            if (photos_count < 0) {
                                jQuery("#photos_count").replaceWith('<p id="photos_count">0 Photos</p>')
                            } else {
                                jQuery("#photos_count").replaceWith('<p id="photos_count">' + photos_count + " Photos</p>")
                            }
                            jQuery("#photo_plus_white").hide();
                            jQuery("#photo_grn_white").show();
                            jQuery("#photo_ul").replaceWith(e);
                            jQuery("#photo_plus_white").hide();
                            jQuery("#upload_file1").removeAttr("disabled");
                            jQuery("#upload_file1").show();
                            var r = 0;
                            r = o + price_status + address_status + listing_status + photo_status + overview_status;
                            var i = 6 - r;
                            jQuery("#steps").replaceWith('<span id="steps">' + i + " steps</span>");
                            if (i == 0) {
                                jQuery.ajax({
                                    url: base_url + "rooms/final_step",
                                    type: "POST",
                                    data: {
                                        room_id: room_id
                                    },
                                    success: function(e) {
                                        jQuery("#steps_count").hide();
                                        jQuery("#list_space").show();
                                        if (photos_count == 1) {
                                            jQuery("#list-button").rotate3Di(720, 750)
                                        }
                                    }
                                })
                            }
                        }, 2e3)
                    } else {
                        alert("Please choose the correct file");
                        return false
                    }
                    var r = false;
                    jQuery("#upload_file").removeAttr("disabled")
                }
            })
        }
    });
    var C = false;
    jQuery("#upload_file1").live("change", function() {
        jQuery("#upload_file1").hide();
        var e = base_url + "rooms/add_photo_user_login";
        jQuery.ajax({
            url: e,
            success: function(e) {
                if (!e) {
                    window.location.assign(base_url + "users/signin")
                }
            }
        });
        if (jQuery("#upload_file1").val() == "") {
            alert("No file choosed");
            return false
        }
        var t = 0;
        var n = 0;
        jQuery.each(jQuery("#upload_file1")[0].files, function(e, r) {
            var i = r.name.toLowerCase();
            var s = i.split(".").pop();
            if (!(s && /^(jpg|png|jpeg|gif)$/.test(s))) {} else {
                t = t + 1
            }
            n = e
        });
        if (n == 0) {
            var r = jQuery("#upload_file1").val().toLowerCase();
            var i = r.split(".").pop();
            if (!(i && /^(jpg|png|jpeg|gif)$/.test(i))) {
                return false
            }
        }
        var s = false;
        if (!s) {
            s = true;
            C = true;
            jQuery.ajaxFileUpload({
                url: base_url + "rooms/add_photo1/" + room_id,
                secureuri: false,
                fileElementId: "upload_file1",
                dataType: "text",
                async: false,
                success: function(e) {
                    if (e == "users/signin") {
                        window.location.href = base_url + e
                    } else if (e != "no") {
                        jQuery("#photos_count").hide();
                        jQuery("#content").show();
                        for (var n = 0; n < 50; n++) {
                            jQuery(".expand").css("width", n + "%")
                        }
                        setTimeout(function() {
                            jQuery("#container_photo").hide();
                            jQuery(".container_add_photo").show();
                            jQuery("#photo_ul").show();
                            jQuery("#photo_ul").replaceWith(e);
                            for (var n = 50; n < 100; n++) {
                                jQuery(".expand").css("width", n + "%")
                            }
                            photo_status = 1;
                            photos_count = photos_count + t;
                            jQuery("#content").hide();
                            jQuery("#photos_count").show();
                            jQuery("#photo_plus_white").hide();
                            jQuery("#photo_grn_white").show();
                            var r = 0;
                            r = o + price_status + address_status + listing_status + photo_status + overview_status;
                            var i = 6 - r;
                            jQuery("#steps").replaceWith('<span id="steps">' + i + " steps</span>");
                            if (i == 0) {
                                jQuery.ajax({
                                    url: base_url + "rooms/final_step",
                                    type: "POST",
                                    data: {
                                        room_id: room_id
                                    },
                                    success: function(e) {
                                        jQuery("#steps_count").hide();
                                        jQuery("#list_space").show();
                                        if (photos_count == 1) {
                                            jQuery("#list-button").rotate3Di(720, 750)
                                        }
                                    }
                                })
                            }
                            s = false;
                            C = false
                        }, 2e3);
                        jQuery.ajax({
                            url: base_url + "rooms/photo_count",
                            type: "POST",
                            data: {
                                room_id: room_id
                            },
                            success: function(e) {
                                jQuery("#photos_count").replaceWith('<p id="photos_count">' + e + " Photos</p>")
                            }
                        })
                    } else {
                        alert("Please choose the correct file");
                        s = false;
                        C = false;
                        return false
                    }
                    jQuery("#upload_file1").replaceWith('<input type="file" style="z-index: 9999; position:absolute; width: 90px; padding: 5px 20px; cursor: default; opacity: 0; margin: -4px -119px 0;" id="upload_file1" name="upload_file1[]" multiple="multiple">')
                }
            })
        }
    });
    if (photo_status == 1) {
        jQuery("#photo_plus").hide();
        jQuery("#photo_grn").show();
        var S = 0;
        S = o + price_status + address_status + listing_status + photo_status + overview_status;
        var x = 6 - S;
        jQuery("#steps").replaceWith('<span id="steps">' + x + " steps</span>");
        if (x == 0) {
            jQuery.ajax({
                url: base_url + "rooms/final_step",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                    jQuery("#steps_count").hide();
                    jQuery("#list_space").show()
                }
            })
        }
    }
    var k = false;
    jQuery.fn.delete_photo = function(e) {
        if (!k && !C) {
            k = true;
            C = true;
            jQuery.ajax({
                url: base_url + "rooms/delete_photo",
                type: "POST",
                data: {
                    room_id: room_id,
                    photo_id: e
                },
                async: false,
                success: function(e) {
                    photos_count = photos_count - 1;
                    jQuery("#photo_ul").replaceWith(e);
                    jQuery.ajax({
                        url: base_url + "rooms/photo_count",
                        type: "POST",
                        data: {
                            room_id: room_id
                        },
                        success: function(e) {
                            jQuery("#photos_count").replaceWith('<p id="photos_count">' + e + " Photos</p>")
                        }
                    });
                    if (photos_count == 0) {
                        photo_status = 0;
                        jQuery("#photo_grn_white").hide();
                        jQuery(".photo_appear").hide();
                        jQuery("#photo_plus_white").show();
                        var t = 0;
                        t = o + price_status + address_status + listing_status + photo_status + overview_status;
                        var n = 6 - t;
                        if (n == 0) {
                            jQuery.ajax({
                                url: base_url + "rooms/final_step",
                                type: "POST",
                                data: {
                                    room_id: room_id
                                },
                                success: function(e) {
                                    jQuery("#steps_count").hide();
                                    jQuery("#list_space").show();
                                    jQuery("#list-button").rotate3Di(720, 750)
                                }
                            })
                        } else {
                            jQuery("#steps").replaceWith('<span id="steps">' + n + " steps</span>");
                            jQuery("#steps_count").show();
                            jQuery("#list_space").hide()
                        }
                    }
                    k = false;
                    C = false
                }
            })
        }
    };
    jQuery.fn.highlight = function(e) {
        msg = jQuery.trim(jQuery(this).val());
        var t = 100;
        jQuery("#highlight_" + e).bind("cut copy paste", function(e) {
            e.preventDefault()
        });
        jQuery("#highlight_" + e).keypress(function(e) {
            if (e.which < 32) {
                return
            }
            if (this.value.length == t) {
                e.preventDefault()
            } else if (this.value.length > t) {
                this.value = this.value.substring(0, t)
            }
        });
        if (msg.length == 100) {
            alert("You can't give the more than 100 characters")
        }
        if (msg.length <= t) {
            jQuery.ajax({
                url: base_url + "rooms/photo_highlight",
                type: "POST",
                data: {
                    room_id: room_id,
                    photo_id: e,
                    msg: msg
                },
                success: function(e) {}
            })
        }
    };
    if (address_status == 1) {
        jQuery("#address_after").hide();
        jQuery("#address_side").show();
        jQuery("#addr_plus_after_grn").show();
        jQuery("#address_before").hide();
        jQuery("#addr_plus").hide();
        jQuery("#add_content").hide();
        jQuery("#add_address").hide();
        jQuery("#after_address").show()
    } else {
        jQuery("#address_after").hide();
        jQuery("#address_side").show()
    }
    jQuery("#address_side").click(function() {
        if (jQuery.trim(jQuery("#title").val()).length == 0) {
            jQuery.ajax({
                url: base_url + "rooms/change_title_status",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                    title_status = 0
                }
            });
            title_status = 0;
            overview_status = 0;
            var e = 0;
            e = o + price_status + address_status + listing_status + photo_status + overview_status;
            var t = 6 - e;
            if (t != 0) {
                jQuery("#steps").replaceWith('<span id="steps">' + t + " steps</span>");
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#list_space").hide();
                        jQuery("#steps_count").show()
                    }
                })
            } else if (t == 0) {
                jQuery("#list_space").show();
                jQuery("#steps_count").hide()
            }
        }
        if (jQuery.trim(jQuery("#summary").val()).length == 0) {
            jQuery.ajax({
                url: base_url + "rooms/change_summary_status",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                    summary_status = 0
                }
            });
            summary_status = 0;
            overview_status = 0;
            var e = 0;
            e = o + price_status + address_status + listing_status + photo_status + overview_status;
            var t = 6 - e;
            if (t != 0) {
                jQuery("#steps").replaceWith('<span id="steps">' + t + " steps</span>");
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#list_space").hide();
                        jQuery("#steps_count").show()
                    }
                })
            } else if (t == 0) {
                jQuery("#list_space").show();
                jQuery("#steps_count").hide()
            }
        }
        jQuery("#cal_container").hide();
        jQuery("#overview_entire").hide();
        jQuery("#amenities_entire").hide();
        jQuery("#listing_entire").hide();
        jQuery("#price_container").hide();
        jQuery("#photos_container").hide();
        jQuery("#address_entire").show();
        jQuery("#address_right").show();
        jQuery("#overview-textbox-hover").hide();
        jQuery("#address_side").hide();
        jQuery("#address_after").show();
        jQuery("#amenities_after").hide();
        jQuery("#amenities").show();
        jQuery("#main_entire_right").hide();
        jQuery("#price-right-hover").hide();
        jQuery("#ded").hide();
        jQuery("#overview-text-right").hide();
        jQuery("#summary-text-hover").hide();
        jQuery("#detail_container").hide();
        jQuery("#terms_container").hide();
        jQuery("#cleaning-price-right").hide();
        jQuery("#additional-price-right").hide();
        jQuery("#terms_side").show();
        jQuery("#terms_side_after").hide();
        if (o == 0) {
            jQuery("#cal").hide();
            jQuery("#cal1").show()
        } else {
            jQuery("#cal").hide();
            jQuery("#cal_after").show();
            jQuery("#cal1").hide()
        }
        if (overview_status == 0) {
            jQuery("#overview").show();
            jQuery("#overview_after").hide()
        } else {
            jQuery("#overview_after").hide();
            jQuery("#overview").show();
            jQuery("#over_plus").hide();
            jQuery("#over_plus_after1").hide();
            jQuery("#over_plus_after").show()
        }
        if (price_status == 1) {
            jQuery("#price_after").hide();
            jQuery("#price").show();
            jQuery("#des_plus").hide();
            jQuery("#des_plus_after").show();
            u = jQuery("#night_price").val();
            var e = 0;
            e = o + price_status + address_status + listing_status + photo_status + overview_status;
            var t = 6 - e;
            if (t != 0) {
                jQuery("#steps").replaceWith('<span id="steps">' + t + " steps</span>");
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#list_space").hide();
                        jQuery("#steps_count").show()
                    }
                })
            } else if (t == 0) {
                jQuery("#list_space").show();
                jQuery("#steps_count").hide()
            }
        } else {
            jQuery("#price_after").hide();
            jQuery("#price").show();
            jQuery("#des_plus_after").hide();
            jQuery("#des_plus").show()
        }
        if (title_status == 1 && summary_status == 1) {
            jQuery("#overview_after").hide();
            jQuery("#overview").show();
            jQuery("#over_plus").hide();
            jQuery("#over_plus_after1").hide();
            jQuery("#over_plus_after").show()
        } else {
            jQuery("#overview_after").hide();
            jQuery("#overview").show();
            jQuery("#over_plus_after").hide();
            jQuery("#over_plus").show()
        }
        if (beds_status == 1 && bathrooms_status == 1 && bedscount_status == 1 && bedtype_status == 1) {
            jQuery("#listing").show();
            jQuery("#listing_after").hide();
            jQuery("#list_plus").hide();
            jQuery("#list_plus_after").show()
        } else {
            jQuery("#listing").show();
            jQuery("#listing_after").hide()
        }
        if (photo_status == 1) {
            jQuery("#photo_after").hide();
            jQuery("#photo").show();
            jQuery("#photo_plus").hide();
            jQuery("#photo_grn").show()
        } else {
            jQuery("#photo_after").hide();
            jQuery("#photo").show();
            jQuery("#photo_grn").hide();
            jQuery("#photo_plus").show()
        }
        if (address_status == 1) {
            jQuery("#address_after").show();
            jQuery("#address_side").hide();
            jQuery("#addr_plus_after_white").show();
            jQuery("#address_before").hide();
            jQuery("#addr_plus").hide();
            jQuery("#static_circle_map").show();
            jQuery("#address_right").hide()
        }
        if (y == 1) {
            jQuery("#detail_side").show();
            jQuery("#detail_side_after").hide();
            jQuery("#detail_plus").hide()
        } else {
            jQuery("#detail_side_after").hide();
            jQuery("#detail_side").show();
            jQuery("#detail_plus").show()
        }
    });
    jQuery('select option[value="' + country + '"]').attr("selected", true);
    jQuery("#add_address").click(function() {
    	
    	/*jQuery('#lys_street_address').val("");
    	jQuery('#apt').val("");
    	jQuery('#state').val("");
    	jQuery('#city').val("");
    	jQuery('#zipcode').val(""); */
        jQuery("#address_popup1").delay(5e3).show()
    
        
    });
    jQuery("#address_popup1_close").click(function() {
        jQuery("#address_popup1").delay(5e3).hide()
    });
    jQuery("#address_popup2_close").click(function() {
        jQuery("#address_popup2").delay(5e3).hide()
    });
    jQuery("#close_popup3").click(function() {
        jQuery("#address_popup3").delay(5e3).hide()
    });
    jQuery("#cancel_popup3").click(function() {
    	  jQuery('#location_found_edit').val("not_found");
        jQuery("#address_popup3").delay(5e3).hide()
    });
    jQuery("#address_popup1_cancel").click(function() {
    	  jQuery('#location_found').val("not_found");
        jQuery("#address_popup1").delay(5e3).hide()
    });
    jQuery("#edit_address").click(function() {
        jQuery("#address_popup2").hide();
        jQuery("#address_popup1").show();
          jQuery('#location_found_edit').val("not_found");
        if (jQuery.trim(jQuery("#lys_street_address").val()) != "" && jQuery.trim(jQuery("#city").val()) != "" && jQuery.trim(jQuery("#zipcode").val()) != "") {
            jQuery(".next_active").css("opacity", 1);
            jQuery(".disable-btn").hide();
            jQuery(".enable-btn").show()
        } else {
            jQuery(".next_active").css("opacity", .65);
            jQuery(".disable-btn").show();
            jQuery(".enable-btn").hide()
        }
    });
    jQuery("#edit_popup3").click(function() {
        jQuery("#address_popup3").hide();
        jQuery("#address_popup1").show();
         jQuery('#location_found_popup_edit').val("not_found");
        if (jQuery.trim(jQuery("#lys_street_address").val()) != "" && jQuery.trim(jQuery("#city").val()) != "" && jQuery.trim(jQuery("#zipcode").val()) != "") {
            jQuery(".next_active").css("opacity", 1);
            jQuery(".disable-btn").hide();
            jQuery(".enable-btn").show()
        } else {
            jQuery(".next_active").css("opacity", .65);
            jQuery(".disable-btn").show();
            jQuery(".enable-btn").hide()
        }
    });
    jQuery("#edit_address1").click(function() {
        jQuery("#address_popup2").hide();
        jQuery("#address_popup1").show();
        if (jQuery.trim(jQuery("#lys_street_address").val()) == "" && jQuery.trim(jQuery("#city").val()) == "" && jQuery.trim(jQuery("#zipcode").val()) == "") {
            jQuery(".next_active").css("opacity", .65);
            jQuery(".disable-btn").show();
            jQuery(".enable-btn").hide()
        } else {
            jQuery(".next_active").css("opacity", 1);
            jQuery(".disable-btn").hide();
            jQuery(".enable-btn").show()
        }
    });
    jQuery("#lys_street_address").keyup(function() {
        if (jQuery.trim(jQuery(this).val()) != "" && jQuery.trim(jQuery("#city").val()) != "" && jQuery.trim(jQuery("#zipcode").val()) != "") {
            jQuery(".next_active").css("opacity", 1);
            jQuery(".disable-btn").hide();
            jQuery(".enable-btn").show();
        } else {
            jQuery(".next_active").css("opacity", .65);
            jQuery(".disable-btn").show();
            jQuery(".enable-btn").hide();
            
        }
    });
    jQuery("#zipcode").keyup(function() {
        if (jQuery.trim(jQuery(this).val()) != "" && jQuery.trim(jQuery("#lys_street_address").val()) != "" && jQuery.trim(jQuery("#city").val()) != "") {
            jQuery(".next_active").css("opacity", 1);
            jQuery(".disable-btn").hide();
            jQuery(".enable-btn").show();
            jQuery('#location_found').val("not_found");
        } else {
            jQuery(".next_active").css("opacity", .65);
            jQuery(".disable-btn").show();
            jQuery(".enable-btn").hide()
        }
    });
    jQuery("#city").keyup(function() {
   
        if (jQuery.trim(jQuery(this).val()) != "" && jQuery.trim(jQuery("#lys_street_address").val()) != "" && jQuery.trim(jQuery("#zipcode").val()) != "") {
            jQuery(".next_active").css("opacity", 1);
            jQuery(".disable-btn").hide();
            jQuery(".enable-btn").show();
            jQuery('#location_found').val("not_found");
            
        } else {
            jQuery(".next_active").css("opacity", .65);
            jQuery(".disable-btn").show();
            jQuery(".enable-btn").hide()
        }
    });
    jQuery(".enable-btn").click(function() {
        jQuery("#address_popup1").hide();
 		if(jQuery('#location_found').val() == "not_found" && jQuery('#location_found_edit').val() == "not_found")
 		{
 			jQuery("#address_popup2").show();
 			jQuery("#address_popup3").hide();
 			jQuery('#location_found').val("found");
 			jQuery('#location_found_edit').val("found");
 		}
 		else if(jQuery('#location_found').val() == "not_found" && jQuery('#location_found_edit').val() != "not_found")
 		{
 			jQuery("#address_popup2").show();
 			jQuery("#address_popup3").hide();
 			jQuery('#location_found').val("found");
 			jQuery('#location_found_edit').val("found");
 		}
 		else if(jQuery('#location_found').val() != "not_found" && jQuery('#location_found_edit').val() == "not_found")
 		{
 			jQuery("#address_popup2").show();
 			jQuery("#address_popup3").hide();
 			jQuery('#location_found').val("found");
 			jQuery('#location_found_edit').val("found");
 		}
 		else
 		{
 			jQuery("#address_popup2").hide();
 			jQuery("#address_popup3").show();
 		}
        
        jQuery("#str_street_address").replaceWith('<strong id="str_street_address">' + jQuery("#lys_street_address").val() + "</strong>");
        jQuery("#str_city_state_address").replaceWith('<strong id="str_city_state_address">' + jQuery("#city").val() + "  " + jQuery("#state").val() + "</strong>");
        jQuery("#str_country").replaceWith('<strong id="str_country">' + jQuery("#country").val() + "</strong>");
        jQuery("#str_zipcode").replaceWith('<strong id="str_zipcode">' + jQuery("#zipcode").val() + "</strong>");
        jQuery("#str_street_address2").replaceWith('<strong id="str_street_address2">' + jQuery("#lys_street_address").val() + "</strong>");
        jQuery("#str_city_state_address2").replaceWith('<strong id="str_city_state_address2">' + jQuery("#city").val() + "  " + jQuery("#state").val() + "</strong>");
        jQuery("#str_country2").replaceWith('<strong id="str_country2">' + jQuery("#country").val() + "</strong>");
        jQuery("#str_zipcode2").replaceWith('<strong id="str_zipcode2">' + jQuery("#zipcode").val() + "</strong>")
 
        
        jQuery(".disable_finish").hide();
        jQuery(".enable_finish").show();
        if (jQuery("#hidden_lat").val() == "") {
            jQuery.ajax({
                url: base_url + "rooms/get_address",
                type: "POST",
                dataType: "json",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                    jQuery.each(e, function(e, t) {
                        city = t["city"];
                        jQuery("#city").val(city);
                        state = t["state"];
                        jQuery("#state").val(state);
                        country = t["country"];
                        jQuery("#country").val(country);
                        jQuery("#hidden_lat").val(t["lat"]);
                        jQuery("#hidden_lng").val(t["long"]);
                        jQuery("#zipcode").val(t["zip_code"]);
                        jQuery("#lys_street_address").val(t["street_address"]);
                        n()
                    })
                }
            })
        } else {
            n()
        }
    });
    jQuery(".disable-btn").click(function() {
        alert("Please enter the data")
    });
    jQuery("#pin-on-map").click(function() {
    	jQuery('#location_found').val("found");
    	jQuery('#location_found_edit').val("found");
        jQuery("#address_popup2").hide();
        jQuery("#address_popup3").show();
        jQuery(".disable_finish").show();
        jQuery(".enable_finish").hide();
        if (jQuery("#hidden_lat").val() == "") {
            jQuery.ajax({
                url: base_url + "rooms/get_address",
                type: "POST",
                dataType: "json",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                    jQuery.each(e, function(e, t) {
                        city = t["city"];
                        jQuery("#city").val(city);
                        state = t["state"];
                        jQuery("#state").val(state);
                        country = t["country"];
                        jQuery("#country").val(country);
                        jQuery("#hidden_lat").val(t["lat"]);
                        jQuery("#hidden_lng").val(t["long"]);
                        jQuery("#zipcode").val(t["zip_code"]);
                        jQuery("#lys_street_address").val(t["street_address"]);
                        n()
                    })
                }
            })
        } else {
            n()
        }
    });
    jQuery("#finish_popup3").click(function() {
    	var address_street =jQuery("#lys_street_address").val();
    	    var add23=address_street.replace(/[^a-zA-Z ]/g, "");
    	var full_add=jQuery("#hidden_address").val();
    	    	    var add234=full_add.replace(/[^a-zA-Z ]/g, "");
    	var full_add1=jQuery("#apt").val();
    	    	    var add235=full_add1.replace(/[^a-zA-Z ]/g, ""); 	    	    

        jQuery.ajax({
            type: "POST",
            url: base_url + "rooms/add_address",
            data: {
                room_id: room_id,
                country: jQuery("#country").val(),
                city: jQuery("#city").val(),
                state: jQuery("#state").val(),
                street_address: add23,
                optional_address: add235,
                zipcode: jQuery("#zipcode").val(),
                lat: jQuery("#hidden_lat").val(),
                lng: jQuery("#hidden_lng").val(),
                full_address: add234
            },
            success: function(e) {
                jQuery("#str_street_address").replaceWith('<strong id="str_street_address">' + jQuery("#lys_street_address").val() + "</strong>");
                jQuery("#str_city_state_address").replaceWith('<strong id="str_city_state_address">' + jQuery("#city").val() + "  " + jQuery("#state").val() + "</strong>");
                jQuery("#str_country").replaceWith('<strong id="str_country">' + jQuery("#country").val() + "</strong>");
                jQuery("#str_zipcode").replaceWith('<strong id="str_zipcode">' + jQuery("#zipcode").val() + "</strong>");
                jQuery("#str_street_address1").replaceWith('<strong id="str_street_address1">' + jQuery("#hidden_address").val() + "</strong>");
                jQuery("#str_city_state_address1").replaceWith('<strong id="str_city_state_address1">' + jQuery("#city").val() + "  " + jQuery("#state").val() + "</strong>");
                jQuery("#str_country1").replaceWith('<strong id="str_country1">' + jQuery("#country").val() + "</strong>");
                jQuery("#str_zipcode1").replaceWith('<strong id="str_zipcode1">' + jQuery("#zipcode").val() + "</strong>");
                jQuery("#str_street_address2").replaceWith('<strong id="str_street_address2">' + jQuery("#lys_street_address").val() + "</strong>");
                jQuery("#str_city_state_address2").replaceWith('<strong id="str_city_state_address2">' + jQuery("#city").val() + "  " + jQuery("#state").val() + "</strong>");
                jQuery("#str_country2").replaceWith('<strong id="str_country2">' + jQuery("#country").val() + "</strong>");
                jQuery("#str_zipcode2").replaceWith('<strong id="str_zipcode2">' + jQuery("#zipcode").val() + "</strong>")
            }
        });
        jQuery("#address_popup3").hide();
        address_status = 1;
        jQuery("#address_before").hide();
        jQuery("#addr_plus_after_white").show();
        jQuery("#add_address").hide();
        jQuery("#add_content").hide();
        jQuery("#after_address").show();
        lat = jQuery("#hidden_lat").val();
        lng = jQuery("#hidden_lng").val();
        jQuery("#static_map").replaceWith('<img id="static_map" src="https://maps.googleapis.com/maps/api/staticmap?center=' + lat + "," + lng + "&key="+places_API+"&size=571x275&zoom=14&format=png&markers=color:red|label:|" + lat + "," + lng + '&sensor=false&maptype=roadmap&style=feature:water|element:geometry.fill|weight:3.3|hue:0x00aaff|lightness:100|saturation:93|gamma:0.01|color:0x5cb8e4">');
        jQuery.ajax({
            url: base_url + "rooms/ajax_circle_map",
            type: "POST",
            data: {
                lat: lat,
                lng: lng
            },
            success: function(e) {
                jQuery("#static_circle_map").show();
                jQuery("#static_circle_map_img").replaceWith('<img width="210" height="210" id="static_circle_map_img" src="' + e + '">')
            }
        });
        var e = 0;
        e = o + price_status + address_status + listing_status + photo_status + overview_status;
        var t = 6 - e;
        jQuery("#steps").replaceWith('<span id="steps">' + t + " steps</span>");
        if (t == 0) {
            jQuery.ajax({
                url: base_url + "rooms/final_step",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                    jQuery("#steps_count").hide();
                    jQuery("#list_space").show();
                    jQuery("#list-button").rotate3Di(720, 750)
                }
            })
        }
    });
    jQuery("#detail_side").click(function() {
        if (jQuery.trim(jQuery("#title").val()).length == 0) {
            jQuery.ajax({
                url: base_url + "rooms/change_title_status",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                    title_status = 0
                }
            });
            title_status = 0;
            overview_status = 0;
            var e = 0;
            e = o + price_status + address_status + listing_status + photo_status + overview_status;
            var t = 6 - e;
            if (t != 0) {
                jQuery("#steps").replaceWith('<span id="steps">' + t + " steps</span>");
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#list_space").hide();
                        jQuery("#steps_count").show()
                    }
                })
            } else if (t == 0) {
                jQuery("#list_space").show();
                jQuery("#steps_count").hide()
            }
        }
        if (jQuery.trim(jQuery("#summary").val()).length == 0) {
            jQuery.ajax({
                url: base_url + "rooms/change_summary_status",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                    summary_status = 0
                }
            });
            summary_status = 0;
            overview_status = 0;
            var e = 0;
            e = o + price_status + address_status + listing_status + photo_status + overview_status;
            var t = 6 - e;
            if (t != 0) {
                jQuery("#steps").replaceWith('<span id="steps">' + t + " steps</span>");
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#list_space").hide();
                        jQuery("#steps_count").show()
                    }
                })
            } else if (t == 0) {
                jQuery("#list_space").show();
                jQuery("#steps_count").hide()
            }
        }
        jQuery("#overview_entire").hide();
        jQuery("#amenities_entire").hide();
        jQuery("#listing_entire").hide();
        jQuery("#price_container").hide();
        jQuery("#photos_container").hide();
        jQuery("#address_entire").hide();
        jQuery("#address_right").hide();
        jQuery("#address_side").hide();
        jQuery("#address_after").hide();
        jQuery("#amenities_after").hide();
        jQuery("#amenities").show();
        jQuery("#main_entire_right").hide();
        jQuery("#price-right-hover").hide();
        jQuery("#overview-text-right").hide();
        jQuery("#summary-text-hover").hide();
        jQuery("#static_circle_map").hide();
        jQuery("#terms_container").hide();
        jQuery("#cleaning-price-right").hide();
        jQuery("#additional-price-right").hide();
        jQuery("#detail_container").show();
        jQuery("#detail_side").hide();
        jQuery("#detail_side_after").show();
        jQuery("#ded").hide();
        jQuery("#cal_container").hide();
        if (o == 0) {
            jQuery("#cal").hide();
            jQuery("#cal1").show()
        } else {
            jQuery("#cal").hide();
            jQuery("#cal_after").show();
            jQuery("#cal1").hide()
        }
        if (overview_status == 0) {
            jQuery("#overview").show();
            jQuery("#overview_after").hide()
        } else {
            jQuery("#overview_after").hide();
            jQuery("#overview").show();
            jQuery("#over_plus").hide();
            jQuery("#over_plus_after1").hide();
            jQuery("#over_plus_after").show()
        }
        if (price_status == 1) {
            jQuery("#price_after").hide();
            jQuery("#price").show();
            jQuery("#des_plus").hide();
            jQuery("#des_plus_after").show();
            u = jQuery("#night_price").val();
            var e = 0;
            e = o + price_status + address_status + listing_status + photo_status + overview_status;
            var t = 6 - e;
            if (t != 0) {
                jQuery("#steps").replaceWith('<span id="steps">' + t + " steps</span>");
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#list_space").hide();
                        jQuery("#steps_count").show()
                    }
                })
            } else if (t == 0) {
                jQuery("#list_space").show();
                jQuery("#steps_count").hide()
            }
        } else {
            jQuery("#price_after").hide();
            jQuery("#price").show();
            jQuery("#des_plus_after").hide();
            jQuery("#des_plus").show()
        }
        if (title_status == 1 && summary_status == 1) {
            jQuery("#overview_after").hide();
            jQuery("#overview").show();
            jQuery("#over_plus").hide();
            jQuery("#over_plus_after1").hide();
            jQuery("#over_plus_after").show()
        } else {
            jQuery("#overview_after").hide();
            jQuery("#overview").show();
            jQuery("#over_plus_after").hide();
            jQuery("#over_plus").show()
        }
        if (beds_status == 1 && bathrooms_status == 1 && bedscount_status == 1 && bedtype_status == 1) {
            jQuery("#listing").show();
            jQuery("#listing_after").hide();
            jQuery("#list_plus").hide();
            jQuery("#list_plus_after").show()
        } else {
            jQuery("#listing").show();
            jQuery("#listing_after").hide()
        }
        if (photo_status == 1) {
            jQuery("#photo_after").hide();
            jQuery("#photo").show();
            jQuery("#photo_plus").hide();
            jQuery("#photo_grn").show()
        } else {
            jQuery("#photo_after").hide();
            jQuery("#photo").show();
            jQuery("#photo_grn").hide();
            jQuery("#photo_plus").show()
        }
        if (address_status == 1) {
            jQuery("#address_after").hide();
            jQuery("#address_side").show();
            jQuery("#addr_plus_after_grn").show();
            jQuery("#address_before").hide();
            jQuery("#addr_plus").hide()
        } else {
            jQuery("#address_after").hide();
            jQuery("#address_side").show()
        }
        if (house_rule != "") {
            jQuery("#detail_plus1").hide()
        }
        jQuery("#terms_side").show();
        jQuery("#terms_side_after").hide()
    });
    jQuery("#house_rules_textbox").focusout(function() {
        var e = jQuery.trim(jQuery(this).val());
        jQuery.ajax({
            url: base_url + "rooms/house_rules",
            type: "POST",
            data: {
                room_id: room_id,
                house_rules: e
            },
            success: function(t) {
                y = 1;
                jQuery("#detail_saving").fadeIn();
                jQuery("#detail_saving").fadeOut();
                if (e == "") {
                    y = 0;
                    jQuery("#detail_plus1").show();
                } else {
                    jQuery("#detail_plus1").hide();
                }
            }
        })
    });
   jQuery("#house_rules_textbox").mouseover(function() {
        jQuery("#ded").show();
    });
    
    if (house_rule != "") {
        jQuery("#detail_plus").hide();
    }
    jQuery("#terms_side").click(function() {
        if (jQuery.trim(jQuery("#title").val()).length == 0) {
            jQuery.ajax({
                url: base_url + "rooms/change_title_status",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                    title_status = 0;
                }
            });
            title_status = 0;
            overview_status = 0;
            var e = 0;
            e = o + price_status + address_status + listing_status + photo_status + overview_status;
            var t = 6 - e;
            if (t != 0) {
                jQuery("#steps").replaceWith('<span id="steps">' + t + " steps</span>");
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#list_space").hide();
                        jQuery("#steps_count").show();
                    }
                })
            } else if (t == 0) {
                jQuery("#list_space").show();
                jQuery("#steps_count").hide();
            }
        }
        if (jQuery.trim(jQuery("#summary").val()).length == 0) {
            jQuery.ajax({
                url: base_url + "rooms/change_summary_status",
                type: "POST",
                data: {
                    room_id: room_id
                },
                success: function(e) {
                    summary_status = 0;
                }
            });
            summary_status = 0;
            overview_status = 0;
            var e = 0;
            e = o + price_status + address_status + listing_status + photo_status + overview_status;
            var t = 6 - e;
            if (t != 0) {
                jQuery("#steps").replaceWith('<span id="steps">' + t + " steps</span>");
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#list_space").hide();
                        jQuery("#steps_count").show();
                    }
                })
            } else if (t == 0) {
                jQuery("#list_space").show();
                jQuery("#steps_count").hide();
            }
        }
        jQuery("#cal_container").hide();
        jQuery("#terms_container").show();
        jQuery("#overview_entire").hide();
        jQuery("#amenities_entire").hide();
        jQuery("#listing_entire").hide();
        jQuery("#price_container").hide();
        jQuery("#photos_container").hide();
        jQuery("#address_entire").hide();
        jQuery("#address_right").hide();
        jQuery("#address_side").hide();
        jQuery("#address_after").hide();
        jQuery("#amenities_after").hide();
        jQuery("#amenities").show();
        jQuery("#main_entire_right").hide();
        jQuery("#price-right-hover").hide();
        jQuery("#overview-text-right").hide();
        jQuery("#summary-text-hover").hide();
        jQuery("#static_circle_map").hide();
        jQuery("#cleaning-price-right").hide();
        jQuery("#additional-price-right").hide();
        jQuery("#detail_container").hide();
        jQuery("#detail_side").show();
        jQuery("#detail_side_after").hide();
        jQuery("#cal_container").hide();
        jQuery("#terms_side").hide();
        jQuery("#terms_side_after").show();
        jQuery("#ded").hide();
        if (o == 0) {
            jQuery("#cal").hide();
            jQuery("#cal1").show();
        } else {
            jQuery("#cal").hide();
            jQuery("#cal_after").show();
            jQuery("#cal1").hide();
        }
        if (overview_status == 0) {
            jQuery("#overview").show();
            jQuery("#overview_after").hide();
        } else {
            jQuery("#overview_after").hide();
            jQuery("#overview").show();
            jQuery("#over_plus").hide();
            jQuery("#over_plus_after1").hide();
            jQuery("#over_plus_after").show();
        }
        if (price_status == 1) {
            jQuery("#price_after").hide();
            jQuery("#price").show();
            jQuery("#des_plus").hide();
            jQuery("#des_plus_after").show();
            u = jQuery("#night_price").val();
            var e = 0;
            e = o + price_status + address_status + listing_status + photo_status + overview_status;
            var t = 6 - e;
            if (t != 0) {
                jQuery("#steps").replaceWith('<span id="steps">' + t + " steps</span>");
                jQuery.ajax({
                    url: base_url + "rooms/final_step",
                    type: "POST",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery("#list_space").hide();
                        jQuery("#steps_count").show();
                    }
                })
            } else if (t == 0) {
                jQuery("#list_space").show();
                jQuery("#steps_count").hide();
            }
        } else {
            jQuery("#price_after").hide();
            jQuery("#price").show();
            jQuery("#des_plus_after").hide();
            jQuery("#des_plus").show();
        }
        if (title_status == 1 && summary_status == 1) {
            jQuery("#overview_after").hide();
            jQuery("#overview").show();
            jQuery("#over_plus").hide();
            jQuery("#over_plus_after1").hide();
            jQuery("#over_plus_after").show();
        } else {
            jQuery("#overview_after").hide();
            jQuery("#overview").show();
            jQuery("#over_plus_after").hide();
            jQuery("#over_plus").show();
        }
        if (beds_status == 1 && bathrooms_status == 1 && bedscount_status == 1 && bedtype_status == 1) {
            jQuery("#listing").show();
            jQuery("#listing_after").hide();
            jQuery("#list_plus").hide();
            jQuery("#list_plus_after").show();
        } else {
            jQuery("#listing").show();
            jQuery("#listing_after").hide();
        }
        if (photo_status == 1) {
            jQuery("#photo_after").hide();
            jQuery("#photo").show();
            jQuery("#photo_plus").hide();
            jQuery("#photo_grn").show();
        } else {
            jQuery("#photo_after").hide();
            jQuery("#photo").show();
            jQuery("#photo_grn").hide();
            jQuery("#photo_plus").show();
        }
        if (address_status == 1) {
            jQuery("#address_after").hide();
            jQuery("#address_side").show();
            jQuery("#addr_plus_after_grn").show();
            jQuery("#address_before").hide();
            jQuery("#addr_plus").hide();
        } else {
            jQuery("#address_after").hide();
            jQuery("#address_side").show();
        }
        if (house_rule != "") {
            jQuery("#detail_plus1").hide();
        }
    });
    jQuery("#cancel_policy").change(function() {
        jQuery.ajax({
            url: base_url + "rooms/cancellation_policy",
            type: "POST",
            data: {
                room_id: room_id,
                policy: jQuery(this).val()
            },
            success: function(e) {
            	if(e != 'failed')
            	{
                jQuery("#terms_plus").hide();
                jQuery("#terms_plus_after").hide();
                jQuery("#policy_saving").fadeIn();
                jQuery("#policy_saving").fadeOut();
               }
               else
               {
               	alert("Your chosen Cancellation Policy already deleted by Admin.");
               }
            }
        })
    });
    jQuery("#country").click(function() {
        jQuery(".next_active").css("opacity", .65);
        jQuery(".disable-btn").show();
        jQuery(".enable-btn").hide();
        jQuery("#lys_street_address").val("");
        jQuery("#city").val("");
        jQuery("#state").val("");
        jQuery("#apt").val("");
        jQuery("#zipcode").val("");
    });
    jQuery("#week_price").mouseover(function() {
        jQuery("#summary-price-right").show();
        jQuery("#price_right").hide();
        jQuery("#cleaning-price-right").hide();
        jQuery("#additional-price-right").hide();
    });
    jQuery("#night_price").mouseover(function() {
        jQuery("#summary-price-right").hide();
        jQuery("#price_right").show();
        jQuery("#cleaning-price-right").hide();
        jQuery("#additional-price-right").hide();
    });
    jQuery("#month_price").mouseover(function() {
        jQuery("#summary-price-right").show();
        jQuery("#price_right").hide();
        jQuery("#cleaning-price-right").hide();
        jQuery("#additional-price-right").hide();
    });
    jQuery("#js-cleaning-fee").mouseover(function() {
        jQuery("#cleaning-price-right").show();
        jQuery("#price_right").hide();
        jQuery("#summary-price-right").hide();
        jQuery("#additional-price-right").hide();
    });
    jQuery("#js-additional-guests").mouseover(function() {
        jQuery("#cleaning-price-right").hide();
        jQuery("#price_right").hide();
        jQuery("#summary-price-right").hide();
        jQuery("#additional-price-right").show();
    });
    jQuery("#title").mouseover(function() {
        jQuery("#overview-text-right").show();
        jQuery("#summary-text-hover").hide();
    });
    jQuery("#summary").mouseover(function() {
        jQuery("#overview-text-right").hide();
        jQuery("#summary-text-hover").show();
    });
    jQuery(window).scroll(function() {
        if (jQuery(window).scrollTop() >= 20) {
            jQuery(".header_bottom_nav").addClass("fixed-top");
        } else {
            jQuery(".header_bottom_nav").removeClass("fixed-top");
        }
    });
    jQuery(window).scroll(function() {
        if (jQuery(window).scrollTop() >= 30) {
            jQuery("#sidebar_main_entire").addClass("fixed-left");
        } else {
            jQuery("#sidebar_main_entire").removeClass("fixed-left");
        }
    });
    var L = document.getElementById("lys_street_address");
    var A = new google.maps.places.Autocomplete(L);
    google.maps.event.addListener(A, "place_changed", function() { 
        var e = A.getPlace();
      //  alert(e.formatted_address);
        var r = e.formatted_address; 
        var t = e.geometry.location.lat();
        var n = e.geometry.location.lng();
        jQuery.getJSON("https://maps.googleapis.com/maps/api/geocode/json?address=" + r + "&sensor=true&key="+places_API+"&language=en", function(e) {
        
            if (e.status == "OK") {
                jQuery("#hidden_lat").val(t);
                jQuery("#hidden_lng").val(n);
                address = e.results[0].formatted_address;
                jQuery("#hidden_address").val(address);
                var r = 0;
                var i = {};
                for (var s = 0; s < e.results[0].address_components.length; s++) {
                    var o = e.results[0].address_components[s].types.join(",");
                    if (o == "street_number") {
                        i.street_number = e.results[0].address_components[s].long_name
                    }
                    if (o == "route" || o == "point_of_interest,establishment") {
                        i.route = e.results[0].address_components[s].long_name;
                        door = i.street_number + " " +i.route;
                       
                       var str = i.street_number;
          
                        if(str == undefined)
                        {
                        	 jQuery("#lys_street_address").val(i.route);
                        }
                        else
                        { 
                        	jQuery("#lys_street_address").val(door);
                        }
                       
                        if (i.route == "[object HTMLInputElement]") {
                            jQuery("#lys_street_address").val("");
                        }
                    }
                    if (o == "sublocality,political" || o == "locality,political" || o == "neighborhood,political" || o == "administrative_area_level_3,political") {
                        i.city = city == "" || o == "locality,political" ? e.results[0].address_components[s].long_name : city;
                        jQuery("#city").val(i.city);
                        if (i.city == "[object HTMLInputElement]") {
                            jQuery("#city").val("")
                        }
                    }
                    if (o == "administrative_area_level_1,political") {
                        r = 1;
                        i.state = e.results[0].address_components[s].long_name;
                        jQuery("#state").val(i.state);
                        if (i.state == "[object HTMLInputElement]") {
                            jQuery("#state").val("")
                        }
                    }
                    if (r != 1) {
                        jQuery("#state").val("")
                    }
                    if (o == "postal_code" || o == "postal_code_prefix,postal_code") {
                        i.zipcode = e.results[0].address_components[s].long_name;
                        jQuery("#zipcode").val(i.zipcode);
                        if (i.zipcode == "[object HTMLInputElement]") {
                            jQuery("#zipcode").val("")
                        }
                    }
                    if (o == "country,political") {
                        i.country = e.results[0].address_components[s].long_name;
                        jQuery("#country option").each(function() {
                            if (jQuery(this).text() == jQuery.trim(i.country)) {
                                jQuery("#country").val(i.country)
                            }
                        })
                    }
                }
            } else {
                jQuery.ajax({
                    url: base_url + "rooms/get_address",
                    type: "POST",
                    dataType: "json",
                    data: {
                        room_id: room_id
                    },
                    success: function(e) {
                        jQuery.each(e, function(e, t) {
                            city = t["city"];
                            jQuery("#city").val(city);
                            state = t["state"];
                            jQuery("#state").val(state);
                            country = t["country"];
                            jQuery("#country").val(country);
                            jQuery("#hidden_lat").val(t["lat"]);
                            jQuery("#hidden_lng").val(t["long"]);
                            jQuery("#zipcode").val(t["zip_code"])
                        })
                    }
                })
            }
        });
        jQuery(".next_active").css("opacity", 1);
        jQuery(".disable-btn").hide();
        jQuery(".enable-btn").show()
    })
});
    /* ajaxfileupload */
jQuery.extend({
    createUploadIframe: function(e, t) {
        var n = "jUploadFrame" + e;
        if (window.ActiveXObject) {
            var r = document.createElement("iframe");
            r.id = r.name = n;
            if (typeof t == "boolean") {
                r.src = "javascript:false"
            } else if (typeof t == "string") {
                r.src = t
            }
        } else {
            var r = document.createElement("iframe");
            r.id = n;
            r.name = n
        }
        r.style.position = "absolute";
        r.style.top = "-1000px";
        r.style.left = "-1000px";
        document.body.appendChild(r);
        return r
    },
    createUploadForm: function(e, t) {
        var n = "jUploadForm" + e;
        var r = "jUploadFile" + e;
        var i = jQuery('<form  action="" method="POST" name="' + n + '" id="' + n + '" enctype="multipart/form-data"></form>');
        var s = jQuery("#" + t);
        var o = jQuery(s).clone();
        jQuery(s).attr("id", r);
        jQuery(s).before(o);
        jQuery(s).appendTo(i);
        jQuery(i).css("position", "absolute");
        jQuery(i).css("top", "-1200px");
        jQuery(i).css("left", "-1200px");
        jQuery(i).appendTo("body");
        return i
    },
    ajaxFileUpload: function(e) {
        e = jQuery.extend({}, jQuery.ajaxSettings, e);
        var t = (new Date).getTime();
        var n = jQuery.createUploadForm(t, e.fileElementId);
        var r = jQuery.createUploadIframe(t, e.secureuri);
        var i = "jUploadFrame" + t;
        var s = "jUploadForm" + t;
        if (e.global && !(jQuery.active++)) {
            jQuery.event.trigger("ajaxStart")
        }
        var o = false;
        var u = {};
        if (e.global) jQuery.event.trigger("ajaxSend", [u, e]);
        var a = function(t) {
            var r = document.getElementById(i);
            try {
                if (r.contentWindow) {
                    u.responseText = r.contentWindow.document.body ? r.contentWindow.document.body.innerHTML : null;
                    u.responseXML = r.contentWindow.document.XMLDocument ? r.contentWindow.document.XMLDocument : r.contentWindow.document
                } else if (r.contentDocument) {
                    u.responseText = r.contentDocument.document.body ? r.contentDocument.document.body.innerHTML : null;
                    u.responseXML = r.contentDocument.document.XMLDocument ? r.contentDocument.document.XMLDocument : r.contentDocument.document
                }
            } catch (s) {
                jQuery.handleError(e, u, null, s)
            }
            if (u || t == "timeout") {
                o = true;
                var a;
                try {
                    a = t != "timeout" ? "success" : "error";
                    if (a != "error") {
                        var f = jQuery.uploadHttpData(u, e.dataType);
                        if (e.success) e.success(f, a);
                        if (e.global) jQuery.event.trigger("ajaxSuccess", [u, e])
                    } else jQuery.handleError(e, u, a)
                } catch (s) {
                    a = "error";
                    jQuery.handleError(e, u, a, s)
                }
                if (e.global) jQuery.event.trigger("ajaxComplete", [u, e]);
                if (e.global && !--jQuery.active) jQuery.event.trigger("ajaxStop");
                if (e.complete) e.complete(u, a);
                jQuery(r).unbind();
                setTimeout(function() {
                    try {
                        jQuery(r).remove();
                        jQuery(n).remove()
                    } catch (t) {
                        jQuery.handleError(e, u, null, t)
                    }
                }, 100);
                u = null
            }
        };
        if (e.timeout > 0) {
            setTimeout(function() {
                if (!o) a("timeout")
            }, e.timeout)
        }
        try {
            var n = jQuery("#" + s);
            jQuery(n).attr("action", e.url);
            jQuery(n).attr("method", "POST");
            jQuery(n).attr("target", i);
            if (n.encoding) {
                n.encoding = "multipart/form-data"
            } else {
                n.enctype = "multipart/form-data"
            }
            jQuery(n).submit()
        } catch (f) {
            jQuery.handleError(e, u, null, f)
        }
        if (window.attachEvent) {
            document.getElementById(i).attachEvent("onload", a)
        } else {
            document.getElementById(i).addEventListener("load", a, false)
        }
        return {
            abort: function() {}
        }
    },
    uploadHttpData: function(r, type) {
        var data = !type;
        data = type == "xml" || data ? r.responseXML : r.responseText;
        if (type == "script") jQuery.globalEval(data);
        if (type == "json") eval("data = " + data);
        if (type == "html") jQuery("<div>").html(data).evalScripts();
        return data
    }
})
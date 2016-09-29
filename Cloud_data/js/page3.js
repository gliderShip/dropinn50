function translate(e) {
    var t = jQuery("#new_translate_button").data("google-key");
    jQuery(".trans").each(function(n, r) {
        var i = jQuery(r);
        jQuery.ajax({
            url: "https://ajax.googleapis.com/ajax/services/language/translate?key=" + t + "&callback=?&v=1.0&langpair=%7C" + e,
            dataType: "json",
            data: {
                q: i.html()
            },
            headers: {
                "X-HTTP-Method-Override": "GET"
            },
            type: "POST",
            success: function(e) {
                if (e && e.responseData && e.responseData.translatedText) {
                    i.html(e.responseData.translatedText)
                }
            }
        })
    })
}

function load_map_wrapper(e) {
    if (map_loaded) {
        if (e && window[e]) {
            window[e]()
        }
    } else {
        (function() {
            var t = document.createElement("script");
            t.type = "text/javascript";
            t.async = true;
            t.src = document.location.protocol + "//maps.google.com/maps/api/js?v=3.22&sensor=false&callback=" + e + "&language=en&key="+places_API;
            var n = document.getElementsByTagName("script")[0];
            n.parentNode.insertBefore(t, n)
        })();
        map_loaded = true
    }
}

function isValidDate(e) {
    if (Object.prototype.toString.call(e) !== "[object Date]") {
        return false
    }
    return !isNaN(e.getTime())
}

function lwlb_signup_button_click() {
    jQuery("#intended_action").val("signup");
    jQuery("#lwlb_signup_spinner").show();
    jQuery("#lwlb_signup_button").hide();
    jQuery("#message_form").submit()
}

function lwlb_login_button_click() {
    jQuery("#intended_action").val("login");
    jQuery("#lwlb_login_spinner").show();
    jQuery("#lwlb_login_button").hide();
    jQuery("#message_form").submit();
    Mixpanel.track("page3.contact_me.submit.signin.submit")
}

function lwlb_hide_and_reset(e) {
    page3Slideshow.enableKeypressListener();
    lwlb_hide(e);
    if (typeof lwlb_reset_messaging !== "undefined") {
        lwlb_reset_messaging()
    }
}

function check_inputs(e, t, n) {
    e = typeof e != "undefined" ? e : true;
    t = typeof t != "undefined" ? t : "checkin";
    n = typeof n != "undefined" ? n : "checkout";
    if (calendar_is_not_set_date(t)) {
        if (e) {
            calendar_show_cal(t)
        }
        return false
    }
    if (calendar_is_not_set_date(n)) {
        if (e) {
            calendar_show_cal(n)
        }
        return false
    }
    return true
}

function copy_checkin_checkout_fields() {
    var e = check_inputs(false);
    if (e) {
        jQuery("#message_checkin").val(jQuery("#checkin").val());
        jQuery("#message_checkout").val(jQuery("#checkout").val());
        jQuery("#message_number_of_guests").val(jQuery("#number_of_guests").val());
        check_availability_of_dates()
    }
}

function copy_message_fields_to_book_it() {
    jQuery("#checkin").val(jQuery("#message_checkin").val());
    jQuery("#checkout").val(jQuery("#message_checkout").val());
    jQuery("#number_of_guests").val(jQuery("#message_number_of_guests").val())
}

function refresh_subtotal() {
    var e = function(e, t, n) {
        var r;
        if (e.available) {
            jQuery("#book_it_disabled").hide();
            jQuery("#book_it_enabled").show();
            r = jQuery("#price_amount").html(e.price_per_night).data("nightly-price", e.price_per_night);
            jQuery("#service_fee").html(e.service_fee);
            CogzidelRooms.staggered = e.staggered;
            if (CogzidelRooms.staggered === true) {
                if (CogzidelRooms.stayOffered !== 0) {
                    jQuery("#payment_period").hide();
                    jQuery("#per_month").show();
                    CogzidelRooms.$cancellationVal.text(Translations.long_term);
                    jQuery("#includesFees").show();
                    jQuery("#book_it_default").addClass("monthly")
                }
                jQuery("#subtotal_area").hide();
                jQuery("#show_more_subtotal_info").hide();
                jQuery("#price_amount").text(Cogzidel.Utils.decode(e.staggered_price));
                r.data("monthly-price", e.staggered_price);
                CogzidelRooms.hideMonthlyPriceDetails()
            } else {
                if (CogzidelRooms.stayOffered === 2) {
                    jQuery("#per_month").hide();
                    CogzidelRooms.$cancellationVal.text(CogzidelRooms.originalCancellationPolicy);
                    jQuery("#includesFees").hide();
                    jQuery("#book_it_default").removeClass("monthly");
                    jQuery("#payment_period").show()
                }
                jQuery("#subtotal_area ").show();
                jQuery("#subtotal_area").find("p").show();
                jQuery("#show_more_subtotal_info").show();
                if (e.extra_guest == 1) {
                    jQuery("#show_more_subtotal_info1").show()
                } else {
                    jQuery("#show_more_subtotal_info1").hide()
                }
                jQuery("#subtotal").html(e.total_price);
                jQuery("#payment_period").val("per_night");
                r.removeAttr("data-monthly-price");
                CogzidelRooms.showMonthlyPriceDetails()
            }
            if (e.can_instant_book) {
                Page3.showInstantBookButton()
            } else {
                Page3.showBookItButton()
            }
        } else {
            if (CogzidelRooms.stayOffered === 1) {
                jQuery("#payment_period").hide();
                jQuery("#per_month").show();
                CogzidelRooms.$cancellationVal.text(Translations.long_term);
                jQuery("#includesFees").show();
                CogzidelRooms.hideMonthlyPriceDetails()
            } else {
                jQuery("#book_it_default").removeClass("monthly");
                jQuery("#payment_period").show();
                jQuery("#per_month").hide();
                CogzidelRooms.$cancellationVal.text(CogzidelRooms.originalCancellationPolicy);
                jQuery("#includesFees").hide();
                jQuery("#price_amount").html(jQuery("#price_amount").data("nightly-price"));
                CogzidelRooms.showMonthlyPriceDetails()
            }
            jQuery("#book_it_disabled_message").html(e.reason_message);
            jQuery("#book_it_enabled").hide();
            jQuery("#book_it_disabled").show();
            jQuery("#show_more_subtotal_info").hide()
        }
        jQuery("#book_it_status").pulsate(1, 600)
    };
    jQuery("#book_it_button").removeAttr("disabled");
    jQuery("#subtotal_area").find("p").hide();
    jQuery("#subtotal_area").show();
    if (calendar_is_not_set_date("checkin") || calendar_is_not_set_date("checkout")) {
        e = function() {};
        jQuery("#book_it_disabled").hide();
        jQuery("#book_it_enabled").show();
        jQuery("#subtotal_area").hide();
        jQuery("#show_more_subtotal_info").hide()
    } else {
        jQuery("#subtotal, #book_it_disabled_message").html('<img src="' + base_url + 'images/spinner.gif" alt="" height="16" width="16" />')
    }
    jQuery.getJSON(base_url + "rooms/ajax_refresh_subtotal", jQuery("#book_it_form").serialize(), e)
}

function setup_lwlb_contact() {
    copy_checkin_checkout_fields();
    lwlb_show("lwlb_contact");
    page3Slideshow.disableKeypressListener();
    jQuery.getJSON(ajax_already_messaged_url, function(e) {
        if (e.has_already_messaged) {
            jQuery("#messaging-errors").addClass("contacted-before")
        }
    })
}

function load_google_map() {
    var e = 14;
    var t = 11;
    var n;
    var r = [];
    var i = [],
        s;
    var o = new google.maps.LatLng(jQuery("#map").data("lat"), jQuery("#map").data("lng"));
    var u = new google.maps.LatLngBounds;
    u.extend(o);
    if (!CogzidelRooms.inIsrael) {
        jQuery("#map").cogzidelMap({
            position: o,
            isFuzzy: true
        });
        var a = jQuery("#map").cogzidelMap().map;
        var f = new google.maps.InfoWindow({
            maxWidth: 160,
            zIndex: 0
        });
        google.maps.event.addListener(a, "click", function() {
            f.close()
        });
        var l = false;
        jQuery("#guidebook-recommendations li").each(function(e, t) {
            l = true;
            var n = jQuery(t);
            var i = new google.maps.LatLng(n.data("lat"), n.data("lng"));
            var s = new google.maps.Marker({
                clickable: true,
                position: i,
                map: a,
                zIndex: 1,
                icon: new google.maps.MarkerImage(jQuery("img", n).attr("src"), null, null, new google.maps.Point(11, 37))
            });
            google.maps.event.addListener(s, "click", function() {
                f.setContent('<address style="color:#808080"><p style="color:#E0007A;font-weight:bold;">' + jQuery("h2", n).html() + "</p><p>" + jQuery("span.location", n).html() + "</p><p><span>" + jQuery("span.city", n).html() + "</span>, <span>" + jQuery("span.state", n).html() + "</span> <span>" + jQuery("span.zipcode", n).html() + '</span></p></address><p style="margin-top: 10px">' + jQuery("div.description", n).html() + "</p>");
                f.open(a, s)
            });
            r.push(s);
            u.extend(i)
        });
        if (l) {
            a.fitBounds(u);
            google.maps.event.addListenerOnce(a, "bounds_changed", function() {
                if (this.getZoom() > 14) {
                    this.setZoom(14)
                }
            })
        }
    } else {
        document.getElementById("map").innerHTML = '<iframe width="639" height="470" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="/rooms/israel_map?id=' + CogzidelRooms.hostingId + '">'
    }
}(function(e, t) {
    var n = e.PhoneNumberInputWidget = function(e) {
        this.init(e)
    };
    n.prototype = {
        prefix: null,
        init: function(e) {
            var n = this;
            this.element = t(e.element);
            this.elements = t.extend({}, {});
            this.options = t.extend({}, e || {});
            this.$("select").change(this.proxy(this.countryChangeHandler));
            this.$("input.pniw-number").keypress(this.proxy(this.numberKeypressHandler)).change(this.proxy(this.numberChangeHandler));
            this.countryChangeHandler();
            if (this.options.country) {
                this.setCountry(this.options.country)
            }
            if (this.options.number) {
                this.setNumber(this.options.number)
            }
        },
        setCountry: function(e) {
            this.$("select").find('option[value="' + e + '"]').attr("selected", "selected").change()
        },
        setNumber: function(e) {
            var t = this.getPrefix(),
                n = (e + "").replace(new RegExp("^" + t), "");
            this.$("input.pniw-number").val(n);
            this.updateHiddenField()
        },
        getNumber: function() {
            var e = this.getPrefix(),
                n = this.$("input.pniw-number").val();
            return t.trim(e + n)
        },
        getCountry: function() {
            return this.$("select").val()
        },
        countryChangeHandler: function(e, t) {
            var n = this.getCountry();
            this.prefix = this.$('option[value="' + n + '"]').data("prefix");
            this.$(".pniw-number-prefix").text("+" + this.getPrefix());
            this.updateHiddenField()
        },
        getPrefix: function() {
            return this.prefix
        },
        setError: function(e) {
            this.$("input.pniw-number").toggleClass("error", e)
        },
        numberKeypressHandler: function(e, n) {
            var r = t(e);
            if (t.trim(r.val().length)) {
                this.setError(false)
            }
            if (n.which === 13) {
                return false
            }
        },
        numberChangeHandler: function(e, t) {
            this.updateHiddenField()
        },
        updateHiddenField: function() {
            this.$('input[type="hidden"]').val(this.getNumber())
        },
        $: function(e, t) {
            if (t || typeof this.elements[e] === "undefined") {
                this.elements[e] = this.element.find(e)
            }
            return this.elements[e]
        },
        proxy: function(e) {
            var t = this;
            return function(n) {
                return e.call(t, this, n)
            }
        }
    };
    t.fn.phoneNumberInputWidget = function() {
        return this.each(function(e, r) {
            var i = new n({
                element: r
            });
            t(this).data("phoneNumberInputWidget", i)
        })
    };
    t.phoneNumberInputWidget = function() {
        t("div.phone-number-input-widget").phoneNumberInputWidget()
    }
})(Cogzidel, jQuery);
(function(e, t) {
    var n = e.PhoneNumberVerifyWidget = function(e) {
        this.init(e)
    };
    n.prototype = {
        ajaxUrl: "/phone_numbers/phone_number_verify_widget",
        init: function(e) {
            var n = this;
            this.elements = t.extend({}, {});
            this.options = t.extend({}, e || {});
            if (e.element) {
                this.element = t(e.element);
                n.initCallback()
            } else {
                t.colorbox.loading();
                t.get(this.ajaxUrl, function(e) {
                    t.colorbox({
                        html: e
                    });
                    n.element = t("#colorbox .phone-number-add-widget");
                    n.initCallback()
                })
            }
        },
        initCallback: function() {
            this.$('.pnaw-verify-container a[rel="sms"], .pnaw-verify-container a[rel="call"]').click(this.proxy(this.stepOneHandler));
            this.$('.pnaw-verify-container a[rel="verify"]').click(this.proxy(this.stepTwoHandler));
            this.$('.pnaw-verify-container a[rel="cancel"]').click(this.proxy(this.cancelHandler));
            this.$("#phone_number_verification").keypress(this.proxy(this.codeKeypressHandler));
            this.phoneNumberInputWidget = this.$(".phone-number-input-widget").data("phoneNumberInputWidget");
            if (this.options.country) {
                this.setCountry(this.options.country)
            }
            if (this.options.number) {
                this.setNumber(this.options.number)
            }
        },
        setCountry: function(e) {
            this.phoneNumberInputWidget.setCountry(e)
        },
        setNumber: function(e) {
            this.phoneNumberInputWidget.setNumber(e)
        },
        getPrefix: function() {
            return this.phoneNumberInputWidget.getPrefix()
        },
        getNumber: function() {
            return this.phoneNumberInputWidget.getNumber()
        },
        getCountry: function() {
            return this.phoneNumberInputWidget.getCountry()
        },
        codeKeypressHandler: function(e, t) {
            if (t.which === 13) {
                this.$('.pnaw-verify-container a[rel="verify"]').click();
                return false
            }
        },
        cancelHandler: function(e, t) {
            this.finish(false);
            this.element.trigger("cancel")
        },
        verifyNumber: function(e, n, r) {
            var i = this,
                s = "/phone_numbers/create",
                e = t.trim(e),
                o = {
                    phone_number: e,
                    phone_type: r,
                    phone_number_country: n
                };
            this.setLoading(true);
            this.stepOneXhr = t.post(s, o, function(e) {
                i.setLoading(false);
                i.stepTwo();
                i.element.data("phone_number_id", e.phone_number_id)
            }).error(function() {
                i.setLoading(false);
                i.phoneNumberInputWidget.setError(true)
            })
        },
        stepOneHandler: function(e, n) {
            var r = this.getPrefix() + "",
                i = this.getNumber() + "",
                s = t(e).attr("rel"),
                o = this.getCountry();
            if (i === r) {
                this.phoneNumberInputWidget.setError(true)
            } else {
                this.verifyNumber(i, o, s)
            }
        },
        stepTwoHandler: function() {
            var e = "/phone_numbers/verify",
                n = this.$("#phone_number_verification").val(),
                r = {
                    id: this.element.data("phone_number_id"),
                    verification_code: n
                },
                i = this;
            i.setLoading(true);
            t.post(e, r, function(e) {
                i.setLoading(false);
                if (e.result === "success") {
                    i.finish();
                    i.$(".pnaw-verification-error").hide()
                } else {
                    i.$("#phone_number_verification").val("").animate({
                        left: "-5px"
                    }, 100, function() {
                        t(this).animate({
                            left: "5px"
                        }, 100, function() {
                            t(this).animate({
                                left: 0
                            }, 100)
                        })
                    });
                    i.$(".pnaw-verification-error").fadeIn(200).text(e.error)
                }
            })
        },
        stepOne: function() {
            this.phoneNumberInputWidget.setNumber("");
            this.$(".pnaw-step1").show();
            this.$(".pnaw-step2").hide()
        },
        stepTwo: function() {
            this.$("#phone_number_verification").val("");
            this.$(".pnaw-step1").hide();
            this.$(".pnaw-step2").fadeIn(200);
            this.$(".pnaw-verification-error").hide()
        },
        finish: function(e) {
            this.stepOne();
            if (this.options.onComplete && (typeof e === "undefined" || typeof e !== "undefined" && e)) {
                this.options.onComplete.call(this)
            }
            this.element.trigger("complete");
            t(document).trigger("phone_number_verify_widget.complete");
            t.colorbox.close()
        },
        setLoading: function(e) {
            if (e) {
                this.element.addClass("loading")
            } else {
                this.element.removeClass("loading")
            }
        },
        $: function(e, t) {
            if (t || typeof this.elements[e] === "undefined") {
                this.elements[e] = this.element.find(e)
            }
            return this.elements[e]
        },
        proxy: function(e) {
            var t = this;
            return function(n) {
                return e.call(t, this, n)
            }
        }
    };
    t.fn.phoneNumberVerifyWidget = function() {
        return this.each(function(e, r) {
            var i = new n({
                element: r
            });
            t(this).data("phoneNumberVerifyWidget", i)
        })
    };
    t.phoneNumberVerifyWidget = function() {
        t("div.phone-number-add-widget").phoneNumberVerifyWidget()
    }
})(Cogzidel, jQuery);
(function(e, t) {
    var n = e.PhoneNumberWidget = function(e, t) {
        this.init(e, t)
    };
    n.prototype = {
        ajaxUrl: "/phone_numbers/phone_number_widget",
        init: function(e, n) {
            var r = this;
            this.options = n || {};
            this.element = t(e);
            this.$add = this.element.find("a.add");
            this.$addWidget = this.element.find(".phone-number-add-widget:first");
            this.$verifyWidget = this.element.find(".phone-number-add-widget:last");
            this.$table = this.element.find(".phone-numbers-table");
            this.$noPhoneNumbers = this.element.find(".no-phone-numbers");
            this.$hideDuringVerify = this.element.find(".phone-numbers-hide-during-verify");
            this.element.delegate("a.remove", "click", this.proxy(this.removeHandler));
            this.element.delegate("a.verify", "click", this.proxy(this.verifyHandler));
            this.$add.click(this.proxy(this.addHandler));
            this.$addWidget.bind("complete", this.proxy(this.completeAddHandler));
            this.addWidget = new Cogzidel.PhoneNumberVerifyWidget({
                element: this.$addWidget,
                onComplete: function() {
                    r.onVerifyComplete()
                }
            });
            this.verifyWidget = new Cogzidel.PhoneNumberVerifyWidget({
                element: this.$verifyWidget,
                onComplete: function() {
                    r.onVerifyComplete()
                }
            });
            this.$verifyWidget.bind("cancel", this.proxy(this.verifyCancelHandler));
            if (this.options.showAddNumberInitially && this.$table.children().length === 0) {
                this.$addWidget.show();
                this.$add.hide();
                this.$noPhoneNumbers.hide()
            }
            if (this.options.noCancel) {
                this.element.addClass("noCancel")
            }
        },
        verifyHandler: function(e, n) {
            var r = t(e),
                i = r.parents("tr"),
                s = i.data("number"),
                o = i.data("country"),
                u = r.attr("rel"),
                a = i.data("id"),
                f = this;
            this.$add.show();
            this.$addWidget.hide();
            this.$verifyWidget.data("phone_number_id", a);
            this.verifyWidget.verifyNumber(s, o, u);
            this.verifyWidget.stepTwo();
            this.verifyWidget.setLoading(false);
            this.$verifyWidget.show();
            this.$hideDuringVerify.hide()
        },
        verifyCancelHandler: function(e, t) {
            this.$hideDuringVerify.show();
            this.$verifyWidget.hide();
            if (this.verifyWidget.stepOneXhr) {
                this.verifyWidget.stepOneXhr.abort()
            }
        },
        onVerifyComplete: function() {
            var e = this,
                n;
            if (this.options.onVerifyComplete) {
                n = this.options.onVerifyComplete.call(this)
            }
            if (n !== false) {
                e.element.addClass("loading");
                t.get(e.ajaxUrl, function(n) {
                    e.element.replaceWith(n);
                    t.phoneNumberWidget(e.options)
                })
            }
            this.$hideDuringVerify.show();
            this.$hideDuringVerify.unbind("cancel")
        },
        completeAddHandler: function(e, t) {
            this.$add.show();
            this.$addWidget.slideUp(200)
        },
        removeHandler: function(e, n) {
            var r = t(e),
                i = r.attr("href"),
                s = this;
            t.post(i);
            r.parents("tr").fadeOut(200, function() {
                t(this).remove();
                if (s.element.find("table.phone-numbers-table tr").length) {
                    s.element.addClass("has-phone-numbers")
                } else {
                    s.element.removeClass("has-phone-numbers")
                }
            });
            return false
        },
        addHandler: function(e, t) {
            this.$add.hide();
            this.$addWidget.slideDown(200)
        },
        proxy: function(e) {
            var t = this;
            return function(n) {
                return e.call(t, this, n)
            }
        }
    };
    t.fn.phoneNumberWidget = function(e) {
        return this.each(function(t, r) {
            new n(r, e)
        })
    };
    t.phoneNumberWidget = function(e) {
        t("div.phone-numbers-container").phoneNumberWidget(e)
    };
    e.PhoneNumberWidget = n
})(Cogzidel, jQuery);
var photo_gallery_loaded = false;
var initPhotoGallery = function() {
    if (photo_gallery_loaded) {
        return
    }
    photo_gallery_loaded = true;
    jQuery("#galleria_container").galleria({
        clicknext: true,
        counter: false,
        height: 491,
        imagePosition: "top",
        transition: "fade",
        transitionSpeed: 100,
        thumbFit: false,
        width: 639
    })
};
var Page3 = {
    showInstantBookButton: function() {
        jQuery("#book_it_button").addClass("force_hide");
        jQuery("#instant_book_it_button").removeClass("force_hide").removeAttr("disabled");
        jQuery("#instant_book_arrow").removeClass("force_hide")
    },
    showBookItButton: function() {
        jQuery("#instant_book_it_button").addClass("force_hide");
        jQuery("#book_it_button").removeClass("force_hide");
        jQuery("#instant_book_arrow").addClass("force_hide")
    },
    modal_log: function(e) {
        if (Statsd) {
            Statsd.increment("p3.modals." + e)
        }
    }
};
var page3Slideshow = {
    keypressListenerEnabled: false,
    freezeSlideshowControls: false,
    deactivateSlideshowControls: function() {
        setTimeout(function() {
            page3Slideshow.freezeSlideshowControls = false
        }, 200)
    },
    galleriaLoaded: function() {
        var e = Galleria.get(0) !== undefined;
        return e
    },
    enableKeypressListener: function() {
        if (page3Slideshow.keypressListenerEnabled === false) {
            if (!this.galleriaLoaded()) {
                return
            }
            var e = Galleria.get(0);
            jQuery(document.documentElement).bind("keydown", function(t) {
                if (page3Slideshow.freezeSlideshowControls === false && page3Slideshow.galleriaLoaded() === true) {
                    var n = t.keyCode || t.which;
                    if (n == jQuery.ui.keyCode.LEFT) {
                        e.prev();
                        page3Slideshow.freezeSlideshowControls = true
                    } else {
                        if (n == jQuery.ui.keyCode.RIGHT) {
                            e.next();
                            page3Slideshow.freezeSlideshowControls = true
                        }
                    }
                    page3Slideshow.deactivateSlideshowControls()
                }
            });
            page3Slideshow.keypressListenerEnabled = true
        }
    },
    disableKeypressListener: function() {
        jQuery(document.documentElement).unbind("keydown");
        page3Slideshow.keypressListenerEnabled = false
    }
};
var map_loaded = false;
var load_pano = function() {
    var e = jQuery("#pano").data("streetView");
    var t;
    if (!e) {
        var n = jQuery("#pano").data("lat");
        var r = jQuery("#pano").data("lng");
        var i = new google.maps.LatLng(n, r);
        var s = new google.maps.StreetViewPanorama(document.getElementById("pano"), {
            position: i,
            visible: false,
            scrollwheel: false,
            enableCloseButton: false
        });
        (new google.maps.StreetViewService).getPanoramaByLocation(i, 50, function(e, n) {
            if (n !== google.maps.StreetViewStatus.OK) {
                jQuery("#pano_error").show();
                jQuery("#pano_no_error").hide();
                return
            }
            s.setPosition(e.location.latLng);
            s.setVisible(true);
            t = window.setInterval(function() {
                if (jQuery("#auto_pan_pano").is(":checked")) {
                    var e = s.getPov();
                    e.heading += 2;
                    s.setPov(e)
                }
            }, 200)
        });
        jQuery("#pano").data("streetView", s)
    }
};
var CogzidelRooms = {
    init: function(e) {
        function t() {
            if (window.FB && FB.Event && FB.Event.subscribe) {
                FB.Event.subscribe("edge.create", function(e) {
                    _gaq.push(["_trackSocial", "facebook", "like", e])
                });
                FB.Event.subscribe("edge.remove", function(e) {
                    _gaq.push(["_trackSocial", "facebook", "unlike", e])
                })
            }
        }
        this.options = e || {};
        if (window.FB) {
            t()
        } else {
            jQuery(document).bind("fbInit", t)
        }
        if (e.inIsrael) {
            this.inIsrael = e.inIsrael
        }
        if (e.hostingId) {
            this.hostingId = e.hostingId
        }
        if (e.staggered !== undefined) {
            this.staggered = e.staggered
        }
        if (e.staggeredPrice !== undefined) {
            this.staggeredPrice = e.staggeredPrice
        }
        if (e.stayOffered !== undefined) {
            this.stayOffered = e.stayOffered
        }
        this.$cancellationVal = jQuery("#cancellation_val").find("a");
        this.originalCancellationPolicy = this.$cancellationVal.text();
        jQuery("#book_it_default").removeClass("monthly");
        jQuery("#per_month").hide();
        jQuery("#includesFees").hide();
        if (e.isMonthly !== undefined) {
            this.isMonthly = e.isMonthly;
            if (this.isMonthly === true) {
                jQuery("#subtotal_area").find("p").hide();
                jQuery("#book_it_default").addClass("monthly");
                jQuery("#per_month").show();
                this.$cancellationVal.text(Translations.long_term);
                jQuery("#includesFees").show();
                this.hideMonthlyPriceDetails()
            }
        }
        this.dateFormat = jQuery.datepicker._defaults.dateFormat;
        jQuery("#book_it_form").cogzidelInputDateSpan({
            onCheckinClose: refresh_subtotal,
            onCheckoutClose: refresh_subtotal
        });
        var n = jQuery("#price_amount");
        n.data("nightly-price", e.nightlyPrice);
        n.data("weekly-price", e.weeklyPrice);
        n.data("monthly-price", e.monthlyPrice);
        if (!this.isMonthly) {
            n.html(n.data("nightly-price"))
        }
        jQuery("#checkin").val(this.dateFormat);
        jQuery("#checkout").val(this.dateFormat);
        jQuery("#contact_wrapper").live("click", this.proxy(this.contactMeHandler));
        if (document.location.hash) {
            jQuery("a[href='" + document.location.hash + "']").parent().click()
        }
        if (jQuery("#photos_div").is(":visible")) {
            initPhotoGallery()
        }
        jQuery("#show_more_user_info1").click(CogzidelRooms.user_info_toggle);
        jQuery("#payment_period").change(function() {
            var e, t, n;
            var r = jQuery("#price_amount"),
                i = jQuery("#subtotal_area"),
                s = jQuery("#show_more_subtotal_info");
            switch (this.value) {
                case "per_night":
                    r.html(r.data("nightly-price"));
                    jQuery("#includesFees").hide();
                    break;
                case "per_week":
                    t = r.data("weekly-price");
                    n = parseInt(t.match(/\d+/), 10);
                    r.html(t.replace(/(\d+)/, n));
                    jQuery("#includesFees").hide();
                    break;
                case "per_month":
                    if (CogzidelRooms.stayOffered === 1 || CogzidelRooms.stayOffered === 2) {
                        t = "" + CogzidelRooms.staggeredPrice;
                        jQuery("#includesFees").show()
                    } else {
                        t = r.data("monthly-price");
                        jQuery("#includesFees").hide()
                    }
                    n = parseInt(t.match(/\d+/), 10);
                    r.html(t.replace(/(\d+)/, n));
                    break;
                default:
                    break
            }
        });
        var r = jQuery("#videos_div embed");
        r.before('<param name="wmode" value="opaque" />');
        r.attr("wmode", "opaque");
        jQuery("#reputation .pagination a").live("click", function() {
            var e = jQuery(this);
            e.parent().append('<img src="/images/spinner.gif" class="spinner" height="16" width="16" alt="" />');
            jQuery.ajax({
                url: e.attr("href"),
                success: function(t) {
                    e.closest(".rep_content").html(t);
                    jQuery("html, body").animate({
                        scrollTop: jQuery("#reputation").offset().top
                    }, "slow")
                }
            });
            return false
        });
        var i = jQuery("#reputation");
        var s = i.data("review-type");
        var o = i.data("hosting-id");
        switch (s) {
            case "listing_has_reviews":
                select_tab("rep", "this_hosting_reviews", jQuery("#this_hosting_reviews_link"));
                jQuery.ajax({
                    url: "/rooms/this_hosting_reviews_first/" + o,
                    success: function(e, t, n) {
                        if (jQuery.trim(e) !== "") {
                            jQuery("#this_hosting_reviews").html(e)
                        }
                    }
                });
                break;
            case "host_has_reviews":
                select_tab("rep", "other_hosting_reviews", jQuery("#other_hosting_reviews_link"));
                jQuery.ajax({
                    url: "/rooms/other_hosting_reviews_first/" + o,
                    success: function(e) {
                        if (jQuery.trim(e) !== "") {
                            jQuery("#other_hosting_reviews").html(e)
                        }
                    }
                });
                break;
            case "host_has_recommendations":
                select_tab("rep", "friends", jQuery("#friends_link"));
                break;
            case "no_reviews":
            default:
                select_tab("rep", "this_hosting_reviews", jQuery("#this_hosting_reviews_link"));
                break
        }
        if (e.otherHostingPrices) {
            jQuery("#my_other_listings").show();
            jQuery.each(e.otherHostingPrices, function(e, t) {
                jQuery("#hosting_" + e + "_nightly_price").html(t)
            })
        }
        if (e.videoProfile) {
            this.videoProfile = e.videoProfile;
            this.videoID = e.videoID;
            if (this.videoProfile === true) {
                jQuery(".profile_pic").addClass("video");
                jQuery("._pm_inner").find("img").hover(function() {
                    jQuery("._pm_inner").toggleClass("hover")
                }, function() {
                    jQuery("._pm_inner").toggleClass("hover")
                })
            }
        }
        $(".twitter-share-button").click(function(e) {
            var t = this.href;
            if (Cogzidel.tweetHashTags) {
                t += encodeURIComponent(" " + Cogzidel.tweetHashTags)
            }
            popup(t, "fixed", 335, 500);
            e.preventDefault()
        })
    },
    showMonthlyPriceDetails: function() {
        jQuery("#monthly_price_string").parent().parent().show();
        jQuery("#weekly_price_string").parent().parent().show()
    },
    hideMonthlyPriceDetails: function() {
        jQuery("#monthly_price_string").parent().parent().hide();
        jQuery("#weekly_price_string").parent().parent().hide()
    },
    proxy: function(e) {
        var t = this;
        return function() {
            return e.call(t, this)
        }
    },
    contactMeHandler: function(e) {
        var t = this;
        this.loadContactLwlb()
    },
    loadContactLwlb: function() {
        var e = jQuery("#contact_wrapper"),
            t = this;
        e.attr("disabled", "disabled");
        jQuery("#question").attr("disabled", "disabled");
        jQuery("#question_holder").css("opacity", "0.5");
        Page3.modal_log("contact_me_clicked");
        Mixpanel.track("page3.contact_me.click");
        jQuery("#lwlb_contact_container").load(ajax_lwlb_contact_url, null, function() {
            jQuery("#message_checkin").val(t.dateFormat);
            jQuery("#message_checkout").val(t.dateFormat);
            e.removeAttr("disabled");
            setup_lwlb_contact();
            jQuery("#message_form").cogzidelInputDateSpan({
                onCheckinClose: check_availability_of_dates,
                onCheckoutClose: check_availability_of_dates
            })
        })
    },
    Helper: {},
    user_info_toggle: function() {
        jQuery("#more_info_text, #less_info_text").toggle();
        jQuery("#more_info_arrow").toggleClass("contract");
        jQuery("#more_info1").toggle()
    },
    video_profile_init: function(e) {},
    staggered: false,
    hostingLengthType: 0,
    inIsrael: false
};
(function(e) {
    var t, n = this,
        r = n.document,
        i = e(r),
        s = false,
        o = navigator.userAgent.toLowerCase(),
        u = n.location.hash.replace(/#\//, ""),
        a = function() {
            return b.TOUCH ? "touchstart" : "click"
        },
        f = function() {
            var e = 3,
                n = r.createElement("div"),
                i = n.getElementsByTagName("i");
            do {
                n.innerHTML = "<!--[if gt IE " + ++e + "]><i></i><![endif]-->"
            } while (i[0]);
            return e > 4 ? e : t
        }(),
        l = function() {
            return {
                html: r.documentElement,
                body: r.body,
                head: r.getElementsByTagName("head")[0],
                title: r.title
            }
        },
        c = "data ready thumbnail loadstart loadfinish image play pause progress fullscreen_enter fullscreen_exit idle_enter idle_exit rescale lightbox_open lightbox_close lightbox_image",
        h = function() {
            var t = [];
            e.each(c.split(" "), function(e, n) {
                t.push(n);
                if (/_/.test(n)) {
                    t.push(n.replace(/_/g, ""))
                }
            });
            return t
        }(),
        p = function(t) {
            var n;
            if (typeof t !== "object") {
                return t
            }
            e.each(t, function(r, i) {
                if (/^[a-z]+_/.test(r)) {
                    n = "";
                    e.each(r.split("_"), function(e, t) {
                        n += e > 0 ? t.substr(0, 1).toUpperCase() + t.substr(1) : t
                    });
                    t[n] = i;
                    delete t[r]
                }
            });
            return t
        },
        d = function(t) {
            if (e.inArray(t, h) > -1) {
                return b[t.toUpperCase()]
            }
            return t
        },
        v = {
            trunk: {},
            add: function(e, t, r, i) {
                i = i || false;
                this.clear(e);
                if (i) {
                    var s = t;
                    t = function() {
                        s();
                        v.add(e, t, r)
                    }
                }
                this.trunk[e] = n.setTimeout(t, r)
            },
            clear: function(e) {
                var t = function(e) {
                        n.clearTimeout(this.trunk[e]);
                        delete this.trunk[e]
                    },
                    r;
                if (!!e && e in this.trunk) {
                    t.call(v, e)
                } else {
                    if (typeof e === "undefined") {
                        for (r in this.trunk) {
                            if (this.trunk.hasOwnProperty(r)) {
                                t.call(v, r)
                            }
                        }
                    }
                }
            }
        },
        m = [],
        g = function() {
            return {
                array: function(e) {
                    return Array.prototype.slice.call(e)
                },
                create: function(e, t) {
                    t = t || "div";
                    var n = r.createElement(t);
                    n.className = e;
                    return n
                },
                forceStyles: function(t, n) {
                    t = e(t);
                    if (t.attr("style")) {
                        t.data("styles", t.attr("style")).removeAttr("style")
                    }
                    t.css(n)
                },
                revertStyles: function() {
                    e.each(g.array(arguments), function(t, n) {
                        n = e(n).removeAttr("style");
                        if (n.data("styles")) {
                            n.attr("style", n.data("styles")).data("styles", null)
                        }
                    })
                },
                moveOut: function(e) {
                    g.forceStyles(e, {
                        position: "absolute",
                        left: -1e4
                    })
                },
                moveIn: function() {
                    g.revertStyles.apply(g, g.array(arguments))
                },
                hide: function(t, n, r) {
                    t = e(t);
                    if (!t.data("opacity")) {
                        t.data("opacity", t.css("opacity"))
                    }
                    var i = {
                        opacity: 0
                    };
                    if (n) {
                        t.stop().animate(i, n, r)
                    } else {
                        t.css(i)
                    }
                },
                show: function(t, n, r) {
                    t = e(t);
                    var i = parseFloat(t.data("opacity")) || 1,
                        s = {
                            opacity: i
                        };
                    if (n) {
                        t.stop().animate(s, n, r)
                    } else {
                        t.css(s)
                    }
                },
                addTimer: function() {
                    v.add.apply(v, g.array(arguments));
                    return this
                },
                clearTimer: function() {
                    v.clear.apply(v, g.array(arguments));
                    return this
                },
                wait: function(t) {
                    t = e.extend({
                        until: function() {
                            return false
                        },
                        success: function() {},
                        error: function() {
                            b.raise("Could not complete wait function.")
                        },
                        timeout: 3e3
                    }, t);
                    var r = g.timestamp(),
                        i, s, o = function() {
                            s = g.timestamp();
                            i = s - r;
                            if (t.until(i)) {
                                t.success();
                                return false
                            }
                            if (s >= r + t.timeout) {
                                t.error();
                                return false
                            }
                            n.setTimeout(o, 2)
                        };
                    n.setTimeout(o, 2)
                },
                toggleQuality: function(e, t) {
                    if (f !== 7 && f !== 8 || !e) {
                        return
                    }
                    if (typeof t === "undefined") {
                        t = e.style.msInterpolationMode === "nearest-neighbor"
                    }
                    e.style.msInterpolationMode = t ? "bicubic" : "nearest-neighbor"
                },
                insertStyleTag: function(e) {
                    var t = r.createElement("style");
                    l().head.appendChild(t);
                    if (t.styleSheet) {
                        t.styleSheet.cssText = e
                    } else {
                        var n = r.createTextNode(e);
                        t.appendChild(n)
                    }
                },
                loadScript: function(t, n) {
                    var r = false,
                        i = e("<script>").attr({
                            src: t,
                            async: true
                        }).get(0);
                    i.onload = i.onreadystatechange = function() {
                        if (!r && (!this.readyState || this.readyState === "loaded" || this.readyState === "complete")) {
                            r = true;
                            i.onload = i.onreadystatechange = null;
                            if (typeof n === "function") {
                                n.call(this, this)
                            }
                        }
                    };
                    l().head.appendChild(i)
                },
                parseValue: function(e) {
                    if (typeof e === "number") {
                        return e
                    } else {
                        if (typeof e === "string") {
                            var t = e.match(/\-?\d/g);
                            return t && t.constructor === Array ? parseInt(t.join(""), 10) : 0
                        } else {
                            return 0
                        }
                    }
                },
                timestamp: function() {
                    return (new Date).getTime()
                },
                loadCSS: function(i, o, u) {
                    var a, c = false,
                        h;
                    e('link[rel="stylesheet"]').each(function() {
                        if ((new RegExp(i)).test(this.href)) {
                            a = this;
                            return false
                        }
                    });
                    if (typeof o === "function") {
                        u = o;
                        o = t
                    }
                    u = u || function() {};
                    if (a) {
                        u.call(a, a);
                        return a
                    }
                    h = r.styleSheets.length;
                    if (s) {
                        i += "?" + g.timestamp()
                    }
                    if (e("#" + o).length) {
                        e("#" + o).attr("href", i);
                        h--;
                        c = true
                    } else {
                        a = e("<link>").attr({
                            rel: "stylesheet",
                            href: i,
                            id: o
                        }).get(0);
                        n.setTimeout(function() {
                            var t = e('link[rel="stylesheet"], style');
                            if (t.length) {
                                t.get(0).parentNode.insertBefore(a, t[0])
                            } else {
                                l().head.appendChild(a)
                            }
                            if (f) {
                                a.attachEvent("onreadystatechange", function(e) {
                                    if (a.readyState === "complete") {
                                        c = true
                                    }
                                })
                            } else {
                                c = true
                            }
                        }, 10)
                    }
                    if (typeof u === "function") {
                        g.wait({
                            until: function() {
                                return c && r.styleSheets.length > h
                            },
                            success: function() {
                                g.addTimer("css", function() {
                                    u.call(a, a)
                                }, 100)
                            },
                            error: function() {
                                b.raise("Theme CSS could not load")
                            },
                            timeout: 1e4
                        })
                    }
                    return a
                }
            }
        }(),
        y = {
            fade: function(t, n) {
                e(t.next).css("opacity", 0).show().animate({
                    opacity: 1
                }, t.speed, n);
                if (t.prev) {
                    e(t.prev).css("opacity", 1).show().animate({
                        opacity: 0
                    }, t.speed)
                }
            },
            flash: function(t, n) {
                e(t.next).css("opacity", 0);
                if (t.prev) {
                    e(t.prev).animate({
                        opacity: 0
                    }, t.speed / 2, function() {
                        e(t.next).animate({
                            opacity: 1
                        }, t.speed, n)
                    })
                } else {
                    e(t.next).animate({
                        opacity: 1
                    }, t.speed, n)
                }
            },
            pulse: function(t, n) {
                if (t.prev) {
                    e(t.prev).hide()
                }
                e(t.next).css("opacity", 0).animate({
                    opacity: 1
                }, t.speed, n)
            },
            slide: function(t, n) {
                var r = e(t.next).parent(),
                    i = this.$("images"),
                    s = this._stageWidth,
                    o = this.getOptions("easing");
                r.css({
                    left: s * (t.rewind ? -1 : 1)
                });
                i.animate({
                    left: s * (t.rewind ? 1 : -1)
                }, {
                    duration: t.speed,
                    queue: false,
                    easing: o,
                    complete: function() {
                        i.css("left", 0);
                        r.css("left", 0);
                        n()
                    }
                })
            },
            fadeslide: function(t, n) {
                var r = 0,
                    i = this.getOptions("easing"),
                    s = this.getStageWidth();
                if (t.prev) {
                    r = g.parseValue(e(t.prev).css("left"));
                    e(t.prev).css({
                        opacity: 1,
                        left: r
                    }).animate({
                        opacity: 0,
                        left: r + s * (t.rewind ? 1 : -1)
                    }, {
                        duration: t.speed,
                        queue: false,
                        easing: i
                    })
                }
                r = g.parseValue(e(t.next).css("left"));
                e(t.next).css({
                    left: r + s * (t.rewind ? -1 : 1),
                    opacity: 0
                }).animate({
                    opacity: 1,
                    left: r
                }, {
                    duration: t.speed,
                    complete: n,
                    queue: false,
                    easing: i
                })
            }
        };
    var b = function() {
        var r = this;
        this._theme = t;
        this._options = {};
        this._playing = false;
        this._playtime = 5e3;
        this._active = null;
        this._queue = {
            length: 0
        };
        this._data = [];
        this._dom = {};
        this._thumbnails = [];
        this._initialized = false;
        this._stageWidth = 0;
        this._stageHeight = 0;
        this._target = t;
        this._id = Math.random();
        var s = "container stage images image-nav image-nav-left image-nav-right info info-text info-title info-description thumbnails thumbnails-list thumbnails-container thumb-nav-left thumb-nav-right loader counter tooltip",
            o = "current total";
        e.each(s.split(" "), function(e, t) {
            r._dom[t] = g.create("galleria-" + t)
        });
        e.each(o.split(" "), function(e, t) {
            r._dom[t] = g.create("galleria-" + t, "span")
        });
        var u = this._keyboard = {
            keys: {
                UP: 38,
                DOWN: 40,
                LEFT: 37,
                RIGHT: 39,
                RETURN: 13,
                ESCAPE: 27,
                BACKSPACE: 8,
                SPACE: 32
            },
            map: {},
            bound: false,
            press: function(e) {
                var t = e.keyCode || e.which;
                if (t in u.map && typeof u.map[t] === "function") {
                    u.map[t].call(r, e)
                }
            },
            attach: function(e) {
                var t, n;
                for (t in e) {
                    if (e.hasOwnProperty(t)) {
                        n = t.toUpperCase();
                        if (n in u.keys) {
                            u.map[u.keys[n]] = e[t]
                        } else {
                            u.map[n] = e[t]
                        }
                    }
                }
                if (!u.bound) {
                    u.bound = true;
                    i.bind("keydown", u.press)
                }
            },
            detach: function() {
                u.bound = false;
                u.map = {};
                i.unbind("keydown", u.press)
            }
        };
        var c = this._controls = {
            0: t,
            1: t,
            active: 0,
            swap: function() {
                c.active = c.active ? 0 : 1
            },
            getActive: function() {
                return c[c.active]
            },
            getNext: function() {
                return c[1 - c.active]
            }
        };
        var h = this._carousel = {
            next: r.$("thumb-nav-right"),
            prev: r.$("thumb-nav-left"),
            width: 0,
            current: 0,
            max: 0,
            hooks: [],
            update: function() {
                var t = 0,
                    n = 0,
                    i = [0];
                e.each(r._thumbnails, function(r, s) {
                    if (s.ready) {
                        t += s.outerWidth || e(s.container).outerWidth(true);
                        i[r + 1] = t;
                        n = Math.max(n, s.outerHeight || e(s.container).outerHeight(true))
                    }
                });
                r.$("thumbnails").css({
                    width: t + 10,
                    height: n
                });
                h.max = t;
                h.hooks = i;
                h.width = r.$("thumbnails-list").width();
                h.setClasses();
                r.$("thumbnails-container").toggleClass("galleria-carousel", t > h.width);
                h.width = r.$("thumbnails-list").width()
            },
            bindControls: function() {
                var e;
                h.next.bind(a(), function(t) {
                    t.preventDefault();
                    if (r._options.carouselSteps === "auto") {
                        for (e = h.current; e < h.hooks.length; e++) {
                            if (h.hooks[e] - h.hooks[h.current] > h.width) {
                                h.set(e - 2);
                                break
                            }
                        }
                    } else {
                        h.set(h.current + r._options.carouselSteps)
                    }
                });
                h.prev.bind(a(), function(t) {
                    t.preventDefault();
                    if (r._options.carouselSteps === "auto") {
                        for (e = h.current; e >= 0; e--) {
                            if (h.hooks[h.current] - h.hooks[e] > h.width) {
                                h.set(e + 2);
                                break
                            } else {
                                if (e === 0) {
                                    h.set(0);
                                    break
                                }
                            }
                        }
                    } else {
                        h.set(h.current - r._options.carouselSteps)
                    }
                })
            },
            set: function(e) {
                e = Math.max(e, 0);
                while (h.hooks[e - 1] + h.width >= h.max && e >= 0) {
                    e--
                }
                h.current = e;
                h.animate()
            },
            getLast: function(e) {
                return (e || h.current) - 1
            },
            follow: function(e) {
                if (e === 0 || e === h.hooks.length - 2) {
                    h.set(e);
                    return
                }
                var t = h.current;
                while (h.hooks[t] - h.hooks[h.current] < h.width && t <= h.hooks.length) {
                    t++
                }
                if (e - 1 < h.current) {
                    h.set(e - 1)
                } else {
                    if (e + 2 > t) {
                        h.set(e - t + h.current + 2)
                    }
                }
            },
            setClasses: function() {
                h.prev.toggleClass("disabled", !h.current);
                h.next.toggleClass("disabled", h.hooks[h.current] + h.width >= h.max)
            },
            animate: function(e) {
                h.setClasses();
                var t = h.hooks[h.current] * -1;
                if (isNaN(t)) {
                    return
                }
                r.$("thumbnails").animate({
                    left: t
                }, {
                    duration: r._options.carouselSpeed,
                    easing: r._options.easing,
                    queue: false
                })
            }
        };
        var p = this._tooltip = {
            initialized: false,
            open: false,
            init: function() {
                p.initialized = true;
                var e = ".galleria-tooltip{padding:3px 8px;max-width:50%;background:#ffe;color:#000;z-index:3;position:absolute;font-size:11px;line-height:1.3opacity:0;box-shadow:0 0 2px rgba(0,0,0,.4);-moz-box-shadow:0 0 2px rgba(0,0,0,.4);-webkit-box-shadow:0 0 2px rgba(0,0,0,.4);}";
                g.insertStyleTag(e);
                r.$("tooltip").css("opacity", .8);
                g.hide(r.get("tooltip"))
            },
            move: function(e) {
                var t = r.getMousePosition(e).x,
                    n = r.getMousePosition(e).y,
                    i = r.$("tooltip"),
                    s = t,
                    o = n,
                    u = i.outerHeight(true) + 1,
                    a = i.outerWidth(true),
                    f = u + 15;
                var l = r.$("container").width() - a - 2,
                    c = r.$("container").height() - u - 2;
                if (!isNaN(s) && !isNaN(o)) {
                    s += 10;
                    o -= 30;
                    s = Math.max(0, Math.min(l, s));
                    o = Math.max(0, Math.min(c, o));
                    if (n < f) {
                        o = f
                    }
                    i.css({
                        left: s,
                        top: o
                    })
                }
            },
            bind: function(t, n) {
                if (!p.initialized) {
                    p.init()
                }
                var i = function(t, n) {
                    p.define(t, n);
                    e(t).hover(function() {
                        g.clearTimer("switch_tooltip");
                        r.$("container").unbind("mousemove", p.move).bind("mousemove", p.move).trigger("mousemove");
                        p.show(t);
                        b.utils.addTimer("tooltip", function() {
                            r.$("tooltip").stop().show();
                            g.show(r.get("tooltip"), 400);
                            p.open = true
                        }, p.open ? 0 : 500)
                    }, function() {
                        r.$("container").unbind("mousemove", p.move);
                        g.clearTimer("tooltip");
                        r.$("tooltip").stop();
                        g.hide(r.get("tooltip"), 200, function() {
                            r.$("tooltip").hide();
                            g.addTimer("switch_tooltip", function() {
                                p.open = false
                            }, 1e3)
                        })
                    })
                };
                if (typeof n === "string") {
                    i(t in r._dom ? r.get(t) : t, n)
                } else {
                    e.each(t, function(e, t) {
                        i(r.get(e), t)
                    })
                }
            },
            show: function(t) {
                t = e(t in r._dom ? r.get(t) : t);
                var i = t.data("tt"),
                    s = function(e) {
                        n.setTimeout(function(e) {
                            return function() {
                                p.move(e)
                            }
                        }(e), 10);
                        t.unbind("mouseup", s)
                    };
                i = typeof i === "function" ? i() : i;
                if (!i) {
                    return
                }
                r.$("tooltip").html(i.replace(/\s/, "&nbsp;"));
                t.bind("mouseup", s)
            },
            define: function(t, n) {
                if (typeof n !== "function") {
                    var i = n;
                    n = function() {
                        return i
                    }
                }
                t = e(t in r._dom ? r.get(t) : t).data("tt", n);
                p.show(t)
            }
        };
        var d = this._fullscreen = {
            scrolled: 0,
            active: false,
            keymap: r._keyboard.map,
            enter: function(t) {
                d.active = true;
                g.hide(r.getActiveImage());
                r.$("container").addClass("fullscreen");
                d.scrolled = e(n).scrollTop();
                g.forceStyles(r.get("container"), {
                    position: "fixed",
                    top: 0,
                    left: 0,
                    width: "100%",
                    height: "100%",
                    zIndex: 1e4
                });
                var i = {
                        height: "100%",
                        overflow: "hidden",
                        margin: 0,
                        padding: 0
                    },
                    s = r.getData();
                g.forceStyles(l().html, i);
                g.forceStyles(l().body, i);
                d.keymap = e.extend({}, r._keyboard.map);
                r.attachKeyboard({
                    escape: r.exitFullscreen,
                    right: r.next,
                    left: r.prev
                });
                if (s && s.big && s.image !== s.big) {
                    var o = new b.Picture,
                        u = o.isCached(s.big),
                        a = r.getIndex(),
                        f = r._thumbnails[a];
                    r.trigger({
                        type: b.LOADSTART,
                        cached: u,
                        index: a,
                        imageTarget: r.getActiveImage(),
                        thumbTarget: f
                    });
                    o.load(s.big, function(t) {
                        r._scaleImage(t, {
                            complete: function(t) {
                                r.trigger({
                                    type: b.LOADFINISH,
                                    cached: u,
                                    index: a,
                                    imageTarget: t.image,
                                    thumbTarget: f
                                });
                                var n = r._controls.getActive().image;
                                if (n) {
                                    e(n).width(t.image.width).height(t.image.height).attr("style", e(t.image).attr("style")).attr("src", t.image.src)
                                }
                            }
                        })
                    })
                }
                r.rescale(function() {
                    g.addTimer("fullscreen_enter", function() {
                        g.show(r.getActiveImage());
                        if (typeof t === "function") {
                            t.call(r)
                        }
                    }, 100);
                    r.trigger(b.FULLSCREEN_ENTER)
                });
                e(n).resize(function() {
                    d.scale()
                })
            },
            scale: function() {
                r.rescale()
            },
            exit: function(t) {
                d.active = false;
                g.hide(r.getActiveImage());
                r.$("container").removeClass("fullscreen");
                g.revertStyles(r.get("container"), l().html, l().body);
                n.scrollTo(0, d.scrolled);
                r.detachKeyboard();
                r.attachKeyboard(d.keymap);
                r.rescale(function() {
                    g.addTimer("fullscreen_exit", function() {
                        g.show(r.getActiveImage());
                        if (typeof t === "function") {
                            t.call(r)
                        }
                    }, 50);
                    r.trigger(b.FULLSCREEN_EXIT)
                });
                e(n).unbind("resize", d.scale)
            }
        };
        var v = this._idle = {
            trunk: [],
            bound: false,
            add: function(t, n) {
                if (!t) {
                    return
                }
                if (!v.bound) {
                    v.addEvent()
                }
                t = e(t);
                var r = {},
                    i;
                for (i in n) {
                    if (n.hasOwnProperty(i)) {
                        r[i] = t.css(i)
                    }
                }
                t.data("idle", {
                    from: r,
                    to: n,
                    complete: true,
                    busy: false
                });
                v.addTimer();
                v.trunk.push(t)
            },
            remove: function(t) {
                t = jQuery(t);
                e.each(v.trunk, function(e, n) {
                    if (n.length && !n.not(t).length) {
                        r._idle.show(t);
                        r._idle.trunk.splice(e, 1)
                    }
                });
                if (!v.trunk.length) {
                    v.removeEvent();
                    g.clearTimer("idle")
                }
            },
            addEvent: function() {
                v.bound = true;
                r.$("container").bind("mousemove click", v.showAll)
            },
            removeEvent: function() {
                v.bound = false;
                r.$("container").unbind("mousemove click", v.showAll)
            },
            addTimer: function() {
                g.addTimer("idle", function() {
                    r._idle.hide()
                }, r._options.idleTime)
            },
            hide: function() {
                r.trigger(b.IDLE_ENTER);
                e.each(v.trunk, function(e, t) {
                    var n = t.data("idle");
                    if (!n) {
                        return
                    }
                    t.data("idle").complete = false;
                    t.stop().animate(n.to, {
                        duration: r._options.idleSpeed,
                        queue: false,
                        easing: "swing"
                    })
                })
            },
            showAll: function() {
                g.clearTimer("idle");
                e.each(r._idle.trunk, function(e, t) {
                    r._idle.show(t)
                })
            },
            show: function(t) {
                var n = t.data("idle");
                if (!n.busy && !n.complete) {
                    n.busy = true;
                    r.trigger(b.IDLE_EXIT);
                    g.clearTimer("idle");
                    t.stop().animate(n.from, {
                        duration: r._options.idleSpeed / 2,
                        queue: false,
                        easing: "swing",
                        complete: function() {
                            e(this).data("idle").busy = false;
                            e(this).data("idle").complete = true
                        }
                    })
                }
                v.addTimer()
            }
        };
        var m = this._lightbox = {
            width: 0,
            height: 0,
            initialized: false,
            active: null,
            image: null,
            elems: {},
            init: function() {
                r.trigger(b.LIGHTBOX_OPEN);
                if (m.initialized) {
                    return
                }
                m.initialized = true;
                var t = "overlay box content shadow title info close prevholder prev nextholder next counter image",
                    n = {},
                    i = r._options,
                    s = "",
                    o = "position:absolute;",
                    u = "lightbox-",
                    c = {
                        overlay: "position:fixed;display:none;opacity:" + i.overlayOpacity + ";filter:alpha(opacity=" + i.overlayOpacity * 100 + ");top:0;left:0;width:100%;height:100%;background:" + i.overlayBackground + ";z-index:99990",
                        box: "position:fixed;display:none;width:400px;height:400px;top:50%;left:50%;margin-top:-200px;margin-left:-200px;z-index:99991",
                        shadow: o + "background:#000;width:100%;height:100%;",
                        content: o + "background-color:#fff;top:10px;left:10px;right:10px;bottom:10px;overflow:hidden",
                        info: o + "bottom:10px;left:10px;right:10px;color:#444;font:11px/13px arial,sans-serif;height:13px",
                        close: o + "top:10px;right:10px;height:20px;width:20px;background:#fff;text-align:center;cursor:pointer;color:#444;font:16px/22px arial,sans-serif;z-index:99999",
                        image: o + "top:10px;left:10px;right:10px;bottom:30px;overflow:hidden;display:block;",
                        prevholder: o + "width:50%;top:0;bottom:40px;cursor:pointer;",
                        nextholder: o + "width:50%;top:0;bottom:40px;right:-1px;cursor:pointer;",
                        prev: o + "top:50%;margin-top:-20px;height:40px;width:30px;background:#fff;left:20px;display:none;text-align:center;color:#000;font:bold 16px/36px arial,sans-serif",
                        next: o + "top:50%;margin-top:-20px;height:40px;width:30px;background:#fff;right:20px;left:auto;display:none;font:bold 16px/36px arial,sans-serif;text-align:center;color:#000",
                        title: "float:left",
                        counter: "float:right;margin-left:8px;"
                    },
                    h = function(t) {
                        return t.hover(function() {
                            e(this).css("color", "#bbb")
                        }, function() {
                            e(this).css("color", "#444")
                        })
                    },
                    p = {};
                if (f === 8) {
                    c.nextholder += "background:#000;filter:alpha(opacity=0);";
                    c.prevholder += "background:#000;filter:alpha(opacity=0);"
                }
                e.each(c, function(e, t) {
                    s += ".galleria-" + u + e + "{" + t + "}"
                });
                g.insertStyleTag(s);
                e.each(t.split(" "), function(e, t) {
                    r.addElement("lightbox-" + t);
                    n[t] = m.elems[t] = r.get("lightbox-" + t)
                });
                m.image = new b.Picture;
                e.each({
                    box: "shadow content close prevholder nextholder",
                    info: "title counter",
                    content: "info image",
                    prevholder: "prev",
                    nextholder: "next"
                }, function(t, n) {
                    var r = [];
                    e.each(n.split(" "), function(e, t) {
                        r.push(u + t)
                    });
                    p[u + t] = r
                });
                r.append(p);
                e(n.image).append(m.image.container);
                e(l().body).append(n.overlay, n.box);
                h(e(n.close).bind(a(), m.hide).html("&#215;"));
                e.each(["Prev", "Next"], function(t, r) {
                    var i = e(n[r.toLowerCase()]).html(/v/.test(r) ? "&#8249;&nbsp;" : "&nbsp;&#8250;"),
                        s = e(n[r.toLowerCase() + "holder"]);
                    s.bind(a(), function() {
                        m["show" + r]()
                    });
                    if (f < 8) {
                        i.show();
                        return
                    }
                    s.hover(function() {
                        i.show()
                    }, function(e) {
                        i.stop().fadeOut(200)
                    })
                });
                e(n.overlay).bind(a(), m.hide)
            },
            rescale: function(t) {
                var i = Math.min(e(n).width() - 40, m.width),
                    s = Math.min(e(n).height() - 60, m.height),
                    o = Math.min(i / m.width, s / m.height),
                    u = m.width * o + 40,
                    a = m.height * o + 60,
                    f = {
                        width: u,
                        height: a,
                        marginTop: Math.ceil(a / 2) * -1,
                        marginLeft: Math.ceil(u / 2) * -1
                    };
                if (t) {
                    e(m.elems.box).css(f)
                } else {
                    e(m.elems.box).animate(f, r._options.lightboxTransitionSpeed, r._options.easing, function() {
                        var t = m.image,
                            n = r._options.lightboxFadeSpeed;
                        r.trigger({
                            type: b.LIGHTBOX_IMAGE,
                            imageTarget: t.image
                        });
                        e(t.container).show();
                        g.show(t.image, n);
                        g.show(m.elems.info, n)
                    })
                }
            },
            hide: function() {
                m.image.image = null;
                e(n).unbind("resize", m.rescale);
                e(m.elems.box).hide();
                g.hide(m.elems.info);
                g.hide(m.elems.overlay, 200, function() {
                    e(this).hide().css("opacity", r._options.overlayOpacity);
                    r.trigger(b.LIGHTBOX_CLOSE)
                })
            },
            showNext: function() {
                m.show(r.getNext(m.active))
            },
            showPrev: function() {
                m.show(r.getPrev(m.active))
            },
            show: function(t) {
                m.active = t = typeof t === "number" ? t : r.getIndex();
                if (!m.initialized) {
                    m.init()
                }
                e(n).unbind("resize", m.rescale);
                var i = r.getData(t),
                    s = r.getDataLength();
                g.hide(m.elems.info);
                m.image.load(i.image, function(r) {
                    m.width = r.original.width;
                    m.height = r.original.height;
                    e(r.image).css({
                        width: "100.5%",
                        height: "100.5%",
                        top: 0,
                        zIndex: 99998
                    });
                    g.hide(r.image);
                    m.elems.title.innerHTML = i.title;
                    m.elems.counter.innerHTML = t + 1 + " / " + s;
                    e(n).resize(m.rescale);
                    m.rescale()
                });
                e(m.elems.overlay).show();
                e(m.elems.box).show()
            }
        };
        return this
    };
    b.prototype = {
        constructor: b,
        init: function(n, r) {
            var i = this;
            r = p(r);
            m.push(this);
            this._original = {
                target: n,
                options: r,
                data: null
            };
            this._target = this._dom.target = n.nodeName ? n : e(n).get(0);
            if (!this._target) {
                b.raise("Target not found.");
                return
            }
            this._options = {
                autoplay: false,
                carousel: true,
                carouselFollow: true,
                carouselSpeed: 400,
                carouselSteps: "auto",
                clicknext: false,
                dataConfig: function(e) {
                    return {}
                },
                dataSelector: "img",
                dataSource: this._target,
                debug: t,
                easing: "galleria",
                extend: function(e) {},
                height: "auto",
                idleTime: 3e3,
                idleSpeed: 200,
                imageCrop: false,
                imageMargin: 0,
                imagePan: false,
                imagePanSmoothness: 12,
                imagePosition: "50%",
                keepSource: false,
                lightbox: false,
                lightboxFadeSpeed: 200,
                lightboxTransitionSpeed: 400,
                linkSourceTmages: true,
                maxScaleRatio: t,
                minScaleRatio: t,
                overlayOpacity: .85,
                overlayBackground: "#0b0b0b",
                pauseOnInteraction: true,
                popupLinks: false,
                preload: 2,
                queue: true,
                show: 0,
                showInfo: true,
                showCounter: true,
                showImagenav: true,
                thumbCrop: true,
                thumbEventType: a(),
                thumbFit: true,
                thumbMargin: 0,
                thumbQuality: "auto",
                thumbnails: true,
                transition: "fade",
                transitionInitial: t,
                transitionSpeed: 400,
                width: "auto"
            };
            if (r && r.debug === true) {
                s = true
            }
            e(this._target).children().hide();
            if (typeof b.theme === "object") {
                this._init()
            } else {
                g.wait({
                    until: function() {
                        return typeof b.theme === "object"
                    },
                    success: function() {
                        i._init.call(i)
                    },
                    error: function() {
                        b.raise("No theme found.", true)
                    },
                    timeout: 5e3
                })
            }
        },
        _init: function() {
            var r = this;
            if (this._initialized) {
                b.raise("Init failed: Gallery instance already initialized.");
                return this
            }
            this._initialized = true;
            if (!b.theme) {
                b.raise("Init failed: No theme found.");
                return this
            }
            e.extend(true, this._options, b.theme.defaults, this._original.options);
            this.bind(b.DATA, function() {
                this._original.data = this._data;
                this.get("total").innerHTML = this.getDataLength();
                var t = this.$("container");
                var i = {
                    width: 0,
                    height: 0
                };
                var s = g.create("galleria-image");
                g.wait({
                    until: function() {
                        e.each(["width", "height"], function(e, n) {
                            if (r._options[n] && typeof r._options[n] === "number") {
                                i[n] = r._options[n]
                            } else {
                                i[n] = Math.max(g.parseValue(t.css(n)), g.parseValue(r.$("target").css(n)), t[n](), r.$("target")[n]())
                            }
                        });
                        var n = function() {
                            return true
                        };
                        if (r._options.thumbnails) {
                            r.$("thumbnails").append(s);
                            n = function() {
                                return !!e(s).height()
                            }
                        }
                        return n() && i.width && i.height > 10
                    },
                    success: function() {
                        e(s).remove();
                        t.width(i.width);
                        t.height(i.height);
                        if (b.WEBKIT) {
                            n.setTimeout(function() {
                                r._run()
                            }, 1)
                        } else {
                            r._run()
                        }
                    },
                    error: function() {
                        b.raise("Width & Height not found.", true)
                    },
                    timeout: 2e3
                })
            });
            var i = false;
            this.bind(b.READY, function(i) {
                return function() {
                    g.show(this.get("counter"));
                    if (this._options.carousel) {
                        this._carousel.bindControls()
                    }
                    if (this._options.autoplay) {
                        this.pause();
                        if (typeof this._options.autoplay === "number") {
                            this._playtime = this._options.autoplay
                        }
                        this.trigger(b.PLAY);
                        this._playing = true
                    }
                    if (i) {
                        if (typeof this._options.show === "number") {
                            this.show(this._options.show)
                        }
                        return
                    }
                    i = true;
                    if (this._options.clicknext) {
                        e.each(this._data, function(e, t) {
                            delete t.link
                        });
                        this.$("stage").css({
                            cursor: "pointer"
                        }).bind(a(), function(e) {
                            if (r._options.pauseOnInteraction) {
                                r.pause()
                            }
                            r.next()
                        })
                    }
                    if (b.History) {
                        b.History.change(function(e) {
                            var i = parseInt(e.value.replace(/\//, ""), 10);
                            if (isNaN(i)) {
                                n.history.go(-1)
                            } else {
                                r.show(i, t, true)
                            }
                        })
                    }
                    b.theme.init.call(this, this._options);
                    this._options.extend.call(this, this._options);
                    if (/^[0-9]{1,4}$/.test(u) && b.History) {
                        this.show(u, t, true)
                    } else {
                        if (this._data[this._options.show]) {
                            this.show(this._options.show)
                        }
                    }
                }
            }(i));
            this.append({
                "info-text": ["info-title", "info-description"],
                info: ["info-text"],
                "image-nav": ["image-nav-right", "image-nav-left"],
                stage: ["images", "loader", "counter", "image-nav"],
                "thumbnails-list": ["thumbnails"],
                "thumbnails-container": ["thumb-nav-left", "thumbnails-list", "thumb-nav-right"],
                container: ["stage", "thumbnails-container", "info", "tooltip"]
            });
            g.hide(this.$("counter").append(this.get("current"), " / ", this.get("total")));
            this.setCounter("&#8211;");
            g.hide(r.get("tooltip"));
            e.each(new Array(2), function(t) {
                var n = new b.Picture;
                e(n.container).css({
                    position: "absolute",
                    top: 0,
                    left: 0
                });
                r.$("images").append(n.container);
                r._controls[t] = n
            });
            this.$("images").css({
                position: "relative",
                top: 0,
                left: 0,
                width: "100%",
                height: "100%"
            });
            this.$("thumbnails, thumbnails-list").css({
                overflow: "hidden",
                position: "relative"
            });
            this.$("image-nav-right, image-nav-left").bind(a(), function(e) {
                if (r._options.clicknext) {
                    e.stopPropagation()
                }
                if (r._options.pauseOnInteraction) {
                    r.pause()
                }
                var t = /right/.test(this.className) ? "next" : "prev";
                r[t]()
            });
            e.each(["info", "counter", "image-nav"], function(e, t) {
                if (r._options["show" + t.substr(0, 1).toUpperCase() + t.substr(1).replace(/-/, "")] === false) {
                    g.moveOut(r.get(t.toLowerCase()))
                }
            });
            this.load();
            if (!this._options.keep_source && !f) {
                this._target.innerHTML = ""
            }
            this.$("target").append(this.get("container"));
            if (this._options.carousel) {
                this.bind(b.THUMBNAIL, function() {
                    this.updateCarousel()
                })
            }
            return this
        },
        _createThumbnails: function() {
            this.get("total").innerHTML = this.getDataLength();
            var t, i, s, o, u, a = this,
                f = this._options,
                l = function() {
                    var e = a.$("thumbnails").find(".active");
                    if (!e.length) {
                        return false
                    }
                    return e.find("img").attr("src")
                }(),
                c = typeof f.thumbnails === "string" ? f.thumbnails.toLowerCase() : null,
                h = function(e) {
                    return r.defaultView && r.defaultView.getComputedStyle ? r.defaultView.getComputedStyle(s.container, null)[e] : u.css(e)
                },
                p = function(t, n, r) {
                    return function() {
                        e(r).append(t);
                        a.trigger({
                            type: b.THUMBNAIL,
                            thumbTarget: t,
                            index: n
                        })
                    }
                },
                d = function(t) {
                    if (f.pauseOnInteraction) {
                        a.pause()
                    }
                    var n = e(t.currentTarget).data("index");
                    if (a.getIndex() !== n) {
                        a.show(n)
                    }
                    t.preventDefault()
                },
                v = function(t) {
                    t.scale({
                        width: t.data.width,
                        height: t.data.height,
                        crop: f.thumbCrop,
                        margin: f.thumbMargin,
                        complete: function(t) {
                            var n = ["left", "top"],
                                r = ["Width", "Height"],
                                i, s;
                            e.each(r, function(r, o) {
                                i = o.toLowerCase();
                                if ((f.thumbCrop !== true || f.thumbCrop === i) && f.thumbFit) {
                                    s = {};
                                    s[i] = t[i];
                                    e(t.container).css(s);
                                    s = {};
                                    s[n[r]] = 0;
                                    e(t.image).css(s)
                                }
                                t["outer" + o] = e(t.container)["outer" + o](true)
                            });
                            g.toggleQuality(t.image, f.thumbQuality === true || f.thumbQuality === "auto" && t.original.width < t.width * 3);
                            a.trigger({
                                type: b.THUMBNAIL,
                                thumbTarget: t.image,
                                index: t.data.order
                            })
                        }
                    })
                };
            this._thumbnails = [];
            this.$("thumbnails").empty();
            for (t = 0; this._data[t]; t++) {
                o = this._data[t];
                if (f.thumbnails === true) {
                    s = new b.Picture(t);
                    i = o.thumb || o.image;
                    this.$("thumbnails").append(s.container);
                    u = e(s.container);
                    s.data = {
                        width: g.parseValue(h("width")),
                        height: g.parseValue(h("height")),
                        order: t
                    };
                    if (f.thumbFit && f.thumbCrop !== true) {
                        u.css({
                            width: 0,
                            height: 0
                        })
                    } else {
                        u.css({
                            width: s.data.width,
                            height: s.data.height
                        })
                    }
                    s.load(i, v);
                    if (f.preload === "all") {
                        s.add(o.image)
                    }
                } else {
                    if (c === "empty" || c === "numbers") {
                        s = {
                            container: g.create("galleria-image"),
                            image: g.create("img", "span"),
                            ready: true
                        };
                        if (c === "numbers") {
                            e(s.image).text(t + 1)
                        }
                        this.$("thumbnails").append(s.container);
                        n.setTimeout(p(s.image, t, s.container), 50 + t * 20)
                    } else {
                        s = {
                            container: null,
                            image: null
                        }
                    }
                }
                e(s.container).add(f.keepSource && f.linkSourceImages ? o.original : null).data("index", t).bind(f.thumbEventType, d);
                if (l === i) {
                    e(s.container).addClass("active")
                }
                this._thumbnails.push(s)
            }
        },
        _run: function() {
            var e = this;
            e._createThumbnails();
            g.wait({
                until: function() {
                    if (b.OPERA) {
                        e.$("stage").css("display", "inline-block")
                    }
                    e._stageWidth = e.$("stage").width();
                    e._stageHeight = e.$("stage").height();
                    return e._stageWidth && e._stageHeight > 50
                },
                success: function() {
                    e.trigger(b.READY)
                },
                error: function() {
                    b.raise("Stage measures not found", true)
                }
            })
        },
        load: function(t, n, r) {
            var i = this;
            this._data = [];
            this._thumbnails = [];
            this.$("thumbnails").empty();
            if (typeof n === "function") {
                r = n;
                n = null
            }
            t = t || this._options.dataSource;
            n = n || this._options.dataSelector;
            r = r || this._options.dataConfig;
            if (t.constructor === Array) {
                if (this.validate(t)) {
                    this._data = t;
                    this._parseData().trigger(b.DATA)
                } else {
                    b.raise("Load failed: JSON Array not valid.")
                }
                return this
            }
            e(t).find(n).each(function(t, n) {
                n = e(n);
                var s = {},
                    o = n.parent(),
                    u = o.attr("href");
                s.image = s.big = u;
                i._data.push(e.extend({
                    title: n.attr("title"),
                    thumb: n.attr("src"),
                    image: n.attr("src"),
                    big: n.attr("src"),
                    description: n.attr("alt"),
                    link: n.attr("longdesc"),
                    original: n.get(0)
                }, s, r(n)))
            });
            if (this.getDataLength()) {
                this.trigger(b.DATA)
            } else {
                b.raise("Load failed: no data found.")
            }
            return this
        },
        _parseData: function() {
            var t = this;
            e.each(this._data, function(e, n) {
                if ("thumb" in n === false) {
                    t._data[e].thumb = n.image
                }
                if (!"big" in n) {
                    t._data[e].big = n.image
                }
            });
            return this
        },
        splice: function() {
            Array.prototype.splice.apply(this._data, g.array(arguments));
            return this._parseData()._createThumbnails()
        },
        push: function() {
            Array.prototype.push.apply(this._data, g.array(arguments));
            return this._parseData()._createThumbnails()
        },
        _getActive: function() {
            return this._controls.getActive()
        },
        validate: function(e) {
            return true
        },
        bind: function(e, t) {
            e = d(e);
            this.$("container").bind(e, this.proxy(t));
            return this
        },
        unbind: function(e) {
            e = d(e);
            this.$("container").unbind(e);
            return this
        },
        trigger: function(t) {
            t = typeof t === "object" ? e.extend(t, {
                scope: this
            }) : {
                type: d(t),
                scope: this
            };
            this.$("container").trigger(t);
            return this
        },
        addIdleState: function(e, t) {
            this._idle.add.apply(this._idle, g.array(arguments));
            return this
        },
        removeIdleState: function(e) {
            this._idle.remove.apply(this._idle, g.array(arguments));
            return this
        },
        enterIdleMode: function() {
            this._idle.hide();
            return this
        },
        exitIdleMode: function() {
            this._idle.showAll();
            return this
        },
        enterFullscreen: function(e) {
            this._fullscreen.enter.apply(this, g.array(arguments));
            return this
        },
        exitFullscreen: function(e) {
            this._fullscreen.exit.apply(this, g.array(arguments));
            return this
        },
        toggleFullscreen: function(e) {
            this._fullscreen[this.isFullscreen() ? "exit" : "enter"].apply(this, g.array(arguments));
            return this
        },
        bindTooltip: function(e, t) {
            this._tooltip.bind.apply(this._tooltip, g.array(arguments));
            return this
        },
        defineTooltip: function(e, t) {
            this._tooltip.define.apply(this._tooltip, g.array(arguments));
            return this
        },
        refreshTooltip: function(e) {
            this._tooltip.show.apply(this._tooltip, g.array(arguments));
            return this
        },
        openLightbox: function() {
            this._lightbox.show.apply(this._lightbox, g.array(arguments));
            return this
        },
        closeLightbox: function() {
            this._lightbox.hide.apply(this._lightbox, g.array(arguments));
            return this
        },
        getActiveImage: function() {
            return this._getActive().image || t
        },
        getActiveThumb: function() {
            return this._thumbnails[this._active].image || t
        },
        getMousePosition: function(e) {
            return {
                x: e.pageX - this.$("container").offset().left,
                y: e.pageY - this.$("container").offset().top
            }
        },
        addPan: function(t) {
            if (this._options.imageCrop === false) {
                return
            }
            t = e(t || this.getActiveImage());
            var n = this,
                r = t.width() / 2,
                i = t.height() / 2,
                s = parseInt(t.css("left"), 10),
                o = parseInt(t.css("top"), 10),
                u = s || 0,
                a = o || 0,
                l = 0,
                c = 0,
                h = false,
                p = g.timestamp(),
                d = 0,
                v = 0,
                m = function(e, n, r) {
                    if (e > 0) {
                        v = Math.round(Math.max(e * -1, Math.min(0, n)));
                        if (d !== v) {
                            d = v;
                            if (f === 8) {
                                t.parent()["scroll" + r](v * -1)
                            } else {
                                var i = {};
                                i[r.toLowerCase()] = v;
                                t.css(i)
                            }
                        }
                    }
                },
                y = function(e) {
                    if (g.timestamp() - p < 50) {
                        return
                    }
                    h = true;
                    r = n.getMousePosition(e).x;
                    i = n.getMousePosition(e).y
                },
                b = function(e) {
                    if (!h) {
                        return
                    }
                    l = t.width() - n._stageWidth;
                    c = t.height() - n._stageHeight;
                    s = r / n._stageWidth * l * -1;
                    o = i / n._stageHeight * c * -1;
                    u += (s - u) / n._options.imagePanSmoothness;
                    a += (o - a) / n._options.imagePanSmoothness;
                    m(c, a, "Top");
                    m(l, u, "Left")
                };
            if (f === 8) {
                t.parent().scrollTop(a * -1).scrollLeft(u * -1);
                t.css({
                    top: 0,
                    left: 0
                })
            }
            this.$("stage").unbind("mousemove", y).bind("mousemove", y);
            g.addTimer("pan", b, 50, true);
            return this
        },
        proxy: function(e, t) {
            if (typeof e !== "function") {
                return function() {}
            }
            t = t || this;
            return function() {
                return e.apply(t, g.array(arguments))
            }
        },
        removePan: function() {
            this.$("stage").unbind("mousemove");
            g.clearTimer("pan");
            return this
        },
        addElement: function(t) {
            var n = this._dom;
            e.each(g.array(arguments), function(e, t) {
                n[t] = g.create("galleria-" + t)
            });
            return this
        },
        attachKeyboard: function(e) {
            this._keyboard.attach.apply(this._keyboard, g.array(arguments));
            return this
        },
        detachKeyboard: function() {
            this._keyboard.detach.apply(this._keyboard, g.array(arguments));
            return this
        },
        appendChild: function(e, t) {
            this.$(e).append(this.get(t) || t);
            return this
        },
        prependChild: function(e, t) {
            this.$(e).prepend(this.get(t) || t);
            return this
        },
        remove: function(e) {
            this.$(g.array(arguments).join(",")).remove();
            return this
        },
        append: function(e) {
            var t, n;
            for (t in e) {
                if (e.hasOwnProperty(t)) {
                    if (e[t].constructor === Array) {
                        for (n = 0; e[t][n]; n++) {
                            this.appendChild(t, e[t][n])
                        }
                    } else {
                        this.appendChild(t, e[t])
                    }
                }
            }
            return this
        },
        _scaleImage: function(t, n) {
            n = e.extend({
                width: this._stageWidth,
                height: this._stageHeight,
                crop: this._options.imageCrop,
                max: this._options.maxScaleRatio,
                min: this._options.minScaleRatio,
                margin: this._options.imageMargin,
                position: this._options.imagePosition
            }, n);
            (t || this._controls.getActive()).scale(n);
            return this
        },
        updateCarousel: function() {
            this._carousel.update();
            return this
        },
        rescale: function(e, n, r) {
            var i = this;
            if (typeof e === "function") {
                r = e;
                e = t
            }
            var s = function() {
                i._stageWidth = e || i.$("stage").width();
                i._stageHeight = n || i.$("stage").height();
                i._scaleImage();
                if (i._options.carousel) {
                    i.updateCarousel()
                }
                i.trigger(b.RESCALE);
                if (typeof r === "function") {
                    r.call(i)
                }
            };
            if (b.WEBKIT && !e && !n) {
                g.addTimer("scale", s, 5)
            } else {
                s.call(i)
            }
            return this
        },
        refreshImage: function() {
            this._scaleImage();
            if (this._options.imagePan) {
                this.addPan()
            }
            return this
        },
        show: function(e, t, n) {
            if (e === false || !this._options.queue && this._queue.stalled) {
                return
            }
            e = Math.max(0, Math.min(parseInt(e, 10), this.getDataLength() - 1));
            t = typeof t !== "undefined" ? !!t : e < this.getIndex();
            n = n || false;
            if (!n && b.History) {
                b.History.value(e.toString());
                return
            }
            this._active = e;
            Array.prototype.push.call(this._queue, {
                index: e,
                rewind: t
            });
            if (!this._queue.stalled) {
                this._show()
            }
            return this
        },
        _show: function() {
            var t = this,
                r = this._queue[0],
                i = this.getData(r.index);
            if (!i) {
                return
            }
            var s = this.isFullscreen() && "big" in i ? i.big : i.image,
                o = this._controls.getActive(),
                u = this._controls.getNext(),
                f = u.isCached(s),
                l = this._thumbnails[r.index];
            var c = function() {
                var s;
                t._queue.stalled = false;
                g.toggleQuality(u.image, t._options.imageQuality);
                e(o.container).css({
                    zIndex: 0,
                    opacity: 0
                });
                e(u.container).css({
                    zIndex: 1,
                    opacity: 1
                });
                t._controls.swap();
                if (t._options.imagePan) {
                    t.addPan(u.image)
                }
                if (i.link || t._options.lightbox) {
                    e(u.image).css({
                        cursor: "pointer"
                    }).bind(a(), function() {
                        if (i.link) {
                            if (t._options.popupLinks) {
                                s = n.open(i.link, "_blank")
                            } else {
                                n.location.href = i.link
                            }
                            return
                        }
                        t.openLightbox()
                    })
                }
                Array.prototype.shift.call(t._queue);
                if (t._queue.length) {
                    t._show()
                }
                t._playCheck();
                t.trigger({
                    type: b.IMAGE,
                    index: r.index,
                    imageTarget: u.image,
                    thumbTarget: l.image
                })
            };
            if (this._options.carousel && this._options.carouselFollow) {
                this._carousel.follow(r.index)
            }
            if (this._options.preload) {
                var h, p, d = this.getNext(),
                    i;
                try {
                    for (p = this._options.preload; p > 0; p--) {
                        h = new b.Picture;
                        i = t.getData(d);
                        h.add(this.isFullscreen() && "big" in i ? i.big : i.image);
                        d = t.getNext(d)
                    }
                } catch (v) {}
            }
            g.show(u.container);
            e(t._thumbnails[r.index].container).addClass("active").siblings(".active").removeClass("active");
            t.trigger({
                type: b.LOADSTART,
                cached: f,
                index: r.index,
                imageTarget: u.image,
                thumbTarget: l.image
            });
            u.load(s, function(e) {
                t._scaleImage(e, {
                    complete: function(e) {
                        g.show(e.container);
                        if ("image" in o) {
                            g.toggleQuality(o.image, false)
                        }
                        g.toggleQuality(e.image, false);
                        t._queue.stalled = true;
                        t.removePan();
                        t.setInfo(r.index);
                        t.setCounter(r.index);
                        t.trigger({
                            type: b.LOADFINISH,
                            cached: f,
                            index: r.index,
                            imageTarget: e.image,
                            thumbTarget: t._thumbnails[r.index].image
                        });
                        var n = o.image === null && t._options.transitionInitial ? t._options.transitionInitial : t._options.transition;
                        if (n in y === false) {
                            c()
                        } else {
                            var i = {
                                prev: o.image,
                                next: e.image,
                                rewind: r.rewind,
                                speed: t._options.transitionSpeed || 400
                            };
                            y[n].call(t, i, c)
                        }
                    }
                })
            })
        },
        getNext: function(e) {
            e = typeof e === "number" ? e : this.getIndex();
            return e === this.getDataLength() - 1 ? 0 : e + 1
        },
        getPrev: function(e) {
            e = typeof e === "number" ? e : this.getIndex();
            return e === 0 ? this.getDataLength() - 1 : e - 1
        },
        next: function() {
            if (this.getDataLength() > 1) {
                this.show(this.getNext(), false)
            }
            return this
        },
        prev: function() {
            if (this.getDataLength() > 1) {
                this.show(this.getPrev(), true)
            }
            return this
        },
        get: function(e) {
            return e in this._dom ? this._dom[e] : null
        },
        getData: function(e) {
            return e in this._data ? this._data[e] : this._data[this._active]
        },
        getDataLength: function() {
            return this._data.length
        },
        getIndex: function() {
            return typeof this._active === "number" ? this._active : false
        },
        getStageHeight: function() {
            return this._stageHeight
        },
        getStageWidth: function() {
            return this._stageWidth
        },
        getOptions: function(e) {
            return typeof e === "undefined" ? this._options : this._options[e]
        },
        setOptions: function(t, n) {
            if (typeof t === "object") {
                e.extend(this._options, t)
            } else {
                this._options[t] = n
            }
            return this
        },
        play: function(e) {
            this._playing = true;
            this._playtime = e || this._playtime;
            this._playCheck();
            this.trigger(b.PLAY);
            return this
        },
        pause: function() {
            this._playing = false;
            this.trigger(b.PAUSE);
            return this
        },
        playToggle: function(e) {
            return this._playing ? this.pause() : this.play(e)
        },
        isPlaying: function() {
            return this._playing
        },
        isFullscreen: function() {
            return this._fullscreen.active
        },
        _playCheck: function() {
            var e = this,
                t = 0,
                n = 20,
                r = g.timestamp(),
                i = "play" + this._id;
            if (this._playing) {
                g.clearTimer(i);
                var s = function() {
                    t = g.timestamp() - r;
                    if (t >= e._playtime && e._playing) {
                        g.clearTimer(i);
                        e.next();
                        return
                    }
                    if (e._playing) {
                        e.trigger({
                            type: b.PROGRESS,
                            percent: Math.ceil(t / e._playtime * 100),
                            seconds: Math.floor(t / 1e3),
                            milliseconds: t
                        });
                        g.addTimer(i, s, n)
                    }
                };
                g.addTimer(i, s, n)
            }
        },
        setIndex: function(e) {
            this._active = e;
            return this
        },
        setCounter: function(e) {
            if (typeof e === "number") {
                e++
            } else {
                if (typeof e === "undefined") {
                    e = this.getIndex() + 1
                }
            }
            this.get("current").innerHTML = e;
            if (f) {
                var t = this.$("counter"),
                    n = t.css("opacity"),
                    r = t.attr("style");
                if (r && parseInt(n, 10) === 1) {
                    t.attr("style", r.replace(/filter[^\;]+\;/i, ""))
                } else {
                    this.$("counter").css("opacity", n)
                }
            }
            return this
        },
        setInfo: function(t) {
            var n = this,
                r = this.getData(t);
            e.each(["title", "description"], function(e, t) {
                var i = n.$("info-" + t);
                if (!!r[t]) {
                    i[r[t].length ? "show" : "hide"]().html(r[t])
                } else {
                    i.empty().hide()
                }
            });
            return this
        },
        hasInfo: function(e) {
            var t = "title description".split(" "),
                n;
            for (n = 0; t[n]; n++) {
                if (!!this.getData(e)[t[n]]) {
                    return true
                }
            }
            return false
        },
        jQuery: function(t) {
            var n = this,
                r = [];
            e.each(t.split(","), function(t, i) {
                i = e.trim(i);
                if (n.get(i)) {
                    r.push(i)
                }
            });
            var i = e(n.get(r.shift()));
            e.each(r, function(e, t) {
                i = i.add(n.get(t))
            });
            return i
        },
        $: function(e) {
            return this.jQuery.apply(this, g.array(arguments))
        }
    };
    e.each(h, function(e, t) {
        var n = /_/.test(t) ? t.replace(/_/g, "") : t;
        b[t.toUpperCase()] = "galleria." + n
    });
    e.extend(b, {
        IE9: f === 9,
        IE8: f === 8,
        IE7: f === 7,
        IE6: f === 6,
        IE: !!f,
        WEBKIT: /webkit/.test(o),
        SAFARI: /safari/.test(o),
        CHROME: /chrome/.test(o),
        QUIRK: f && r.compatMode && r.compatMode === "BackCompat",
        MAC: /mac/.test(navigator.platform.toLowerCase()),
        OPERA: !!n.opera,
        IPHONE: /iphone/.test(o),
        IPAD: /ipad/.test(o),
        ANDROID: /android/.test(o),
        TOUCH: !!(/iphone/.test(o) || /ipad/.test(o) || /android/.test(o))
    });
    b.addTheme = function(t, n) {
        if (!t.name) {
            b.raise("No theme name specified")
        }
        if (typeof t.defaults !== "object") {
            t.defaults = {}
        } else {
            t.defaults = p(t.defaults)
        }
        var r = false,
            i;
        if (typeof t.css === "string") {
            e("link").each(function(e, n) {
                i = new RegExp(t.css);
                if (i.test(n.href)) {
                    r = true;
                    b.theme = t;
                    return false
                }
            });
            if (!r) {
                e("script").each(function(e, n) {
                    i = new RegExp("galleria\\." + t.name.toLowerCase() + "\\.");
                    if (i.test(n.src)) {
                        r = n.src.replace(/[^\/]*$/, "") + t.css;
                        g.addTimer("css", function() {
                            g.loadCSS(r, "galleria-theme", function() {
                                b.theme = t
                            })
                        }, 1)
                    }
                })
            }
            if (!r) {
                if (n) {
                    b.raise("No theme CSS loaded")
                } else {
                    b.theme = t
                }
            }
        } else {
            b.theme = t
        }
        return t
    };
    b.loadTheme = function(n, r) {
        var i = false,
            s = m.length;
        b.theme = t;
        g.loadScript(n, function() {
            i = true
        });
        g.wait({
            until: function() {
                return i
            },
            error: function() {
                b.raise("Theme at " + n + " could not load, check theme path.", true)
            },
            success: function() {
                if (s) {
                    var t = [];
                    e.each(b.get(), function(n, i) {
                        var s = e.extend(i._original.options, {
                            data_source: i._data
                        }, r);
                        i.$("container").remove();
                        var o = new b;
                        o._id = i._id;
                        o.init(i._original.target, s);
                        t.push(o)
                    });
                    m = t
                }
            },
            timeout: 2e3
        })
    };
    b.get = function(e) {
        if (!!m[e]) {
            return m[e]
        } else {
            if (typeof e !== "number") {
                return m
            } else {
                b.raise("Gallery index " + e + " not found")
            }
        }
    };
    b.addTransition = function(e, t) {
        y[e] = t
    };
    b.utils = g;
    b.log = function() {
        try {
            n.console.log.apply(n.console, g.array(arguments))
        } catch (e) {
            try {
                n.opera.postError.apply(n.opera, arguments)
            } catch (t) {
                n.alert(g.array(arguments).split(", "))
            }
        }
    };
    b.raise = function(e, t) {
        if (s || t) {
            var n = t ? "Fatal error" : "Error";
            throw new Error(n + ": " + e)
        }
    };
    b.Picture = function(t) {
        this.id = t || null;
        this.image = null;
        this.container = g.create("galleria-image");
        e(this.container).css({
            overflow: "hidden",
            position: "relative"
        });
        this.original = {
            width: 0,
            height: 0
        };
        this.ready = false;
        this.loaded = false
    };
    b.Picture.prototype = {
        cache: {},
        add: function(t) {
            var n = 0,
                r = this,
                i = new Image,
                s = function() {
                    e(i).load(o).attr("src", cdn_img_url + "images/no_image.jpg")
                },
                o = function() {
                    newSrc = e(this).attr("src");
                    if ((!this.width || !this.height) && n < 1e3) {
                        n++;
                        e(i).load(o).attr("src", newSrc + "?" + (new Date).getTime())
                    }
                    r.original = {
                        height: 428,
                        width: 639
                    };
                    r.cache[t] = newSrc;
                    r.loaded = true
                };
            e(i).css("display", "block");
            if (r.cache[t]) {
                i.src = r.cache[t];
                o.call(i);
                return i
            }
            e(i).load(o).error(s).attr("src", t);
            return i
        },
        show: function() {
            g.show(this.image)
        },
        hide: function() {
            g.moveOut(this.image)
        },
        clear: function() {
            this.image = null
        },
        isCached: function(e) {
            return !!this.cache[e]
        },
        load: function(t, r) {
            var i = this;
            e(this.container).empty(true);
            this.image = this.add(t);
            g.hide(this.image);
            e(this.container).append(this.image);
            g.wait({
                until: function() {
                    return i.loaded && i.image.complete && i.original.width && i.image.width
                },
                success: function() {
                    n.setTimeout(function() {
                        r.call(i, i)
                    }, 50)
                },
                error: function() {
                    n.setTimeout(function() {
                        r.call(i, i)
                    }, 50);
                    b.raise("image not loaded in 30 seconds: " + t)
                },
                timeout: 3e4
            });
            return this.container
        },
        scale: function(n) {
            n = e.extend({
                width: 0,
                height: 0,
                min: t,
                max: t,
                margin: 0,
                complete: function() {},
                position: "center",
                crop: false
            }, n);
            if (!this.image) {
                return this.container
            }
            var r, i, s = this,
                o = e(s.container);
            g.wait({
                until: function() {
                    r = n.width || o.width() || g.parseValue(o.css("width"));
                    i = n.height || o.height() || g.parseValue(o.css("height"));
                    return r && i
                },
                success: function() {
                    var t = (r - n.margin * 2) / s.original.width,
                        o = (i - n.margin * 2) / s.original.height,
                        u = {
                            "true": Math.max(t, o),
                            width: t,
                            height: o,
                            "false": Math.min(t, o)
                        },
                        a = u[n.crop.toString()];
                    if (n.max) {
                        a = Math.min(n.max, a)
                    }
                    if (n.min) {
                        a = Math.max(n.min, a)
                    }
                    e(s.container).width(r).height(i);
                    e.each(["width", "height"], function(t, n) {
                        e(s.image)[n](s.image[n] = s[n] = Math.round(s.original[n] * a))
                    });
                    var f = {},
                        l = {},
                        c = function(t, n, r) {
                            var i = 0;
                            if (/\%/.test(t)) {
                                var o = parseInt(t, 10) / 100,
                                    u = s.image[n] || e(s.image)[n]();
                                i = Math.ceil(u * -1 * o + r * o)
                            } else {
                                i = g.parseValue(t)
                            }
                            return i
                        },
                        h = {
                            top: {
                                top: 0
                            },
                            left: {
                                left: 0
                            },
                            right: {
                                left: "100%"
                            },
                            bottom: {
                                top: "100%"
                            }
                        };
                    e.each(n.position.toLowerCase().split(" "), function(e, t) {
                        if (t === "center") {
                            t = "50%"
                        }
                        f[e ? "top" : "left"] = t
                    });
                    e.each(f, function(t, n) {
                        if (h.hasOwnProperty(n)) {
                            e.extend(l, h[n])
                        }
                    });
                    f = f.top ? e.extend(f, l) : l;
                    f = e.extend({
                        top: "50%",
                        left: "50%"
                    }, f);
                    e(s.image).css({
                        position: "relative",
                        top: c(f.top, "height", i),
                        left: c(f.left, "width", r)
                    });
                    s.show();
                    s.ready = true;
                    n.complete.call(s, s)
                },
                error: function() {
                    b.raise("Could not scale image: " + s.image.src)
                },
                timeout: 1e3
            });
            return this
        }
    };
    e.extend(e.easing, {
        galleria: function(e, t, n, r, i) {
            if ((t /= i / 2) < 1) {
                return r / 2 * t * t * t * t + n
            }
            return -r / 2 * ((t -= 2) * t * t * t - 2) + n
        },
        galleriaIn: function(e, t, n, r, i) {
            return r * (t /= i) * t * t * t + n
        },
        galleriaOut: function(e, t, n, r, i) {
            return -r * ((t = t / i - 1) * t * t * t - 1) + n
        }
    });
    e.fn.galleria = function(e) {
        return this.each(function() {
            var t = new b;
            t.init(this, e)
        })
    };
    n.Galleria = b
})(jQuery);
(function(e) {
    Galleria.addTheme({
        name: "classic",
        author: "Galleria",
        css: "galleria.classic.css",
        defaults: {
            transition: "slide",
            thumbCrop: "height",
            _toggleInfo: false
        },
        init: function(t) {
            this.addElement("info-link", "info-close");
            this.append({
                info: ["info-link", "info-close"]
            });
            var n = this.$("info-link,info-close,info-text"),
                r = Galleria.TOUCH,
                i = r ? "touchstart" : "click";
            this.$("loader,counter").show().css("opacity", .4);
            if (!r) {
                this.addIdleState(this.get("counter"), {
                    opacity: 0
                })
            }
            if (t._toggleInfo === true) {
                n.bind(i, function() {
                    n.toggle()
                })
            } else {
                n.show();
                this.$("info-link, info-close").hide()
            }
            this.bind("thumbnail", function(n) {
                if (!r) {
                    e(n.thumbTarget).css("opacity", .6).parent().hover(function() {
                        e(this).not(".active").children().stop().fadeTo(100, 1)
                    }, function() {
                        e(this).not(".active").children().stop().fadeTo(400, .6)
                    });
                    if (n.index === t.show) {
                        e(n.thumbTarget).css("opacity", 1)
                    }
                }
            });
            this.bind("loadstart", function(t) {
                if (!t.cached) {
                    this.$("loader").show().fadeTo(200, .4)
                }
                this.$("info").toggle(this.hasInfo());
                e(t.thumbTarget).css("opacity", 1).parent().siblings().children().css("opacity", .6)
            });
            this.bind("loadfinish", function(e) {
                this.$("loader").fadeOut(200)
            })
        }
    })
})(jQuery);
(function(e) {
    var t = function(e, t) {
        if (e) {
            this.init(e, t)
        }
    };
    e.extend(t.prototype, {
        name: "cogzidelMap",
        init: function(t, n) {
            this.element = e(t);
            e.data(t, this.name, this);
            var r = this;
            if (n.position) {
                this.position = n.position
            }
            if (n.isFuzzy) {
                this.isFuzzy = n.isFuzzy
            }
            if (n.onMarkerClick) {
                this.onMarkerClick = n.onMarkerClick
            }
            if (n.accuracy) {
                this.accuracy = n.accuracy
            }
            if (this.isFuzzy) {
                var i = 11;
                if (this.accuracy >= 3 && this.accuracy <= 9) {
                    i = this.accuracy + 6
                } else {
                    if (this.accuracy == 2) {
                        i = 6
                    } else {
                        if (this.accuracy == 1) {
                            i = 4
                        } else {
                            i = 1
                        }
                    }
                }
                this.map = new google.maps.Map(t, {
                    zoom: i,
                    center: this.position,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    mapTypeControl: false,
                    streetViewControl: false,
                    scrollwheel: false
                });
                this.marker = new google.maps.Circle({
                    center: this.position,
                    map: this.map,
                    fillColor: "rgb(255, 0, 162)",
                    fillOpacity: .25,
                    radius: CogzidelConstants.MapCircleSizes[i],
                    strokeOpacity: 0,
                    clickable: false
                });
                var s = function() {
                    var e = r.marker.getBounds();
                    var t = r.map.getBounds();
                    if (e.contains && e.contains(t.getNorthEast()) && e.contains(t.getSouthWest())) {
                        if (!r.markerHidden) {
                            r.marker.setOptions({
                                fillOpacity: 0
                            });
                            r.markerHidden = true
                        }
                    } else {
                        if (r.markerHidden) {
                            r.marker.setOptions({
                                fillOpacity: .25
                            });
                            r.markerHidden = false
                        }
                    }
                };
                var o = function() {
                    r.marker.setRadius(CogzidelConstants.MapCircleSizes[r.map.getZoom()])
                };
                google.maps.event.addListener(r.map, "bounds_changed", s);
                google.maps.event.addListener(r.map, "zoom_changed", o)
            } else {
                this.map = new google.maps.Map(t, {
                    zoom: 15,
                    center: this.position,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    mapTypeControl: false,
                    streetViewControl: false,
                    scrollwheel: false,
                    scaleControl: true
                });
                this.marker = new google.maps.Marker({
                    clickable: !!this.onMarkerClick,
                    position: this.position,
                    map: this.map,
                    zIndex: 10,
                    icon: new google.maps.MarkerImage("/images/guidebook/pin_home.png", null, null, new google.maps.Point(14, 32))
                })
            }
            if (this.onMarkerClick) {
                google.maps.event.addListener(this.marker, "click", function() {
                    r.onMarkerClick(r)
                })
            }
        },
        setMarkerPosition: function(e) {
            if (this.isFuzzy) {
                this.marker.setCenter(e)
            } else {
                this.marker.setPosition(e)
            }
            this.map.panTo(e)
        },
        position: null,
        isFuzzy: false,
        map: null,
        marker: null,
        onMarkerClick: null,
        accuracy: 9,
        minZoom: 1,
        markerHidden: false
    });
    e.fn.cogzidelMap = function(n) {
        var r = e.makeArray(arguments),
            i = r.slice(1);
        var s;
        var o = this.each(function() {
            s = e.data(this, "cogzidelMap");
            if (s) {
                if (typeof n === "string") {
                    s[n].apply(s, i)
                } else {
                    if (s.update) {
                        s.update.apply(s, r)
                    }
                }
            } else {
                new t(this, n)
            }
        });
        return s ? s : o
    }
})(jQuery);
if (!window.CogzidelConstants) {
    var CogzidelConstants = {}
}
CogzidelConstants.MapCircleSizes = [4096e3, 2048e3, 1024e3, 512e3, 256e3, 128e3, 64e3, 32e3, 16e3, 8e3, 4e3, 2e3, 1e3, 500, 500, 500, 500, 500, 500, 500];
this.tooltip = function() {
    xOffset = 20;
    yOffset = 20;
    jQuery("a.tooltip").hover(function(e) {
        this.t = this.title;
        this.title = "";
        jQuery("body").append("<p id='tooltip'>" + this.t.replace(/\n/g, "<br />") + "</p>");
        jQuery("#tooltip").css("top", e.pageY - xOffset + "px").css("left", e.pageX + yOffset + "px").fadeIn("fast")
    }, function() {
        this.title = this.t;
        jQuery("#tooltip").remove()
    });
    jQuery("a.tooltip").mousemove(function(e) {
        jQuery("#tooltip").css("top", e.pageY - xOffset + "px").css("left", e.pageX + yOffset + "px")
    })
};
jQuery(document).ready(function() {
    tooltip()
});
(function(e) {
    SimpleStateMachine = function(e, t) {
        var n = this;
        n.init(e, t)
    };
    SimpleStateMachine.prototype.currentState = 0;
    e.extend(SimpleStateMachine.prototype, {
        States: {
            Init: 0
        },
        options: {},
        init: function(t, n) {
            var r = this;
            e.extend(r.States, t);
            e.extend(r.options, n);
            r.transitions = {};
            r.currentState = r.States.Init;
            e.each(r.States, function(t, n) {
                e.each(r.States, function(e, t) {
                    r.transitions[n + "_" + t] = []
                });
                r.transitions["_" + n.toString()] = [];
                r.transitions[n.toString() + "_"] = []
            })
        },
        addTransitionHandler: function(e, t) {
            var n = this;
            var r = "";
            if (typeof e === "object") {
                var i = e.from == null ? "" : e.from;
                var s = e.to == null ? "" : e.to;
                r = i + "_" + s
            } else {
                if (typeof e === "number") {
                    r = "_" + e.toString()
                }
            }
            n.transitions[r].push(t)
        },
        transitionTo: function(t) {
            var n = this;
            var r = function(e, t) {
                t.call(n.options.context || null)
            };
            var i = [n.currentState.toString() + "_", n.currentState.toString() + "_" + t.toString(), "_" + t.toString()];
            for (var s = 0, o = i.length; s < o; s++) {
                e.each(n.transitions[i[s]], r)
            }
            n.currentState = t
        }
    })
})(jQuery);
(function(e, t) {
    var n = e.VerificationFlow = function(e) {
        this.options = t.extend({
            element: null,
            showIntro: false,
            onComplete: function() {}
        }, e);
        this.init()
    };
    n.prototype = {
        states: {
            basic_profile: 0,
            profile_photo: 1,
            phone_verification: 2,
            real_name: 3
        },
        steps: [],
        statesFlipped: {},
        numStates: 0,
        currentState: 0,
        init: function() {
            var e = this;
            this.element = t(this.options.element);
            this.$continue = this.$(".button-bar a.continue");
            this.steps = [];
            this.$(".verification-flow-panel").each(function() {
                e.steps.push(t(this).data("step"))
            });
            if (this.element.length === 0 || this.steps.length === 0) {
                return
            }
            t.each(this.states, function(t, n) {
                e.statesFlipped[n] = t;
                e.numStates++
            });
            this.sm = new SimpleStateMachine(this.states, {
                context: this
            });
            t.each(this.transitionHandlers, function(t, n) {
                var r = e.states[t];
                e.sm.addTransitionHandler(r, n)
            });
            for (var n = this.currentState; n < this.numStates; n++) {
                if (this.$panel(this.statesFlipped[this.currentState]).length) {
                    break
                }
                this.currentState++
            }
            if (this.currentState === this.numStates) {
                this.finish();
                return
            }
            this.sm.transitionTo(this.currentState);
            this.$continue.click(function() {
                e.nextState()
            });
            this.initRealName();
            if (this.options.showIntro) {
                var r = this.$(".verification-flow-intro").show(),
                    i = this.$(".verification-flow-panels").hide();
                this.$("a.start").click(function() {
                    r.hide();
                    i.show();
                    e.start()
                });
                e.trackEvent("show_intro", {
                    numSteps: e.steps.length,
                    steps: e.steps
                })
            } else {
                e.start()
            }
            if (this.steps.length > 1) {
                this.$(".verification-flow-step span:eq(1)").text(this.steps.length)
            } else {
                this.$(".verification-flow-step").hide()
            }
        },
        start: function() {
            var e = this;
            this.trackEvent("start", {
                numSteps: e.steps.length,
                steps: e.steps
            });
            this.updateStep()
        },
        transitionHandlers: {
            basic_profile: function() {
                this.$(".verification-flow-panel").hide();
                this.$panel("basic_profile").show()
            },
            phone_verification: function() {
                var e = this;
                this.$(".verification-flow-panel").hide();
                this.$panel("phone_verification").show();
                t.phoneNumberWidget({
                    showAddNumberInitially: true,
                    onVerifyComplete: function() {
                        e.hasVerifiedPhoneNumber = true;
                        e.nextState();
                        return false
                    }
                })
            },
            profile_photo: function() {
                this.$(".verification-flow-panel").hide();
                this.$panel("profile_photo").show()
            },
            real_name: function() {
                this.$(".verification-flow-panel").hide();
                this.$panel("real_name").show();
                this.$continue.hide()
            }
        },
        submitHandlers: {
            basic_profile: function(e) {
                if (t.trim(t("#user_profile_info_about").val()) === "") {
                    this.showError("You need to enter a profile description!")
                } else {
                    var n = this.$panel("basic_profile").find("textarea"),
                        r = {};
                    r[n.attr("name")] = n.val();
                    t.post(n.attr("data-url"), r);
                    e.call(this)
                }
            },
            phone_verification: function(e) {
                if (!this.hasVerifiedPhoneNumber) {
                    this.showError("You need to verify your phone number before continuing.")
                } else {
                    e.call(this)
                }
            },
            profile_photo: function(e) {
                var n = this;
                n.setLoading(true);
                t.getJSON("/users/has_profile_pic", function(t) {
                    n.setLoading(false);
                    if (t.has_profile_pic) {
                        e.call(n)
                    } else {
                        n.showError("You need to add a profile photo before continuing.")
                    }
                })
            },
            real_name: function(e) {
                if (!this.hasCompletedRealName) {
                    this.showError("You need to confirm your real name before continuing.")
                } else {
                    e.call(this)
                }
            }
        },
        showError: function(e) {
            alert(e)
        },
        setLoading: function(e) {
            if (e) {
                this.element.addClass("loading");
                this.$continue.attr("disabled", "disabled")
            } else {
                this.element.removeClass("loading");
                this.$continue.removeAttr("disabled")
            }
        },
        nextState: function() {
            function s() {
                this.trackEvent("step_completed." + t);
                if (r === e.numStates) {
                    e.finish()
                } else {
                    e.currentState = r;
                    e.sm.transitionTo(e.currentState);
                    e.updateStep()
                }
            }
            var e = this,
                t = this.statesFlipped[this.currentState],
                n = this.submitHandlers[t],
                r;
            for (var i = this.currentState; i < this.numStates; i++) {
                r = i + 1;
                if (this.$panel(this.statesFlipped[r]).length) {
                    break
                }
            }
            if (this.$panel(t).length) {
                n.call(this, s)
            } else {
                s()
            }
        },
        updateStep: function() {
            var e = this.$(".verification-flow-panel:visible"),
                t = e.index() === -1 ? 1 : e.index() + 1,
                r = this.statesFlipped[this.currentState];
            this.$(".verification-flow-step span:first").text(t);
            if (t === this.$(".verification-flow-panel").length) {
                this.$continue.text(n.translations.finish)
            }
            this.trackEvent("step_start." + r)
        },
        $: function(e) {
            return this.element.find(e)
        },
        $panel: function(e) {
            return this.$(".verification-flow-panel." + e)
        },
        finish: function() {
            this.element.addClass("complete");
            if (this.options.showIntro) {
                this.$(".verification-flow-panels").hide();
                this.$(".verification-flow-complete").show()
            }
            this.options.onComplete.call(this);
            this.trackEvent("completed")
        },
        initRealName: function() {
            var r = this,
                i = this.$panel("real_name"),
                s;
            if (!i.length) {
                return
            }
            i.addClass("loading");
            this.realNameFlow = new e.RealNameFlow({
                form: false,
                container: i,
                onLoad: function() {
                    i.removeClass("loading");
                    s = t("#real_name_flow .form_submit .button-glossy.submit").removeClass("green").addClass("blue");
                    if (r.steps.length > 1) {
                        s.text(n.translations.finish)
                    }
                    if (r.statesFlipped[r.currentState] === "real_name") {
                        r.$continue.hide()
                    }
                },
                onSuccess: function() {
                    r.hasCompletedRealName = true;
                    r.nextState()
                }
            })
        },
        trackEvent: function(e, t) {
            var n = "verification_flow.";
            Mixpanel.track(n + e, t)
        }
    };
    n.translations = {
        finish: "Finish"
    };
    n.addTranslations = function(e) {
        t.extend(n.translations, e)
    };
    t.fn.verificationFlow = function(n) {
        n = t.extend({}, n || {}, {
            element: this
        });
        var r = new e.VerificationFlow(n);
        t(this).data("verificationFlow", r)
    }
})(Cogzidel, jQuery, undefined)
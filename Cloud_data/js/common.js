function ajax_log(e, t, n) {
    var r = {
        namespace: e,
        name: t
    };
    if (n) r.fields = n;
    jQuery.ajax({
        url: "/ajax_log",
        type: "post",
        dataType: "json",
        data: r
    })
}

function bind_logging(e, t, n, r) {
    var i;
    var s = $(e);
    if (!s.get(0)) return;
    var o = s.get(0).nodeName.toLowerCase(),
        u = r.selector || [o, "#", s.attr("id")].join(""),
        a = r.prefix ? r.prefix + "." : "",
        f = r.field_fns ? r.field_fns : {},
        l = function(e) {
            var t = [a, e.type, "_", u].join("");
            var r = f[e.type] ? f[e.type](s) : {};
            ajax_log(n, t, r)
        };
    var c = {
        blur: function(e) {
            return {
                value: e.val()
            }
        },
        change: function(e) {
            if (o == "select") return {
                option: e.find("option:selected").val()
            };
            else if (o == "input" || o == "textarea") return {
                value: e.val()
            };
            else return {}
        },
        click: function(e) {
            if (o == "input" && e.attr("type") == "checkbox") return {
                checked: e.attr("checked")
            };
            else return {}
        }
    };
    for (i in c)
        if (c.hasOwnProperty(i) && !f[i]) f[i] = c[i];
    if (typeof t == "string") t = [t];
    for (i in t)
        if (t.hasOwnProperty(i)) s.bind(t[i], l)
}

function rollover(e, t, n) {
    jQuery("#" + e).addClass(n).removeClass(t)
}

function calendar_is_not_set_date(e) {
    var t = null;
    if (typeof e === "string") t = jQuery("#" + e);
    else if (typeof e === "object") t = jQuery(e);
    return jQuery.trim(t.val()) === "" || t.val() === jQuery.datepicker._defaults.dateFormat
}

function calendar_process_onfocus(e) {
    if (calendar_is_not_set_date(e)) e.onclick()
}

function calendar_helper_simple_today() {
    var e = new Date;
    e.setHours(0);
    e.setMinutes(0);
    e.setSeconds(0);
    e.setMilliseconds(0);
    return e
}

function calendar_show_cal(e, t, n) {
    if (arguments.length < 3) n = "checkout";
    t = typeof t != "undefined" ? t : "absolute";
    var r = 0;
    if (n != "one_calendar_override")
        if (calendar_is_not_set_date(e))
            if (typeof e == "string") jQuery("#" + e).datepicker("show");
            else jQuery(e).datepicker("show");
    else if (calendar_is_not_set_date(n)) jQuery("#" + n).datepicker("show")
}

function calendar_show_cal_checkout(e, t, n) {
    n = typeof n != "undefined" ? n : "absolute";
    var r = new Date;
    if (!calendar_is_not_set_date(t)) r = Date.parse(jQuery("#" + t).val());
    if (isNaN(r)) r = new Date;
    var i = Math.ceil((r - new Date) / (24 * 60 * 60 * 1e3)) + 1;
    return new CalendarDateSelect(e, {
        position: n,
        month_year: "label",
        buttons: true,
        clear_button: true,
        default_date_offset: i,
        year_range: [(new Date).getFullYear(), (new Date).getFullYear() + 2],
        valid_date_check: function(e) {
            return e > r
        }
    })
}

function censor_validate_content(e, t) {
    if (re_cogzidel.test(e)) return true;
    var n = re_http.test(e) || re_www.test(e) || re_domain_ext.test(e);
    var r = re_phone_number.test(e) || re_phone_word.test(e);
    var i = re_email.test(e) || re_email_domain.test(e);
    if (n || r || i) {
        censor_attempt_counter++;
        if (censor_attempt_counter > 3 && t) alert("Warning: It looks like you may be trying to send contact information. 100% of scams begin with contact information being exchanged and ultimately exchanging money offline. If you follow the rules, you are 100% protected against scams. If you believe your message does not contain a website, phone number, or email address, then email us at contact@cogzidel.com and we can help.");
        else alert("It appears as though you entered a website, phone number, or email address. This information cannot be exchanged until after the booking is complete for your protection. Scams can only occur if you exchange money outside of the system. Please edit your message and try again.");
        return false
    }
    return true
}

function lwlb_show(e, t) {
    if (!t) t = {};
    jQuery("#lwlb_overlay").css("display", "block");
    jQuery("#" + e).css("display", "block");
    if (!t.no_scroll) window.scroll(0, 0)
}

function lwlb_hide(e) {
    jQuery("#" + e).hide();
    jQuery("#lwlb_overlay").hide()
}

function select_tab(e, t, n) {
    jQuery("." + e + "_link").removeClass("selected");
    n.addClass("selected");
    jQuery("#" + t).show();
    jQuery("." + e + "_content").hide();
    jQuery("#" + t).show()
}

function popup(e, t, n, r) {
    var i = "";
    if (newWin != null && !newWin.closed) newWin.close();
    if (t == "console") i = "resizable,height=" + n + ",width=" + r;
    if (t == "fixed") i = "status,height=" + n + ",width=" + r;
    if (t == "elastic") i = "toolbar,menubar,scrollbars,resizable,location,height=" + n + ",width=" + r;
    newWin = window.open(e, "newWin", i);
    newWin.focus()
}

function add_data_to_cookie(e, t) {
    existing_data = jQuery.cookie(e);
    if (existing_data == null) new_data = t;
    else {
        new_data = existing_data + "," + t;
        new_data = new_data.split(",");
        while (new_data.length > 75) new_data.splice(0, 1);
        new_data = jQuery.unique(new_data);
        new_data = new_data.join(",")
    }
    jQuery.cookie(e, new_data, DEFAULT_COOKIE_OPTIONS)
}

function show_super_lightbox(e) {
    jQuery("#transparent_bg_overlay").fadeIn(40).click(function() {
        hide_super_lightbox(e)
    });
    jQuery("#" + e).fadeIn(40)
}

function hide_super_lightbox(e) {
    jQuery("#" + e).fadeOut(40);
    jQuery("#transparent_bg_overlay").fadeOut(40).unbind("click")
}

function log(e) {
    if (window.console && window.console.firebug) console.log(e)
}(function(e, t) {
    function n(e) {
        return H.isWindow(e) ? e : e.nodeType === 9 ? e.defaultView || e.parentWindow : !1
    }

    function r(e) {
        if (!hn[e]) {
            var t = _.body,
                n = H("<" + e + ">").appendTo(t),
                r = n.css("display");
            n.remove();
            if (r === "none" || r === "") {
                pn || (pn = _.createElement("iframe"), pn.frameBorder = pn.width = pn.height = 0), t.appendChild(pn);
                if (!dn || !pn.createElement) dn = (pn.contentWindow || pn.contentDocument).document, dn.write((_.compatMode === "CSS1Compat" ? "<!doctype html>" : "") + "<html><body>"), dn.close();
                n = dn.createElement(e), dn.body.appendChild(n), r = H.css(n, "display"), t.removeChild(pn)
            }
            hn[e] = r
        }
        return hn[e]
    }

    function i(e, t) {
        var n = {};
        H.each(yn.concat.apply([], yn.slice(0, t)), function() {
            n[this] = e
        });
        return n
    }

    function s() {
        bn = t
    }

    function o() {
        setTimeout(s, 0);
        return bn = H.now()
    }

    function u() {
        try {
            return new e.ActiveXObject("Microsoft.XMLHTTP")
        } catch (t) {}
    }

    function a() {
        try {
            return new e.XMLHttpRequest
        } catch (t) {}
    }

    function f(e, n) {
        e.dataFilter && (n = e.dataFilter(n, e.dataType));
        var r = e.dataTypes,
            i = {},
            s, o, u = r.length,
            a, f = r[0],
            l, c, h, p, d;
        for (s = 1; s < u; s++) {
            if (s === 1)
                for (o in e.converters) typeof o == "string" && (i[o.toLowerCase()] = e.converters[o]);
            l = f, f = r[s];
            if (f === "*") f = l;
            else if (l !== "*" && l !== f) {
                c = l + " " + f, h = i[c] || i["* " + f];
                if (!h) {
                    d = t;
                    for (p in i) {
                        a = p.split(" ");
                        if (a[0] === l || a[0] === "*") {
                            d = i[a[1] + " " + f];
                            if (d) {
                                p = i[p], p === !0 ? h = d : d === !0 && (h = p);
                                break
                            }
                        }
                    }
                }!h && !d && H.error("No conversion from " + c.replace(" ", " to ")), h !== !0 && (n = h ? h(n) : d(p(n)))
            }
        }
        return n
    }

    function l(e, n, r) {
        var i = e.contents,
            s = e.dataTypes,
            o = e.responseFields,
            u, a, f, l;
        for (a in o) a in r && (n[o[a]] = r[a]);
        while (s[0] === "*") s.shift(), u === t && (u = e.mimeType || n.getResponseHeader("content-type"));
        if (u)
            for (a in i)
                if (i[a] && i[a].test(u)) {
                    s.unshift(a);
                    break
                }
        if (s[0] in r) f = s[0];
        else {
            for (a in r) {
                if (!s[0] || e.converters[a + " " + s[0]]) {
                    f = a;
                    break
                }
                l || (l = a)
            }
            f = f || l
        }
        if (f) {
            f !== s[0] && s.unshift(f);
            return r[f]
        }
    }

    function c(e, t, n, r) {
        if (H.isArray(t)) H.each(t, function(t, i) {
            n || qt.test(e) ? r(e, i) : c(e + "[" + (typeof i == "object" || H.isArray(i) ? t : "") + "]", i, n, r)
        });
        else if (!n && t != null && typeof t == "object")
            for (var i in t) c(e + "[" + i + "]", t[i], n, r);
        else r(e, t)
    }

    function h(e, n, r, i, s, o) {
        s = s || n.dataTypes[0], o = o || {}, o[s] = !0;
        var u = e[s],
            a = 0,
            f = u ? u.length : 0,
            l = e === tn,
            c;
        for (; a < f && (l || !c); a++) c = u[a](n, r, i), typeof c == "string" && (!l || o[c] ? c = t : (n.dataTypes.unshift(c), c = h(e, n, r, i, c, o)));
        (l || !c) && !o["*"] && (c = h(e, n, r, i, "*", o));
        return c
    }

    function p(e) {
        return function(t, n) {
            typeof t != "string" && (n = t, t = "*");
            if (H.isFunction(n)) {
                var r = t.toLowerCase().split(Gt),
                    i = 0,
                    s = r.length,
                    o, u, a;
                for (; i < s; i++) o = r[i], a = /^\+/.test(o), a && (o = o.substr(1) || "*"), u = e[o] = e[o] || [], u[a ? "unshift" : "push"](n)
            }
        }
    }

    function d(e, t, n) {
        var r = t === "width" ? e.offsetWidth : e.offsetHeight,
            i = t === "width" ? Pt : Ht;
        if (r > 0) {
            n !== "border" && H.each(i, function() {
                n || (r -= parseFloat(H.css(e, "padding" + this)) || 0), n === "margin" ? r += parseFloat(H.css(e, n + this)) || 0 : r -= parseFloat(H.css(e, "border" + this + "Width")) || 0
            });
            return r + "px"
        }
        r = Bt(e, t, t);
        if (r < 0 || r == null) r = e.style[t] || 0;
        r = parseFloat(r) || 0, n && H.each(i, function() {
            r += parseFloat(H.css(e, "padding" + this)) || 0, n !== "padding" && (r += parseFloat(H.css(e, "border" + this + "Width")) || 0), n === "margin" && (r += parseFloat(H.css(e, n + this)) || 0)
        });
        return r + "px"
    }

    function v(e, t) {
        t.src ? H.ajax({
            url: t.src,
            async: !1,
            dataType: "script"
        }) : H.globalEval((t.text || t.textContent || t.innerHTML || "").replace(Tt, "/*$0*/")), t.parentNode && t.parentNode.removeChild(t)
    }

    function m(e) {
        H.nodeName(e, "input") ? g(e) : "getElementsByTagName" in e && H.grep(e.getElementsByTagName("input"), g)
    }

    function g(e) {
        if (e.type === "checkbox" || e.type === "radio") e.defaultChecked = e.checked
    }

    function y(e) {
        return "getElementsByTagName" in e ? e.getElementsByTagName("*") : "querySelectorAll" in e ? e.querySelectorAll("*") : []
    }

    function b(e, t) {
        var n;
        if (t.nodeType === 1) {
            t.clearAttributes && t.clearAttributes(), t.mergeAttributes && t.mergeAttributes(e), n = t.nodeName.toLowerCase();
            if (n === "object") t.outerHTML = e.outerHTML;
            else if (n !== "input" || e.type !== "checkbox" && e.type !== "radio")
                if (n === "option") t.selected = e.defaultSelected;
                else {
                    if (n === "input" || n === "textarea") t.defaultValue = e.defaultValue
                } else e.checked && (t.defaultChecked = t.checked = e.checked), t.value !== e.value && (t.value = e.value);
            t.removeAttribute(H.expando)
        }
    }

    function w(e, t) {
        if (t.nodeType === 1 && !!H.hasData(e)) {
            var n = H.expando,
                r = H.data(e),
                i = H.data(t, r);
            if (r = r[n]) {
                var s = r.events;
                i = i[n] = H.extend({}, r);
                if (s) {
                    delete i.handle, i.events = {};
                    for (var o in s)
                        for (var u = 0, a = s[o].length; u < a; u++) H.event.add(t, o + (s[o][u].namespace ? "." : "") + s[o][u].namespace, s[o][u], s[o][u].data)
                }
            }
        }
    }

    function E(e, t) {
        return H.nodeName(e, "table") ? e.getElementsByTagName("tbody")[0] || e.appendChild(e.ownerDocument.createElement("tbody")) : e
    }

    function S(e, t, n) {
        t = t || 0;
        if (H.isFunction(t)) return H.grep(e, function(e, r) {
            var i = !!t.call(e, r, e);
            return i === n
        });
        if (t.nodeType) return H.grep(e, function(e, r) {
            return e === t === n
        });
        if (typeof t == "string") {
            var r = H.grep(e, function(e) {
                return e.nodeType === 1
            });
            if (ct.test(t)) return H.filter(t, r, !n);
            t = H.filter(t, r)
        }
        return H.grep(e, function(e, r) {
            return H.inArray(e, t) >= 0 === n
        })
    }

    function x(e) {
        return !e || !e.parentNode || e.parentNode.nodeType === 11
    }

    function T(e, t) {
        return (e && e !== "*" ? e + "." : "") + t.replace(Y, "`").replace(Z, "&")
    }

    function N(e) {
        var t, n, r, i, s, o, u, a, f, l, c, h, p, d = [],
            v = [],
            m = H._data(this, "events");
        if (!(e.liveFired === this || !m || !m.live || e.target.disabled || e.button && e.type === "click")) {
            e.namespace && (h = new RegExp("(^|\\.)" + e.namespace.split(".").join("\\.(?:.*\\.)?") + "(\\.|$)")), e.liveFired = this;
            var g = m.live.slice(0);
            for (u = 0; u < g.length; u++) s = g[u], s.origType.replace(Q, "") === e.type ? v.push(s.selector) : g.splice(u--, 1);
            i = H(e.target).closest(v, e.currentTarget);
            for (a = 0, f = i.length; a < f; a++) {
                c = i[a];
                for (u = 0; u < g.length; u++) {
                    s = g[u];
                    if (c.selector === s.selector && (!h || h.test(s.namespace)) && !c.elem.disabled) {
                        o = c.elem, r = null;
                        if (s.preType === "mouseenter" || s.preType === "mouseleave") e.type = s.preType, r = H(e.relatedTarget).closest(s.selector)[0], r && H.contains(o, r) && (r = o);
                        (!r || r !== o) && d.push({
                            elem: o,
                            handleObj: s,
                            level: c.level
                        })
                    }
                }
            }
            for (a = 0, f = d.length; a < f; a++) {
                i = d[a];
                if (n && i.level > n) break;
                e.currentTarget = i.elem, e.data = i.handleObj.data, e.handleObj = i.handleObj, p = i.handleObj.origHandler.apply(i.elem, arguments);
                if (p === !1 || e.isPropagationStopped()) {
                    n = i.level, p === !1 && (t = !1);
                    if (e.isImmediatePropagationStopped()) break
                }
            }
            return t
        }
    }

    function C(e, n, r) {
        var i = H.extend({}, r[0]);
        i.type = e, i.originalEvent = {}, i.liveFired = t, H.event.handle.call(n, i), i.isDefaultPrevented() && r[0].preventDefault()
    }

    function k() {
        return !0
    }

    function L() {
        return !1
    }

    function A(e, n, r) {
        var i = n + "defer",
            s = n + "queue",
            o = n + "mark",
            u = H.data(e, i, t, !0);
        u && (r === "queue" || !H.data(e, s, t, !0)) && (r === "mark" || !H.data(e, o, t, !0)) && setTimeout(function() {
            !H.data(e, s, t, !0) && !H.data(e, o, t, !0) && (H.removeData(e, i, !0), u.resolve())
        }, 0)
    }

    function O(e) {
        for (var t in e)
            if (t !== "toJSON") return !1;
        return !0
    }

    function M(e, n, r) {
        if (r === t && e.nodeType === 1) {
            var i = "data-" + n.replace(I, "$1-$2").toLowerCase();
            r = e.getAttribute(i);
            if (typeof r == "string") {
                try {
                    r = r === "true" ? !0 : r === "false" ? !1 : r === "null" ? null : H.isNaN(r) ? F.test(r) ? H.parseJSON(r) : r : parseFloat(r)
                } catch (s) {}
                H.data(e, n, r)
            } else r = t
        }
        return r
    }
    var _ = e.document,
        D = e.navigator,
        P = e.location,
        H = function() {
            function n() {
                if (!r.isReady) {
                    try {
                        _.documentElement.doScroll("left")
                    } catch (e) {
                        setTimeout(n, 1);
                        return
                    }
                    r.ready()
                }
            }
            var r = function(e, t) {
                    return new r.fn.init(e, t, o)
                },
                i = e.jQuery,
                s = e.$,
                o, u = /^(?:[^<]*(<[\w\W]+>)[^>]*$|#([\w\-]*)$)/,
                a = /\S/,
                f = /^\s+/,
                l = /\s+$/,
                c = /\d/,
                h = /^<(\w+)\s*\/?>(?:<\/\1>)?$/,
                p = /^[\],:{}\s]*$/,
                d = /\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g,
                v = /"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g,
                m = /(?:^|:|,)(?:\s*\[)+/g,
                g = /(webkit)[ \/]([\w.]+)/,
                y = /(opera)(?:.*version)?[ \/]([\w.]+)/,
                b = /(msie) ([\w.]+)/,
                w = /(mozilla)(?:.*? rv:([\w.]+))?/,
                E = /-([a-z])/ig,
                S = function(e, t) {
                    return t.toUpperCase()
                },
                x = D.userAgent,
                T, N, C, k = Object.prototype.toString,
                L = Object.prototype.hasOwnProperty,
                A = Array.prototype.push,
                O = Array.prototype.slice,
                M = String.prototype.trim,
                P = Array.prototype.indexOf,
                H = {};
            r.fn = r.prototype = {
                constructor: r,
                init: function(e, n, i) {
                    var s, o, a, f;
                    if (!e) return this;
                    if (e.nodeType) {
                        this.context = this[0] = e, this.length = 1;
                        return this
                    }
                    if (e === "body" && !n && _.body) {
                        this.context = _, this[0] = _.body, this.selector = e, this.length = 1;
                        return this
                    }
                    if (typeof e == "string") {
                        e.charAt(0) !== "<" || e.charAt(e.length - 1) !== ">" || e.length < 3 ? s = u.exec(e) : s = [null, e, null];
                        if (s && (s[1] || !n)) {
                            if (s[1]) {
                                n = n instanceof r ? n[0] : n, f = n ? n.ownerDocument || n : _, a = h.exec(e), a ? r.isPlainObject(n) ? (e = [_.createElement(a[1])], r.fn.attr.call(e, n, !0)) : e = [f.createElement(a[1])] : (a = r.buildFragment([s[1]], [f]), e = (a.cacheable ? r.clone(a.fragment) : a.fragment).childNodes);
                                return r.merge(this, e)
                            }
                            o = _.getElementById(s[2]);
                            if (o && o.parentNode) {
                                if (o.id !== s[2]) return i.find(e);
                                this.length = 1, this[0] = o
                            }
                            this.context = _, this.selector = e;
                            return this
                        }
                        return !n || n.jquery ? (n || i).find(e) : this.constructor(n).find(e)
                    }
                    if (r.isFunction(e)) return i.ready(e);
                    e.selector !== t && (this.selector = e.selector, this.context = e.context);
                    return r.makeArray(e, this)
                },
                selector: "",
                jquery: "1.6.2",
                length: 0,
                size: function() {
                    return this.length
                },
                toArray: function() {
                    return O.call(this, 0)
                },
                get: function(e) {
                    return e == null ? this.toArray() : e < 0 ? this[this.length + e] : this[e]
                },
                pushStack: function(e, t, n) {
                    var i = this.constructor();
                    r.isArray(e) ? A.apply(i, e) : r.merge(i, e), i.prevObject = this, i.context = this.context, t === "find" ? i.selector = this.selector + (this.selector ? " " : "") + n : t && (i.selector = this.selector + "." + t + "(" + n + ")");
                    return i
                },
                each: function(e, t) {
                    return r.each(this, e, t)
                },
                ready: function(e) {
                    r.bindReady(), N.done(e);
                    return this
                },
                eq: function(e) {
                    return e === -1 ? this.slice(e) : this.slice(e, +e + 1)
                },
                first: function() {
                    return this.eq(0)
                },
                last: function() {
                    return this.eq(-1)
                },
                slice: function() {
                    return this.pushStack(O.apply(this, arguments), "slice", O.call(arguments).join(","))
                },
                map: function(e) {
                    return this.pushStack(r.map(this, function(t, n) {
                        return e.call(t, n, t)
                    }))
                },
                end: function() {
                    return this.prevObject || this.constructor(null)
                },
                push: A,
                sort: [].sort,
                splice: [].splice
            }, r.fn.init.prototype = r.fn, r.extend = r.fn.extend = function() {
                var e, n, i, s, o, u, a = arguments[0] || {},
                    f = 1,
                    l = arguments.length,
                    c = !1;
                typeof a == "boolean" && (c = a, a = arguments[1] || {}, f = 2), typeof a != "object" && !r.isFunction(a) && (a = {}), l === f && (a = this, --f);
                for (; f < l; f++)
                    if ((e = arguments[f]) != null)
                        for (n in e) {
                            i = a[n], s = e[n];
                            if (a === s) continue;
                            c && s && (r.isPlainObject(s) || (o = r.isArray(s))) ? (o ? (o = !1, u = i && r.isArray(i) ? i : []) : u = i && r.isPlainObject(i) ? i : {}, a[n] = r.extend(c, u, s)) : s !== t && (a[n] = s)
                        }
                    return a
            }, r.extend({
                noConflict: function(t) {
                    e.$ === r && (e.$ = s), t && e.jQuery === r && (e.jQuery = i);
                    return r
                },
                isReady: !1,
                readyWait: 1,
                holdReady: function(e) {
                    e ? r.readyWait++ : r.ready(!0)
                },
                ready: function(e) {
                    if (e === !0 && !--r.readyWait || e !== !0 && !r.isReady) {
                        if (!_.body) return setTimeout(r.ready, 1);
                        r.isReady = !0;
                        if (e !== !0 && --r.readyWait > 0) return;
                        N.resolveWith(_, [r]), r.fn.trigger && r(_).trigger("ready").unbind("ready")
                    }
                },
                bindReady: function() {
                    if (!N) {
                        N = r._Deferred();
                        if (_.readyState === "complete") return setTimeout(r.ready, 1);
                        if (_.addEventListener) _.addEventListener("DOMContentLoaded", C, !1), e.addEventListener("load", r.ready, !1);
                        else if (_.attachEvent) {
                            _.attachEvent("onreadystatechange", C), e.attachEvent("onload", r.ready);
                            var t = !1;
                            try {
                                t = e.frameElement == null
                            } catch (i) {}
                            _.documentElement.doScroll && t && n()
                        }
                    }
                },
                isFunction: function(e) {
                    return r.type(e) === "function"
                },
                isArray: Array.isArray || function(e) {
                    return r.type(e) === "array"
                },
                isWindow: function(e) {
                    return e && typeof e == "object" && "setInterval" in e
                },
                isNaN: function(e) {
                    return e == null || !c.test(e) || isNaN(e)
                },
                type: function(e) {
                    return e == null ? String(e) : H[k.call(e)] || "object"
                },
                isPlainObject: function(e) {
                    if (!e || r.type(e) !== "object" || e.nodeType || r.isWindow(e)) return !1;
                    if (e.constructor && !L.call(e, "constructor") && !L.call(e.constructor.prototype, "isPrototypeOf")) return !1;
                    var n;
                    for (n in e);
                    return n === t || L.call(e, n)
                },
                isEmptyObject: function(e) {
                    for (var t in e) return !1;
                    return !0
                },
                error: function(e) {
                    throw e
                },
                parseJSON: function(t) {
                    if (typeof t != "string" || !t) return null;
                    t = r.trim(t);
                    if (e.JSON && e.JSON.parse) return e.JSON.parse(t);
                    if (p.test(t.replace(d, "@").replace(v, "]").replace(m, ""))) return (new Function("return " + t))();
                    r.error("Invalid JSON: " + t)
                },
                parseXML: function(t, n, i) {
                    e.DOMParser ? (i = new DOMParser, n = i.parseFromString(t, "text/xml")) : (n = new ActiveXObject("Microsoft.XMLDOM"), n.async = "false", n.loadXML(t)), i = n.documentElement, (!i || !i.nodeName || i.nodeName === "parsererror") && r.error("Invalid XML: " + t);
                    return n
                },
                noop: function() {},
                globalEval: function(t) {
                    t && a.test(t) && (e.execScript || function(t) {
                        e.eval.call(e, t)
                    })(t)
                },
                camelCase: function(e) {
                    return e.replace(E, S)
                },
                nodeName: function(e, t) {
                    return e.nodeName && e.nodeName.toUpperCase() === t.toUpperCase()
                },
                each: function(e, n, i) {
                    var s, o = 0,
                        u = e.length,
                        a = u === t || r.isFunction(e);
                    if (i)
                        if (a)
                            for (s in e) {
                                if (n.apply(e[s], i) === !1) break
                            } else
                                for (; o < u;) {
                                    if (n.apply(e[o++], i) === !1) break
                                } else if (a)
                                    for (s in e) {
                                        if (n.call(e[s], s, e[s]) === !1) break
                                    } else
                                        for (; o < u;)
                                            if (n.call(e[o], o, e[o++]) === !1) break;
                    return e
                },
                trim: M ? function(e) {
                    return e == null ? "" : M.call(e)
                } : function(e) {
                    return e == null ? "" : (e + "").replace(f, "").replace(l, "")
                },
                makeArray: function(e, t) {
                    var n = t || [];
                    if (e != null) {
                        var i = r.type(e);
                        e.length == null || i === "string" || i === "function" || i === "regexp" || r.isWindow(e) ? A.call(n, e) : r.merge(n, e)
                    }
                    return n
                },
                inArray: function(e, t) {
                    if (P) return P.call(t, e);
                    for (var n = 0, r = t.length; n < r; n++)
                        if (t[n] === e) return n;
                    return -1
                },
                merge: function(e, n) {
                    var r = e.length,
                        i = 0;
                    if (typeof n.length == "number")
                        for (var s = n.length; i < s; i++) e[r++] = n[i];
                    else
                        while (n[i] !== t) e[r++] = n[i++];
                    e.length = r;
                    return e
                },
                grep: function(e, t, n) {
                    var r = [],
                        i;
                    n = !!n;
                    for (var s = 0, o = e.length; s < o; s++) i = !!t(e[s], s), n !== i && r.push(e[s]);
                    return r
                },
                map: function(e, n, i) {
                    var s, o, u = [],
                        a = 0,
                        f = e.length,
                        l = e instanceof r || f !== t && typeof f == "number" && (f > 0 && e[0] && e[f - 1] || f === 0 || r.isArray(e));
                    if (l)
                        for (; a < f; a++) s = n(e[a], a, i), s != null && (u[u.length] = s);
                    else
                        for (o in e) s = n(e[o], o, i), s != null && (u[u.length] = s);
                    return u.concat.apply([], u)
                },
                guid: 1,
                proxy: function(e, n) {
                    if (typeof n == "string") {
                        var i = e[n];
                        n = e, e = i
                    }
                    if (!r.isFunction(e)) return t;
                    var s = O.call(arguments, 2),
                        o = function() {
                            return e.apply(n, s.concat(O.call(arguments)))
                        };
                    o.guid = e.guid = e.guid || o.guid || r.guid++;
                    return o
                },
                access: function(e, n, i, s, o, u) {
                    var a = e.length;
                    if (typeof n == "object") {
                        for (var f in n) r.access(e, f, n[f], s, o, i);
                        return e
                    }
                    if (i !== t) {
                        s = !u && s && r.isFunction(i);
                        for (var l = 0; l < a; l++) o(e[l], n, s ? i.call(e[l], l, o(e[l], n)) : i, u);
                        return e
                    }
                    return a ? o(e[0], n) : t
                },
                now: function() {
                    return (new Date).getTime()
                },
                uaMatch: function(e) {
                    e = e.toLowerCase();
                    var t = g.exec(e) || y.exec(e) || b.exec(e) || e.indexOf("compatible") < 0 && w.exec(e) || [];
                    return {
                        browser: t[1] || "",
                        version: t[2] || "0"
                    }
                },
                sub: function() {
                    function e(t, n) {
                        return new e.fn.init(t, n)
                    }
                    r.extend(!0, e, this), e.superclass = this, e.fn = e.prototype = this(), e.fn.constructor = e, e.sub = this.sub, e.fn.init = function(n, i) {
                        i && i instanceof r && !(i instanceof e) && (i = e(i));
                        return r.fn.init.call(this, n, i, t)
                    }, e.fn.init.prototype = e.fn;
                    var t = e(_);
                    return e
                },
                browser: {}
            }), r.each("Boolean Number String Function Array Date RegExp Object".split(" "), function(e, t) {
                H["[object " + t + "]"] = t.toLowerCase()
            }), T = r.uaMatch(x), T.browser && (r.browser[T.browser] = !0, r.browser.version = T.version), r.browser.webkit && (r.browser.safari = !0), a.test("� ") && (f = /^[\s\xA0]+/, l = /[\s\xA0]+$/), o = r(_), _.addEventListener ? C = function() {
                _.removeEventListener("DOMContentLoaded", C, !1), r.ready()
            } : _.attachEvent && (C = function() {
                _.readyState === "complete" && (_.detachEvent("onreadystatechange", C), r.ready())
            });
            return r
        }(),
        B = "done fail isResolved isRejected promise then always pipe".split(" "),
        j = [].slice;
    H.extend({
        _Deferred: function() {
            var e = [],
                t, n, r, i = {
                    done: function() {
                        if (!r) {
                            var n = arguments,
                                s, o, u, a, f;
                            t && (f = t, t = 0);
                            for (s = 0, o = n.length; s < o; s++) u = n[s], a = H.type(u), a === "array" ? i.done.apply(i, u) : a === "function" && e.push(u);
                            f && i.resolveWith(f[0], f[1])
                        }
                        return this
                    },
                    resolveWith: function(i, s) {
                        if (!r && !t && !n) {
                            s = s || [], n = 1;
                            try {
                                while (e[0]) e.shift().apply(i, s)
                            } finally {
                                t = [i, s], n = 0
                            }
                        }
                        return this
                    },
                    resolve: function() {
                        i.resolveWith(this, arguments);
                        return this
                    },
                    isResolved: function() {
                        return !!n || !!t
                    },
                    cancel: function() {
                        r = 1, e = [];
                        return this
                    }
                };
            return i
        },
        Deferred: function(e) {
            var t = H._Deferred(),
                n = H._Deferred(),
                r;
            H.extend(t, {
                then: function(e, n) {
                    t.done(e).fail(n);
                    return this
                },
                always: function() {
                    return t.done.apply(t, arguments).fail.apply(this, arguments)
                },
                fail: n.done,
                rejectWith: n.resolveWith,
                reject: n.resolve,
                isRejected: n.isResolved,
                pipe: function(e, n) {
                    return H.Deferred(function(r) {
                        H.each({
                            done: [e, "resolve"],
                            fail: [n, "reject"]
                        }, function(e, n) {
                            var i = n[0],
                                s = n[1],
                                o;
                            H.isFunction(i) ? t[e](function() {
                                o = i.apply(this, arguments), o && H.isFunction(o.promise) ? o.promise().then(r.resolve, r.reject) : r[s](o)
                            }) : t[e](r[s])
                        })
                    }).promise()
                },
                promise: function(e) {
                    if (e == null) {
                        if (r) return r;
                        r = e = {}
                    }
                    var n = B.length;
                    while (n--) e[B[n]] = t[B[n]];
                    return e
                }
            }), t.done(n.cancel).fail(t.cancel), delete t.cancel, e && e.call(t, t);
            return t
        },
        when: function(e) {
            function t(e) {
                return function(t) {
                    n[e] = arguments.length > 1 ? j.call(arguments, 0) : t, --s || o.resolveWith(o, j.call(n, 0))
                }
            }
            var n = arguments,
                r = 0,
                i = n.length,
                s = i,
                o = i <= 1 && e && H.isFunction(e.promise) ? e : H.Deferred();
            if (i > 1) {
                for (; r < i; r++) n[r] && H.isFunction(n[r].promise) ? n[r].promise().then(t(r), o.reject) : --s;
                s || o.resolveWith(o, n)
            } else o !== e && o.resolveWith(o, i ? [e] : []);
            return o.promise()
        }
    }), H.support = function() {
        var e = _.createElement("div"),
            t = _.documentElement,
            n, r, i, s, o, u, a, f, l, c, h, p, d, v, m, g, y;
        e.setAttribute("className", "t"), e.innerHTML = "   <link/><table></table><a href='/a' style='top:1px;float:left;opacity:.55;'>a</a><input type='checkbox'/>", n = e.getElementsByTagName("*"), r = e.getElementsByTagName("a")[0];
        if (!n || !n.length || !r) return {};
        i = _.createElement("select"), s = i.appendChild(_.createElement("option")), o = e.getElementsByTagName("input")[0], a = {
            leadingWhitespace: e.firstChild.nodeType === 3,
            tbody: !e.getElementsByTagName("tbody").length,
            htmlSerialize: !!e.getElementsByTagName("link").length,
            style: /top/.test(r.getAttribute("style")),
            hrefNormalized: r.getAttribute("href") === "/a",
            opacity: /^0.55$/.test(r.style.opacity),
            cssFloat: !!r.style.cssFloat,
            checkOn: o.value === "on",
            optSelected: s.selected,
            getSetAttribute: e.className !== "t",
            submitBubbles: !0,
            changeBubbles: !0,
            focusinBubbles: !1,
            deleteExpando: !0,
            noCloneEvent: !0,
            inlineBlockNeedsLayout: !1,
            shrinkWrapBlocks: !1,
            reliableMarginRight: !0
        }, o.checked = !0, a.noCloneChecked = o.cloneNode(!0).checked, i.disabled = !0, a.optDisabled = !s.disabled;
        try {
            delete e.test
        } catch (b) {
            a.deleteExpando = !1
        }!e.addEventListener && e.attachEvent && e.fireEvent && (e.attachEvent("onclick", function() {
            a.noCloneEvent = !1
        }), e.cloneNode(!0).fireEvent("onclick")), o = _.createElement("input"), o.value = "t", o.setAttribute("type", "radio"), a.radioValue = o.value === "t", o.setAttribute("checked", "checked"), e.appendChild(o), f = _.createDocumentFragment(), f.appendChild(e.firstChild), a.checkClone = f.cloneNode(!0).cloneNode(!0).lastChild.checked, e.innerHTML = "", e.style.width = e.style.paddingLeft = "1px", l = _.getElementsByTagName("body")[0], h = _.createElement(l ? "div" : "body"), p = {
            visibility: "hidden",
            width: 0,
            height: 0,
            border: 0,
            margin: 0
        }, l && H.extend(p, {
            position: "absolute",
            left: -1e3,
            top: -1e3
        });
        for (g in p) h.style[g] = p[g];
        h.appendChild(e), c = l || t, c.insertBefore(h, c.firstChild), a.appendChecked = o.checked, a.boxModel = e.offsetWidth === 2, "zoom" in e.style && (e.style.display = "inline", e.style.zoom = 1, a.inlineBlockNeedsLayout = e.offsetWidth === 2, e.style.display = "", e.innerHTML = "<div style='width:4px;'></div>", a.shrinkWrapBlocks = e.offsetWidth !== 2), e.innerHTML = "<table><tr><td style='padding:0;border:0;display:none'></td><td>t</td></tr></table>", d = e.getElementsByTagName("td"), y = d[0].offsetHeight === 0, d[0].style.display = "", d[1].style.display = "none", a.reliableHiddenOffsets = y && d[0].offsetHeight === 0, e.innerHTML = "", _.defaultView && _.defaultView.getComputedStyle && (u = _.createElement("div"), u.style.width = "0", u.style.marginRight = "0", e.appendChild(u), a.reliableMarginRight = (parseInt((_.defaultView.getComputedStyle(u, null) || {
            marginRight: 0
        }).marginRight, 10) || 0) === 0), h.innerHTML = "", c.removeChild(h);
        if (e.attachEvent)
            for (g in {
                    submit: 1,
                    change: 1,
                    focusin: 1
                }) m = "on" + g, y = m in e, y || (e.setAttribute(m, "return;"), y = typeof e[m] == "function"), a[g + "Bubbles"] = y;
        h = f = i = s = l = u = e = o = null;
        return a
    }(), H.boxModel = H.support.boxModel;
    var F = /^(?:\{.*\}|\[.*\])$/,
        I = /([a-z])([A-Z])/g;
    H.extend({
        cache: {},
        uuid: 0,
        expando: "jQuery" + (H.fn.jquery + Math.random()).replace(/\D/g, ""),
        noData: {
            embed: !0,
            object: "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000",
            applet: !0
        },
        hasData: function(e) {
            e = e.nodeType ? H.cache[e[H.expando]] : e[H.expando];
            return !!e && !O(e)
        },
        data: function(e, n, r, i) {
            if (!!H.acceptData(e)) {
                var s = H.expando,
                    o = typeof n == "string",
                    u, a = e.nodeType,
                    f = a ? H.cache : e,
                    l = a ? e[H.expando] : e[H.expando] && H.expando;
                if ((!l || i && l && !f[l][s]) && o && r === t) return;
                l || (a ? e[H.expando] = l = ++H.uuid : l = H.expando), f[l] || (f[l] = {}, a || (f[l].toJSON = H.noop));
                if (typeof n == "object" || typeof n == "function") i ? f[l][s] = H.extend(f[l][s], n) : f[l] = H.extend(f[l], n);
                u = f[l], i && (u[s] || (u[s] = {}), u = u[s]), r !== t && (u[H.camelCase(n)] = r);
                if (n === "events" && !u[n]) return u[s] && u[s].events;
                return o ? u[H.camelCase(n)] || u[n] : u
            }
        },
        removeData: function(t, n, r) {
            if (!!H.acceptData(t)) {
                var i = H.expando,
                    s = t.nodeType,
                    o = s ? H.cache : t,
                    u = s ? t[H.expando] : H.expando;
                if (!o[u]) return;
                if (n) {
                    var a = r ? o[u][i] : o[u];
                    if (a) {
                        delete a[n];
                        if (!O(a)) return
                    }
                }
                if (r) {
                    delete o[u][i];
                    if (!O(o[u])) return
                }
                var f = o[u][i];
                H.support.deleteExpando || o != e ? delete o[u] : o[u] = null, f ? (o[u] = {}, s || (o[u].toJSON = H.noop), o[u][i] = f) : s && (H.support.deleteExpando ? delete t[H.expando] : t.removeAttribute ? t.removeAttribute(H.expando) : t[H.expando] = null)
            }
        },
        _data: function(e, t, n) {
            return H.data(e, t, n, !0)
        },
        acceptData: function(e) {
            if (e.nodeName) {
                var t = H.noData[e.nodeName.toLowerCase()];
                if (t) return t !== !0 && e.getAttribute("classid") === t
            }
            return !0
        }
    }), H.fn.extend({
        data: function(e, n) {
            var r = null;
            if (typeof e == "undefined") {
                if (this.length) {
                    r = H.data(this[0]);
                    if (this[0].nodeType === 1) {
                        var i = this[0].attributes,
                            s;
                        for (var o = 0, u = i.length; o < u; o++) s = i[o].name, s.indexOf("data-") === 0 && (s = H.camelCase(s.substring(5)), M(this[0], s, r[s]))
                    }
                }
                return r
            }
            if (typeof e == "object") return this.each(function() {
                H.data(this, e)
            });
            var a = e.split(".");
            a[1] = a[1] ? "." + a[1] : "";
            if (n === t) {
                r = this.triggerHandler("getData" + a[1] + "!", [a[0]]), r === t && this.length && (r = H.data(this[0], e), r = M(this[0], e, r));
                return r === t && a[1] ? this.data(a[0]) : r
            }
            return this.each(function() {
                var t = H(this),
                    r = [a[0], n];
                t.triggerHandler("setData" + a[1] + "!", r), H.data(this, e, n), t.triggerHandler("changeData" + a[1] + "!", r)
            })
        },
        removeData: function(e) {
            return this.each(function() {
                H.removeData(this, e)
            })
        }
    }), H.extend({
        _mark: function(e, n) {
            e && (n = (n || "fx") + "mark", H.data(e, n, (H.data(e, n, t, !0) || 0) + 1, !0))
        },
        _unmark: function(e, n, r) {
            e !== !0 && (r = n, n = e, e = !1);
            if (n) {
                r = r || "fx";
                var i = r + "mark",
                    s = e ? 0 : (H.data(n, i, t, !0) || 1) - 1;
                s ? H.data(n, i, s, !0) : (H.removeData(n, i, !0), A(n, r, "mark"))
            }
        },
        queue: function(e, n, r) {
            if (e) {
                n = (n || "fx") + "queue";
                var i = H.data(e, n, t, !0);
                r && (!i || H.isArray(r) ? i = H.data(e, n, H.makeArray(r), !0) : i.push(r));
                return i || []
            }
        },
        dequeue: function(e, t) {
            t = t || "fx";
            var n = H.queue(e, t),
                r = n.shift(),
                i;
            r === "inprogress" && (r = n.shift()), r && (t === "fx" && n.unshift("inprogress"), r.call(e, function() {
                H.dequeue(e, t)
            })), n.length || (H.removeData(e, t + "queue", !0), A(e, t, "queue"))
        }
    }), H.fn.extend({
        queue: function(e, n) {
            typeof e != "string" && (n = e, e = "fx");
            if (n === t) return H.queue(this[0], e);
            return this.each(function() {
                var t = H.queue(this, e, n);
                e === "fx" && t[0] !== "inprogress" && H.dequeue(this, e)
            })
        },
        dequeue: function(e) {
            return this.each(function() {
                H.dequeue(this, e)
            })
        },
        delay: function(e, t) {
            e = H.fx ? H.fx.speeds[e] || e : e, t = t || "fx";
            return this.queue(t, function() {
                var n = this;
                setTimeout(function() {
                    H.dequeue(n, t)
                }, e)
            })
        },
        clearQueue: function(e) {
            return this.queue(e || "fx", [])
        },
        promise: function(e, n) {
            function r() {
                --u || i.resolveWith(s, [s])
            }
            typeof e != "string" && (n = e, e = t), e = e || "fx";
            var i = H.Deferred(),
                s = this,
                o = s.length,
                u = 1,
                a = e + "defer",
                f = e + "queue",
                l = e + "mark",
                c;
            while (o--)
                if (c = H.data(s[o], a, t, !0) || (H.data(s[o], f, t, !0) || H.data(s[o], l, t, !0)) && H.data(s[o], a, H._Deferred(), !0)) u++, c.done(r);
            r();
            return i.promise()
        }
    });
    var q = /[\n\t\r]/g,
        R = /\s+/,
        U = /\r/g,
        z = /^(?:button|input)$/i,
        W = /^(?:button|input|object|select|textarea)$/i,
        X = /^a(?:rea)?$/i,
        V = /^(?:autofocus|autoplay|async|checked|controls|defer|disabled|hidden|loop|multiple|open|readonly|required|scoped|selected)$/i,
        $ = /\:|^on/,
        J, K;
    H.fn.extend({
        attr: function(e, t) {
            return H.access(this, e, t, !0, H.attr)
        },
        removeAttr: function(e) {
            return this.each(function() {
                H.removeAttr(this, e)
            })
        },
        prop: function(e, t) {
            return H.access(this, e, t, !0, H.prop)
        },
        removeProp: function(e) {
            e = H.propFix[e] || e;
            return this.each(function() {
                try {
                    this[e] = t, delete this[e]
                } catch (n) {}
            })
        },
        addClass: function(e) {
            var t, n, r, i, s, o, u;
            if (H.isFunction(e)) return this.each(function(t) {
                H(this).addClass(e.call(this, t, this.className))
            });
            if (e && typeof e == "string") {
                t = e.split(R);
                for (n = 0, r = this.length; n < r; n++) {
                    i = this[n];
                    if (i.nodeType === 1)
                        if (!i.className && t.length === 1) i.className = e;
                        else {
                            s = " " + i.className + " ";
                            for (o = 0, u = t.length; o < u; o++) ~s.indexOf(" " + t[o] + " ") || (s += t[o] + " ");
                            i.className = H.trim(s)
                        }
                }
            }
            return this
        },
        removeClass: function(e) {
            var n, r, i, s, o, u, a;
            if (H.isFunction(e)) return this.each(function(t) {
                H(this).removeClass(e.call(this, t, this.className))
            });
            if (e && typeof e == "string" || e === t) {
                n = (e || "").split(R);
                for (r = 0, i = this.length; r < i; r++) {
                    s = this[r];
                    if (s.nodeType === 1 && s.className)
                        if (e) {
                            o = (" " + s.className + " ").replace(q, " ");
                            for (u = 0, a = n.length; u < a; u++) o = o.replace(" " + n[u] + " ", " ");
                            s.className = H.trim(o)
                        } else s.className = ""
                }
            }
            return this
        },
        toggleClass: function(e, t) {
            var n = typeof e,
                r = typeof t == "boolean";
            if (H.isFunction(e)) return this.each(function(n) {
                H(this).toggleClass(e.call(this, n, this.className, t), t)
            });
            return this.each(function() {
                if (n === "string") {
                    var i, s = 0,
                        o = H(this),
                        u = t,
                        a = e.split(R);
                    while (i = a[s++]) u = r ? u : !o.hasClass(i), o[u ? "addClass" : "removeClass"](i)
                } else if (n === "undefined" || n === "boolean") this.className && H._data(this, "__className__", this.className), this.className = this.className || e === !1 ? "" : H._data(this, "__className__") || ""
            })
        },
        hasClass: function(e) {
            var t = " " + e + " ";
            for (var n = 0, r = this.length; n < r; n++)
                if ((" " + this[n].className + " ").replace(q, " ").indexOf(t) > -1) return !0;
            return !1
        },
        val: function(e) {
            var n, r, i = this[0];
            if (!arguments.length) {
                if (i) {
                    n = H.valHooks[i.nodeName.toLowerCase()] || H.valHooks[i.type];
                    if (n && "get" in n && (r = n.get(i, "value")) !== t) return r;
                    r = i.value;
                    return typeof r == "string" ? r.replace(U, "") : r == null ? "" : r
                }
                return t
            }
            var s = H.isFunction(e);
            return this.each(function(r) {
                var i = H(this),
                    o;
                if (this.nodeType === 1) {
                    s ? o = e.call(this, r, i.val()) : o = e, o == null ? o = "" : typeof o == "number" ? o += "" : H.isArray(o) && (o = H.map(o, function(e) {
                        return e == null ? "" : e + ""
                    })), n = H.valHooks[this.nodeName.toLowerCase()] || H.valHooks[this.type];
                    if (!n || !("set" in n) || n.set(this, o, "value") === t) this.value = o
                }
            })
        }
    }), H.extend({
        valHooks: {
            option: {
                get: function(e) {
                    var t = e.attributes.value;
                    return !t || t.specified ? e.value : e.text
                }
            },
            select: {
                get: function(e) {
                    var t, n = e.selectedIndex,
                        r = [],
                        i = e.options,
                        s = e.type === "select-one";
                    if (n < 0) return null;
                    for (var o = s ? n : 0, u = s ? n + 1 : i.length; o < u; o++) {
                        var a = i[o];
                        if (a.selected && (H.support.optDisabled ? !a.disabled : a.getAttribute("disabled") === null) && (!a.parentNode.disabled || !H.nodeName(a.parentNode, "optgroup"))) {
                            t = H(a).val();
                            if (s) return t;
                            r.push(t)
                        }
                    }
                    if (s && !r.length && i.length) return H(i[n]).val();
                    return r
                },
                set: function(e, t) {
                    var n = H.makeArray(t);
                    H(e).find("option").each(function() {
                        this.selected = H.inArray(H(this).val(), n) >= 0
                    }), n.length || (e.selectedIndex = -1);
                    return n
                }
            }
        },
        attrFn: {
            val: !0,
            css: !0,
            html: !0,
            text: !0,
            data: !0,
            width: !0,
            height: !0,
            offset: !0
        },
        attrFix: {
            tabindex: "tabIndex"
        },
        attr: function(e, n, r, i) {
            var s = e.nodeType;
            if (!e || s === 3 || s === 8 || s === 2) return t;
            if (i && n in H.attrFn) return H(e)[n](r);
            if (!("getAttribute" in e)) return H.prop(e, n, r);
            var o, u, a = s !== 1 || !H.isXMLDoc(e);
            a && (n = H.attrFix[n] || n, u = H.attrHooks[n], u || (V.test(n) ? u = K : J && n !== "className" && (H.nodeName(e, "form") || $.test(n)) && (u = J)));
            if (r !== t) {
                if (r === null) {
                    H.removeAttr(e, n);
                    return t
                }
                if (u && "set" in u && a && (o = u.set(e, r, n)) !== t) return o;
                e.setAttribute(n, "" + r);
                return r
            }
            if (u && "get" in u && a && (o = u.get(e, n)) !== null) return o;
            o = e.getAttribute(n);
            return o === null ? t : o
        },
        removeAttr: function(e, t) {
            var n;
            e.nodeType === 1 && (t = H.attrFix[t] || t, H.support.getSetAttribute ? e.removeAttribute(t) : (H.attr(e, t, ""), e.removeAttributeNode(e.getAttributeNode(t))), V.test(t) && (n = H.propFix[t] || t) in e && (e[n] = !1))
        },
        attrHooks: {
            type: {
                set: function(e, t) {
                    if (z.test(e.nodeName) && e.parentNode) H.error("type property can't be changed");
                    else if (!H.support.radioValue && t === "radio" && H.nodeName(e, "input")) {
                        var n = e.value;
                        e.setAttribute("type", t), n && (e.value = n);
                        return t
                    }
                }
            },
            tabIndex: {
                get: function(e) {
                    var n = e.getAttributeNode("tabIndex");
                    return n && n.specified ? parseInt(n.value, 10) : W.test(e.nodeName) || X.test(e.nodeName) && e.href ? 0 : t
                }
            },
            value: {
                get: function(e, t) {
                    if (J && H.nodeName(e, "button")) return J.get(e, t);
                    return t in e ? e.value : null
                },
                set: function(e, t, n) {
                    if (J && H.nodeName(e, "button")) return J.set(e, t, n);
                    e.value = t
                }
            }
        },
        propFix: {
            tabindex: "tabIndex",
            readonly: "readOnly",
            "for": "htmlFor",
            "class": "className",
            maxlength: "maxLength",
            cellspacing: "cellSpacing",
            cellpadding: "cellPadding",
            rowspan: "rowSpan",
            colspan: "colSpan",
            usemap: "useMap",
            frameborder: "frameBorder",
            contenteditable: "contentEditable"
        },
        prop: function(e, n, r) {
            var i = e.nodeType;
            if (!e || i === 3 || i === 8 || i === 2) return t;
            var s, o, u = i !== 1 || !H.isXMLDoc(e);
            u && (n = H.propFix[n] || n, o = H.propHooks[n]);
            return r !== t ? o && "set" in o && (s = o.set(e, r, n)) !== t ? s : e[n] = r : o && "get" in o && (s = o.get(e, n)) !== t ? s : e[n]
        },
        propHooks: {}
    }), K = {
        get: function(e, n) {
            return H.prop(e, n) ? n.toLowerCase() : t
        },
        set: function(e, t, n) {
            var r;
            t === !1 ? H.removeAttr(e, n) : (r = H.propFix[n] || n, r in e && (e[r] = !0), e.setAttribute(n, n.toLowerCase()));
            return n
        }
    }, H.support.getSetAttribute || (H.attrFix = H.propFix, J = H.attrHooks.name = H.attrHooks.title = H.valHooks.button = {
        get: function(e, n) {
            var r;
            r = e.getAttributeNode(n);
            return r && r.nodeValue !== "" ? r.nodeValue : t
        },
        set: function(e, t, n) {
            var r = e.getAttributeNode(n);
            if (r) {
                r.nodeValue = t;
                return t
            }
        }
    }, H.each(["width", "height"], function(e, t) {
        H.attrHooks[t] = H.extend(H.attrHooks[t], {
            set: function(e, n) {
                if (n === "") {
                    e.setAttribute(t, "auto");
                    return n
                }
            }
        })
    })), H.support.hrefNormalized || H.each(["href", "src", "width", "height"], function(e, n) {
        H.attrHooks[n] = H.extend(H.attrHooks[n], {
            get: function(e) {
                var r = e.getAttribute(n, 2);
                return r === null ? t : r
            }
        })
    }), H.support.style || (H.attrHooks.style = {
        get: function(e) {
            return e.style.cssText.toLowerCase() || t
        },
        set: function(e, t) {
            return e.style.cssText = "" + t
        }
    }), H.support.optSelected || (H.propHooks.selected = H.extend(H.propHooks.selected, {
        get: function(e) {
            var t = e.parentNode;
            t && (t.selectedIndex, t.parentNode && t.parentNode.selectedIndex)
        }
    })), H.support.checkOn || H.each(["radio", "checkbox"], function() {
        H.valHooks[this] = {
            get: function(e) {
                return e.getAttribute("value") === null ? "on" : e.value
            }
        }
    }), H.each(["radio", "checkbox"], function() {
        H.valHooks[this] = H.extend(H.valHooks[this], {
            set: function(e, t) {
                if (H.isArray(t)) return e.checked = H.inArray(H(e).val(), t) >= 0
            }
        })
    });
    var Q = /\.(.*)$/,
        G = /^(?:textarea|input|select)$/i,
        Y = /\./g,
        Z = / /g,
        et = /[^\w\s.|`]/g,
        tt = function(e) {
            return e.replace(et, "\\$&")
        };
    H.event = {
        add: function(e, n, r, i) {
            if (e.nodeType !== 3 && e.nodeType !== 8) {
                if (r === !1) r = L;
                else if (!r) return;
                var s, o;
                r.handler && (s = r, r = s.handler), r.guid || (r.guid = H.guid++);
                var u = H._data(e);
                if (!u) return;
                var a = u.events,
                    f = u.handle;
                a || (u.events = a = {}), f || (u.handle = f = function(e) {
                    return typeof H != "undefined" && (!e || H.event.triggered !== e.type) ? H.event.handle.apply(f.elem, arguments) : t
                }), f.elem = e, n = n.split(" ");
                var l, c = 0,
                    h;
                while (l = n[c++]) {
                    o = s ? H.extend({}, s) : {
                        handler: r,
                        data: i
                    }, l.indexOf(".") > -1 ? (h = l.split("."), l = h.shift(), o.namespace = h.slice(0).sort().join(".")) : (h = [], o.namespace = ""), o.type = l, o.guid || (o.guid = r.guid);
                    var p = a[l],
                        d = H.event.special[l] || {};
                    if (!p) {
                        p = a[l] = [];
                        if (!d.setup || d.setup.call(e, i, h, f) === !1) e.addEventListener ? e.addEventListener(l, f, !1) : e.attachEvent && e.attachEvent("on" + l, f)
                    }
                    d.add && (d.add.call(e, o), o.handler.guid || (o.handler.guid = r.guid)), p.push(o), H.event.global[l] = !0
                }
                e = null
            }
        },
        global: {},
        remove: function(e, n, r, i) {
            if (e.nodeType !== 3 && e.nodeType !== 8) {
                r === !1 && (r = L);
                var s, o, u, a, f = 0,
                    l, c, h, p, d, v, m, g = H.hasData(e) && H._data(e),
                    y = g && g.events;
                if (!g || !y) return;
                n && n.type && (r = n.handler, n = n.type);
                if (!n || typeof n == "string" && n.charAt(0) === ".") {
                    n = n || "";
                    for (o in y) H.event.remove(e, o + n);
                    return
                }
                n = n.split(" ");
                while (o = n[f++]) {
                    m = o, v = null, l = o.indexOf(".") < 0, c = [], l || (c = o.split("."), o = c.shift(), h = new RegExp("(^|\\.)" + H.map(c.slice(0).sort(), tt).join("\\.(?:.*\\.)?") + "(\\.|$)")), d = y[o];
                    if (!d) continue;
                    if (!r) {
                        for (a = 0; a < d.length; a++) {
                            v = d[a];
                            if (l || h.test(v.namespace)) H.event.remove(e, m, v.handler, a), d.splice(a--, 1)
                        }
                        continue
                    }
                    p = H.event.special[o] || {};
                    for (a = i || 0; a < d.length; a++) {
                        v = d[a];
                        if (r.guid === v.guid) {
                            if (l || h.test(v.namespace)) i == null && d.splice(a--, 1), p.remove && p.remove.call(e, v);
                            if (i != null) break
                        }
                    }
                    if (d.length === 0 || i != null && d.length === 1)(!p.teardown || p.teardown.call(e, c) === !1) && H.removeEvent(e, o, g.handle), s = null, delete y[o]
                }
                if (H.isEmptyObject(y)) {
                    var b = g.handle;
                    b && (b.elem = null), delete g.events, delete g.handle, H.isEmptyObject(g) && H.removeData(e, t, !0)
                }
            }
        },
        customEvent: {
            getData: !0,
            setData: !0,
            changeData: !0
        },
        trigger: function(n, r, i, s) {
            var o = n.type || n,
                u = [],
                a;
            o.indexOf("!") >= 0 && (o = o.slice(0, -1), a = !0), o.indexOf(".") >= 0 && (u = o.split("."), o = u.shift(), u.sort());
            if (!!i && !H.event.customEvent[o] || !!H.event.global[o]) {
                n = typeof n == "object" ? n[H.expando] ? n : new H.Event(o, n) : new H.Event(o), n.type = o, n.exclusive = a, n.namespace = u.join("."), n.namespace_re = new RegExp("(^|\\.)" + u.join("\\.(?:.*\\.)?") + "(\\.|$)");
                if (s || !i) n.preventDefault(), n.stopPropagation();
                if (!i) {
                    H.each(H.cache, function() {
                        var e = H.expando,
                            t = this[e];
                        t && t.events && t.events[o] && H.event.trigger(n, r, t.handle.elem)
                    });
                    return
                }
                if (i.nodeType === 3 || i.nodeType === 8) return;
                n.result = t, n.target = i, r = r != null ? H.makeArray(r) : [], r.unshift(n);
                var f = i,
                    l = o.indexOf(":") < 0 ? "on" + o : "";
                do {
                    var c = H._data(f, "handle");
                    n.currentTarget = f, c && c.apply(f, r), l && H.acceptData(f) && f[l] && f[l].apply(f, r) === !1 && (n.result = !1, n.preventDefault()), f = f.parentNode || f.ownerDocument || f === n.target.ownerDocument && e
                } while (f && !n.isPropagationStopped());
                if (!n.isDefaultPrevented()) {
                    var h, p = H.event.special[o] || {};
                    if ((!p._default || p._default.call(i.ownerDocument, n) === !1) && (o !== "click" || !H.nodeName(i, "a")) && H.acceptData(i)) {
                        try {
                            l && i[o] && (h = i[l], h && (i[l] = null), H.event.triggered = o, i[o]())
                        } catch (d) {}
                        h && (i[l] = h), H.event.triggered = t
                    }
                }
                return n.result
            }
        },
        handle: function(n) {
            n = H.event.fix(n || e.event);
            var r = ((H._data(this, "events") || {})[n.type] || []).slice(0),
                i = !n.exclusive && !n.namespace,
                s = Array.prototype.slice.call(arguments, 0);
            s[0] = n, n.currentTarget = this;
            for (var o = 0, u = r.length; o < u; o++) {
                var a = r[o];
                if (i || n.namespace_re.test(a.namespace)) {
                    n.handler = a.handler, n.data = a.data, n.handleObj = a;
                    var f = a.handler.apply(this, s);
                    f !== t && (n.result = f, f === !1 && (n.preventDefault(), n.stopPropagation()));
                    if (n.isImmediatePropagationStopped()) break
                }
            }
            return n.result
        },
        props: "altKey attrChange attrName bubbles button cancelable charCode clientX clientY ctrlKey currentTarget data detail eventPhase fromElement handler keyCode layerX layerY metaKey newValue offsetX offsetY pageX pageY prevValue relatedNode relatedTarget screenX screenY shiftKey srcElement target toElement view wheelDelta which".split(" "),
        fix: function(e) {
            if (e[H.expando]) return e;
            var n = e;
            e = H.Event(n);
            for (var r = this.props.length, i; r;) i = this.props[--r], e[i] = n[i];
            e.target || (e.target = e.srcElement || _), e.target.nodeType === 3 && (e.target = e.target.parentNode), !e.relatedTarget && e.fromElement && (e.relatedTarget = e.fromElement === e.target ? e.toElement : e.fromElement);
            if (e.pageX == null && e.clientX != null) {
                var s = e.target.ownerDocument || _,
                    o = s.documentElement,
                    u = s.body;
                e.pageX = e.clientX + (o && o.scrollLeft || u && u.scrollLeft || 0) - (o && o.clientLeft || u && u.clientLeft || 0), e.pageY = e.clientY + (o && o.scrollTop || u && u.scrollTop || 0) - (o && o.clientTop || u && u.clientTop || 0)
            }
            e.which == null && (e.charCode != null || e.keyCode != null) && (e.which = e.charCode != null ? e.charCode : e.keyCode), !e.metaKey && e.ctrlKey && (e.metaKey = e.ctrlKey), !e.which && e.button !== t && (e.which = e.button & 1 ? 1 : e.button & 2 ? 3 : e.button & 4 ? 2 : 0);
            return e
        },
        guid: 1e8,
        proxy: H.proxy,
        special: {
            ready: {
                setup: H.bindReady,
                teardown: H.noop
            },
            live: {
                add: function(e) {
                    H.event.add(this, T(e.origType, e.selector), H.extend({}, e, {
                        handler: N,
                        guid: e.handler.guid
                    }))
                },
                remove: function(e) {
                    H.event.remove(this, T(e.origType, e.selector), e)
                }
            },
            beforeunload: {
                setup: function(e, t, n) {
                    H.isWindow(this) && (this.onbeforeunload = n)
                },
                teardown: function(e, t) {
                    this.onbeforeunload === t && (this.onbeforeunload = null)
                }
            }
        }
    }, H.removeEvent = _.removeEventListener ? function(e, t, n) {
        e.removeEventListener && e.removeEventListener(t, n, !1)
    } : function(e, t, n) {
        e.detachEvent && e.detachEvent("on" + t, n)
    }, H.Event = function(e, t) {
        if (!this.preventDefault) return new H.Event(e, t);
        e && e.type ? (this.originalEvent = e, this.type = e.type, this.isDefaultPrevented = e.defaultPrevented || e.returnValue === !1 || e.getPreventDefault && e.getPreventDefault() ? k : L) : this.type = e, t && H.extend(this, t), this.timeStamp = H.now(), this[H.expando] = !0
    }, H.Event.prototype = {
        preventDefault: function() {
            this.isDefaultPrevented = k;
            var e = this.originalEvent;
            !e || (e.preventDefault ? e.preventDefault() : e.returnValue = !1)
        },
        stopPropagation: function() {
            this.isPropagationStopped = k;
            var e = this.originalEvent;
            !e || (e.stopPropagation && e.stopPropagation(), e.cancelBubble = !0)
        },
        stopImmediatePropagation: function() {
            this.isImmediatePropagationStopped = k, this.stopPropagation()
        },
        isDefaultPrevented: L,
        isPropagationStopped: L,
        isImmediatePropagationStopped: L
    };
    var nt = function(e) {
            var t = e.relatedTarget,
                n = !1,
                r = e.type;
            e.type = e.data, t !== this && (t && (n = H.contains(this, t)), n || (H.event.handle.apply(this, arguments), e.type = r))
        },
        rt = function(e) {
            e.type = e.data, H.event.handle.apply(this, arguments)
        };
    H.each({
        mouseenter: "mouseover",
        mouseleave: "mouseout"
    }, function(e, t) {
        H.event.special[e] = {
            setup: function(n) {
                H.event.add(this, t, n && n.selector ? rt : nt, e)
            },
            teardown: function(e) {
                H.event.remove(this, t, e && e.selector ? rt : nt)
            }
        }
    }), H.support.submitBubbles || (H.event.special.submit = {
        setup: function(e, t) {
            if (!H.nodeName(this, "form")) H.event.add(this, "click.specialSubmit", function(e) {
                var t = e.target,
                    n = t.type;
                (n === "submit" || n === "image") && H(t).closest("form").length && C("submit", this, arguments)
            }), H.event.add(this, "keypress.specialSubmit", function(e) {
                var t = e.target,
                    n = t.type;
                (n === "text" || n === "password") && H(t).closest("form").length && e.keyCode === 13 && C("submit", this, arguments)
            });
            else return !1
        },
        teardown: function(e) {
            H.event.remove(this, ".specialSubmit")
        }
    });
    if (!H.support.changeBubbles) {
        var it, st = function(e) {
                var t = e.type,
                    n = e.value;
                t === "radio" || t === "checkbox" ? n = e.checked : t === "select-multiple" ? n = e.selectedIndex > -1 ? H.map(e.options, function(e) {
                    return e.selected
                }).join("-") : "" : H.nodeName(e, "select") && (n = e.selectedIndex);
                return n
            },
            ot = function(e) {
                var n = e.target,
                    r, i;
                if (!!G.test(n.nodeName) && !n.readOnly) {
                    r = H._data(n, "_change_data"), i = st(n), (e.type !== "focusout" || n.type !== "radio") && H._data(n, "_change_data", i);
                    if (r === t || i === r) return;
                    if (r != null || i) e.type = "change", e.liveFired = t, H.event.trigger(e, arguments[1], n)
                }
            };
        H.event.special.change = {
            filters: {
                focusout: ot,
                beforedeactivate: ot,
                click: function(e) {
                    var t = e.target,
                        n = H.nodeName(t, "input") ? t.type : "";
                    (n === "radio" || n === "checkbox" || H.nodeName(t, "select")) && ot.call(this, e)
                },
                keydown: function(e) {
                    var t = e.target,
                        n = H.nodeName(t, "input") ? t.type : "";
                    (e.keyCode === 13 && !H.nodeName(t, "textarea") || e.keyCode === 32 && (n === "checkbox" || n === "radio") || n === "select-multiple") && ot.call(this, e)
                },
                beforeactivate: function(e) {
                    var t = e.target;
                    H._data(t, "_change_data", st(t))
                }
            },
            setup: function(e, t) {
                if (this.type === "file") return !1;
                for (var n in it) H.event.add(this, n + ".specialChange", it[n]);
                return G.test(this.nodeName)
            },
            teardown: function(e) {
                H.event.remove(this, ".specialChange");
                return G.test(this.nodeName)
            }
        }, it = H.event.special.change.filters, it.focus = it.beforeactivate
    }
    H.support.focusinBubbles || H.each({
        focus: "focusin",
        blur: "focusout"
    }, function(e, t) {
        function n(e) {
            var n = H.event.fix(e);
            n.type = t, n.originalEvent = {}, H.event.trigger(n, null, n.target), n.isDefaultPrevented() && e.preventDefault()
        }
        var r = 0;
        H.event.special[t] = {
            setup: function() {
                r++ === 0 && _.addEventListener(e, n, !0)
            },
            teardown: function() {
                --r === 0 && _.removeEventListener(e, n, !0)
            }
        }
    }), H.each(["bind", "one"], function(e, n) {
        H.fn[n] = function(e, r, i) {
            var s;
            if (typeof e == "object") {
                for (var o in e) this[n](o, r, e[o], i);
                return this
            }
            if (arguments.length === 2 || r === !1) i = r, r = t;
            n === "one" ? (s = function(e) {
                H(this).unbind(e, s);
                return i.apply(this, arguments)
            }, s.guid = i.guid || H.guid++) : s = i;
            if (e === "unload" && n !== "one") this.one(e, r, i);
            else
                for (var u = 0, a = this.length; u < a; u++) H.event.add(this[u], e, s, r);
            return this
        }
    }), H.fn.extend({
        unbind: function(e, t) {
            if (typeof e == "object" && !e.preventDefault)
                for (var n in e) this.unbind(n, e[n]);
            else
                for (var r = 0, i = this.length; r < i; r++) H.event.remove(this[r], e, t);
            return this
        },
        delegate: function(e, t, n, r) {
            return this.live(t, n, r, e)
        },
        undelegate: function(e, t, n) {
            return arguments.length === 0 ? this.unbind("live") : this.die(t, null, n, e)
        },
        trigger: function(e, t) {
            return this.each(function() {
                H.event.trigger(e, t, this)
            })
        },
        triggerHandler: function(e, t) {
            if (this[0]) return H.event.trigger(e, t, this[0], !0)
        },
        toggle: function(e) {
            var t = arguments,
                n = e.guid || H.guid++,
                r = 0,
                i = function(n) {
                    var i = (H.data(this, "lastToggle" + e.guid) || 0) % r;
                    H.data(this, "lastToggle" + e.guid, i + 1), n.preventDefault();
                    return t[i].apply(this, arguments) || !1
                };
            i.guid = n;
            while (r < t.length) t[r++].guid = n;
            return this.click(i)
        },
        hover: function(e, t) {
            return this.mouseenter(e).mouseleave(t || e)
        }
    });
    var ut = {
        focus: "focusin",
        blur: "focusout",
        mouseenter: "mouseover",
        mouseleave: "mouseout"
    };
    H.each(["live", "die"], function(e, n) {
            H.fn[n] = function(e, r, i, s) {
                var o, u = 0,
                    a, f, l, c = s || this.selector,
                    h = s ? this : H(this.context);
                if (typeof e == "object" && !e.preventDefault) {
                    for (var p in e) h[n](p, r, e[p], c);
                    return this
                }
                if (n === "die" && !e && s && s.charAt(0) === ".") {
                    h.unbind(s);
                    return this
                }
                if (r === !1 || H.isFunction(r)) i = r || L, r = t;
                e = (e || "").split(" ");
                while ((o = e[u++]) != null) {
                    a = Q.exec(o), f = "", a && (f = a[0], o = o.replace(Q, ""));
                    if (o === "hover") {
                        e.push("mouseenter" + f, "mouseleave" + f);
                        continue
                    }
                    l = o, ut[o] ? (e.push(ut[o] + f), o = o + f) : o = (ut[o] || o) + f;
                    if (n === "live")
                        for (var d = 0, v = h.length; d < v; d++) H.event.add(h[d], "live." + T(o, c), {
                            data: r,
                            selector: c,
                            handler: i,
                            origType: o,
                            origHandler: i,
                            preType: l
                        });
                    else h.unbind("live." + T(o, c), i)
                }
                return this
            }
        }), H.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error".split(" "), function(e, t) {
            H.fn[t] = function(e, n) {
                n == null && (n = e, e = null);
                return arguments.length > 0 ? this.bind(t, e, n) : this.trigger(t)
            }, H.attrFn && (H.attrFn[t] = !0)
        }),
        function() {
            function e(e, t, n, r, i, s) {
                for (var o = 0, u = r.length; o < u; o++) {
                    var a = r[o];
                    if (a) {
                        var f = !1;
                        a = a[e];
                        while (a) {
                            if (a.sizcache === n) {
                                f = r[a.sizset];
                                break
                            }
                            if (a.nodeType === 1) {
                                s || (a.sizcache = n, a.sizset = o);
                                if (typeof t != "string") {
                                    if (a === t) {
                                        f = !0;
                                        break
                                    }
                                } else if (l.filter(t, [a]).length > 0) {
                                    f = a;
                                    break
                                }
                            }
                            a = a[e]
                        }
                        r[o] = f
                    }
                }
            }

            function n(e, t, n, r, i, s) {
                for (var o = 0, u = r.length; o < u; o++) {
                    var a = r[o];
                    if (a) {
                        var f = !1;
                        a = a[e];
                        while (a) {
                            if (a.sizcache === n) {
                                f = r[a.sizset];
                                break
                            }
                            a.nodeType === 1 && !s && (a.sizcache = n, a.sizset = o);
                            if (a.nodeName.toLowerCase() === t) {
                                f = a;
                                break
                            }
                            a = a[e]
                        }
                        r[o] = f
                    }
                }
            }
            var r = /((?:\((?:\([^()]+\)|[^()]+)+\)|\[(?:\[[^\[\]]*\]|['"][^'"]*['"]|[^\[\]'"]+)+\]|\\.|[^ >+~,(\[\\]+)+|[>+~])(\s*,\s*)?((?:.|\r|\n)*)/g,
                i = 0,
                s = Object.prototype.toString,
                o = !1,
                u = !0,
                a = /\\/g,
                f = /\W/;
            [0, 0].sort(function() {
                u = !1;
                return 0
            });
            var l = function(e, t, n, i) {
                n = n || [], t = t || _;
                var o = t;
                if (t.nodeType !== 1 && t.nodeType !== 9) return [];
                if (!e || typeof e != "string") return n;
                var u, a, f, p, d, m, g, y, w = !0,
                    E = l.isXML(t),
                    S = [],
                    x = e;
                do {
                    r.exec(""), u = r.exec(x);
                    if (u) {
                        x = u[3], S.push(u[1]);
                        if (u[2]) {
                            p = u[3];
                            break
                        }
                    }
                } while (u);
                if (S.length > 1 && h.exec(e))
                    if (S.length === 2 && c.relative[S[0]]) a = b(S[0] + S[1], t);
                    else {
                        a = c.relative[S[0]] ? [t] : l(S.shift(), t);
                        while (S.length) e = S.shift(), c.relative[e] && (e += S.shift()), a = b(e, a)
                    } else {
                    !i && S.length > 1 && t.nodeType === 9 && !E && c.match.ID.test(S[0]) && !c.match.ID.test(S[S.length - 1]) && (d = l.find(S.shift(), t, E), t = d.expr ? l.filter(d.expr, d.set)[0] : d.set[0]);
                    if (t) {
                        d = i ? {
                            expr: S.pop(),
                            set: v(i)
                        } : l.find(S.pop(), S.length === 1 && (S[0] === "~" || S[0] === "+") && t.parentNode ? t.parentNode : t, E), a = d.expr ? l.filter(d.expr, d.set) : d.set, S.length > 0 ? f = v(a) : w = !1;
                        while (S.length) m = S.pop(), g = m, c.relative[m] ? g = S.pop() : m = "", g == null && (g = t), c.relative[m](f, g, E)
                    } else f = S = []
                }
                f || (f = a), f || l.error(m || e);
                if (s.call(f) === "[object Array]")
                    if (!w) n.push.apply(n, f);
                    else if (t && t.nodeType === 1)
                    for (y = 0; f[y] != null; y++) f[y] && (f[y] === !0 || f[y].nodeType === 1 && l.contains(t, f[y])) && n.push(a[y]);
                else
                    for (y = 0; f[y] != null; y++) f[y] && f[y].nodeType === 1 && n.push(a[y]);
                else v(f, n);
                p && (l(p, o, n, i), l.uniqueSort(n));
                return n
            };
            l.uniqueSort = function(e) {
                if (g) {
                    o = u, e.sort(g);
                    if (o)
                        for (var t = 1; t < e.length; t++) e[t] === e[t - 1] && e.splice(t--, 1)
                }
                return e
            }, l.matches = function(e, t) {
                return l(e, null, null, t)
            }, l.matchesSelector = function(e, t) {
                return l(t, null, null, [e]).length > 0
            }, l.find = function(e, t, n) {
                var r;
                if (!e) return [];
                for (var i = 0, s = c.order.length; i < s; i++) {
                    var o, u = c.order[i];
                    if (o = c.leftMatch[u].exec(e)) {
                        var f = o[1];
                        o.splice(1, 1);
                        if (f.substr(f.length - 1) !== "\\") {
                            o[1] = (o[1] || "").replace(a, ""), r = c.find[u](o, t, n);
                            if (r != null) {
                                e = e.replace(c.match[u], "");
                                break
                            }
                        }
                    }
                }
                r || (r = typeof t.getElementsByTagName != "undefined" ? t.getElementsByTagName("*") : []);
                return {
                    set: r,
                    expr: e
                }
            }, l.filter = function(e, n, r, i) {
                var s, o, u = e,
                    a = [],
                    f = n,
                    h = n && n[0] && l.isXML(n[0]);
                while (e && n.length) {
                    for (var p in c.filter)
                        if ((s = c.leftMatch[p].exec(e)) != null && s[2]) {
                            var d, v, m = c.filter[p],
                                g = s[1];
                            o = !1, s.splice(1, 1);
                            if (g.substr(g.length - 1) === "\\") continue;
                            f === a && (a = []);
                            if (c.preFilter[p]) {
                                s = c.preFilter[p](s, f, r, a, i, h);
                                if (!s) o = d = !0;
                                else if (s === !0) continue
                            }
                            if (s)
                                for (var y = 0;
                                    (v = f[y]) != null; y++)
                                    if (v) {
                                        d = m(v, s, y, f);
                                        var b = i ^ !!d;
                                        r && d != null ? b ? o = !0 : f[y] = !1 : b && (a.push(v), o = !0)
                                    }
                            if (d !== t) {
                                r || (f = a), e = e.replace(c.match[p], "");
                                if (!o) return [];
                                break
                            }
                        }
                    if (e === u)
                        if (o == null) l.error(e);
                        else break;
                    u = e
                }
                return f
            }, l.error = function(e) {
                throw "Syntax error, unrecognized expression: " + e
            };
            var c = l.selectors = {
                    order: ["ID", "NAME", "TAG"],
                    match: {
                        ID: /#((?:[\w\u00c0-\uFFFF\-]|\\.)+)/,
                        CLASS: /\.((?:[\w\u00c0-\uFFFF\-]|\\.)+)/,
                        NAME: /\[name=['"]*((?:[\w\u00c0-\uFFFF\-]|\\.)+)['"]*\]/,
                        ATTR: /\[\s*((?:[\w\u00c0-\uFFFF\-]|\\.)+)\s*(?:(\S?=)\s*(?:(['"])(.*?)\3|(#?(?:[\w\u00c0-\uFFFF\-]|\\.)*)|)|)\s*\]/,
                        TAG: /^((?:[\w\u00c0-\uFFFF\*\-]|\\.)+)/,
                        CHILD: /:(only|nth|last|first)-child(?:\(\s*(even|odd|(?:[+\-]?\d+|(?:[+\-]?\d*)?n\s*(?:[+\-]\s*\d+)?))\s*\))?/,
                        POS: /:(nth|eq|gt|lt|first|last|even|odd)(?:\((\d*)\))?(?=[^\-]|$)/,
                        PSEUDO: /:((?:[\w\u00c0-\uFFFF\-]|\\.)+)(?:\((['"]?)((?:\([^\)]+\)|[^\(\)]*)+)\2\))?/
                    },
                    leftMatch: {},
                    attrMap: {
                        "class": "className",
                        "for": "htmlFor"
                    },
                    attrHandle: {
                        href: function(e) {
                            return e.getAttribute("href")
                        },
                        type: function(e) {
                            return e.getAttribute("type")
                        }
                    },
                    relative: {
                        "+": function(e, t) {
                            var n = typeof t == "string",
                                r = n && !f.test(t),
                                i = n && !r;
                            r && (t = t.toLowerCase());
                            for (var s = 0, o = e.length, u; s < o; s++)
                                if (u = e[s]) {
                                    while ((u = u.previousSibling) && u.nodeType !== 1);
                                    e[s] = i || u && u.nodeName.toLowerCase() === t ? u || !1 : u === t
                                }
                            i && l.filter(t, e, !0)
                        },
                        ">": function(e, t) {
                            var n, r = typeof t == "string",
                                i = 0,
                                s = e.length;
                            if (r && !f.test(t)) {
                                t = t.toLowerCase();
                                for (; i < s; i++) {
                                    n = e[i];
                                    if (n) {
                                        var o = n.parentNode;
                                        e[i] = o.nodeName.toLowerCase() === t ? o : !1
                                    }
                                }
                            } else {
                                for (; i < s; i++) n = e[i], n && (e[i] = r ? n.parentNode : n.parentNode === t);
                                r && l.filter(t, e, !0)
                            }
                        },
                        "": function(t, r, s) {
                            var o, u = i++,
                                a = e;
                            typeof r == "string" && !f.test(r) && (r = r.toLowerCase(), o = r, a = n), a("parentNode", r, u, t, o, s)
                        },
                        "~": function(t, r, s) {
                            var o, u = i++,
                                a = e;
                            typeof r == "string" && !f.test(r) && (r = r.toLowerCase(), o = r, a = n), a("previousSibling", r, u, t, o, s)
                        }
                    },
                    find: {
                        ID: function(e, t, n) {
                            if (typeof t.getElementById != "undefined" && !n) {
                                var r = t.getElementById(e[1]);
                                return r && r.parentNode ? [r] : []
                            }
                        },
                        NAME: function(e, t) {
                            if (typeof t.getElementsByName != "undefined") {
                                var n = [],
                                    r = t.getElementsByName(e[1]);
                                for (var i = 0, s = r.length; i < s; i++) r[i].getAttribute("name") === e[1] && n.push(r[i]);
                                return n.length === 0 ? null : n
                            }
                        },
                        TAG: function(e, t) {
                            if (typeof t.getElementsByTagName != "undefined") return t.getElementsByTagName(e[1])
                        }
                    },
                    preFilter: {
                        CLASS: function(e, t, n, r, i, s) {
                            e = " " + e[1].replace(a, "") + " ";
                            if (s) return e;
                            for (var o = 0, u;
                                (u = t[o]) != null; o++) u && (i ^ (u.className && (" " + u.className + " ").replace(/[\t\n\r]/g, " ").indexOf(e) >= 0) ? n || r.push(u) : n && (t[o] = !1));
                            return !1
                        },
                        ID: function(e) {
                            return e[1].replace(a, "")
                        },
                        TAG: function(e, t) {
                            return e[1].replace(a, "").toLowerCase()
                        },
                        CHILD: function(e) {
                            if (e[1] === "nth") {
                                e[2] || l.error(e[0]), e[2] = e[2].replace(/^\+|\s*/g, "");
                                var t = /(-?)(\d*)(?:n([+\-]?\d*))?/.exec(e[2] === "even" && "2n" || e[2] === "odd" && "2n+1" || !/\D/.test(e[2]) && "0n+" + e[2] || e[2]);
                                e[2] = t[1] + (t[2] || 1) - 0, e[3] = t[3] - 0
                            } else e[2] && l.error(e[0]);
                            e[0] = i++;
                            return e
                        },
                        ATTR: function(e, t, n, r, i, s) {
                            var o = e[1] = e[1].replace(a, "");
                            !s && c.attrMap[o] && (e[1] = c.attrMap[o]), e[4] = (e[4] || e[5] || "").replace(a, ""), e[2] === "~=" && (e[4] = " " + e[4] + " ");
                            return e
                        },
                        PSEUDO: function(e, t, n, i, s) {
                            if (e[1] === "not")
                                if ((r.exec(e[3]) || "").length > 1 || /^\w/.test(e[3])) e[3] = l(e[3], null, null, t);
                                else {
                                    var o = l.filter(e[3], t, n, !0 ^ s);
                                    n || i.push.apply(i, o);
                                    return !1
                                } else if (c.match.POS.test(e[0]) || c.match.CHILD.test(e[0])) return !0;
                            return e
                        },
                        POS: function(e) {
                            e.unshift(!0);
                            return e
                        }
                    },
                    filters: {
                        enabled: function(e) {
                            return e.disabled === !1 && e.type !== "hidden"
                        },
                        disabled: function(e) {
                            return e.disabled === !0
                        },
                        checked: function(e) {
                            return e.checked === !0
                        },
                        selected: function(e) {
                            e.parentNode && e.parentNode.selectedIndex;
                            return e.selected === !0
                        },
                        parent: function(e) {
                            return !!e.firstChild
                        },
                        empty: function(e) {
                            return !e.firstChild
                        },
                        has: function(e, t, n) {
                            return !!l(n[3], e).length
                        },
                        header: function(e) {
                            return /h\d/i.test(e.nodeName)
                        },
                        text: function(e) {
                            var t = e.getAttribute("type"),
                                n = e.type;
                            return e.nodeName.toLowerCase() === "input" && "text" === n && (t === n || t === null)
                        },
                        radio: function(e) {
                            return e.nodeName.toLowerCase() === "input" && "radio" === e.type
                        },
                        checkbox: function(e) {
                            return e.nodeName.toLowerCase() === "input" && "checkbox" === e.type
                        },
                        file: function(e) {
                            return e.nodeName.toLowerCase() === "input" && "file" === e.type
                        },
                        password: function(e) {
                            return e.nodeName.toLowerCase() === "input" && "password" === e.type
                        },
                        submit: function(e) {
                            var t = e.nodeName.toLowerCase();
                            return (t === "input" || t === "button") && "submit" === e.type
                        },
                        image: function(e) {
                            return e.nodeName.toLowerCase() === "input" && "image" === e.type
                        },
                        reset: function(e) {
                            var t = e.nodeName.toLowerCase();
                            return (t === "input" || t === "button") && "reset" === e.type
                        },
                        button: function(e) {
                            var t = e.nodeName.toLowerCase();
                            return t === "input" && "button" === e.type || t === "button"
                        },
                        input: function(e) {
                            return /input|select|textarea|button/i.test(e.nodeName)
                        },
                        focus: function(e) {
                            return e === e.ownerDocument.activeElement
                        }
                    },
                    setFilters: {
                        first: function(e, t) {
                            return t === 0
                        },
                        last: function(e, t, n, r) {
                            return t === r.length - 1
                        },
                        even: function(e, t) {
                            return t % 2 === 0
                        },
                        odd: function(e, t) {
                            return t % 2 === 1
                        },
                        lt: function(e, t, n) {
                            return t < n[3] - 0
                        },
                        gt: function(e, t, n) {
                            return t > n[3] - 0
                        },
                        nth: function(e, t, n) {
                            return n[3] - 0 === t
                        },
                        eq: function(e, t, n) {
                            return n[3] - 0 === t
                        }
                    },
                    filter: {
                        PSEUDO: function(e, t, n, r) {
                            var i = t[1],
                                s = c.filters[i];
                            if (s) return s(e, n, t, r);
                            if (i === "contains") return (e.textContent || e.innerText || l.getText([e]) || "").indexOf(t[3]) >= 0;
                            if (i === "not") {
                                var o = t[3];
                                for (var u = 0, a = o.length; u < a; u++)
                                    if (o[u] === e) return !1;
                                return !0
                            }
                            l.error(i)
                        },
                        CHILD: function(e, t) {
                            var n = t[1],
                                r = e;
                            switch (n) {
                                case "only":
                                case "first":
                                    while (r = r.previousSibling)
                                        if (r.nodeType === 1) return !1;
                                    if (n === "first") return !0;
                                    r = e;
                                case "last":
                                    while (r = r.nextSibling)
                                        if (r.nodeType === 1) return !1;
                                    return !0;
                                case "nth":
                                    var i = t[2],
                                        s = t[3];
                                    if (i === 1 && s === 0) return !0;
                                    var o = t[0],
                                        u = e.parentNode;
                                    if (u && (u.sizcache !== o || !e.nodeIndex)) {
                                        var a = 0;
                                        for (r = u.firstChild; r; r = r.nextSibling) r.nodeType === 1 && (r.nodeIndex = ++a);
                                        u.sizcache = o
                                    }
                                    var f = e.nodeIndex - s;
                                    return i === 0 ? f === 0 : f % i === 0 && f / i >= 0
                            }
                        },
                        ID: function(e, t) {
                            return e.nodeType === 1 && e.getAttribute("id") === t
                        },
                        TAG: function(e, t) {
                            return t === "*" && e.nodeType === 1 || e.nodeName.toLowerCase() === t
                        },
                        CLASS: function(e, t) {
                            return (" " + (e.className || e.getAttribute("class")) + " ").indexOf(t) > -1
                        },
                        ATTR: function(e, t) {
                            var n = t[1],
                                r = c.attrHandle[n] ? c.attrHandle[n](e) : e[n] != null ? e[n] : e.getAttribute(n),
                                i = r + "",
                                s = t[2],
                                o = t[4];
                            return r == null ? s === "!=" : s === "=" ? i === o : s === "*=" ? i.indexOf(o) >= 0 : s === "~=" ? (" " + i + " ").indexOf(o) >= 0 : o ? s === "!=" ? i !== o : s === "^=" ? i.indexOf(o) === 0 : s === "$=" ? i.substr(i.length - o.length) === o : s === "|=" ? i === o || i.substr(0, o.length + 1) === o + "-" : !1 : i && r !== !1
                        },
                        POS: function(e, t, n, r) {
                            var i = t[2],
                                s = c.setFilters[i];
                            if (s) return s(e, n, t, r)
                        }
                    }
                },
                h = c.match.POS,
                p = function(e, t) {
                    return "\\" + (t - 0 + 1)
                };
            for (var d in c.match) c.match[d] = new RegExp(c.match[d].source + /(?![^\[]*\])(?![^\(]*\))/.source), c.leftMatch[d] = new RegExp(/(^(?:.|\r|\n)*?)/.source + c.match[d].source.replace(/\\(\d+)/g, p));
            var v = function(e, t) {
                e = Array.prototype.slice.call(e, 0);
                if (t) {
                    t.push.apply(t, e);
                    return t
                }
                return e
            };
            try {
                Array.prototype.slice.call(_.documentElement.childNodes, 0)[0].nodeType
            } catch (m) {
                v = function(e, t) {
                    var n = 0,
                        r = t || [];
                    if (s.call(e) === "[object Array]") Array.prototype.push.apply(r, e);
                    else if (typeof e.length == "number")
                        for (var i = e.length; n < i; n++) r.push(e[n]);
                    else
                        for (; e[n]; n++) r.push(e[n]);
                    return r
                }
            }
            var g, y;
            _.documentElement.compareDocumentPosition ? g = function(e, t) {
                    if (e === t) {
                        o = !0;
                        return 0
                    }
                    if (!e.compareDocumentPosition || !t.compareDocumentPosition) return e.compareDocumentPosition ? -1 : 1;
                    return e.compareDocumentPosition(t) & 4 ? -1 : 1
                } : (g = function(e, t) {
                    if (e === t) {
                        o = !0;
                        return 0
                    }
                    if (e.sourceIndex && t.sourceIndex) return e.sourceIndex - t.sourceIndex;
                    var n, r, i = [],
                        s = [],
                        u = e.parentNode,
                        a = t.parentNode,
                        f = u;
                    if (u === a) return y(e, t);
                    if (!u) return -1;
                    if (!a) return 1;
                    while (f) i.unshift(f), f = f.parentNode;
                    f = a;
                    while (f) s.unshift(f), f = f.parentNode;
                    n = i.length, r = s.length;
                    for (var l = 0; l < n && l < r; l++)
                        if (i[l] !== s[l]) return y(i[l], s[l]);
                    return l === n ? y(e, s[l], -1) : y(i[l], t, 1)
                }, y = function(e, t, n) {
                    if (e === t) return n;
                    var r = e.nextSibling;
                    while (r) {
                        if (r === t) return -1;
                        r = r.nextSibling
                    }
                    return 1
                }), l.getText = function(e) {
                    var t = "",
                        n;
                    for (var r = 0; e[r]; r++) n = e[r], n.nodeType === 3 || n.nodeType === 4 ? t += n.nodeValue : n.nodeType !== 8 && (t += l.getText(n.childNodes));
                    return t
                },
                function() {
                    var e = _.createElement("div"),
                        n = "script" + (new Date).getTime(),
                        r = _.documentElement;
                    e.innerHTML = "<a name='" + n + "'/>", r.insertBefore(e, r.firstChild), _.getElementById(n) && (c.find.ID = function(e, n, r) {
                        if (typeof n.getElementById != "undefined" && !r) {
                            var i = n.getElementById(e[1]);
                            return i ? i.id === e[1] || typeof i.getAttributeNode != "undefined" && i.getAttributeNode("id").nodeValue === e[1] ? [i] : t : []
                        }
                    }, c.filter.ID = function(e, t) {
                        var n = typeof e.getAttributeNode != "undefined" && e.getAttributeNode("id");
                        return e.nodeType === 1 && n && n.nodeValue === t
                    }), r.removeChild(e), r = e = null
                }(),
                function() {
                    var e = _.createElement("div");
                    e.appendChild(_.createComment("")), e.getElementsByTagName("*").length > 0 && (c.find.TAG = function(e, t) {
                        var n = t.getElementsByTagName(e[1]);
                        if (e[1] === "*") {
                            var r = [];
                            for (var i = 0; n[i]; i++) n[i].nodeType === 1 && r.push(n[i]);
                            n = r
                        }
                        return n
                    }), e.innerHTML = "<a href='#'></a>", e.firstChild && typeof e.firstChild.getAttribute != "undefined" && e.firstChild.getAttribute("href") !== "#" && (c.attrHandle.href = function(e) {
                        return e.getAttribute("href", 2)
                    }), e = null
                }(), _.querySelectorAll && function() {
                    var e = l,
                        t = _.createElement("div"),
                        n = "__sizzle__";
                    t.innerHTML = "<p class='TEST'></p>";
                    if (!t.querySelectorAll || t.querySelectorAll(".TEST").length !== 0) {
                        l = function(t, r, i, s) {
                            r = r || _;
                            if (!s && !l.isXML(r)) {
                                var o = /^(\w+$)|^\.([\w\-]+$)|^#([\w\-]+$)/.exec(t);
                                if (o && (r.nodeType === 1 || r.nodeType === 9)) {
                                    if (o[1]) return v(r.getElementsByTagName(t), i);
                                    if (o[2] && c.find.CLASS && r.getElementsByClassName) return v(r.getElementsByClassName(o[2]), i)
                                }
                                if (r.nodeType === 9) {
                                    if (t === "body" && r.body) return v([r.body], i);
                                    if (o && o[3]) {
                                        var u = r.getElementById(o[3]);
                                        if (!u || !u.parentNode) return v([], i);
                                        if (u.id === o[3]) return v([u], i)
                                    }
                                    try {
                                        return v(r.querySelectorAll(t), i)
                                    } catch (a) {}
                                } else if (r.nodeType === 1 && r.nodeName.toLowerCase() !== "object") {
                                    var f = r,
                                        h = r.getAttribute("id"),
                                        p = h || n,
                                        d = r.parentNode,
                                        m = /^\s*[+~]/.test(t);
                                    h ? p = p.replace(/'/g, "\\$&") : r.setAttribute("id", p), m && d && (r = r.parentNode);
                                    try {
                                        if (!m || d) return v(r.querySelectorAll("[id='" + p + "'] " + t), i)
                                    } catch (g) {} finally {
                                        h || f.removeAttribute("id")
                                    }
                                }
                            }
                            return e(t, r, i, s)
                        };
                        for (var r in e) l[r] = e[r];
                        t = null
                    }
                }(),
                function() {
                    var e = _.documentElement,
                        t = e.matchesSelector || e.mozMatchesSelector || e.webkitMatchesSelector || e.msMatchesSelector;
                    if (t) {
                        var n = !t.call(_.createElement("div"), "div"),
                            r = !1;
                        try {
                            t.call(_.documentElement, "[test!='']:sizzle")
                        } catch (i) {
                            r = !0
                        }
                        l.matchesSelector = function(e, i) {
                            i = i.replace(/\=\s*([^'"\]]*)\s*\]/g, "='$1']");
                            if (!l.isXML(e)) try {
                                if (r || !c.match.PSEUDO.test(i) && !/!=/.test(i)) {
                                    var s = t.call(e, i);
                                    if (s || !n || e.document && e.document.nodeType !== 11) return s
                                }
                            } catch (o) {}
                            return l(i, null, null, [e]).length > 0
                        }
                    }
                }(),
                function() {
                    var e = _.createElement("div");
                    e.innerHTML = "<div class='test e'></div><div class='test'></div>";
                    if (!!e.getElementsByClassName && e.getElementsByClassName("e").length !== 0) {
                        e.lastChild.className = "e";
                        if (e.getElementsByClassName("e").length === 1) return;
                        c.order.splice(1, 0, "CLASS"), c.find.CLASS = function(e, t, n) {
                            if (typeof t.getElementsByClassName != "undefined" && !n) return t.getElementsByClassName(e[1])
                        }, e = null
                    }
                }(), _.documentElement.contains ? l.contains = function(e, t) {
                    return e !== t && (e.contains ? e.contains(t) : !0)
                } : _.documentElement.compareDocumentPosition ? l.contains = function(e, t) {
                    return !!(e.compareDocumentPosition(t) & 16)
                } : l.contains = function() {
                    return !1
                }, l.isXML = function(e) {
                    var t = (e ? e.ownerDocument || e : 0).documentElement;
                    return t ? t.nodeName !== "HTML" : !1
                };
            var b = function(e, t) {
                var n, r = [],
                    i = "",
                    s = t.nodeType ? [t] : t;
                while (n = c.match.PSEUDO.exec(e)) i += n[0], e = e.replace(c.match.PSEUDO, "");
                e = c.relative[e] ? e + "*" : e;
                for (var o = 0, u = s.length; o < u; o++) l(e, s[o], r);
                return l.filter(i, r)
            };
            H.find = l, H.expr = l.selectors, H.expr[":"] = H.expr.filters, H.unique = l.uniqueSort, H.text = l.getText, H.isXMLDoc = l.isXML, H.contains = l.contains
        }();
    var at = /Until$/,
        ft = /^(?:parents|prevUntil|prevAll)/,
        lt = /,/,
        ct = /^.[^:#\[\.,]*$/,
        ht = Array.prototype.slice,
        pt = H.expr.match.POS,
        dt = {
            children: !0,
            contents: !0,
            next: !0,
            prev: !0
        };
    H.fn.extend({
        find: function(e) {
            var t = this,
                n, r;
            if (typeof e != "string") return H(e).filter(function() {
                for (n = 0, r = t.length; n < r; n++)
                    if (H.contains(t[n], this)) return !0
            });
            var i = this.pushStack("", "find", e),
                s, o, u;
            for (n = 0, r = this.length; n < r; n++) {
                s = i.length, H.find(e, this[n], i);
                if (n > 0)
                    for (o = s; o < i.length; o++)
                        for (u = 0; u < s; u++)
                            if (i[u] === i[o]) {
                                i.splice(o--, 1);
                                break
                            }
            }
            return i
        },
        has: function(e) {
            var t = H(e);
            return this.filter(function() {
                for (var e = 0, n = t.length; e < n; e++)
                    if (H.contains(this, t[e])) return !0
            })
        },
        not: function(e) {
            return this.pushStack(S(this, e, !1), "not", e)
        },
        filter: function(e) {
            return this.pushStack(S(this, e, !0), "filter", e)
        },
        is: function(e) {
            return !!e && (typeof e == "string" ? H.filter(e, this).length > 0 : this.filter(e).length > 0)
        },
        closest: function(e, t) {
            var n = [],
                r, i, s = this[0];
            if (H.isArray(e)) {
                var o, u, a = {},
                    f = 1;
                if (s && e.length) {
                    for (r = 0, i = e.length; r < i; r++) u = e[r], a[u] || (a[u] = pt.test(u) ? H(u, t || this.context) : u);
                    while (s && s.ownerDocument && s !== t) {
                        for (u in a) o = a[u], (o.jquery ? o.index(s) > -1 : H(s).is(o)) && n.push({
                            selector: u,
                            elem: s,
                            level: f
                        });
                        s = s.parentNode, f++
                    }
                }
                return n
            }
            var l = pt.test(e) || typeof e != "string" ? H(e, t || this.context) : 0;
            for (r = 0, i = this.length; r < i; r++) {
                s = this[r];
                while (s) {
                    if (l ? l.index(s) > -1 : H.find.matchesSelector(s, e)) {
                        n.push(s);
                        break
                    }
                    s = s.parentNode;
                    if (!s || !s.ownerDocument || s === t || s.nodeType === 11) break
                }
            }
            n = n.length > 1 ? H.unique(n) : n;
            return this.pushStack(n, "closest", e)
        },
        index: function(e) {
            if (!e || typeof e == "string") return H.inArray(this[0], e ? H(e) : this.parent().children());
            return H.inArray(e.jquery ? e[0] : e, this)
        },
        add: function(e, t) {
            var n = typeof e == "string" ? H(e, t) : H.makeArray(e && e.nodeType ? [e] : e),
                r = H.merge(this.get(), n);
            return this.pushStack(x(n[0]) || x(r[0]) ? r : H.unique(r))
        },
        andSelf: function() {
            return this.add(this.prevObject)
        }
    }), H.each({
        parent: function(e) {
            var t = e.parentNode;
            return t && t.nodeType !== 11 ? t : null
        },
        parents: function(e) {
            return H.dir(e, "parentNode")
        },
        parentsUntil: function(e, t, n) {
            return H.dir(e, "parentNode", n)
        },
        next: function(e) {
            return H.nth(e, 2, "nextSibling")
        },
        prev: function(e) {
            return H.nth(e, 2, "previousSibling")
        },
        nextAll: function(e) {
            return H.dir(e, "nextSibling")
        },
        prevAll: function(e) {
            return H.dir(e, "previousSibling")
        },
        nextUntil: function(e, t, n) {
            return H.dir(e, "nextSibling", n)
        },
        prevUntil: function(e, t, n) {
            return H.dir(e, "previousSibling", n)
        },
        siblings: function(e) {
            return H.sibling(e.parentNode.firstChild, e)
        },
        children: function(e) {
            return H.sibling(e.firstChild)
        },
        contents: function(e) {
            return H.nodeName(e, "iframe") ? e.contentDocument || e.contentWindow.document : H.makeArray(e.childNodes)
        }
    }, function(e, t) {
        H.fn[e] = function(n, r) {
            var i = H.map(this, t, n),
                s = ht.call(arguments);
            at.test(e) || (r = n), r && typeof r == "string" && (i = H.filter(r, i)), i = this.length > 1 && !dt[e] ? H.unique(i) : i, (this.length > 1 || lt.test(r)) && ft.test(e) && (i = i.reverse());
            return this.pushStack(i, e, s.join(","))
        }
    }), H.extend({
        filter: function(e, t, n) {
            n && (e = ":not(" + e + ")");
            return t.length === 1 ? H.find.matchesSelector(t[0], e) ? [t[0]] : [] : H.find.matches(e, t)
        },
        dir: function(e, n, r) {
            var i = [],
                s = e[n];
            while (s && s.nodeType !== 9 && (r === t || s.nodeType !== 1 || !H(s).is(r))) s.nodeType === 1 && i.push(s), s = s[n];
            return i
        },
        nth: function(e, t, n, r) {
            t = t || 1;
            var i = 0;
            for (; e; e = e[n])
                if (e.nodeType === 1 && ++i === t) break;
            return e
        },
        sibling: function(e, t) {
            var n = [];
            for (; e; e = e.nextSibling) e.nodeType === 1 && e !== t && n.push(e);
            return n
        }
    });
    var vt = / jQuery\d+="(?:\d+|null)"/g,
        mt = /^\s+/,
        gt = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/ig,
        yt = /<([\w:]+)/,
        bt = /<tbody/i,
        wt = /<|&#?\w+;/,
        Et = /<(?:script|object|embed|option|style)/i,
        St = /checked\s*(?:[^=]|=\s*.checked.)/i,
        xt = /\/(java|ecma)script/i,
        Tt = /^\s*<!(?:\[CDATA\[|\-\-)/,
        Nt = {
            option: [1, "<select multiple='multiple'>", "</select>"],
            legend: [1, "<fieldset>", "</fieldset>"],
            thead: [1, "<table>", "</table>"],
            tr: [2, "<table><tbody>", "</tbody></table>"],
            td: [3, "<table><tbody><tr>", "</tr></tbody></table>"],
            col: [2, "<table><tbody></tbody><colgroup>", "</colgroup></table>"],
            area: [1, "<map>", "</map>"],
            _default: [0, "", ""]
        };
    Nt.optgroup = Nt.option, Nt.tbody = Nt.tfoot = Nt.colgroup = Nt.caption = Nt.thead, Nt.th = Nt.td, H.support.htmlSerialize || (Nt._default = [1, "div<div>", "</div>"]), H.fn.extend({
        text: function(e) {
            if (H.isFunction(e)) return this.each(function(t) {
                var n = H(this);
                n.text(e.call(this, t, n.text()))
            });
            if (typeof e != "object" && e !== t) return this.empty().append((this[0] && this[0].ownerDocument || _).createTextNode(e));
            return H.text(this)
        },
        wrapAll: function(e) {
            if (H.isFunction(e)) return this.each(function(t) {
                H(this).wrapAll(e.call(this, t))
            });
            if (this[0]) {
                var t = H(e, this[0].ownerDocument).eq(0).clone(!0);
                this[0].parentNode && t.insertBefore(this[0]), t.map(function() {
                    var e = this;
                    while (e.firstChild && e.firstChild.nodeType === 1) e = e.firstChild;
                    return e
                }).append(this)
            }
            return this
        },
        wrapInner: function(e) {
            if (H.isFunction(e)) return this.each(function(t) {
                H(this).wrapInner(e.call(this, t))
            });
            return this.each(function() {
                var t = H(this),
                    n = t.contents();
                n.length ? n.wrapAll(e) : t.append(e)
            })
        },
        wrap: function(e) {
            return this.each(function() {
                H(this).wrapAll(e)
            })
        },
        unwrap: function() {
            return this.parent().each(function() {
                H.nodeName(this, "body") || H(this).replaceWith(this.childNodes)
            }).end()
        },
        append: function() {
            return this.domManip(arguments, !0, function(e) {
                this.nodeType === 1 && this.appendChild(e)
            })
        },
        prepend: function() {
            return this.domManip(arguments, !0, function(e) {
                this.nodeType === 1 && this.insertBefore(e, this.firstChild)
            })
        },
        before: function() {
            if (this[0] && this[0].parentNode) return this.domManip(arguments, !1, function(e) {
                this.parentNode.insertBefore(e, this)
            });
            if (arguments.length) {
                var e = H(arguments[0]);
                e.push.apply(e, this.toArray());
                return this.pushStack(e, "before", arguments)
            }
        },
        after: function() {
            if (this[0] && this[0].parentNode) return this.domManip(arguments, !1, function(e) {
                this.parentNode.insertBefore(e, this.nextSibling)
            });
            if (arguments.length) {
                var e = this.pushStack(this, "after", arguments);
                e.push.apply(e, H(arguments[0]).toArray());
                return e
            }
        },
        remove: function(e, t) {
            for (var n = 0, r;
                (r = this[n]) != null; n++)
                if (!e || H.filter(e, [r]).length) !t && r.nodeType === 1 && (H.cleanData(r.getElementsByTagName("*")), H.cleanData([r])), r.parentNode && r.parentNode.removeChild(r);
            return this
        },
        empty: function() {
            for (var e = 0, t;
                (t = this[e]) != null; e++) {
                t.nodeType === 1 && H.cleanData(t.getElementsByTagName("*"));
                while (t.firstChild) t.removeChild(t.firstChild)
            }
            return this
        },
        clone: function(e, t) {
            e = e == null ? !1 : e, t = t == null ? e : t;
            return this.map(function() {
                return H.clone(this, e, t)
            })
        },
        html: function(e) {
            if (e === t) return this[0] && this[0].nodeType === 1 ? this[0].innerHTML.replace(vt, "") : null;
            if (typeof e == "string" && !Et.test(e) && (H.support.leadingWhitespace || !mt.test(e)) && !Nt[(yt.exec(e) || ["", ""])[1].toLowerCase()]) {
                e = e.replace(gt, "<$1></$2>");
                try {
                    for (var n = 0, r = this.length; n < r; n++) this[n].nodeType === 1 && (H.cleanData(this[n].getElementsByTagName("*")), this[n].innerHTML = e)
                } catch (i) {
                    this.empty().append(e)
                }
            } else H.isFunction(e) ? this.each(function(t) {
                var n = H(this);
                n.html(e.call(this, t, n.html()))
            }) : this.empty().append(e);
            return this
        },
        replaceWith: function(e) {
            if (this[0] && this[0].parentNode) {
                if (H.isFunction(e)) return this.each(function(t) {
                    var n = H(this),
                        r = n.html();
                    n.replaceWith(e.call(this, t, r))
                });
                typeof e != "string" && (e = H(e).detach());
                return this.each(function() {
                    var t = this.nextSibling,
                        n = this.parentNode;
                    H(this).remove(), t ? H(t).before(e) : H(n).append(e)
                })
            }
            return this.length ? this.pushStack(H(H.isFunction(e) ? e() : e), "replaceWith", e) : this
        },
        detach: function(e) {
            return this.remove(e, !0)
        },
        domManip: function(e, n, r) {
            var i, s, o, u, a = e[0],
                f = [];
            if (!H.support.checkClone && arguments.length === 3 && typeof a == "string" && St.test(a)) return this.each(function() {
                H(this).domManip(e, n, r, !0)
            });
            if (H.isFunction(a)) return this.each(function(i) {
                var s = H(this);
                e[0] = a.call(this, i, n ? s.html() : t), s.domManip(e, n, r)
            });
            if (this[0]) {
                u = a && a.parentNode, H.support.parentNode && u && u.nodeType === 11 && u.childNodes.length === this.length ? i = {
                    fragment: u
                } : i = H.buildFragment(e, this, f), o = i.fragment, o.childNodes.length === 1 ? s = o = o.firstChild : s = o.firstChild;
                if (s) {
                    n = n && H.nodeName(s, "tr");
                    for (var l = 0, c = this.length, h = c - 1; l < c; l++) r.call(n ? E(this[l], s) : this[l], i.cacheable || c > 1 && l < h ? H.clone(o, !0, !0) : o)
                }
                f.length && H.each(f, v)
            }
            return this
        }
    }), H.buildFragment = function(e, t, n) {
        var r, i, s, o;
        t && t[0] && (o = t[0].ownerDocument || t[0]), o.createDocumentFragment || (o = _), e.length === 1 && typeof e[0] == "string" && e[0].length < 512 && o === _ && e[0].charAt(0) === "<" && !Et.test(e[0]) && (H.support.checkClone || !St.test(e[0])) && (i = !0, s = H.fragments[e[0]], s && s !== 1 && (r = s)), r || (r = o.createDocumentFragment(), H.clean(e, o, r, n)), i && (H.fragments[e[0]] = s ? r : 1);
        return {
            fragment: r,
            cacheable: i
        }
    }, H.fragments = {}, H.each({
        appendTo: "append",
        prependTo: "prepend",
        insertBefore: "before",
        insertAfter: "after",
        replaceAll: "replaceWith"
    }, function(e, t) {
        H.fn[e] = function(n) {
            var r = [],
                i = H(n),
                s = this.length === 1 && this[0].parentNode;
            if (s && s.nodeType === 11 && s.childNodes.length === 1 && i.length === 1) {
                i[t](this[0]);
                return this
            }
            for (var o = 0, u = i.length; o < u; o++) {
                var a = (o > 0 ? this.clone(!0) : this).get();
                H(i[o])[t](a), r = r.concat(a)
            }
            return this.pushStack(r, e, i.selector)
        }
    }), H.extend({
        clone: function(e, t, n) {
            var r = e.cloneNode(!0),
                i, s, o;
            if ((!H.support.noCloneEvent || !H.support.noCloneChecked) && (e.nodeType === 1 || e.nodeType === 11) && !H.isXMLDoc(e)) {
                b(e, r), i = y(e), s = y(r);
                for (o = 0; i[o]; ++o) b(i[o], s[o])
            }
            if (t) {
                w(e, r);
                if (n) {
                    i = y(e), s = y(r);
                    for (o = 0; i[o]; ++o) w(i[o], s[o])
                }
            }
            i = s = null;
            return r
        },
        clean: function(e, t, n, r) {
            var i;
            t = t || _, typeof t.createElement == "undefined" && (t = t.ownerDocument || t[0] && t[0].ownerDocument || _);
            var s = [],
                o;
            for (var u = 0, a;
                (a = e[u]) != null; u++) {
                typeof a == "number" && (a += "");
                if (!a) continue;
                if (typeof a == "string")
                    if (!wt.test(a)) a = t.createTextNode(a);
                    else {
                        a = a.replace(gt, "<$1></$2>");
                        var f = (yt.exec(a) || ["", ""])[1].toLowerCase(),
                            l = Nt[f] || Nt._default,
                            c = l[0],
                            h = t.createElement("div");
                        h.innerHTML = l[1] + a + l[2];
                        while (c--) h = h.lastChild;
                        if (!H.support.tbody) {
                            var p = bt.test(a),
                                d = f === "table" && !p ? h.firstChild && h.firstChild.childNodes : l[1] === "<table>" && !p ? h.childNodes : [];
                            for (o = d.length - 1; o >= 0; --o) H.nodeName(d[o], "tbody") && !d[o].childNodes.length && d[o].parentNode.removeChild(d[o])
                        }!H.support.leadingWhitespace && mt.test(a) && h.insertBefore(t.createTextNode(mt.exec(a)[0]), h.firstChild), a = h.childNodes
                    }
                var v;
                if (!H.support.appendChecked)
                    if (a[0] && typeof(v = a.length) == "number")
                        for (o = 0; o < v; o++) m(a[o]);
                    else m(a);
                a.nodeType ? s.push(a) : s = H.merge(s, a)
            }
            if (n) {
                i = function(e) {
                    return !e.type || xt.test(e.type)
                };
                for (u = 0; s[u]; u++)
                    if (r && H.nodeName(s[u], "script") && (!s[u].type || s[u].type.toLowerCase() === "text/javascript")) r.push(s[u].parentNode ? s[u].parentNode.removeChild(s[u]) : s[u]);
                    else {
                        if (s[u].nodeType === 1) {
                            var g = H.grep(s[u].getElementsByTagName("script"), i);
                            s.splice.apply(s, [u + 1, 0].concat(g))
                        }
                        n.appendChild(s[u])
                    }
            }
            return s
        },
        cleanData: function(e) {
            var t, n, r = H.cache,
                i = H.expando,
                s = H.event.special,
                o = H.support.deleteExpando;
            for (var u = 0, a;
                (a = e[u]) != null; u++) {
                if (a.nodeName && H.noData[a.nodeName.toLowerCase()]) continue;
                n = a[H.expando];
                if (n) {
                    t = r[n] && r[n][i];
                    if (t && t.events) {
                        for (var f in t.events) s[f] ? H.event.remove(a, f) : H.removeEvent(a, f, t.handle);
                        t.handle && (t.handle.elem = null)
                    }
                    o ? delete a[H.expando] : a.removeAttribute && a.removeAttribute(H.expando), delete r[n]
                }
            }
        }
    });
    var Ct = /alpha\([^)]*\)/i,
        kt = /opacity=([^)]*)/,
        Lt = /([A-Z]|^ms)/g,
        At = /^-?\d+(?:px)?$/i,
        Ot = /^-?\d/,
        Mt = /^[+\-]=/,
        _t = /[^+\-\.\de]+/g,
        Dt = {
            position: "absolute",
            visibility: "hidden",
            display: "block"
        },
        Pt = ["Left", "Right"],
        Ht = ["Top", "Bottom"],
        Bt, jt, Ft;
    H.fn.css = function(e, n) {
        if (arguments.length === 2 && n === t) return this;
        return H.access(this, e, n, !0, function(e, n, r) {
            return r !== t ? H.style(e, n, r) : H.css(e, n)
        })
    }, H.extend({
        cssHooks: {
            opacity: {
                get: function(e, t) {
                    if (t) {
                        var n = Bt(e, "opacity", "opacity");
                        return n === "" ? "1" : n
                    }
                    return e.style.opacity
                }
            }
        },
        cssNumber: {
            fillOpacity: !0,
            fontWeight: !0,
            lineHeight: !0,
            opacity: !0,
            orphans: !0,
            widows: !0,
            zIndex: !0,
            zoom: !0
        },
        cssProps: {
            "float": H.support.cssFloat ? "cssFloat" : "styleFloat"
        },
        style: function(e, n, r, i) {
            if (!!e && e.nodeType !== 3 && e.nodeType !== 8 && !!e.style) {
                var s, o, u = H.camelCase(n),
                    a = e.style,
                    f = H.cssHooks[u];
                n = H.cssProps[u] || u;
                if (r === t) {
                    if (f && "get" in f && (s = f.get(e, !1, i)) !== t) return s;
                    return a[n]
                }
                o = typeof r;
                if (o === "number" && isNaN(r) || r == null) return;
                o === "string" && Mt.test(r) && (r = +r.replace(_t, "") + parseFloat(H.css(e, n)), o = "number"), o === "number" && !H.cssNumber[u] && (r += "px");
                if (!f || !("set" in f) || (r = f.set(e, r)) !== t) try {
                    a[n] = r
                } catch (l) {}
            }
        },
        css: function(e, n, r) {
            var i, s;
            n = H.camelCase(n), s = H.cssHooks[n], n = H.cssProps[n] || n, n === "cssFloat" && (n = "float");
            if (s && "get" in s && (i = s.get(e, !0, r)) !== t) return i;
            if (Bt) return Bt(e, n)
        },
        swap: function(e, t, n) {
            var r = {};
            for (var i in t) r[i] = e.style[i], e.style[i] = t[i];
            n.call(e);
            for (i in t) e.style[i] = r[i]
        }
    }), H.curCSS = H.css, H.each(["height", "width"], function(e, t) {
        H.cssHooks[t] = {
            get: function(e, n, r) {
                var i;
                if (n) {
                    if (e.offsetWidth !== 0) return d(e, t, r);
                    H.swap(e, Dt, function() {
                        i = d(e, t, r)
                    });
                    return i
                }
            },
            set: function(e, t) {
                if (!At.test(t)) return t;
                t = parseFloat(t);
                if (t >= 0) return t + "px"
            }
        }
    }), H.support.opacity || (H.cssHooks.opacity = {
        get: function(e, t) {
            return kt.test((t && e.currentStyle ? e.currentStyle.filter : e.style.filter) || "") ? parseFloat(RegExp.$1) / 100 + "" : t ? "1" : ""
        },
        set: function(e, t) {
            var n = e.style,
                r = e.currentStyle;
            n.zoom = 1;
            var i = H.isNaN(t) ? "" : "alpha(opacity=" + t * 100 + ")",
                s = r && r.filter || n.filter || "";
            n.filter = Ct.test(s) ? s.replace(Ct, i) : s + " " + i
        }
    }), H(function() {
        H.support.reliableMarginRight || (H.cssHooks.marginRight = {
            get: function(e, t) {
                var n;
                H.swap(e, {
                    display: "inline-block"
                }, function() {
                    t ? n = Bt(e, "margin-right", "marginRight") : n = e.style.marginRight
                });
                return n
            }
        })
    }), _.defaultView && _.defaultView.getComputedStyle && (jt = function(e, n) {
        var r, i, s;
        n = n.replace(Lt, "-$1").toLowerCase();
        if (!(i = e.ownerDocument.defaultView)) return t;
        if (s = i.getComputedStyle(e, null)) r = s.getPropertyValue(n), r === "" && !H.contains(e.ownerDocument.documentElement, e) && (r = H.style(e, n));
        return r
    }), _.documentElement.currentStyle && (Ft = function(e, t) {
        var n, r = e.currentStyle && e.currentStyle[t],
            i = e.runtimeStyle && e.runtimeStyle[t],
            s = e.style;
        !At.test(r) && Ot.test(r) && (n = s.left, i && (e.runtimeStyle.left = e.currentStyle.left), s.left = t === "fontSize" ? "1em" : r || 0, r = s.pixelLeft + "px", s.left = n, i && (e.runtimeStyle.left = i));
        return r === "" ? "auto" : r
    }), Bt = jt || Ft, H.expr && H.expr.filters && (H.expr.filters.hidden = function(e) {
        var t = e.offsetWidth,
            n = e.offsetHeight;
        return t === 0 && n === 0 || !H.support.reliableHiddenOffsets && (e.style.display || H.css(e, "display")) === "none"
    }, H.expr.filters.visible = function(e) {
        return !H.expr.filters.hidden(e)
    });
    var It = /%20/g,
        qt = /\[\]$/,
        Rt = /\r?\n/g,
        Ut = /#.*$/,
        zt = /^(.*?):[ \t]*([^\r\n]*)\r?$/mg,
        Wt = /^(?:color|date|datetime|email|hidden|month|number|password|range|search|tel|text|time|url|week)$/i,
        Xt = /^(?:about|app|app\-storage|.+\-extension|file|widget):$/,
        Vt = /^(?:GET|HEAD)$/,
        $t = /^\/\//,
        Jt = /\?/,
        Kt = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,
        Qt = /^(?:select|textarea)/i,
        Gt = /\s+/,
        Yt = /([?&])_=[^&]*/,
        Zt = /^([\w\+\.\-]+:)(?:\/\/([^\/?#:]*)(?::(\d+))?)?/,
        en = H.fn.load,
        tn = {},
        nn = {},
        rn, sn;
    try {
        rn = P.href
    } catch (on) {
        rn = _.createElement("a"), rn.href = "", rn = rn.href
    }
    sn = Zt.exec(rn.toLowerCase()) || [], H.fn.extend({
        load: function(e, n, r) {
            if (typeof e != "string" && en) return en.apply(this, arguments);
            if (!this.length) return this;
            var i = e.indexOf(" ");
            if (i >= 0) {
                var s = e.slice(i, e.length);
                e = e.slice(0, i)
            }
            var o = "GET";
            n && (H.isFunction(n) ? (r = n, n = t) : typeof n == "object" && (n = H.param(n, H.ajaxSettings.traditional), o = "POST"));
            var u = this;
            H.ajax({
                url: e,
                type: o,
                dataType: "html",
                data: n,
                complete: function(e, t, n) {
                    n = e.responseText, e.isResolved() && (e.done(function(e) {
                        n = e
                    }), u.html(s ? H("<div>").append(n.replace(Kt, "")).find(s) : n)), r && u.each(r, [n, t, e])
                }
            });
            return this
        },
        serialize: function() {
            return H.param(this.serializeArray())
        },
        serializeArray: function() {
            return this.map(function() {
                return this.elements ? H.makeArray(this.elements) : this
            }).filter(function() {
                return this.name && !this.disabled && (this.checked || Qt.test(this.nodeName) || Wt.test(this.type))
            }).map(function(e, t) {
                var n = H(this).val();
                return n == null ? null : H.isArray(n) ? H.map(n, function(e, n) {
                    return {
                        name: t.name,
                        value: e.replace(Rt, "\r\n")
                    }
                }) : {
                    name: t.name,
                    value: n.replace(Rt, "\r\n")
                }
            }).get()
        }
    }), H.each("ajaxStart ajaxStop ajaxComplete ajaxError ajaxSuccess ajaxSend".split(" "), function(e, t) {
        H.fn[t] = function(e) {
            return this.bind(t, e)
        }
    }), H.each(["get", "post"], function(e, n) {
        H[n] = function(e, r, i, s) {
            H.isFunction(r) && (s = s || i, i = r, r = t);
            return H.ajax({
                type: n,
                url: e,
                data: r,
                success: i,
                dataType: s
            })
        }
    }), H.extend({
        getScript: function(e, n) {
            return H.get(e, t, n, "script")
        },
        getJSON: function(e, t, n) {
            return H.get(e, t, n, "json")
        },
        ajaxSetup: function(e, t) {
            t ? H.extend(!0, e, H.ajaxSettings, t) : (t = e, e = H.extend(!0, H.ajaxSettings, t));
            for (var n in {
                    context: 1,
                    url: 1
                }) n in t ? e[n] = t[n] : n in H.ajaxSettings && (e[n] = H.ajaxSettings[n]);
            return e
        },
        ajaxSettings: {
            url: rn,
            isLocal: Xt.test(sn[1]),
            global: !0,
            type: "GET",
            contentType: "application/x-www-form-urlencoded",
            processData: !0,
            async: !0,
            accepts: {
                xml: "application/xml, text/xml",
                html: "text/html",
                text: "text/plain",
                json: "application/json, text/javascript",
                "*": "*/*"
            },
            contents: {
                xml: /xml/,
                html: /html/,
                json: /json/
            },
            responseFields: {
                xml: "responseXML",
                text: "responseText"
            },
            converters: {
                "* text": e.String,
                "text html": !0,
                "text json": H.parseJSON,
                "text xml": H.parseXML
            }
        },
        ajaxPrefilter: p(tn),
        ajaxTransport: p(nn),
        ajax: function(e, n) {
            function r(e, n, r, h) {
                if (E !== 2) {
                    E = 2, b && clearTimeout(b), y = t, m = h || "", T.readyState = e ? 4 : 0;
                    var d, v, g, w = r ? l(i, T, r) : t,
                        x, N;
                    if (e >= 200 && e < 300 || e === 304) {
                        if (i.ifModified) {
                            if (x = T.getResponseHeader("Last-Modified")) H.lastModified[p] = x;
                            if (N = T.getResponseHeader("Etag")) H.etag[p] = N
                        }
                        if (e === 304) n = "notmodified", d = !0;
                        else try {
                            v = f(i, w), n = "success", d = !0
                        } catch (C) {
                            n = "parsererror", g = C
                        }
                    } else {
                        g = n;
                        if (!n || e) n = "error", e < 0 && (e = 0)
                    }
                    T.status = e, T.statusText = n, d ? u.resolveWith(s, [v, n, T]) : u.rejectWith(s, [T, n, g]), T.statusCode(c), c = t, S && o.trigger("ajax" + (d ? "Success" : "Error"), [T, i, d ? v : g]), a.resolveWith(s, [T, n]), S && (o.trigger("ajaxComplete", [T, i]), --H.active || H.event.trigger("ajaxStop"))
                }
            }
            typeof e == "object" && (n = e, e = t), n = n || {};
            var i = H.ajaxSetup({}, n),
                s = i.context || i,
                o = s !== i && (s.nodeType || s instanceof H) ? H(s) : H.event,
                u = H.Deferred(),
                a = H._Deferred(),
                c = i.statusCode || {},
                p, d = {},
                v = {},
                m, g, y, b, w, E = 0,
                S, x, T = {
                    readyState: 0,
                    setRequestHeader: function(e, t) {
                        if (!E) {
                            var n = e.toLowerCase();
                            e = v[n] = v[n] || e, d[e] = t
                        }
                        return this
                    },
                    getAllResponseHeaders: function() {
                        return E === 2 ? m : null
                    },
                    getResponseHeader: function(e) {
                        var n;
                        if (E === 2) {
                            if (!g) {
                                g = {};
                                while (n = zt.exec(m)) g[n[1].toLowerCase()] = n[2]
                            }
                            n = g[e.toLowerCase()]
                        }
                        return n === t ? null : n
                    },
                    overrideMimeType: function(e) {
                        E || (i.mimeType = e);
                        return this
                    },
                    abort: function(e) {
                        e = e || "abort", y && y.abort(e), r(0, e);
                        return this
                    }
                };
            u.promise(T), T.success = T.done, T.error = T.fail, T.complete = a.done, T.statusCode = function(e) {
                if (e) {
                    var t;
                    if (E < 2)
                        for (t in e) c[t] = [c[t], e[t]];
                    else t = e[T.status], T.then(t, t)
                }
                return this
            }, i.url = ((e || i.url) + "").replace(Ut, "").replace($t, sn[1] + "//"), i.dataTypes = H.trim(i.dataType || "*").toLowerCase().split(Gt), i.crossDomain == null && (w = Zt.exec(i.url.toLowerCase()), i.crossDomain = !(!w || w[1] == sn[1] && w[2] == sn[2] && (w[3] || (w[1] === "http:" ? 80 : 443)) == (sn[3] || (sn[1] === "http:" ? 80 : 443)))), i.data && i.processData && typeof i.data != "string" && (i.data = H.param(i.data, i.traditional)), h(tn, i, n, T);
            if (E === 2) return !1;
            S = i.global, i.type = i.type.toUpperCase(), i.hasContent = !Vt.test(i.type), S && H.active++ === 0 && H.event.trigger("ajaxStart");
            if (!i.hasContent) {
                i.data && (i.url += (Jt.test(i.url) ? "&" : "?") + i.data), p = i.url;
                if (i.cache === !1) {
                    var N = H.now(),
                        C = i.url.replace(Yt, "$1_=" + N);
                    i.url = C + (C === i.url ? (Jt.test(i.url) ? "&" : "?") + "_=" + N : "")
                }
            }(i.data && i.hasContent && i.contentType !== !1 || n.contentType) && T.setRequestHeader("Content-Type", i.contentType), i.ifModified && (p = p || i.url, H.lastModified[p] && T.setRequestHeader("If-Modified-Since", H.lastModified[p]), H.etag[p] && T.setRequestHeader("If-None-Match", H.etag[p])), T.setRequestHeader("Accept", i.dataTypes[0] && i.accepts[i.dataTypes[0]] ? i.accepts[i.dataTypes[0]] + (i.dataTypes[0] !== "*" ? ", */*; q=0.01" : "") : i.accepts["*"]);
            for (x in i.headers) T.setRequestHeader(x, i.headers[x]);
            if (i.beforeSend && (i.beforeSend.call(s, T, i) === !1 || E === 2)) {
                T.abort();
                return !1
            }
            for (x in {
                    success: 1,
                    error: 1,
                    complete: 1
                }) T[x](i[x]);
            y = h(nn, i, n, T);
            if (!y) r(-1, "No Transport");
            else {
                T.readyState = 1, S && o.trigger("ajaxSend", [T, i]), i.async && i.timeout > 0 && (b = setTimeout(function() {
                    T.abort("timeout")
                }, i.timeout));
                try {
                    E = 1, y.send(d, r)
                } catch (k) {
                    status < 2 ? r(-1, k) : H.error(k)
                }
            }
            return T
        },
        param: function(e, n) {
            var r = [],
                i = function(e, t) {
                    t = H.isFunction(t) ? t() : t, r[r.length] = encodeURIComponent(e) + "=" + encodeURIComponent(t)
                };
            n === t && (n = H.ajaxSettings.traditional);
            if (H.isArray(e) || e.jquery && !H.isPlainObject(e)) H.each(e, function() {
                i(this.name, this.value)
            });
            else
                for (var s in e) c(s, e[s], n, i);
            return r.join("&").replace(It, "+")
        }
    }), H.extend({
        active: 0,
        lastModified: {},
        etag: {}
    });
    var un = H.now(),
        an = /(\=)\?(&|$)|\?\?/i;
    H.ajaxSetup({
        jsonp: "callback",
        jsonpCallback: function() {
            return H.expando + "_" + un++
        }
    }), H.ajaxPrefilter("json jsonp", function(t, n, r) {
        var i = t.contentType === "application/x-www-form-urlencoded" && typeof t.data == "string";
        if (t.dataTypes[0] === "jsonp" || t.jsonp !== !1 && (an.test(t.url) || i && an.test(t.data))) {
            var s, o = t.jsonpCallback = H.isFunction(t.jsonpCallback) ? t.jsonpCallback() : t.jsonpCallback,
                u = e[o],
                a = t.url,
                f = t.data,
                l = "$1" + o + "$2";
            t.jsonp !== !1 && (a = a.replace(an, l), t.url === a && (i && (f = f.replace(an, l)), t.data === f && (a += (/\?/.test(a) ? "&" : "?") + t.jsonp + "=" + o))), t.url = a, t.data = f, e[o] = function(e) {
                s = [e]
            }, r.always(function() {
                e[o] = u, s && H.isFunction(u) && e[o](s[0])
            }), t.converters["script json"] = function() {
                s || H.error(o + " was not called");
                return s[0]
            }, t.dataTypes[0] = "json";
            return "script"
        }
    }), H.ajaxSetup({
        accepts: {
            script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"
        },
        contents: {
            script: /javascript|ecmascript/
        },
        converters: {
            "text script": function(e) {
                H.globalEval(e);
                return e
            }
        }
    }), H.ajaxPrefilter("script", function(e) {
        e.cache === t && (e.cache = !1), e.crossDomain && (e.type = "GET", e.global = !1)
    }), H.ajaxTransport("script", function(e) {
        if (e.crossDomain) {
            var n, r = _.head || _.getElementsByTagName("head")[0] || _.documentElement;
            return {
                send: function(i, s) {
                    n = _.createElement("script"), n.async = "async", e.scriptCharset && (n.charset = e.scriptCharset), n.src = e.url, n.onload = n.onreadystatechange = function(e, i) {
                        if (i || !n.readyState || /loaded|complete/.test(n.readyState)) n.onload = n.onreadystatechange = null, r && n.parentNode && r.removeChild(n), n = t, i || s(200, "success")
                    }, r.insertBefore(n, r.firstChild)
                },
                abort: function() {
                    n && n.onload(0, 1)
                }
            }
        }
    });
    var fn = e.ActiveXObject ? function() {
            for (var e in cn) cn[e](0, 1)
        } : !1,
        ln = 0,
        cn;
    H.ajaxSettings.xhr = e.ActiveXObject ? function() {
            return !this.isLocal && a() || u()
        } : a,
        function(e) {
            H.extend(H.support, {
                ajax: !!e,
                cors: !!e && "withCredentials" in e
            })
        }(H.ajaxSettings.xhr()), H.support.ajax && H.ajaxTransport(function(n) {
            if (!n.crossDomain || H.support.cors) {
                var r;
                return {
                    send: function(i, s) {
                        var o = n.xhr(),
                            u, a;
                        n.username ? o.open(n.type, n.url, n.async, n.username, n.password) : o.open(n.type, n.url, n.async);
                        if (n.xhrFields)
                            for (a in n.xhrFields) o[a] = n.xhrFields[a];
                        n.mimeType && o.overrideMimeType && o.overrideMimeType(n.mimeType), !n.crossDomain && !i["X-Requested-With"] && (i["X-Requested-With"] = "XMLHttpRequest");
                        try {
                            for (a in i) o.setRequestHeader(a, i[a])
                        } catch (f) {}
                        o.send(n.hasContent && n.data || null), r = function(e, i) {
                            var a, f, l, c, h;
                            try {
                                if (r && (i || o.readyState === 4)) {
                                    r = t, u && (o.onreadystatechange = H.noop, fn && delete cn[u]);
                                    if (i) o.readyState !== 4 && o.abort();
                                    else {
                                        a = o.status, l = o.getAllResponseHeaders(), c = {}, h = o.responseXML, h && h.documentElement && (c.xml = h), c.text = o.responseText;
                                        try {
                                            f = o.statusText
                                        } catch (p) {
                                            f = ""
                                        }!a && n.isLocal && !n.crossDomain ? a = c.text ? 200 : 404 : a === 1223 && (a = 204)
                                    }
                                }
                            } catch (d) {
                                i || s(-1, d)
                            }
                            c && s(a, f, c, l)
                        }, !n.async || o.readyState === 4 ? r() : (u = ++ln, fn && (cn || (cn = {}, H(e).unload(fn)), cn[u] = r), o.onreadystatechange = r)
                    },
                    abort: function() {
                        r && r(0, 1)
                    }
                }
            }
        });
    var hn = {},
        pn, dn, vn = /^(?:toggle|show|hide)$/,
        mn = /^([+\-]=)?([\d+.\-]+)([a-z%]*)$/i,
        gn, yn = [
            ["height", "marginTop", "marginBottom", "paddingTop", "paddingBottom"],
            ["width", "marginLeft", "marginRight", "paddingLeft", "paddingRight"],
            ["opacity"]
        ],
        bn, wn = e.webkitRequestAnimationFrame || e.mozRequestAnimationFrame || e.oRequestAnimationFrame;
    H.fn.extend({
        show: function(e, t, n) {
            var s, o;
            if (e || e === 0) return this.animate(i("show", 3), e, t, n);
            for (var u = 0, a = this.length; u < a; u++) s = this[u], s.style && (o = s.style.display, !H._data(s, "olddisplay") && o === "none" && (o = s.style.display = ""), o === "" && H.css(s, "display") === "none" && H._data(s, "olddisplay", r(s.nodeName)));
            for (u = 0; u < a; u++) {
                s = this[u];
                if (s.style) {
                    o = s.style.display;
                    if (o === "" || o === "none") s.style.display = H._data(s, "olddisplay") || ""
                }
            }
            return this
        },
        hide: function(e, t, n) {
            if (e || e === 0) return this.animate(i("hide", 3), e, t, n);
            for (var r = 0, s = this.length; r < s; r++)
                if (this[r].style) {
                    var o = H.css(this[r], "display");
                    o !== "none" && !H._data(this[r], "olddisplay") && H._data(this[r], "olddisplay", o)
                }
            for (r = 0; r < s; r++) this[r].style && (this[r].style.display = "none");
            return this
        },
        _toggle: H.fn.toggle,
        toggle: function(e, t, n) {
            var r = typeof e == "boolean";
            H.isFunction(e) && H.isFunction(t) ? this._toggle.apply(this, arguments) : e == null || r ? this.each(function() {
                var t = r ? e : H(this).is(":hidden");
                H(this)[t ? "show" : "hide"]()
            }) : this.animate(i("toggle", 3), e, t, n);
            return this
        },
        fadeTo: function(e, t, n, r) {
            return this.filter(":hidden").css("opacity", 0).show().end().animate({
                opacity: t
            }, e, n, r)
        },
        animate: function(e, t, n, i) {
            var s = H.speed(t, n, i);
            if (H.isEmptyObject(e)) return this.each(s.complete, [!1]);
            e = H.extend({}, e);
            return this[s.queue === !1 ? "each" : "queue"](function() {
                s.queue === !1 && H._mark(this);
                var t = H.extend({}, s),
                    n = this.nodeType === 1,
                    i = n && H(this).is(":hidden"),
                    o, u, a, f, l, c, h, p, d;
                t.animatedProperties = {};
                for (a in e) {
                    o = H.camelCase(a), a !== o && (e[o] = e[a], delete e[a]), u = e[o], H.isArray(u) ? (t.animatedProperties[o] = u[1], u = e[o] = u[0]) : t.animatedProperties[o] = t.specialEasing && t.specialEasing[o] || t.easing || "swing";
                    if (u === "hide" && i || u === "show" && !i) return t.complete.call(this);
                    n && (o === "height" || o === "width") && (t.overflow = [this.style.overflow, this.style.overflowX, this.style.overflowY], H.css(this, "display") === "inline" && H.css(this, "float") === "none" && (H.support.inlineBlockNeedsLayout ? (f = r(this.nodeName), f === "inline" ? this.style.display = "inline-block" : (this.style.display = "inline", this.style.zoom = 1)) : this.style.display = "inline-block"))
                }
                t.overflow != null && (this.style.overflow = "hidden");
                for (a in e) l = new H.fx(this, t, a), u = e[a], vn.test(u) ? l[u === "toggle" ? i ? "show" : "hide" : u]() : (c = mn.exec(u), h = l.cur(), c ? (p = parseFloat(c[2]), d = c[3] || (H.cssNumber[a] ? "" : "px"), d !== "px" && (H.style(this, a, (p || 1) + d), h = (p || 1) / l.cur() * h, H.style(this, a, h + d)), c[1] && (p = (c[1] === "-=" ? -1 : 1) * p + h), l.custom(h, p, d)) : l.custom(h, u, ""));
                return !0
            })
        },
        stop: function(e, t) {
            e && this.queue([]), this.each(function() {
                var e = H.timers,
                    n = e.length;
                t || H._unmark(!0, this);
                while (n--) e[n].elem === this && (t && e[n](!0), e.splice(n, 1))
            }), t || this.dequeue();
            return this
        }
    }), H.each({
        slideDown: i("show", 1),
        slideUp: i("hide", 1),
        slideToggle: i("toggle", 1),
        fadeIn: {
            opacity: "show"
        },
        fadeOut: {
            opacity: "hide"
        },
        fadeToggle: {
            opacity: "toggle"
        }
    }, function(e, t) {
        H.fn[e] = function(e, n, r) {
            return this.animate(t, e, n, r)
        }
    }), H.extend({
        speed: function(e, t, n) {
            var r = e && typeof e == "object" ? H.extend({}, e) : {
                complete: n || !n && t || H.isFunction(e) && e,
                duration: e,
                easing: n && t || t && !H.isFunction(t) && t
            };
            r.duration = H.fx.off ? 0 : typeof r.duration == "number" ? r.duration : r.duration in H.fx.speeds ? H.fx.speeds[r.duration] : H.fx.speeds._default, r.old = r.complete, r.complete = function(e) {
                H.isFunction(r.old) && r.old.call(this), r.queue !== !1 ? H.dequeue(this) : e !== !1 && H._unmark(this)
            };
            return r
        },
        easing: {
            linear: function(e, t, n, r) {
                return n + r * e
            },
            swing: function(e, t, n, r) {
                return (-Math.cos(e * Math.PI) / 2 + .5) * r + n
            }
        },
        timers: [],
        fx: function(e, t, n) {
            this.options = t, this.elem = e, this.prop = n, t.orig = t.orig || {}
        }
    }), H.fx.prototype = {
        update: function() {
            this.options.step && this.options.step.call(this.elem, this.now, this), (H.fx.step[this.prop] || H.fx.step._default)(this)
        },
        cur: function() {
            if (this.elem[this.prop] != null && (!this.elem.style || this.elem.style[this.prop] == null)) return this.elem[this.prop];
            var e, t = H.css(this.elem, this.prop);
            return isNaN(e = parseFloat(t)) ? !t || t === "auto" ? 0 : t : e
        },
        custom: function(e, t, n) {
            function r(e) {
                return i.step(e)
            }
            var i = this,
                s = H.fx,
                u;
            this.startTime = bn || o(), this.start = e, this.end = t, this.unit = n || this.unit || (H.cssNumber[this.prop] ? "" : "px"), this.now = this.start, this.pos = this.state = 0, r.elem = this.elem, r() && H.timers.push(r) && !gn && (wn ? (gn = !0, u = function() {
                gn && (wn(u), s.tick())
            }, wn(u)) : gn = setInterval(s.tick, s.interval))
        },
        show: function() {
            this.options.orig[this.prop] = H.style(this.elem, this.prop), this.options.show = !0, this.custom(this.prop === "width" || this.prop === "height" ? 1 : 0, this.cur()), H(this.elem).show()
        },
        hide: function() {
            this.options.orig[this.prop] = H.style(this.elem, this.prop), this.options.hide = !0, this.custom(this.cur(), 0)
        },
        step: function(e) {
            var t = bn || o(),
                n = !0,
                r = this.elem,
                i = this.options,
                s, u;
            if (e || t >= i.duration + this.startTime) {
                this.now = this.end, this.pos = this.state = 1, this.update(), i.animatedProperties[this.prop] = !0;
                for (s in i.animatedProperties) i.animatedProperties[s] !== !0 && (n = !1);
                if (n) {
                    i.overflow != null && !H.support.shrinkWrapBlocks && H.each(["", "X", "Y"], function(e, t) {
                        r.style["overflow" + t] = i.overflow[e]
                    }), i.hide && H(r).hide();
                    if (i.hide || i.show)
                        for (var a in i.animatedProperties) H.style(r, a, i.orig[a]);
                    i.complete.call(r)
                }
                return !1
            }
            i.duration == Infinity ? this.now = t : (u = t - this.startTime, this.state = u / i.duration, this.pos = H.easing[i.animatedProperties[this.prop]](this.state, u, 0, 1, i.duration), this.now = this.start + (this.end - this.start) * this.pos), this.update();
            return !0
        }
    }, H.extend(H.fx, {
        tick: function() {
            for (var e = H.timers, t = 0; t < e.length; ++t) e[t]() || e.splice(t--, 1);
            e.length || H.fx.stop()
        },
        interval: 13,
        stop: function() {
            clearInterval(gn), gn = null
        },
        speeds: {
            slow: 600,
            fast: 200,
            _default: 400
        },
        step: {
            opacity: function(e) {
                H.style(e.elem, "opacity", e.now)
            },
            _default: function(e) {
                e.elem.style && e.elem.style[e.prop] != null ? e.elem.style[e.prop] = (e.prop === "width" || e.prop === "height" ? Math.max(0, e.now) : e.now) + e.unit : e.elem[e.prop] = e.now
            }
        }
    }), H.expr && H.expr.filters && (H.expr.filters.animated = function(e) {
        return H.grep(H.timers, function(t) {
            return e === t.elem
        }).length
    });
    var En = /^t(?:able|d|h)$/i,
        Sn = /^(?:body|html)$/i;
    "getBoundingClientRect" in _.documentElement ? H.fn.offset = function(e) {
        var t = this[0],
            r;
        if (e) return this.each(function(t) {
            H.offset.setOffset(this, e, t)
        });
        if (!t || !t.ownerDocument) return null;
        if (t === t.ownerDocument.body) return H.offset.bodyOffset(t);
        try {
            r = t.getBoundingClientRect()
        } catch (i) {}
        var s = t.ownerDocument,
            o = s.documentElement;
        if (!r || !H.contains(o, t)) return r ? {
            top: r.top,
            left: r.left
        } : {
            top: 0,
            left: 0
        };
        var u = s.body,
            a = n(s),
            f = o.clientTop || u.clientTop || 0,
            l = o.clientLeft || u.clientLeft || 0,
            c = a.pageYOffset || H.support.boxModel && o.scrollTop || u.scrollTop,
            h = a.pageXOffset || H.support.boxModel && o.scrollLeft || u.scrollLeft,
            p = r.top + c - f,
            d = r.left + h - l;
        return {
            top: p,
            left: d
        }
    } : H.fn.offset = function(e) {
        var t = this[0];
        if (e) return this.each(function(t) {
            H.offset.setOffset(this, e, t)
        });
        if (!t || !t.ownerDocument) return null;
        if (t === t.ownerDocument.body) return H.offset.bodyOffset(t);
        H.offset.initialize();
        var n, r = t.offsetParent,
            i = t,
            s = t.ownerDocument,
            o = s.documentElement,
            u = s.body,
            a = s.defaultView,
            f = a ? a.getComputedStyle(t, null) : t.currentStyle,
            l = t.offsetTop,
            c = t.offsetLeft;
        while ((t = t.parentNode) && t !== u && t !== o) {
            if (H.offset.supportsFixedPosition && f.position === "fixed") break;
            n = a ? a.getComputedStyle(t, null) : t.currentStyle, l -= t.scrollTop, c -= t.scrollLeft, t === r && (l += t.offsetTop, c += t.offsetLeft, H.offset.doesNotAddBorder && (!H.offset.doesAddBorderForTableAndCells || !En.test(t.nodeName)) && (l += parseFloat(n.borderTopWidth) || 0, c += parseFloat(n.borderLeftWidth) || 0), i = r, r = t.offsetParent), H.offset.subtractsBorderForOverflowNotVisible && n.overflow !== "visible" && (l += parseFloat(n.borderTopWidth) || 0, c += parseFloat(n.borderLeftWidth) || 0), f = n
        }
        if (f.position === "relative" || f.position === "static") l += u.offsetTop, c += u.offsetLeft;
        H.offset.supportsFixedPosition && f.position === "fixed" && (l += Math.max(o.scrollTop, u.scrollTop), c += Math.max(o.scrollLeft, u.scrollLeft));
        return {
            top: l,
            left: c
        }
    }, H.offset = {
        initialize: function() {
            var e = _.body,
                t = _.createElement("div"),
                n, r, i, s, o = parseFloat(H.css(e, "marginTop")) || 0,
                u = "<div style='position:absolute;top:0;left:0;margin:0;border:5px solid #000;padding:0;width:1px;height:1px;'><div></div></div><table style='position:absolute;top:0;left:0;margin:0;border:5px solid #000;padding:0;width:1px;height:1px;' cellpadding='0' cellspacing='0'><tr><td></td></tr></table>";
            H.extend(t.style, {
                position: "absolute",
                top: 0,
                left: 0,
                margin: 0,
                border: 0,
                width: "1px",
                height: "1px",
                visibility: "hidden"
            }), t.innerHTML = u, e.insertBefore(t, e.firstChild), n = t.firstChild, r = n.firstChild, s = n.nextSibling.firstChild.firstChild, this.doesNotAddBorder = r.offsetTop !== 5, this.doesAddBorderForTableAndCells = s.offsetTop === 5, r.style.position = "fixed", r.style.top = "20px", this.supportsFixedPosition = r.offsetTop === 20 || r.offsetTop === 15, r.style.position = r.style.top = "", n.style.overflow = "hidden", n.style.position = "relative", this.subtractsBorderForOverflowNotVisible = r.offsetTop === -5, this.doesNotIncludeMarginInBodyOffset = e.offsetTop !== o, e.removeChild(t), H.offset.initialize = H.noop
        },
        bodyOffset: function(e) {
            var t = e.offsetTop,
                n = e.offsetLeft;
            H.offset.initialize(), H.offset.doesNotIncludeMarginInBodyOffset && (t += parseFloat(H.css(e, "marginTop")) || 0, n += parseFloat(H.css(e, "marginLeft")) || 0);
            return {
                top: t,
                left: n
            }
        },
        setOffset: function(e, t, n) {
            var r = H.css(e, "position");
            r === "static" && (e.style.position = "relative");
            var i = H(e),
                s = i.offset(),
                o = H.css(e, "top"),
                u = H.css(e, "left"),
                a = (r === "absolute" || r === "fixed") && H.inArray("auto", [o, u]) > -1,
                f = {},
                l = {},
                c, h;
            a ? (l = i.position(), c = l.top, h = l.left) : (c = parseFloat(o) || 0, h = parseFloat(u) || 0), H.isFunction(t) && (t = t.call(e, n, s)), t.top != null && (f.top = t.top - s.top + c), t.left != null && (f.left = t.left - s.left + h), "using" in t ? t.using.call(e, f) : i.css(f)
        }
    }, H.fn.extend({
        position: function() {
            if (!this[0]) return null;
            var e = this[0],
                t = this.offsetParent(),
                n = this.offset(),
                r = Sn.test(t[0].nodeName) ? {
                    top: 0,
                    left: 0
                } : t.offset();
            n.top -= parseFloat(H.css(e, "marginTop")) || 0, n.left -= parseFloat(H.css(e, "marginLeft")) || 0, r.top += parseFloat(H.css(t[0], "borderTopWidth")) || 0, r.left += parseFloat(H.css(t[0], "borderLeftWidth")) || 0;
            return {
                top: n.top - r.top,
                left: n.left - r.left
            }
        },
        offsetParent: function() {
            return this.map(function() {
                var e = this.offsetParent || _.body;
                while (e && !Sn.test(e.nodeName) && H.css(e, "position") === "static") e = e.offsetParent;
                return e
            })
        }
    }), H.each(["Left", "Top"], function(e, r) {
        var i = "scroll" + r;
        H.fn[i] = function(r) {
            var s, o;
            if (r === t) {
                s = this[0];
                if (!s) return null;
                o = n(s);
                return o ? "pageXOffset" in o ? o[e ? "pageYOffset" : "pageXOffset"] : H.support.boxModel && o.document.documentElement[i] || o.document.body[i] : s[i]
            }
            return this.each(function() {
                o = n(this), o ? o.scrollTo(e ? H(o).scrollLeft() : r, e ? r : H(o).scrollTop()) : this[i] = r
            })
        }
    }), H.each(["Height", "Width"], function(e, n) {
        var r = n.toLowerCase();
        H.fn["inner" + n] = function() {
            var e = this[0];
            return e && e.style ? parseFloat(H.css(e, r, "padding")) : null
        }, H.fn["outer" + n] = function(e) {
            var t = this[0];
            return t && t.style ? parseFloat(H.css(t, r, e ? "margin" : "border")) : null
        }, H.fn[r] = function(e) {
            var i = this[0];
            if (!i) return e == null ? null : this;
            if (H.isFunction(e)) return this.each(function(t) {
                var n = H(this);
                n[r](e.call(this, t, n[r]()))
            });
            if (H.isWindow(i)) {
                var s = i.document.documentElement["client" + n];
                return i.document.compatMode === "CSS1Compat" && s || i.document.body["client" + n] || s
            }
            if (i.nodeType === 9) return Math.max(i.documentElement["client" + n], i.body["scroll" + n], i.documentElement["scroll" + n], i.body["offset" + n], i.documentElement["offset" + n]);
            if (e === t) {
                var o = H.css(i, r),
                    u = parseFloat(o);
                return H.isNaN(u) ? o : u
            }
            return this.css(r, typeof e == "string" ? e : e + "px")
        }
    }), e.jQuery = e.$ = H
})(window);
LazyLoad = function(e) {
    function t(t, n) {
        var r = e.createElement(t),
            i;
        for (i in n) n.hasOwnProperty(i) && r.setAttribute(i, n[i]);
        return r
    }

    function n(e) {
        var t = a[e],
            n, r;
        if (t) n = t.callback, r = t.urls, r.shift(), f = 0, r.length || (n && n.call(t.context, t.obj), a[e] = null, l[e].length && i(e))
    }

    function r() {
        if (!o) {
            var t = navigator.userAgent;
            o = {
                async: e.createElement("script").async === !0
            };
            (o.webkit = /AppleWebKit\//.test(t)) || (o.ie = /MSIE/.test(t)) || (o.opera = /Opera/.test(t)) || (o.gecko = /Gecko\//.test(t)) || (o.unknown = !0)
        }
    }

    function i(i, f, c, h, p) {
        var m = function() {
                n(i)
            },
            y = i === "css",
            b, w, E, S;
        r();
        if (f)
            if (f = typeof f === "string" ? [f] : f.concat(), y || o.async || o.gecko || o.opera) l[i].push({
                urls: f,
                callback: c,
                obj: h,
                context: p
            });
            else {
                b = 0;
                for (w = f.length; b < w; ++b) l[i].push({
                    urls: [f[b]],
                    callback: b === w - 1 ? c : null,
                    obj: h,
                    context: p
                })
            }
        if (!a[i] && (S = a[i] = l[i].shift())) {
            u || (u = e.head || e.getElementsByTagName("head")[0]);
            f = S.urls;
            b = 0;
            for (w = f.length; b < w; ++b) c = f[b], y ? E = o.gecko ? t("style") : t("link", {
                href: c,
                rel: "stylesheet"
            }) : (E = t("script", {
                src: c
            }), E.async = !1), E.className = "lazyload", E.setAttribute("charset", "utf-8"), o.ie && !y ? E.onreadystatechange = function() {
                if (/loaded|complete/.test(E.readyState)) E.onreadystatechange = null, m()
            } : y && (o.gecko || o.webkit) ? o.webkit ? (S.urls[b] = E.href, s()) : (E.innerHTML = '@import "' + c + '";', n("css")) : E.onload = E.onerror = m, u.appendChild(E)
        }
    }

    function s() {
        var e = a.css,
            t;
        if (e) {
            for (t = c.length; --t >= 0;)
                if (c[t].href === e.urls[0]) {
                    n("css");
                    break
                }
            f += 1;
            e && (f < 200 ? setTimeout(s, 50) : n("css"))
        }
    }
    var o, u, a = {},
        f = 0,
        l = {
            css: [],
            js: []
        },
        c = e.styleSheets;
    return {
        css: function(e, t, n, r) {
            i("css", e, t, n, r)
        },
        js: function(e, t, n, r) {
            i("js", e, t, n, r)
        }
    }
}(this.document);
var re_cogzidel = /cogzidel\.com/;
var re_http = /(ftp|http|https):\/\//i;
var re_www = /www\.\w+/i;
var re_domain_ext = /\w+\.(com|net|org|biz|ws|name)/i;
var re_phone_number = /([0-9]{3,9}[\- ]?){3,9}/;
var re_phone_word = /((zero|one|two|three|four|five|six|seven|eight|nine)\W+){6,100}/i;
var re_email = /\w+(\.\w+){0,1}(@)[\w|\-]+(\.|\W{1,3}dot\W{1,3})\w+/;
var re_email_domain = /( aol|gmail|hotmail|msn|yahoo)(\.com){0,1}/i;
var censor_attempt_counter = 0;
var newWin = null;
var DEFAULT_COOKIE_OPTIONS = {
    path: "/",
    expires: 30
};
jQuery.cookie = function(e, t, n) {
    if (typeof t != "undefined") {
        n = n || {};
        if (t === null) {
            t = "";
            n = jQuery.extend({}, n);
            n.expires = -1
        }
        var r = "";
        if (n.expires && (typeof n.expires == "number" || n.expires.toUTCString)) {
            var i;
            if (typeof n.expires == "number") {
                i = new Date;
                i.setTime(i.getTime() + n.expires * 24 * 60 * 60 * 1e3)
            } else i = n.expires;
            r = "; expires=" + i.toUTCString()
        }
        var s = n.path ? "; path=" + n.path : "";
        var o = n.domain ? "; domain=" + n.domain : "";
        var u = n.secure ? "; secure" : "";
        document.cookie = [e, "=", encodeURIComponent(t), r, s, o, u].join("")
    } else {
        var a = null;
        if (document.cookie && document.cookie !== "") {
            var f = document.cookie.split(";");
            for (var l = 0; l < f.length; l++) {
                var c = jQuery.trim(f[l]);
                if (c.substring(0, e.length + 1) == e + "=") {
                    a = decodeURIComponent(c.substring(e.length + 1));
                    break
                }
            }
        }
        return a
    }
};
Array.prototype.unique = function() {
    var e = this;
    var t = [];
    for (var n = e.length; n--;) {
        var r = e[n];
        if (jQuery.inArray(r, t) === -1) t.unshift(r)
    }
    return t
};
(function(e) {
    e.support.placeholder = function() {
        var e = document.createElement("input");
        return "placeholder" in e
    }();
    e.fn.pulsate = function(e, t, n) {
        if (typeof n === "undefined") {
            e = e * 2;
            t = t / 2
        }
        var r = this;
        var i = n ? 1 : 0;
        if (e > 0) this.fadeTo(t, i, function() {
            r.pulsate(e - 1, t, !n)
        })
    }
})(jQuery);
jQuery(document).ready(function(e) {
    var t = e("#language_selector");
    e("#language_display").toggle(function() {
        e("#language_selector_container").show();
        e(this).addClass("selected")
    }, function() {
        e("#language_selector_container").hide();
        e(this).removeClass("selected")
    });
    e("#language").mouseleave(function(t) {
        if (e("#language_selector_container").is(":visible") && !(e(t.relatedTarget).parents().index(e("#language_selector_container")) >= 0)) e("#language_display").click()
    });
    t.delegate("li.language", "click", function() {
        e.post(base_url + "users/change_language", {
            lang_code: e(this).attr("name")
        }, function() {
            window.location.reload(true)
        });
        t.css("cursor", "progress");
        e(this).pulsate(10, 1e3)
    }).delegate("li.language", "click", function() {
        e.post(base_url + "users/change_language", {
            lang_code: e(this).attr("name")
        }, function() {
            window.location.reload(true)
        })
    })
});
jQuery(document).ready(function(e) {
    var t = e("#currency_selector");
    e("#currency_display").toggle(function() {
        e("#currency_selector_container").show();
        e(this).addClass("selected")
    }, function() {
        e("#currency_selector_container").hide();
        e(this).removeClass("selected")
    });
    e("#currency").mouseleave(function(t) {
        if (e("#currency_selector_container").is(":visible") && !(e(t.relatedTarget).parents().index(e("#currency_selector_container")) >= 0)) e("#currency_display").click()
    });
    t.delegate("li.language", "click", function() {
        e.post(base_url + "users/change_language", {
            lang_code: e(this).attr("name")
        }, function() {
            window.location.reload(true)
        });
        t.css("cursor", "progress");
        e(this).pulsate(10, 1e3)
    }).delegate("li.currency", "click", function() {
        e.post(base_url + "users/change_currency", {
            currency_code: e(this).attr("name")
        }, function() {
            window.location.reload(true)
        });
        t.css("cursor", "progress");
        e(this).pulsate(10, 1e3)
    })
});
if (!Array.indexOf) Array.prototype.indexOf = function(e) {
    for (var t = 0; t < this.length; t++)
        if (this[t] == e) return t;
    return -1
};
(function(e) {
    e.fn.cogzidelInputDateSpan = function(t) {
        var n = {
            minDate: 0,
            maxDate: "+2Y",
            nextText: "",
            prevText: "",
            numberOfMonths: 1,
            showButtonPanel: true,
            closeText: "Clear Dates"
        };
        t = t || {};
        if (typeof t === "object") {
            var r = jQuery(this);
            var i = {
                checkinDatePicker: jQuery(t.checkin),
                checkoutDatePicker: jQuery(t.checkout),
                onSuccessCallback: t.onSuccess,
                onCheckinClose: t.onCheckinClose,
                onCheckoutClose: t.onCheckoutClose
            };
            if (!t.defaultsCheckin) t.defaultsCheckin = n;
            if (!t.defaultsCheckout) t.defaultsCheckout = n;
            if (!t.checkin) i.checkinDatePicker = r.find("input.checkin");
            if (!t.checkout) i.checkoutDatePicker = r.find("input.checkout");
            jQuery.each(["onSuccessCallback", "onCheckinClose", "onCheckoutClose"], function(e, t) {
                if (!i[t]) i[t] = function() {}
            });
            r.data("cogzidel-datepickeroptions", i);
            var s = jQuery.extend(jQuery.extend(true, {}, t.defaultsCheckin), {
                beforeShow: function(t, n) {
                    var r = e.datepicker._defaults.dateFormat;
                    var i = e(t);
                    var s = i.val();
                    if (s === "" || s === r) {
                        i.datepicker("option", "minDate", "+0");
                        i.val(r)
                    }
                },
                onClose: function(t, n) {
                    var i = e.datepicker._defaults.dateFormat;
                    var s = r.data("cogzidel-datepickeroptions");
                    if (t !== i) {
                        var o = e.datepicker.parseDate(i, t);
                        o = new Date(o.setDate(o.getDate() + 1));
                        var u = s.checkoutDatePicker;
                        try {
                            var f = e.datepicker.parseDate(i, u.val());
                            u.datepicker("option", "minDate", o);
                            if (o > f) {
                                u.val(e.datepicker.formatDate(i, o));
                                setTimeout(function() {
                                    u.datepicker("show")
                                }, 0)
                            }
                        } catch (l) {
                            u.datepicker("option", "minDate", o);
                            u.val(e.datepicker.formatDate(i, o));
                            setTimeout(function() {
                                u.datepicker("show")
                            }, 0)
                        }
                    }
                    s.onCheckinClose()
                }
            });
            var o = jQuery.extend(jQuery.extend(true, {}, t.defaultsCheckout), {
                beforeShow: function(t, n) {
                    var r = e.datepicker._defaults.dateFormat;
                    var i = e(t);
                    var s = i.val();
                    if (s === "" || s === r) {
                        i.datepicker("option", "minDate", "+1");
                        i.val(r)
                    }
                },
                onClose: function(t, n) {
                    var i = e.datepicker._defaults.dateFormat;
                    var s = r.data("cogzidel-datepickeroptions");
                    if (t !== i) {
                        var o = e.datepicker.parseDate(i, t);
                        o = new Date(o.setDate(o.getDate() - 1));
                        var u = s.checkinDatePicker;
                        try {
                            var f = e.datepicker.parseDate(i, u.val());
                            if (o < f) {
                                u.val(e.datepicker.formatDate(i, o));
                                setTimeout(function() {
                                    u.datepicker("show")
                                }, 0)
                            } else {
                                e("#search_inputs").css("background-color", "#ececec");
                                s.onSuccessCallback()
                            }
                        } catch (l) {
                            e("#search_inputs").css("background-color", "#ffe75f");
                            u.val(e.datepicker.formatDate(i, o));
                            setTimeout(function() {
                                u.datepicker("show")
                            }, 0)
                        }
                    }
                    s.onCheckoutClose()
                }
            });
            i.checkinDatePicker.datepicker(s);
            i.checkoutDatePicker.datepicker(o);
            var u = r.data("cogzidel-datepickeroptions");
            e(".ui-datepicker-close").live("mouseup", function() {
                var t = u.checkinDatePicker;
                var n = u.checkoutDatePicker;
                t.val(e.datepicker._defaults.dateFormat);
                n.val(e.datepicker._defaults.dateFormat);
                e("#search_inputs").css("background-color", "#ffe75f");
                u.onSuccessCallback()
            })
        }
    }
})(jQuery);
var Cogzidel = {
    init: function(e) {
        Cogzidel.Utils.formatPhoneNumbers();
        Cogzidel.Utils.isUserLoggedIn = !e || !e.userLoggedIn ? false : true
    },
    StringValidator: {
        Regexes: {
            url: /(https?)|(webcal):\/\/([-\w\.]+)+(:\d+)?(\/([\w\/_\.]*(\?\S+)?)?)?/,
            email: /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
            date: /[0-9]{2}\/[0-9]{2}\/[0-9]{4}/,
            phone: /((.*)?\d(.*?)){10,15}/
        },
        validate: function(e, t) {
            if (e === undefined || t === undefined || typeof t != "string") return false;
            return t.match(Cogzidel.StringValidator.Regexes[e]) !== null
        }
    },
    Bookmarks: {
        initializeStarIcons: function(e) {
            jQuery(".star_icon_container").clickStar(e)
        },
        starredIds: []
    },
    Utils: {
        isUserLoggedIn: false,
        usingIosDevice: function() {
            return !(navigator.userAgent.indexOf("iPhone") == -1 && navigator.userAgent.indexOf("iPod") == -1 && navigator.userAgent.indexOf("iPad") == -1)
        },
        fb_status: function() {
            return jQuery.cookie("fbs")
        },
        keyPressEventName: jQuery.browser.mozilla ? "keypress" : "keydown",
        decode: function(e) {
            return jQuery("<div/>").html(e).text()
        },
        setInnerText: function(e) {
            jQuery.each(jQuery(".inner_text"), function(t, n) {
                var r = jQuery(n).next("input, textarea");
                var i = r.val();
                if (jQuery.support.placeholder && r.attr("placeholder") !== "undefined" && r.attr("placeholder") !== "") {
                    jQuery(n).hide();
                    return
                }
                r.attr("defaultValue", n.innerHTML);
                r.val(n.innerHTML);
                if (i.length === 0);
                else {
                    r.val(i);
                    r.addClass("active")
                }
                r.bind("focus", function() {
                    if (jQuery(r).val() == r.attr("defaultValue")) {
                        jQuery(r).val("");
                        global_test_var = jQuery(r);
                        jQuery(r).addClass("active")
                    }
                    jQuery(r).removeClass("error");
                    return true
                });
                r.bind("blur", function() {
                    if (jQuery(r).val() === "") {
                        jQuery(r).removeClass("active");
                        jQuery(r).val(r.attr("defaultValue"))
                    } else jQuery(r).removeClass("error")
                });
                if (e) e.push(r);
                jQuery(n).remove()
            })
        },
        clearInnerText: function(e) {
            var t, n, r;
            for (n = 0; n < e.length; n++) {
                t = jQuery(e[n]);
                if (t.val() === t.attr("defaultValue")) t.val("")
            }
        },
        textCounter: function(e, t, n) {
            if (e.val().length > n) e.val(e.val().substring(0, n));
            else t.html(n - e.val().length)
        },
        formatPhoneNumbers: function() {
            var e;
            var t = jQuery(".phone_number_to_format");
            if (t.length > 0) {
                try {
                    e = i18n.phonenumbers.PhoneNumberUtil.getInstance()
                } catch (n) {
                    LazyLoad.js(["/javascripts/libphonenumber.compiled.js", "/javascripts/jquery.validatedphone.js"], function() {
                        Cogzidel.Utils.formatPhoneNumbers()
                    });
                    return false
                }
                t.each(function(t) {
                    var n = this.nodeName.toUpperCase() === "INPUT" ? "val" : "html";
                    var r = jQuery(this);
                    try {
                        var i = e.parseAndKeepRawInput(r[n](), "US");
                        var s = e.format(i, i18n.phonenumbers.PhoneNumberFormat.INTERNATIONAL);
                        r[n](s)
                    } catch (o) {}
                })
            }
        },
        initHowItWorksLightbox: function(e, t) {
            var n = '<object id="video" width="764" height="458"><param name="movie" value="http://www.youtube.com/v/' + t + '?fs=1&hl=en_US&rel=0&hd=1&autoplay=1"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/' + t + '?fs=1&hl=en_US&rel=0&hd=1&autoplay=1" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="764" height="458"></embed></object>';
            Cogzidel.Utils.initVideoLightbox(e, Translations.video_lightbox_title, n)
        },
        initVideoLightbox: function(e, t, n) {
            if (jQuery("#video_lightbox_content").length === 0) jQuery("body").append('<div id="video_lightbox_content"></div>');
            jQuery(e).colorbox({
                inline: true,
                href: "#video_lightbox_content",
                onLoad: function() {
                    jQuery("#video_lightbox_content").html(n)
                },
                onComplete: function() {
                    jQuery("#cboxTitle").html(t)
                },
                onCleanup: function() {
                    jQuery("#video_lightbox_content").html("");
                    jQuery("#cboxTitle").html("")
                }
            })
        }
    },
    Currency: function() {
        var e = {},
            t = {
                USD: {
                    symbol: "$",
                    rate: 1
                },
                EUR: {
                    symbol: "&euro;",
                    rate: .673737
                },
                DKK: {
                    symbol: "kr",
                    rate: 6.0803
                },
                CAD: {
                    symbol: "$",
                    rate: 1.06408
                },
                JPY: {
                    symbol: "&yen;",
                    rate: 90.8836
                },
                GBP: {
                    symbol: "&pound;",
                    rate: .60359
                },
                AUD: {
                    symbol: "$",
                    rate: 1.10004
                },
                ZAR: {
                    symbol: "R",
                    rate: 7.64502615
                }
            };
        e.currencyConversionTable = t;
        e.setCurrencyConversions = function(e) {
            for (var n in e)
                if (e.hasOwnProperty(n)) t[n].rate = e[n]
        };
        e.convert = function(e, n, r, i) {
            var s = e * t[r].rate / t[n].rate;
            if (i) return parseInt(Math.round(s), 10);
            return s
        };
        e.getSymbolForCurrency = function(e) {
            return t[e].symbol
        };
        return e
    }()
};
(function(e) {
    e.fn.labelBlur = function() {
        return this.each(function() {
            var t = e(this).show(),
                n = e("#" + t.attr("for")),
                r;
            var i = function() {
                if (n.val().length) {
                    t.hide();
                    if (r) clearInterval(r)
                }
            };
            i();
            r = setInterval(i, 100);
            n.focus(function() {
                t.hide();
                if (r) clearInterval(r)
            }).blur(function() {
                if (n.val() === "") t.show()
            });
            return this
        })
    };
    e.labelBlur = function() {
        e("label.labelBlur").labelBlur()
    };
    e(document).ready(function() {
        e.labelBlur()
    });
    e.fn.disableSubmit = function() {
        return this.each(function() {
            var t = e(this),
                n;
            if (t.is("input:submit")) n = t;
            else n = t.find("input:submit");
            n.attr("disabled", "disabled");
            return this
        })
    }
})(jQuery);
(function(e) {
    e(document).ready(function() {
        e(".fb-button").click(function() {
            e(this).addClass("loading")
        })
    })
})(jQuery);
(function(e, t) {
    function c(t, n) {
        t = t ? ' id="' + w + t + '"' : "";
        n = n ? ' style="' + n + '"' : "";
        return e("<div" + t + n + "/>")
    }

    function h(e, t) {
        t = t === l ? q.width() : q.height();
        return typeof e === "string" ? Math.round(/%/.test(e) ? t / 100 * parseInt(e, 10) : parseInt(e, 10)) : e
    }

    function p(e) {
        return nt.photo || /\.(gif|png|jpg|jpeg|bmp)(?:\?([^#]*))?(?:#(\.*))?$/i.test(e)
    }

    function d(t) {
        for (var n in t)
            if (e.isFunction(t[n]) && n.substring(0, 2) !== "on") t[n] = t[n].call(et);
        t.rel = t.rel || et.rel || f;
        t.href = t.href || e(et).attr("href");
        t.title = t.title || et.title;
        return t
    }

    function v(t, n) {
        n && n.call(et);
        e.event.trigger(t)
    }

    function m() {
        var e, t = w + "Slideshow_",
            n = "click." + w,
            r, i;
        if (nt.slideshow && I[1]) {
            r = function() {
                V.text(nt.slideshowStop).unbind(n).bind(x, function() {
                    if (tt < I.length - 1 || nt.loop) e = setTimeout(ot.next, nt.slideshowSpeed)
                }).bind(S, function() {
                    clearTimeout(e)
                }).one(n + " " + T, i);
                _.removeClass(t + "off").addClass(t + "on");
                e = setTimeout(ot.next, nt.slideshowSpeed)
            };
            i = function() {
                clearTimeout(e);
                V.text(nt.slideshowStart).unbind([x, S, T, n].join(" ")).one(n, r);
                _.removeClass(t + "on").addClass(t + "off")
            };
            nt.slideshowAuto ? r() : i()
        }
    }

    function g(t) {
        if (!st) {
            et = t;
            nt = d(e.extend({}, e.data(et, b)));
            I = e(et);
            tt = 0;
            if (nt.rel !== f) {
                I = e("." + ut).filter(function() {
                    return (e.data(this, b).rel || this.rel) === nt.rel
                });
                tt = I.index(et);
                if (tt === -1) {
                    I = I.add(et);
                    tt = I.length - 1
                }
            }
            if (!rt) {
                rt = it = a;
                _.show();
                if (nt.returnFocus) try {
                    et.blur();
                    e(et).one(N, function() {
                        try {
                            this.focus()
                        } catch (e) {}
                    })
                } catch (n) {}
                M.css({
                    opacity: +nt.opacity,
                    cursor: nt.overlayClose ? "pointer" : u
                }).show();
                nt.w = h(nt.initialWidth, l);
                nt.h = h(nt.initialHeight, o);
                ot.position(0);
                A && q.bind(s + O + " scroll." + O, function() {
                    M.css({
                        width: q.width(),
                        height: q.height(),
                        top: q.scrollTop(),
                        left: q.scrollLeft()
                    })
                }).trigger("scroll." + O);
                v(E, nt.onOpen);
                X.add(J).add($).add(V).add(W).hide();
                K.html(nt.close).show()
            }
            ot.load(a)
        }
    }
    var n = "none",
        r = "LoadedContent",
        i = false,
        s = "resize.",
        o = "y",
        u = "auto",
        a = true,
        f = "nofollow",
        l = "x";
    var y = {
            transition: "elastic",
            speed: 300,
            width: i,
            initialWidth: "600",
            innerWidth: i,
            maxWidth: i,
            height: i,
            initialHeight: "450",
            innerHeight: i,
            maxHeight: i,
            scalePhotos: a,
            scrolling: a,
            inline: i,
            html: i,
            iframe: i,
            photo: i,
            href: i,
            title: i,
            rel: i,
            opacity: .9,
            preloading: a,
            current: "image {current} of {total}",
            previous: "previous",
            next: "next",
            close: "close",
            open: i,
            returnFocus: a,
            loop: a,
            slideshow: i,
            slideshowAuto: a,
            slideshowSpeed: 2500,
            slideshowStart: "start slideshow",
            slideshowStop: "stop slideshow",
            onOpen: i,
            onLoad: i,
            onComplete: i,
            onCleanup: i,
            onClosed: i,
            overlayClose: a,
            escKey: a,
            arrowKey: a
        },
        b = "colorbox",
        w = "cbox",
        E = w + "_open",
        S = w + "_load",
        x = w + "_complete",
        T = w + "_cleanup",
        N = w + "_closed",
        C = w + "_purge",
        k = w + "_loaded",
        L = e.browser.msie && !e.support.opacity,
        A = L && e.browser.version < 7,
        O = w + "_IE6",
        M, _, D, P, H, B, j, F, I, q, R, U, z, W, X, V, $, J, K, Q, G, Y, Z, et, tt, nt, rt, it, st = i,
        ot, ut = w + "Element";
    ot = e.fn[b] = e[b] = function(t, n) {
        var r = this,
            i;
        if (!r[0] && r.selector) return r;
        t = t || {};
        if (n) t.onComplete = n;
        if (!r[0] || r.selector === undefined) {
            r = e("<a/>");
            t.open = a
        }
        r.each(function() {
            e.data(this, b, e.extend({}, e.data(this, b) || y, t));
            e(this).addClass(ut)
        });
        i = t.open;
        if (e.isFunction(i)) i = i.call(r);
        i && g(r[0]);
        return r
    };
    ot.init = function() {
        var n = "hover",
            s = "clear:left";
        q = e(t);
        _ = c().attr({
            id: b,
            "class": L ? w + "IE" : ""
        });
        M = c("Overlay", A ? "position:absolute" : "").hide();
        D = c("Wrapper");
        P = c("Content").append(R = c(r, "width:0; height:0; overflow:hidden"), z = c("LoadingOverlay").add(c("LoadingGraphic")), W = c("Title"), X = c("Current"), $ = c("Next"), J = c("Previous"), V = c("Slideshow").bind(E, m), K = c("Close"));
        D.append(c().append(c("TopLeft"), H = c("TopCenter"), c("TopRight")), c(i, s).append(B = c("MiddleLeft"), P, j = c("MiddleRight")), c(i, s).append(c("BottomLeft"), F = c("BottomCenter"), c("BottomRight"))).children().children().css({
            "float": "left"
        });
        U = c(i, "position:absolute; width:9999px; visibility:hidden; display:none");
        e("body").prepend(M, _.append(D, U));
        P.children().hover(function() {
            e(this).addClass(n)
        }, function() {
            e(this).removeClass(n)
        }).addClass(n);
        Q = H.height() + F.height() + P.outerHeight(a) - P.height();
        G = B.width() + j.width() + P.outerWidth(a) - P.width();
        Y = R.outerHeight(a);
        Z = R.outerWidth(a);
        _.css({
            "padding-bottom": Q,
            "padding-right": G
        }).hide();
        $.click(ot.next);
        J.click(ot.prev);
        K.click(ot.close);
        P.children().removeClass(n);
        e("." + ut).live("click", function(e) {
            if (!(e.button !== 0 && typeof e.button !== "undefined" || e.ctrlKey || e.shiftKey || e.altKey)) {
                e.preventDefault();
                g(this)
            }
        });
        M.click(function() {
            nt.overlayClose && ot.close()
        });
        e(document).bind("keydown", function(e) {
            if (rt && nt.escKey && e.keyCode === 27) {
                e.preventDefault();
                ot.close()
            }
            if (rt && nt.arrowKey && !it && I[1])
                if (e.keyCode === 37 && (tt || nt.loop)) {
                    e.preventDefault();
                    J.click()
                } else if (e.keyCode === 39 && (tt < I.length - 1 || nt.loop)) {
                e.preventDefault();
                $.click()
            }
        })
    };
    ot.remove = function() {
        _.add(M).remove();
        e("." + ut).die("click").removeData(b).removeClass(ut)
    };
    ot.position = function(e, t) {
        function n(e) {
            H[0].style.width = F[0].style.width = P[0].style.width = e.style.width;
            z[0].style.height = z[1].style.height = P[0].style.height = B[0].style.height = j[0].style.height = e.style.height
        }
        var r, s = Math.max(document.documentElement.clientHeight - nt.h - Y - Q, 0) / 2 + q.scrollTop(),
            o = Math.max(q.width() - nt.w - Z - G, 0) / 2 + q.scrollLeft();
        r = _.width() === nt.w + Z && _.height() === nt.h + Y ? 0 : e;
        D[0].style.width = D[0].style.height = "9999px";
        _.dequeue().animate({
            width: nt.w + Z,
            height: nt.h + Y,
            top: s,
            left: o
        }, {
            duration: r,
            complete: function() {
                n(this);
                it = i;
                D[0].style.width = nt.w + Z + G + "px";
                D[0].style.height = nt.h + Y + Q + "px";
                t && t()
            },
            step: function() {
                n(this)
            }
        })
    };
    ot.resize = function(e) {
        if (rt) {
            e = e || {};
            if (e.width) nt.w = h(e.width, l) - Z - G;
            if (e.innerWidth) nt.w = h(e.innerWidth, l);
            R.css({
                width: nt.w
            });
            if (e.height) nt.h = h(e.height, o) - Y - Q;
            if (e.innerHeight) nt.h = h(e.innerHeight, o);
            if (!e.innerHeight && !e.height) {
                e = R.wrapInner("<div style='overflow:auto'></div>").children();
                nt.h = e.height();
                e.replaceWith(e.children())
            }
            R.css({
                height: nt.h
            });
            ot.position(nt.transition === n ? 0 : nt.speed)
        }
    };
    ot.prep = function(t) {
        function o(t) {
            var n, r, i, o, u = I.length,
                l = nt.loop;
            ot.position(t, function() {
                function t() {
                    L && _[0].style.removeAttribute("filter")
                }
                if (rt) {
                    L && a && R.fadeIn(100);
                    R.show();
                    v(k);
                    W.show().html(nt.title);
                    if (u > 1) {
                        typeof nt.current === "string" && X.html(nt.current.replace(/\{current\}/, tt + 1).replace(/\{total\}/, u)).show();
                        $[l || tt < u - 1 ? "show" : "hide"]().html(nt.next);
                        J[l || tt ? "show" : "hide"]().html(nt.previous);
                        n = tt ? I[tt - 1] : I[u - 1];
                        i = tt < u - 1 ? I[tt + 1] : I[0];
                        nt.slideshow && V.show();
                        if (nt.preloading) {
                            o = e.data(i, b).href || i.href;
                            r = e.data(n, b).href || n.href;
                            o = e.isFunction(o) ? o.call(i) : o;
                            r = e.isFunction(r) ? r.call(n) : r;
                            if (p(o)) e("<img/>")[0].src = o;
                            if (p(r)) e("<img/>")[0].src = r
                        }
                    }
                    z.hide();
                    nt.transition === "fade" ? _.fadeTo(f, 1, function() {
                        t()
                    }) : t();
                    q.bind(s + w, function() {
                        ot.position(0)
                    });
                    v(x, nt.onComplete)
                }
            })
        }
        var i = "hidden";
        if (rt) {
            var a, f = nt.transition === n ? 0 : nt.speed;
            q.unbind(s + w);
            R.remove();
            R = c(r).html(t);
            R.hide().appendTo(U.show()).css({
                width: function() {
                    nt.w = nt.w || R.width();
                    nt.w = nt.mw && nt.mw < nt.w ? nt.mw : nt.w;
                    return nt.w
                }(),
                overflow: nt.scrolling ? u : i
            }).css({
                height: function() {
                    nt.h = nt.h || R.height();
                    nt.h = nt.mh && nt.mh < nt.h ? nt.mh : nt.h;
                    return nt.h
                }()
            }).prependTo(P);
            U.hide();
            e("#" + w + "Photo").css({
                cssFloat: n,
                marginLeft: u,
                marginRight: u
            });
            A && e("select").not(_.find("select")).filter(function() {
                return this.style.visibility !== i
            }).css({
                visibility: i
            }).one(T, function() {
                this.style.visibility = "inherit"
            });
            nt.transition === "fade" ? _.fadeTo(f, 0, function() {
                o(0)
            }) : o(f)
        }
    };
    ot.load = function(t) {
        var r, i, s, u = ot.prep;
        it = a;
        et = I[tt];
        t || (nt = d(e.extend({}, e.data(et, b))));
        v(C);
        v(S, nt.onLoad);
        nt.h = nt.height ? h(nt.height, o) - Y - Q : nt.innerHeight && h(nt.innerHeight, o);
        nt.w = nt.width ? h(nt.width, l) - Z - G : nt.innerWidth && h(nt.innerWidth, l);
        nt.mw = nt.w;
        nt.mh = nt.h;
        if (nt.maxWidth) {
            nt.mw = h(nt.maxWidth, l) - Z - G;
            nt.mw = nt.w && nt.w < nt.mw ? nt.w : nt.mw
        }
        if (nt.maxHeight) {
            nt.mh = h(nt.maxHeight, o) - Y - Q;
            nt.mh = nt.h && nt.h < nt.mh ? nt.h : nt.mh
        }
        r = nt.href;
        z.show();
        if (nt.inline) {
            c().hide().insertBefore(e(r)[0]).one(C, function() {
                e(this).replaceWith(R.children())
            });
            u(e(r))
        } else if (nt.iframe) {
            _.one(k, function() {
                var t = e("<iframe frameborder='0' style='width:100%; height:100%; border:0; display:block'/>")[0];
                t.name = w + +(new Date);
                t.src = nt.href;
                if (!nt.scrolling) t.scrolling = "no";
                if (L) t.allowtransparency = "true";
                e(t).appendTo(R).one(C, function() {
                    t.src = "//about:blank"
                })
            });
            u(" ")
        } else if (nt.html) u(nt.html);
        else if (p(r)) {
            i = new Image;
            i.onload = function() {
                var t;
                i.onload = null;
                i.id = w + "Photo";
                e(i).css({
                    border: n,
                    display: "block",
                    cssFloat: "left"
                });
                if (nt.scalePhotos) {
                    s = function() {
                        i.height -= i.height * t;
                        i.width -= i.width * t
                    };
                    if (nt.mw && i.width > nt.mw) {
                        t = (i.width - nt.mw) / i.width;
                        s()
                    }
                    if (nt.mh && i.height > nt.mh) {
                        t = (i.height - nt.mh) / i.height;
                        s()
                    }
                }
                if (nt.h) i.style.marginTop = Math.max(nt.h - i.height, 0) / 2 + "px";
                I[1] && (tt < I.length - 1 || nt.loop) && e(i).css({
                    cursor: "pointer"
                }).click(ot.next);
                if (L) i.style.msInterpolationMode = "bicubic";
                setTimeout(function() {
                    u(i)
                }, 1)
            };
            setTimeout(function() {
                i.src = r
            }, 1)
        } else r && U.load(r, function(t, n, r) {
            u(n === "error" ? "Request unsuccessful: " + r.statusText : e(this).children())
        })
    };
    ot.next = function() {
        if (!it) {
            tt = tt < I.length - 1 ? tt + 1 : 0;
            ot.load()
        }
    };
    ot.prev = function() {
        if (!it) {
            tt = tt ? tt - 1 : I.length - 1;
            ot.load()
        }
    };
    ot.close = function() {
        if (rt && !st) {
            st = a;
            rt = i;
            v(T, nt.onCleanup);
            q.unbind("." + w + " ." + O);
            M.fadeTo("fast", 0);
            _.stop().fadeTo("fast", 0, function() {
                v(C);
                R.remove();
                _.add(M).css({
                    opacity: 1,
                    cursor: u
                }).hide();
                setTimeout(function() {
                    st = i;
                    v(N, nt.onClosed)
                }, 1)
            })
        }
    };
    ot.element = function() {
        return e(et)
    };
    ot.settings = y;
    e(ot.init)
})(jQuery, this);
(function(e) {
    e(document).bind("cbox_complete", function() {
        e("#cboxOverlay").css("opacity", .8);
        e('#cboxContent a[rel="close"]').click(function() {
            e.colorbox.close()
        });
        if (e.colorbox.getContentEl().children().eq(0).hasClass("noClose")) e.colorbox.noClose()
    });
    e.colorbox.loading = function() {
        var t = e('<div class="loading"></div>').appendTo("#cboxContent").fadeIn("fast"),
            n = e.colorbox.getContentEl().fadeOut("fast");
        e("#cboxOverlay").css("opacity", .8);
        e(document).one("cbox_load", function() {
            t.fadeOut("fast", function() {
                t.remove()
            });
            n.fadeIn("fast")
        })
    };
    e.colorbox.noClose = function() {
        e("#colorbox").addClass("noClose")
    };
    e.colorbox.getContentEl = function() {
        return e("#cboxLoadedContent")
    }
})(jQuery);
(function(e) {
    function h(t, n) {
        throw e.extend(t, n), t
    }

    function p(e) {
        var n = [];
        if (c.call(e) !== s) return t;
        for (var r = 0, i = e.length; r < i; r++) n[r] = e[r].jqote_id;
        return n.length ? n.sort().join(".").replace(/(\b\d+\b)\.(?:\1(\.|$))+/g, "$1$2") : t
    }

    function d(n, r) {
        var i, o = [],
            r = r || f,
            a = c.call(n);
        if (a === u) return n.jqote_id ? [n] : t;
        if (a !== s) return [e.jqotec(n, r)];
        if (a === s)
            for (var l = 0, h = n.length; l < h; l++) return o.length ? o : t
    }
    var t = false,
        n = "UndefinedTemplateError",
        r = "TemplateCompilationError",
        i = "TemplateExecutionError",
        s = "[object Array]",
        o = "[object String]",
        u = "[object Function]",
        a = 1,
        f = "%",
        l = /^[^<]*(<[\w\W]+>)[^>]*$/,
        c = Object.prototype.toString;
    e.fn.extend({
        jqote: function(t, n) {
            var t = c.call(t) === s ? t : [t],
                r = "";
            this.each(function(i) {
                var s = e.jqotec(this, n);
                for (var o = 0; o < t.length; o++) r += s.call(t[o], i, o, t, s)
            });
            return r
        }
    });
    e.each({
        app: "append",
        pre: "prepend",
        sub: "html"
    }, function(t, n) {
        e.fn["jqote" + t] = function(r, i, s) {
            var o, u, a = e.jqote(r, i, s),
                f = !l.test(a) ? function(t) {
                    return e(document.createTextNode(t))
                } : e;
            if (!!(o = p(d(r)))) u = new RegExp("(^|\\.)" + o.split(".").join("\\.(.*)?") + "(\\.|$)");
            return this.each(function() {
                var r = f(a);
                e(this)[n](r);
                (r[0].nodeType === 3 ? e(this) : r).trigger("jqote." + t, [r, u])
            })
        }
    });
    e.extend({
        jqote: function(e, r, i) {
            var o = "",
                i = i || f,
                u = d(e);
            if (u === t) h(new Error("Empty or undefined template passed to $.jqote"), {
                type: n
            });
            r = c.call(r) !== s ? [r] : r;
            for (var a = 0, l = u.length; a < l; a++)
                for (var p = 0; p < r.length; p++) o += u[a].call(r[p], a, p, r, u[a]);
            return o
        },
        jqotec: function(t, s) {
            var u, p, d, s = s || f,
                v = c.call(t);
            if (v === o && l.test(t)) {
                p = d = t;
                if (u = e.jqotecache[t]) return u
            } else {
                p = v === o || t.nodeType ? e(t) : t instanceof jQuery ? t : null;
                if (!p[0] || !(d = p[0].innerHTML) && !(d = p.text())) h(new Error("Empty or undefined template passed to $.jqotec"), {
                    type: n
                });
                if (u = e.jqotecache[e.data(p[0], "jqote_id")]) return u
            }
            var y = "",
                w, E = d.replace(/\s*<!\[CDATA\[\s*|\s*\]\]>\s*|[\r\n\t]/g, "").split("<" + s).join(s + ">").split(s + ">");
            for (var S = 0, x = E.length; S < x; S++) y += E[S].charAt(0) !== "" ? "out+='" + E[S].replace(/(\\|["'])/g, "\\$1") + "'" : E[S].charAt(1) === "=" ? ";out+=(" + E[S].substr(2) + ");" : E[S].charAt(1) === "!" ? ";out+=$.jqotenc((" + E[S].substr(2) + "));" : ";" + E[S].substr(1);
            y = "try{" + ('var out="";' + y + ";return out;").split("out+='';").join("").split('var out="";out+=').join("var out=") + '}catch(e){e.type="' + i + '";e.args=arguments;e.template=arguments.callee.toString();throw e;}';
            try {
                var T = new Function("i, j, data, fn", y)
            } catch (p) {
                h(p, {
                    type: r
                })
            }
            w = p instanceof jQuery ? e.data(p[0], "jqote_id", a) : p;
            return e.jqotecache[w] = (T.jqote_id = a++, T)
        },
        jqotefn: function(n) {
            var r = c.call(n),
                i = r === o && l.test(n) ? n : e.data(e(n)[0], "jqote_id");
            return e.jqotecache[i] || t
        },
        jqotetag: function(e) {
            if (c.call(e) === o) f = e
        },
        jqotenc: function(e) {
            return e.toString().replace(/&(?!\w+;)/g, "&#38;").split("<").join("&#60;").split(">").join("&#62;").split('"').join("&#34;").split("'").join("&#39;")
        },
        jqotecache: {}
    });
    e.event.special.jqote = {
        add: function(e) {
            var t, n = e.handler,
                r = !e.data ? [] : c.call(e.data) !== s ? [e.data] : e.data;
            if (!e.namespace) e.namespace = "app.pre.sub";
            if (!r.length || !(t = p(d(r)))) return;
            e.handler = function(e, r, i) {
                return !i || i.test(t) ? n.apply(this, [e, r]) : null
            }
        }
    }
})(jQuery);
(function(e) {
    FlagWidget = function(e, t) {
        if (e) this.init(e, t)
    };
    e.extend(FlagWidget.prototype, {
        name: "flagWidget",
        success: function() {},
        init: function(t, n) {
            this.element = e(t);
            e.data(t, this.name, this);
            var r = this;
            this.element.show();
            this.element.children(".click-target").click(function() {
                r.togglePanel();
                return false
            });
            var i = function(e) {
                e.data.hidePanel()
            };
            this.element.hover(function() {
                jQuery(document).unbind("click", i)
            }, function() {
                jQuery(document).bind("click", r, i)
            });
            this.element.find("ul li a").click(function() {
                r.itemClick(this);
                return false
            });
            if (n && n.success) this.success = n.success;
            this.element.parent().submit(function() {
                var e = jQuery(this);
                if (e.find('input[name="user_flag[name]"]').val() === "Other") {
                    var t = e.find('input[name="user_flag[other_note]"]').val();
                    if (t === undefined || jQuery.trim(t) === "") return false
                }
                r.hidePanel();
                r.element.addClass("spinner");
                jQuery.post(e.attr("action"), e.serialize(), function(e) {
                    r.element.children(".click-target").unbind("click");
                    r.element.removeClass("spinner");
                    r.element.addClass("success").delay(2e3).fadeOut(1e3);
                    r.success()
                });
                return false
            })
        },
        showPanel: function() {
            if (!this.element.hasClass("expanded")) this.element.addClass("expanded")
        },
        hidePanel: function() {
            this.element.removeClass("expanded");
            this.element.find("li.other.clicked").removeClass("clicked")
        },
        togglePanel: function() {
            if (this.element.hasClass("expanded")) this.hidePanel();
            else this.showPanel()
        },
        itemClick: function(e) {
            var t = jQuery(e);
            var n = this.element.parent();
            var r = t.parent();
            n.find('input[name="user_flag[name]"]').val(t.data("reason-id"));
            if (r.hasClass("other")) {
                var i = r.find("input");
                i.val("");
                r.addClass("clicked")
            } else {
                r.find("input").val("");
                n.submit()
            }
        }
    });
    e.fn.flagWidget = function(t) {
        var n = e.makeArray(arguments),
            r = n.slice(1);
        return this.each(function() {
            var i = e.data(this, "flagWidget");
            if (i)
                if (typeof t === "string") i[t].apply(i, r);
                else {
                    if (i.update) i.update.apply(i, n)
                } else new FlagWidget(this, t)
        })
    };
    e.fn.clickStar = function(t) {
        var n = e("#star_count");
        var r = parseInt(n.html());
        return this.each(function() {
            var i = e(this).data("hosting_id");
            if (i == undefined || i == null) e(this).data("hosting_id", parseInt(e(this).attr("id").substring(5)));
            else return;
            if (e.inArray(e(this).data("hosting_id"), Cogzidel.Bookmarks.starredIds) != -1) e(this).find("div.star_icon").addClass("starred");
            e(this).click(function() {
                if (!Cogzidel.Utils.isUserLoggedIn) {
                    if (confirm("You must create a free account or login to use this feature. Continue?")) window.location = "/signup_login";
                    return
                }
                var i = e(this).find("div.star_icon");
                var s = i.hasClass("starred");
                if (s) {
                    var o = e.inArray(e(this).data("hosting_id"), Cogzidel.Bookmarks.starredIds);
                    if (o != -1) Cogzidel.Bookmarks.starredIds.splice(o, 1);
                    r--;
                    i.removeClass("starred")
                } else {
                    Cogzidel.Bookmarks.starredIds.push(e(this).data("hosting_id"));
                    r++;
                    i.addClass("starred")
                }
                n.fadeOut(100, function() {
                    n.html(r);
                    n.fadeIn(300, function() {
                        var e = jQuery("#star-indicator");
                        if (e.is(":visible") && r == 0) e.fadeOut(500);
                        else if (!e.is(":visible") && r > 0) e.fadeIn(500)
                    })
                });
                jQuery.ajax({
                    url: "/favorites/" + e(this).data("hosting_id") + "/star",
                    type: s ? "DELETE" : "POST",
                    dataType: "json",
                    async: true,
                    context: this,
                    success: function(t) {
                        var n = e(this).find("div.star_icon");
                        if (t.result == "login") {
                            n.removeClass("starred");
                            if (confirm("You must create a free account to use this feature. Continue?")) window.location = t.redirect_to
                        } else if (t.result == "deleted" && n.hasClass("starred")) n.removeClass("starred");
                        else if (t.result == "created" && !n.hasClass("starred")) n.addClass("starred")
                    }
                });
                s = !s;
                if (t) t(this, s);
                return false
            })
        })
    }
})(jQuery);
(function(e, t) {
    var n = [],
        r = "38,38,40,40,37,39,37,39,66,65";
    t(window).bind("keydown", function(e) {
        n.push(e.keyCode);
        if (n.toString().indexOf(r) >= 0) {
            n = [];
            t(window).trigger("konami")
        }
    });
    e.EasterEgg = function() {
        function h(e, t) {
            e.animate({
                left: "+" + (s + 100) + "px"
            }, t, function() {
                p()
            })
        }

        function p() {
            n++;
            if (n === e) $container.remove()
        }
        var e = 20,
            n = 0,
            r = t("#logo img"),
            i = t(window).height(),
            s = t(window).width(),
            o, u, a, f, l;
        $container = t("<div/>").attr("id", "easter-egg-container").prependTo("body").css({
            overflow: "hidden",
            width: s + "px",
            height: i + "px",
            position: "absolute",
            top: 0,
            left: 0,
            "z-index": 99999
        });
        for (var c = 0; c < e; c++) {
            l = Math.random() * 5e3;
            u = function() {
                a = Math.random() + .5;
                f = 5e3 / a;
                o = r.clone().addClass("easter-egg").appendTo("#easter-egg-container").css({
                    position: "absolute",
                    top: i * Math.random() + "px",
                    left: "-200px",
                    "-webkit-transform": "scale(" + a + ")",
                    "-moz-transform": "scale(" + a + ")"
                });
                h(o, f)
            };
            setTimeout(u, l)
        }
    };
    t(window).bind("konami", e.EasterEgg)
})(Cogzidel, jQuery)
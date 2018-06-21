function calculate_shipping_zone() {
    var e = ["AT", "BE", "BG", "CY", "CZ", "DE", "DK", "EE", "ES", "FI", "FR", "GB", "GR", "HR", "HU", "IE", "IT", "LT", "LU", "LV", "MT", "NL", "PL", "PT", "RO", "SE", "SI", "SK"],
        s = "",
        a = "";
    f_c = $("#sent-country-from").val(), t_c = $("#sent-country-to").val(), s = $.inArray(f_c, e) > -1 ? "europe" : "worldwide", a = $.inArray(t_c, e) > -1 ? "europe" : "worldwide", zone = "europe" === s && "europe" === a ? "europe" : "worldwide", "GB" !== f_c && (zone = "worldwide"), "worldwide" === zone ? ($("#std-cb").hide(), $("#service-express").attr("checked", "checked"), $("#service-standard").prop("checked", !1), $("#service-express").prop("checked", !0), service_type = "express", $("#customs-yes-no").val("yes"), $("#customs-yes-no").attr("disabled", "disabled"), $("#customs-info").show()) : ($("#service-standard").prop("checked", !0), $("#std-cb").show(), service_type = "standard", $("#service-express").prop("checked", !1))
}

function remove_row_parent(e) {
    var s = $(e).parent().parent().attr("id"),
        a = parseFloat($(e).parent().parent().attr("data-price")),
        t = parseFloat(grand_total),
        r = t - a;
    grand_total = r, $("#ajax-price").html(currency_symbol + grand_total.toFixed(2)), $("#list-" + s).remove(), $(e).parent().parent().remove()
}

function add_to_quote_from_modal(e) {
    var s = $(e).attr("data-price"),
        a = $(e).attr("data-rate"),
        t = $(e).attr("data-object-id"),
        r = $(e).attr("data-description"),
        o = $(e).attr("data-dimensions"),
        l = $(e).attr("data-weight"),
        d = $(e).attr("data-orig"),
        n = ($(e).attr("data-currency"), $(e).attr("data-provider")),
        i = '<tr data-price="' + s + '" class="basketItem" id="' + t + '" data-rate="' + a + '" data-oprice="' + d + '">';
    i += "<td class='" + r + "'>" + n + " " + r + "</td>", i += '<td class="hidden-xs-down xdimensions">' + o + "</td>", i += '<td class="hidden-xs-down xweight">' + l + "</td>", i += '<th class="text-center" scope="row"><a href="Javascript:void(0);" onClick="remove_row_parent(this)"; class="smallredcross">', i += '<i class="fa fa-times" aria-hidden="true"></i></a></th></tr>', $("#orderStep3 tbody").append(i), $(".fake-price").remove(), $("#ajax-items-list").append("<div id='list-" + t + "'>1- " + n + " " + r + " - " + currency_symbol + " " + s + "</div>"), $("#itemWidth, #itemHeight, #itemLength, #itemWeight, #itemDesc").val(""), total = parseFloat(grand_total);
    var c = total + parseFloat(s);
    grand_total = c, $("#ajax-price").html(currency_symbol + grand_total.toFixed(2)), $("#rates-modal").modal("hide")
}

function change_address(e) {
    $(".col-" + e + "-to-hide").slideDown("fast"), $(".col-" + e + "-to-show").slideUp("fast"), $("#step-1-controls").slideUp("fast")
}
$ = jQuery;
var collection_address = {},
    delivery_address = {},
    label_address = {},
    delivery_service = "standard",
    post_labels = "off",
    cancellation_cover = "off",
    current_insurance_price = 0,
    ems_user_id = 0,
    nouser_first_name = "",
    nouser_last_name = "",
    nouser_email = "",
    journey_type = "single",
    service_type = "standard",
    f_c = "GB",
    t_c = "GB",
    zone = "worldwide";
$(document).ready(function() {
    function e() {
        label_address.addressType = $("#label-address-type").val(), label_address.custName = $("#label-customer-name").val(), label_address.custPhone_CountryCode = $("#label-phone-code").find(":selected").attr("data-code"), label_address.custPhone = $("#label-phone").val(), label_address.custAddress1 = $("#label-address-1").val(), label_address.custAddress2 = $("#label-address-2").val(), label_address.custCity = $("#label-city").val(), label_address.cusPostcode = $("#label-postcode").val(), label_address.country = $("#label-country").val(), label_address.buzzer = $("#label-buzzer").val(), $(".waiting-cover").fadeIn("fast");
        var e = $("#ems-session").val(),
            s = $("#shipping-date").val(),
            a = [];
        $("#orderStep3 tbody tr").each(function() {
            var e = {};
            e.shipping_object = $(this).attr("id"), e.rate_object = $(this).attr("data-rate"), e.description = $(".xdesc", this).html(), e.dimensions = $(".xdimensions", this).html(), e.weight = $(".xweight", this).html(), e.price = $(this).attr("data-price"), e.original_price = $(this).attr("data-oprice"), a.push(e)
        });
        var t = JSON.stringify(a),
            r = $("#labelHolders").val(),
            o = $("#postMyLabels").val(),
            l = $("#ccCover").val(),
            d = $("#coverLevel").val(),
            n = $("#whyUs").val(),
            i = grand_total;
        $.post(ajax_url, {
            ems_cmd: "save_cart",
            ems_session: e,
            collection_address: collection_address,
            delivery_address: delivery_address,
            shipping_date: s,
            delivery_service: delivery_service,
            parcels: t,
            currency: base_currency,
            send_free_labels: r,
            post_free_labels: o,
            cancellation_cover: l,
            level_of_cover: d,
            why_us: n,
            total: i,
            label_address: label_address,
            ems_user_id: ems_user_id,
            nouser_first_name: nouser_first_name,
            nouser_last_name: nouser_last_name,
            nouser_email: nouser_email
        }).done(function(e) {
            "success" === e ? window.location = $("#ems-base-url").val() + "/pay" : $(".waiting-cover").fadeOut("fast")
        })
    }

    function s() {
        l.abort();
        var e = $("#quote-Origin_CountryIso").val(),
            s = $("#quote-Destination_CountryIso").val(),
            a = $("#quote-width").val(),
            t = $("#quote-height").val(),
            r = $("#quote-length").val();
        if ("" == e || "" == s) return $("#miniload").hide(), $("#error_msg").css("display", "block"), !1;
        if ($("#ajax-show-info .btn").hide().attr("href", "Javascript:void(0);"), $("#miniload").show(), $("#quote-wrap").slideUp("slow"), $("#error_msg").css("display", "none"), $("#OneWayJourney").is(":checked")) var o = "single";
        else var o = "return";
        var _ = $("#spinner-url").val(),
            v = "<img src='" + _ + "' />";
        $("#data-5-express, #data-5-standard, #data-15-express, #data-15-standard, #data-30-express, #data-30-standard").html(v), 
        $.when($.post(ajax_url, {
            from: e,
            to: s,
            journey: o,
            action: "do_quick_quote",
            width: a,
            height: t,
            length: r,
            weight: 5
        }).done(function(e) {
            "" != e && $("#quote-wrap").slideDown("slow");
            var s = jQuery.parseJSON(e),
                a = s.currency_symbol;
            s.w5kg.hasOwnProperty("standard") ? (d = parseFloat(s.w5kg.standard.amount), $("#data-5-standard").html(a + d.toFixed(2)), $("#standard-5-button").attr("href", s.w5kg.standard.url)) : $(".standard-results").slideUp("fast"), c = parseFloat(s.w5kg.express.amount), $("#data-5-express").html(a + c.toFixed(2)), $("#express-5-button").attr("href", s.w5kg.express.url), $("#miniload").hide(), $("#ajax-show-info .btn").show();

            s.w15kg.hasOwnProperty("standard") ? (n = parseFloat(s.w15kg.standard.amount), $("#data-15-standard").html(a + n.toFixed(2)), $("#standard-15-button").attr("href", s.w15kg.standard.url)) : $(".standard-results").slideUp("fast"), u = parseFloat(s.w15kg.express.amount), $("#data-15-express").html(a + u.toFixed(2)), $("#express-15-button").attr("href", s.w15kg.express.url), $("#ajax-show-info .btn").show();



            s.w30kg.hasOwnProperty("standard") ? (i = parseFloat(s.w30kg.standard.amount), $("#data-30-standard").html(a + i.toFixed(2)), $("#standard-30-button").attr("href", s.w30kg.standard.url)) : $(".standard-results").slideUp("fast"), p = parseFloat(s.w30kg.express.amount), $("#data-30-express").html(a + p.toFixed(2)), $("#express-30-button").attr("href", s.w30kg.express.url), $("#ajax-show-info .btn").show();


        })

/*
        , $.post(ajax_url, {
            from: e,
            to: s,
            journey: o,
            action: "do_quick_quote",
            width: a,
            height: t,
            length: r,
            weight: 15
        }).done(function(e) {
            "" != e && $("#quote-wrap").slideDown("slow");
            var s = jQuery.parseJSON(e),
                a = s.currency_symbol;
            s.w15kg.hasOwnProperty("standard") ? (n = parseFloat(s.w15kg.standard.amount), $("#data-15-standard").html(a + n.toFixed(2)), $("#standard-15-button").attr("href", s.w15kg.standard.url)) : $(".standard-results").slideUp("fast"), u = parseFloat(s.w15kg.express.amount), $("#data-15-express").html(a + u.toFixed(2)), $("#express-15-button").attr("href", s.w15kg.express.url), $("#ajax-show-info .btn").show()
        }), $.post(ajax_url, {
            from: e,
            to: s,
            journey: o,
            action: "do_quick_quote",
            width: a,
            height: t,
            length: r,
            weight: 30
        }).done(function(e) {
            "" != e && $("#quote-wrap").slideDown("slow");
            var s = jQuery.parseJSON(e),
                a = s.currency_symbol;
            s.w30kg.hasOwnProperty("standard") ? (i = parseFloat(s.w30kg.standard.amount), $("#data-30-standard").html(a + i.toFixed(2)), $("#standard-30-button").attr("href", s.w30kg.standard.url)) : $(".standard-results").slideUp("fast"), p = parseFloat(s.w30kg.express.amount), $("#data-30-express").html(a + p.toFixed(2)), $("#express-30-button").attr("href", s.w30kg.express.url), $("#ajax-show-info .btn").show()
        })*/


        ).then(function() {})
    }

    function a() {
        var e = $(".shop-packs").find(":selected").data("price"),
            s = parseFloat($("#price-per-pack").val()),
            a = $(".pack-numbers").val();
        if (parseFloat(a) > 0) var t = s * a,
            r = parseFloat(e) + t,
            o = "(£" + r.toFixed(2) + ")";
        else var o = "(£" + e.toFixed(2) + ")";
        $(".shopping-buy-now span").html(o)
    }
    if ($("#OneWayJourney").click(function() {
            journey_type = "single"
        }), $("#ReturnJourney").click(function() {
            journey_type = "return"
        }), $("#quoted-price").val(""), $(".proceed-to-order").click(function(e) {
            e.preventDefault();
            var s = $(this).attr("data-price");
            $("#quoted-price").val(s), $("#quick-quote").submit()
        }), $("body").dblclick(function() {
            $.post("/ems/wp-admin/admin-ajax.php", {
                action: "retrieve_batch",
                ems_cmd: "retrieve_batch",
                batch_id: "670d8bfaa12840baa847ca6ec8bf1185"
            }).done(function(e) {})
        }), $("body").hasClass("page-template-orderpage") && ($("#Metric").click(function() {
            units_type = "metric", $(".lwunit").html("(cm)"), $(".wunit").html("(kg)")
        }), $("#Imperial").click(function() {
            units_type = "imperial", $(".lwunit").html("(in)"), $(".wunit").html("(lb)")
        }), $("#add-a-bag").click(function() {
            if ("yes" === $("#customs-yes-no").val() && "" === $("#customs-value").val()) return void alert("Please enter a value for your parcel for customs before adding your bag!");
            var e = units_type,
                s = $("#itemWidth").val(),
                a = $("#itemHeight").val(),
                t = $("#itemLength").val(),
                r = $("#itemWeight").val(),
                o = $("#itemType").val(),
                l = $("#itemDesc").val(),
                d = $("#state-from").val(),
                n = $("#state-to").val(),
                i = $("#customs-yes-no").val(),
                c = $("#customs-value").val(),
                u = $("#customs-contents").val(),
                p = $("#customs-contents-other").val();
            "" != s && "" != a && "" != t && "" != r && $(".waiting-cover").fadeIn("slow"), $.post(ajax_url, {
                action: "ems_create_shipment",
                from_country: collection_address.country,
                from_postcode: collection_address.cusPostcode,
                from_city: collection_address.custCity,
                from_address_1: collection_address.custAddress1,
                from_address_2: collection_address.custAddress2,
                from_name: collection_address.custName,
                from_telephone: collection_address.custPhone_CountryCode + collection_address.custPhone,
                to_postcode: delivery_address.cusPostcode,
                to_country: delivery_address.country,
                to_address_1: delivery_address.custAddress1,
                to_address_2: delivery_address.custAddress2,
                to_name: delivery_address.custName,
                to_telephone: delivery_address.custPhone_CountryCode + delivery_address.custPhone,
                to_city: delivery_address.custCity,
                state_from: d,
                state_to: n,
                item_width: s,
                item_height: a,
                item_length: t,
                item_weight: r,
                item_type: o,
                item_description: l,
                units: e,
                journey_type: journey_type,
                shipping_date: $("#shipping-date").val(),
                return_shipping_date: $("#return-date").val(),
                delivery_service: delivery_service,
                currency: base_currency,
                service_type: service_type,
                has_customs: i,
                customs_contents: u,
                customs_contents_other: p,
                customs_value: c
            }).done(function(o) {
                if ($("#customs-value, #customs-contents-other").val(""), $("#customs-contents").val("GIFT"), $("#show-customs-other").hide(), $(".waiting-cover").fadeOut("slow"), "norates" === o) $("#norates-modal").modal();
                else {
                    var d = '<table class="table table-striped table-hover">',
                        n = jQuery.parseJSON(o);
                    if ("undefined" != typeof n.message) return $(".waiting-cover").fadeOut("fast"), $("#message-modal .modal-body").html(n.message), $("#message-modal").modal(), !1;
                    $.each(n, function(o, n) {
                        var i = n.object_id,
                            c = n.amount,
                            u = n.provider,
                            p = n.rate,
                            $ = n.original_price,
                            _ = n.currency,
                            v = n.provider_image,
                            m = n.arrives_by,
                            h = n.servicelevel_name,
                            f = n.days;
                        if ("metric" === e) var y = "cm",
                            g = "kg";
                        else var y = "in",
                            g = "lb";
                        d += "<tr><td><img src='" + v + "' /><br />" + u + "</td>", d += "<td><strong>Arrives by:</strong> " + m + " (" + f + " days)<br />" + h + "</td>", d += "<td>" + c + " " + _ + "</td>", d += "<td><a class='btn btn-primary btn-lg' href='Javascript:void(0);' data-price='" + c + "' data-rate='" + p + "' data-object-id='" + i + "'", d += "data-description='" + l + "' data-dimensions='" + s + y + " x " + a + y + " x " + t + y + "'", d += "data-weight='" + r + g + "' data-orig='" + $ + "' data-provider='" + u + "'", d += "data-currency='" + _ + "' onClick='add_to_quote_from_modal(this);'>+ Add to Quote</a></td></tr>"
                    }), d += "</table>", $("#rates-modal .modal-body").html(d), $("#rates-modal").modal()
                }
            })
        })), $("#set-collect-address").click(function() {
            var e = "";
            if ($(".req1").each(function() {
                    "" === $(this).val() && ($(this).addClass("errors"), e += ".")
                }), "" === e) {
                collection_address.addressType = $("#addressType").val(), collection_address.custName = $("#custName").val(), collection_address.custPhone_CountryCode = $("#custPhone_CountryCode").find(":selected").attr("data-code"), collection_address.custPhone = $("#custPhone").val(), collection_address.custAddress1 = $("#custAddress1").val(), collection_address.custAddress2 = $("#custAddress2").val(), collection_address.custCity = $("#custCity").val(), collection_address.cusPostcode = $("#custPostcode").val(), collection_address.country = $("#custOrigin_CountryIso").val(), collection_address.buzzer = $("#custBuzzer").val();
                var s = "<h3>Collection Address</h3>" + collection_address.custName + "<br />" + collection_address.custAddress1 + "<br />";
                "" != collection_address.custAddress2 && (s += collection_address.custAddress2 + "<br />"), s += collection_address.custCity + " " + collection_address.country, $(".col-1-to-show").html(s), $(".col-1-to-hide").slideUp("fast"), $(".col-1-to-show").slideDown("slow"), "" != $(".col-1-to-show").html() && "" != $(".col-2-to-show").html() && $("#step-1-controls").slideDown("slow"), $("#collect-errors").hide()
            } else $("#collect-errors").fadeIn("slow")
        }), $("#set-deliver-address").click(function() {
            var e = "";
            if ($(".req2").each(function() {
                    "" === $(this).val() && ($(this).addClass("errors"), e += ".")
                }), "" === e) {
                delivery_address.addressType = $("#addressType2").val(), delivery_address.custName = $("#custName2").val(), delivery_address.custPhone_CountryCode = $("#custPhone_CountryCode2").find(":selected").attr("data-code"), delivery_address.custPhone = $("#custPhone2").val(), delivery_address.custAddress1 = $("#custAddress1_2").val(), delivery_address.custAddress2 = $("#custAddress2_2").val(), delivery_address.custCity = $("#custCity2").val(), delivery_address.cusPostcode = $("#custPostcode2").val(), delivery_address.country = $("#custOrigin_CountryIso2").val(), e.buzzer = $("#custBuzzer2").val();
                var s = "<h3>Delivery Address</h3>" + delivery_address.custName + "<br />" + delivery_address.custAddress1 + "<br />";
                "" != delivery_address.custAddress2 && (s += delivery_address.custAddress2 + "<br />"), s += delivery_address.custCity + " " + delivery_address.country, $(".col-2-to-show").html(s), $(".col-2-to-hide").slideUp("fast"), $(".col-2-to-show").slideDown("slow"), "" != $(".col-1-to-show").html() && "" != $(".col-2-to-show").html() && $("#step-1-controls").slideDown("slow"), $("#deliver-errors").hide()
            } else $("#deliver-errors").fadeIn("slow")
        }), $("#go-to-step-2").click(function() {
            calculate_shipping_zone(), $("#orderStep1").fadeOut("fast"), $("#orderStep2").fadeIn("fast"), $("#cc").html($("#custOrigin_CountryIso").val() + ' <i class="fa fa-arrow-right orangetext"></i> '), $("#dc").html($("#custOrigin_CountryIso2").val()), $("#tofromcc").fadeIn("slow"), $(".entry-header .nav-link").removeClass("active"), $("#pill-step-2").addClass("active"), $("#pill-step-2").removeClass("disabled"), "return" === journey_type && $("#return-date-select").show()
        }), $(".entry-header .nav-link").click(function() {
            if (!$(this).hasClass("disabled")) {
                $("#orderStep1, #orderStep2, #orderStep3, #orderStep4").fadeOut("fast");
                var e = $(this).attr("data-step");
                $("#orderStep" + e).fadeIn("slow"), $(".nav-link").removeClass("active"), $("#pill-step-" + e).addClass("active")
            }
        }), $("#go-to-step-3").click(function() {
            "" != $("#shipping-date").val() ? ($("#orderStep2").fadeOut("fast"), $("#orderStep3").fadeIn("fast"), $(".entry-header .nav-link").removeClass("active"), $("#pill-step-3").addClass("active"), $("#pill-step-3").removeClass("disabled"), delivery_service = $("#Standard").is(":checked") ? "standard" : "express") : alert("Please select a collection date")
        }), $("#go-to-step-4").click(function() {
            $("#ca-copy").html($("#custAddress1").val()), "" != $("#orderStep3 tbody").html() ? ($("#orderStep3").fadeOut("fast"), $("#orderStep4").fadeIn("fast"), $(".entry-header .nav-link").removeClass("active"), $("#pill-step-4").addClass("active"), $("#pill-step-4").removeClass("disabled")) : alert("Please add your parcels before proceeding.")
        }), $("#shipping-date").val(""), "GBP" === base_currency) var t = $("#labels_price").val(),
        r = parseFloat(t);
    else var t = $("#labels_price").val(),
        r = parseFloat($("#exchange-rate").val()) * parseFloat(t);
    if ($("#post-labels-price-x").html(currency_symbol + r.toFixed(2)), $("#postMyLabels").change(function() {
            var e = $("#labels_price").val(),
                s = parseFloat(e);
            if ("GBP" === base_currency) var a = s;
            else var a = parseFloat($("#exchange-rate").val()) * s;
            if ("1" === $(this).val()) {
                if ($("#list-post-labels").show(), "off" === post_labels) {
                    post_labels = "on";
                    var t = (grand_total, parseFloat(grand_total) + a);
                    grand_total = t, $("#ajax-price").html(currency_symbol + grand_total.toFixed(2)), $("#sidebar-post-labels-price").html(currency_symbol + a.toFixed(2))
                }
            } else {
                if ("on" === post_labels) {
                    post_labels = "off";
                    var t = (grand_total, parseFloat(grand_total) - a);
                    grand_total = t, $("#ajax-price").html(currency_symbol + grand_total.toFixed(2)), $("#sidebar-post-labels-price").html(currency_symbol + a.toFixed(2))
                }
                $("#list-post-labels").hide()
            }
        }), "GBP" != base_currency) var o = 4 * parseFloat($("#exchange-rate").val());
    else var o = 4;
    $("#cancellation-cover-price").html(currency_symbol + o), $("#ccCover").change(function() {
        if ("1" === $(this).val()) {
            if ($("#list-cancellation-cover").show(), "off" === cancellation_cover) {
                cancellation_cover = "on";
                var e = (grand_total, parseFloat(grand_total) + o);
                grand_total = e, $("#ajax-price").html(currency_symbol + grand_total.toFixed(2)), $("#sidebar-cancellation-cover-price").html(currency_symbol + o.toFixed(2))
            }
        } else {
            if ("on" === cancellation_cover) {
                cancellation_cover = "off";
                var e = (grand_total, parseFloat(grand_total) - o);
                grand_total = e, $("#ajax-price").html(currency_symbol + grand_total.toFixed(2)), $("#sidebar-cancellation-cover-price").html(currency_symbol + o.toFixed(2))
            }
            $("#list-cancellation-cover").hide()
        }
    }), $("#coverLevel").change(function() {
        var e = $(this).val();
        switch (e) {
            case "1":
                var s = 0,
                    a = "60";
                break;
            case "2":
                if ("GBP" === base_currency) var s = 5;
                else var s = 5 * parseFloat($("#exchange-rate").val());
                var a = "125";
                break;
            case "3":
                if ("GBP" === base_currency) var s = 10;
                else var s = 10 * parseFloat($("#exchange-rate").val());
                var a = "300";
                break;
            case "4":
                if ("GBP" === base_currency) var s = 20;
                else var s = 20 * parseFloat($("#exchange-rate").val());
                var a = "600"
        }
        $("#selected-insurance-price").html(currency_symbol + s.toFixed(2)), $("#selected-insurance-amount").html(a);
        var t = parseFloat(current_insurance_price),
            r = parseFloat(grand_total);
        grand_total = r - t;
        var o = grand_total + s;
        grand_total = o, current_insurance_price = s, $("#ajax-price").html(currency_symbol + grand_total.toFixed(2)), $("#selected-insurance-price").html(currency_symbol + s.toFixed(2))
    }), $("#labelHolders, #postMyLabels, #ccCover").val("2"), $("#login-register-option").click(function() {
        $("#insurance-options").slideUp("fast"), $("#accountSetup").slideDown("fast")
    }), $("#do-register").click(function() {
        $("#user-password").val() != $("#user-password-confirm").val() ? alert("The passwords you entered do not match") : $.post(ajax_url, {
            ems_cmd: "ems_register",
            first_name: $("#user-first-name").val(),
            last_name: $("#user-last-name").val(),
            email_address: $("#user-email").val(),
            password: $("#user-password").val()
        }).done(function(s) {
            var a = jQuery.parseJSON(s);
            "error" === a.status ? $("#register-msg").html(a.reason).fadeIn("slow") : (ems_user_id = a.user_id, e())
        })
    }), $("#do-register-page").click(function() {
        $("#user-password").val() != $("#user-password-confirm").val() ? alert("The passwords you entered do not match") : $.post(ajax_url, {
            ems_cmd: "ems_register",
            first_name: $("#user-first-name").val(),
            last_name: $("#user-last-name").val(),
            email_address: $("#user-email").val(),
            password: $("#user-password").val()
        }).done(function(e) {
            var s = jQuery.parseJSON(e);
            "error" === s.status ? $("#register-msg").html(s.reason).fadeIn("slow") : ($("#register-msg").hide(), $("#register-success").fadeIn("slow"))
        })
    }), $("#do-login").click(function() {
        var s = $("#login-email").val(),
            a = $("#login-password").val();
        $.post(ajax_url, {
            ems_cmd: "ems_login",
            email_address: s,
            password: a
        }).done(function(s) {
            var a = jQuery.parseJSON(s);
            "error" === a.status ? $("#login-msg").html(a.reason).fadeIn("slow") : (ems_user_id = a.user_id, e())
        })
    }), $("#do-login-page").click(function() {
        var e = $("#login-email").val(),
            s = $("#login-password").val();
        $.post(ajax_url, {
            ems_cmd: "ems_login",
            email_address: e,
            password: s
        }).done(function(e) {
            var s = jQuery.parseJSON(e);
            "error" === s.status ? $("#login-msg").html(s.reason).fadeIn("slow") : (ems_user_id = s.user_id, $("#login-form").submit())
        })
    }), $("#accountNo").click(function() {
        $("#accountYesForm").slideUp("fast"), $("#no-account").slideDown("fast")
    }), $("#accountYes").prop("checked", !0), $("#accountNo").prop("checked", !1), $("#accountAlready").prop("checked", !1), $("#accountAlready, #accountYes").click(function() {
        $("#no-account").slideUp("fast")
    }), $("#accountNo").click(function() {
        $("#accountAlreadyForm").slideUp("fast")
    }), $("#fill-user").click(function() {
        var s = $("#nouser-first-name").val(),
            a = $("#nouser-last-name").val(),
            t = $("#nouser-email").val();
        if ("" == t || "" === s || "" === a) {
            var r = "Please provide all fields";
            $("#nouser-msg").html(r).fadeIn("slow")
        } else nouser_first_name = s, nouser_last_name = a, nouser_email = t, e()
    }), $("#user-account-form").submit(function() {
        return "" != $("#user-password").val() && $("#user-password").val() != $("#user-password-confirm").val() ? (alert("Your passwords do not match"), !1) : !0
    }), $(".bnbtn").click(function(e) {
        e.preventDefault();
        var s = $("#Collection_Postcode").val(),
            a = $("#Destination_Postcode").val(),
            t = "";
        "" != s && (t += "&fpc=" + s), "" != a && (t += "&tpc=" + a);
        var r = $(this).attr("href"),
            o = r + t;
        window.location = o
    }), $("#custPhone_CountryCode option, #custPhone_CountryCode2 option").each(function() {
        $(this).text($(this).attr("data-code"))
    });
    var l = $.ajax({
            type: "POST",
            url: ajax_url,
            success: function(e) {}
        }),
        d = 0,
        n = 0,
        i = 0,
        c = 0,
        u = 0,
        p = 0;
    $(".page-template-quotepage #Collection_Postcode, .page-template-quotepage #Destination_Postcode").keyup(function() {
        var e = $("#Collection_Postcode").val(),
            s = $("#Destination_Postcode").val(),
            a = "&fp=" + e + "&tp=" + s;
        $("#ajax-show-info .btn").each(function() {
            var e = $(this).attr("data-orig"),
                s = e + a;
            $(this).attr("href", s)
        })
    }), $("#quote-Origin_CountryIso, #quote-Destination_CountryIso").change(function() {
        s()
    }), $("#OneWayJourney, #ReturnJourney").click(function() {
        s()
    }), ($("body").hasClass("page-template-quotepage") || $(".destinations-sidebar").length) && "" != $("#quote-Origin_CountryIso").val() && "" != $("#quote-Destination_CountryIso").val() && s(), $("#custOrigin_CountryIso").change(function() {
        "US" == $(this).val() ? $("#state-list-from").slideDown("fast") : $("#state-list-from").slideUp("fast")
    }), $("#custOrigin_CountryIso2").change(function() {
        "US" == $(this).val() ? $("#state-list-to").slideDown("fast") : $("#state-list-to").slideUp("fast")
    }), $("#service-express").click(function() {
        service_type = "express"
    }), $("#service-standard").click(function() {
        service_type = "standard"
    }), service_type = $("#service-express").is(":checked") ? "express" : "standard", $("#custOrigin_CountryIso").change(function() {
        f_c = $(this).val(), calculate_shipping_zone()
    }), $("#custOrigin_CountryIso2").change(function() {
        t_c = $(this).val(), calculate_shipping_zone()
    }), f_c = $("#custOrigin_CountryIso").val(), t_c = $("#custOrigin_CountryIso2").val(), calculate_shipping_zone(), $("#gobacktostep1").click(function() {
        $("#orderStep1, #orderStep2, #orderStep3, #orderStep4").fadeOut("fast");
        var e = 1;
        $("#orderStep" + e).fadeIn("slow"), $(".nav-link").removeClass("active"), $("#pill-step-" + e).addClass("active")
    }), "OTHER" === $("#customs-contents").val() ? $("#show-customs-other").show() : $("#show-customs-other").hide(), $("#customs-contents").change(function() {
        "OTHER" === $(this).val() ? $("#show-customs-other").show() : $("#show-customs-other").hide()
    }), "yes" === $("#customs-yes-no").val() ? $(".customs-info").slideDown("fast") : $(".customs-info").slideUp("fast"), $("#customs-yes-no").change(function() {
        "yes" === $(this).val() ? $(".customs-info").slideDown("fast") : $(".customs-info").slideUp("fast")
    }), $("#quote-width, #quote-height, #quote-length").change(function() {
        s()
    }), $("#quote-width, #quote-height, #quote-length").keyup(function() {
        s()
    }), $(".shop-packs, .pack-numbers").change(function() {
        a()
    }), $(".pack-numbers").keyup(function() {
        a()
    }), $(".shopping-buy-now").click(function() {
        var e = ($(".shop-packs").find(":selected").data("price"), $(".shop-packs").find(":selected").data("index")),
            s = $(".pack-numbers").val();
        window.location = base_url + "/buy-shopping?package=" + e + "&extra_packages=" + s
    }), $("#do-shopping-login").click(function() {
        var e = $("#login-email").val(),
            s = $("#login-password").val();
        "" !== e && "" !== s ? $.post(ajax_url, {
            ems_cmd: "ems_login",
            email_address: e,
            password: s
        }).done(function(e) {
            var s = jQuery.parseJSON(e);
            "error" === s.status ? $("#login-msg").html(s.reason).fadeIn("slow") : ($("#shopping-buy").slideDown("slow"), $("#shopping-register-login").hide(), $("#shopping-user-id").val(s.user_id))
        }) : alert("Please enter a username and password")
    }), $("#do-shopping-register").click(function() {
        $("#user-password").val() != $("#user-password-confirm").val() ? alert("The passwords you entered do not match") : $.post(ajax_url, {
            ems_cmd: "ems_register",
            first_name: $("#user-first-name").val(),
            last_name: $("#user-last-name").val(),
            email_address: $("#user-email").val(),
            password: $("#user-password").val()
        }).done(function(e) {
            var s = jQuery.parseJSON(e);
            "error" === s.status ? $("#register-msg").html(s.reason).fadeIn("slow") : (ems_user_id = s.user_id, $("#shopping-user-id").val(ems_user_id), $("#shopping-buy").slideDown("slow"), $("#shopping-register-login").hide())
        })
    }), $("#pay-with-worldpay").click(function() {
        $.post(ajax_url, {
            ems_cmd: "ems_buy_shopping",
            user_id: $("#shopping-user-id").val(),
            collection_address: collection_address,
            delivery_address: delivery_address,
            package_name: $("#package-name").val(),
            package_index: $("#package-index").val(),
            package_amount: $("#package-amount").val(),
            ems_session: $("#ems-session").val(),
            extra_packages: $("#extra-packages").val()
        }).done(function(e) {
            var s = jQuery.parseJSON(e);
            "SUCCESS" === s.status && $("#shopping-worldpay").submit()
        })
    })
});
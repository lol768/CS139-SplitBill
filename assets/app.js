var SplitBill = SplitBill || {};

SplitBill.Configuration = {
    /**
     * WebSocket URL.
     */
    wsUrl: "ws://americano.adamwilliams.host:8765",
    version: "0.1"
};

SplitBill.FlashMessages = {
    wireUpEvents: function() {
        jQuery(".flash a.close").click(function() {
            jQuery(this).parent().hide();
        });
    },

    initialise: function() {
        this.wireUpEvents();
    }
};

SplitBill.Dropdowns = {
    wireUpEvents: function() {
        jQuery(".with-dropdown > a").click(function(event) {
            jQuery(this).parent().toggleClass("active");
            event.stopPropagation();
            return false;
        });

        jQuery(".with-dropdown .menu").click(function(event) {
            event.stopPropagation();
        });

        jQuery("body").click(function() {
            jQuery(".with-dropdown.active").removeClass("active");
        });
    },

    initialise: function() {
        this.wireUpEvents();
    }
};

SplitBill.UserSearchResults = {
    wireUpEvents: function() {
        var salvagedThis = this;
        jQuery(".user-search-results input").on("keyup", function(event) {
            var queryData = {"action": "usersSearch", "q": jQuery(this).val()};
            var $el = jQuery(this).parent();
            var $resultsContainer = $el.find(".results");

            if (jQuery(this).val() === "") {
                $resultsContainer.html("");
                return;
            }

            jQuery.get("api.php", queryData, function(data) {
                var $resultsList = jQuery("<ul>");
                for (var user of data.data) {
                    var $li = jQuery(jQuery("#ajaxUserSearchResultTmpl").html());
                    var $link = $li.find("a.name");
                    $link.text(user.name);
                    $link.data("uid", user.uid);
                    if (user.avatar) {
                        $li.find("img").attr("src", "assets/avatars/" + user.uid + "_t.png");
                    }
                    $resultsList.append($li);
                }
                $resultsContainer.html("");
                $resultsContainer.append($resultsList);
            });
            event.stopPropagation();
            return false;
        });
    },

    selectHandler: function() {
        var uid = jQuery(this).data("uid");
        var $container = jQuery(this).parent().parent().parent().parent();
        $container.find(".selected").show();
        $container.find(".selected").find("span").text("You have selected " + jQuery(this).text());
        $container.find("input").val("");
        $container.find("input[type=text]").hide();
        $container.find("i").hide();
        $container.find("a i").show();
        $container.find(".results").html("");
        $container.find("input[name=selectedId]").val(uid);
        return false;
    },

    clearHandler: function () {
        var $container = jQuery(this).parent().parent();
        $container.find(".selected").hide();
        $container.find(".selected").find("span").text("");
        $container.find("input[type=text]").show();
        $container.find("input[type=text]").focus();
        $container.find("input[name=selectedId]").val("");
        $container.find("i").show();
        return false;
    },

    addResultsBlocks: function () {
        var obj = this;
        jQuery(".user-search-results").each(function() {
            var $res = jQuery("<div>");
            $res.addClass("results");
            jQuery(this).append($res);
            jQuery(this).find(".selected").hide();
            $res.on("click", "a.name", obj.selectHandler);
            jQuery(this).find(".clear-user-search-results").on("click", obj.clearHandler);
        });
    },

    initialise: function() {
        this.wireUpEvents();
        this.addResultsBlocks();

    }
};

SplitBill.AlertManager = {
    /**
     * Array of alerts
     */
    alerts: [],

    /**
     * Adds an alert to the existing list.
     * @param message The message.
     */
    addAlert: function(message) {
        this.alerts.push(message);
        this.updatePage();
    },

    /**
     * Counts the number of alerts.
     * @returns {Number}
     */
    getCountOfAlerts: function() {
        return this.alerts.length;
    },

    /**
     * Gets all of the (undismissed) alerts.
     * @returns {Array}
     */
    getAlerts: function() {
        return this.alerts;
    },

    /**
     * Updates the page based on the alerts stored.
     */
    updatePage: function() {
        var $notificationsLink = jQuery(".notifications-link");
        $notificationsLink.find("span.counter").remove();
        var $notsMenu = jQuery(".notifications-dropdown .menu");
        if (this.getCountOfAlerts() !== 0) {
            jQuery(".no-alerts").hide();
            var $counter = jQuery("<span>");
            $counter.addClass("counter");
            $counter.addClass("anim");
            $counter.text(this.getCountOfAlerts());
            $notificationsLink.append($counter);
            var $clone = $notificationsLink.clone(true);

            $notificationsLink.before($clone);
            jQuery(".notifications-link:last").remove();
            jQuery(".notifications-link").addClass("anim");
            var $alertsList = jQuery("ul.alerts");
            $alertsList.html("");
            for (var alert of this.getAlerts()) {
                $alertsList.append(jQuery("<li>").text(alert));
            }
            $notsMenu.show();
        } else {
            jQuery(".no-alerts").show();
        }
    },

    initialise: function() {
        this.updatePage();
    }
};

SplitBill.Modals = {

    wireUpEvents: function() {

        var $body = jQuery("body");
        $body.find(".modal").hide();
        $body.addClass("modals-enabled");
        var th = this;
        $body.on("click", ".modal-inner", function(event) {
            event.stopPropagation();
        });

        $body.on("click", ".modal-inner .exit", function(event) {
            th.closeModal();
            return false;
        });

        $body.on("click", ".modal", function() {
            th.closeModal();
        });

        $body.on("keyup", function(event) {
            if (event.keyCode == 27) {
                th.closeModal();
            }
            return false;
        });

        jQuery(".modal-trigger").click(function() {
            SplitBill.Modals.openModal(jQuery(jQuery(this).data("selector")));
            return false;
        });
    },

    openModal: function($element) {
        jQuery("body").addClass("modal-open");
        $element.fadeIn();
    },

    closeModal: function() {
        jQuery(".modal").fadeOut();
        jQuery("body").removeClass("modal-open");
    },

    initialise: function() {
        this.wireUpEvents();
    }
};

SplitBill.WebSockets = {
    socket: null,

    /**
     * Create the socket
     */
    initialise: function() {
        this.socket = new WebSocket(SplitBill.Configuration.wsUrl);
        this.socket.onopen = this.socketOpened;
        this.socket.onmessage = this.socketMessageReceived;
        window.onbeforeunload = this.beforeUnloadHandler;
    },

    /**
     * Send an application identifier.
     * @param event
     */
    socketOpened: function(event) {
        this.socket.send("SplitBill");
    },

    /**
     * Executed whenever we get a message from the server.
     * @param event
     */
    socketMessageReceived: function(event) {
        var data = JSON.parse(event.data);
        if (data.type == "alert") {
            SplitBill.AlertManager.addAlert(data.message);
        }
    },

    /**
     * Handle browsers which don't close sockets properly..
     */
    beforeUnloadHandler: function() {
        this.socket.close();
    },

    /**
     * Retrieve the socket.
     * @returns WebSocket
     */
    getSocket: function() {
        return this.socket;
    }
};

SplitBill.Security = {
    csrfToken: jQuery("#csrf-token").attr("content"),
    getCsrfToken: function() {
        return this.csrfToken;
    }
};

SplitBill.JQueryCustomisations = {

    initialise: function() {
        jQuery(document).ajaxComplete(this.ajaxCompleteHandler);
        jQuery.ajaxSetup({
            headers: {
                "Authorization": "Token csrf=\"" + SplitBill.Security.getCsrfToken() + "\""
            }
        });
    },

    /**
     * Handles AJAX request completions.
     *
     * @param event Event
     * @param xhr XMLHttpRequest
     * @param settings PlainObject
     */
    ajaxCompleteHandler: function(event, xhr, settings) {
        if (xhr.statusCode().status != 200) {
            SplitBill.JQueryCustomisations.createErrorModal(settings, xhr.statusCode());
        }
    },

    /**
     * Tell the user if an AJAX req failed.
     * @param settings
     * @param status
     */
    createErrorModal: function(settings, status) {
        jQuery(".ajax-error-modal").remove();
        var $html = jQuery("#ajaxErrorTmpl").html();
        jQuery("footer").after($html);
        jQuery(".request-url").text(settings.url);
        jQuery(".request-type").text(settings.type);
        jQuery(".request-status").text(status.status + " (" + status.statusText + ")");
        SplitBill.Modals.openModal(jQuery(".ajax-error-modal"));
    }
};

SplitBill.HomepageAuthModals = {
    initialise: function() {
        this.wireUpEvents();
    },

    wireUpEvents: function() {
        jQuery("nav ul.right a").click(function() {
            if (jQuery(this).text() === "Register") {
                SplitBill.Modals.openModal(jQuery(".registration-modal"));
                return false;
            }

            if (jQuery(this).text() === "Login") {
                SplitBill.Modals.openModal(jQuery(".login-modal"));
                return false;
            }
        });


    }
};

jQuery(function() {
    console.log("SplitBill v" + SplitBill.Configuration.version);
    var profilingData = JSON.parse(jQuery("#profiling").html());
    console.log("Did " + profilingData.queries.length + " SQL queries to build the page:");
    for (var item of profilingData.queries) {
        console.log(item);
    }
    console.log("Total time spent rendering server-side: " + profilingData.time + "ms");
    console.log("Begin loading modules...");
    var modules = JSON.parse(jQuery("#appState").html()).modules;
    for (var module of modules) {
        console.log("Initialising module " + module);
        SplitBill[module].initialise();
    }
    console.log("Finished loading modules");
});

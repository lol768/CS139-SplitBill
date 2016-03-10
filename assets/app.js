var SplitBill = SplitBill || {};

SplitBill.Configuration = {
    /**
     * WebSocket URL.
     */
    wsUrl: "ws://americano.adamwilliams.host:8765"
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
        $notsMenu.hide();
        if (this.getCountOfAlerts() !== 0) {
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
        })
    },

    openModal: function($element) {
        jQuery("body").addClass("modal-open");
        $element.show();
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

SplitBill.HomepageAuthModals = {
    initialise: function() {
        this.wireUpEvents();
    },

    wireUpEvents: function() {

    }
};

jQuery(function() {
    var modules = JSON.parse(jQuery("#appState").html()).modules;
    for (var module of modules) {
        console.log("Initialising module " + module);
        SplitBill[module].initialise();
    }
});

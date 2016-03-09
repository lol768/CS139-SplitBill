var SplitBill = SplitBill || {};

SplitBill.Configuration = {
    /**
     * WebSocket URL.
     */
    wsUrl: "ws://americano.adamwilliams.host:8765"
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
    }
};

SplitBill.WebSockets = {

    socket: null,

    /**
     * Create the socket
     */
    initialiseSocket: function() {
        this.socket = new WebSocket(SplitBill.Configuration.wsUrl);
        window.addEventListener("beforeunload", this.beforeUnloadHandler);
    },

    socketOpened: function(event) {
        this.socket.send("SplitBill");
    },

    socketMessageReceived: function(event) {
        var data = JSON.parse(event.data);
        if (data.type == "alert") {
            SplitBill.AlertManager.addAlert(data.message);
        }
    },

    beforeUnloadHandler: function() {
        this.socket.close();
    },

    getSocket: function() {
        return this.socket;
    }
};

jQuery(function() {
    SplitBill.WebSockets.initialiseSocket();
    SplitBill.AlertManager.updatePage();
});

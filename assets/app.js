var SplitBill = SplitBill || {};

SplitBill.AlertManager = {
    alerts: [],
    addAlert: function(message) {
        this.alerts.push(message);
        this.updatePage();
    },
    getCountOfAlerts: function() {
        return this.alerts.length;
    },
    getAlerts: function() {
        return this.alerts;
    },
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
            for (var i = 0; i < this.alerts.length; i++) {
                $alertsList.append(jQuery("<li>").text(this.alerts[i]));
            }
            $notsMenu.show();
        }
    }
};

jQuery(function() {

    var socket = new WebSocket("ws://americano.adamwilliams.host:8765");
    socket.onopen = function (event) {
        socket.send("SplitBill");
    };

    socket.onmessage = function (event) {
        var data = JSON.parse(event.data);
        if (data.type == "alert") {
            SplitBill.AlertManager.addAlert(data.message);
        }
    };

    jQuery(window).on('beforeunload', function(){
        socket.close();
    });

    SplitBill.socket = socket;
    SplitBill.AlertManager.updatePage();
});

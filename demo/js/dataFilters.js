/**
 * To use background blocker you need to include blockui.js or create two custom
 * jQuery functions to block/unblock content:
 * $.setBackgroundStyle();
 * $.unblockUI();  
 * You may disable blocker by passing boolean false into 3rd param of init() mentod
 */
DataFilters = {
    notificationSelector: '#filtersIndicator',
    useBackground: false,
    gridID: '', //grid ID
    combinedID: '', //grid Id with user ID
    init: function(gridID, combinedID, useBackground) {
        this.gridID = gridID;
        this.combinedID = combinedID;
        this.useBackground = useBackground;
        return this;
    },
    rememberSettings: function(data) {
        if (typeof (Storage) !== "undefined") {
            sessionStorage[this.combinedID] = JSON.stringify(data); //remember current selection into HTML5 local storage
        }
    },
    checkExistance: function() {
        if ((typeof (Storage) !== "undefined") && (sessionStorage[this.combinedID] !== undefined)) {
            if (this.useBackground) {
                $.setBackgroundStyle('<br> <a href="#" onclick ="DataFilters.clearAllSettings();">Retry</a> ');
            }
        }
    },
    applyFilters: function() {

        var gridID = this.gridID;
        var combinedID = this.combinedID;
        var notificationSelector = this.notificationSelector;

        if (typeof (Storage) !== "undefined") {
            if (sessionStorage[combinedID] !== undefined) {
                //some data exist in local storage - > need to re-load the grid
                setTimeout(function() {
                    $('#' + gridID).yiiGridView('update', {
                        data: JSON.parse(sessionStorage[combinedID])
                    });
                    if (notificationSelector.length !== 0) {
                        $(notificationSelector).css('display', 'block');
                    }
                }, 500);
            }
        }
    },
    removeFielters: function() {
        sessionStorage.removeItem(this.combinedID);
        if (this.useBackground) {
            $.setBackgroundStyle();
        }
        location.reload(true);
    },
    clearAllSettings: function() {
        sessionStorage.clear();
        location.reload(true);
    }
};
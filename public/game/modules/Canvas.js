
function Canvas(context) {

    this.context = context;
}

Canvas.prototype = {

    init: function() {

        this.clickBehaviours = [];
    },

    addClickBehaviour: function(callback) {

        this.clickBehaviours.push(callback);
    },

    runClickBehaviours: function(event) {

        for (var i=0, l=this.clickBehaviours.length; i<l; i++) {
            this.clickBehaviours[i](event, this.canvas);
        }
    }
}

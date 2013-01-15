
function Dom(document) {

    this.document = document;
}

Dom.prototype = {

    select: function(selector) {

        if ('String' === typeof selector) {
            return this.document.querySelector(selector);
        }

        return new Error('Selector must be a string');
    },

    selectAll: function(selector) {

        if ('String' === typeof selector) {
            return this.document.querySelectorAll(selector);
        }

        return new Error('Selector must be a string');
    },

    selectChild: function(parent, selector) {

        // TODO: verify dom elements have querySelector* methods
        if ('Object' === typeof parent
         && 'undefined' !== parent.querySelector) {
            return parent.querySelector(selector);
        }

        return new Error('Parent element is not a valid DOM object');
    },

    selectChildren: function() {

        // TODO: verify dom elements have querySelector* methods
        if ('Object' === typeof parent
         && 'undefined' !== parent.querySelectorAll) {
            return parent.querySelectorAll(selector);
        }

        return new Error('Parent element is not a valid DOM object');
    }
}


function Game(dom, wrapper, options) {

    this.dom = dom;
    this.wrapper = wrapper;
    this.options = options;

    this.init();
}

Game.prototype = {

    init: function() {

        // Find canvas element
        this.canvas = new Canvas(this.dom.select('.game'));
    }

}

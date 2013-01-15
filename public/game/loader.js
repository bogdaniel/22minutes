
if (config.require
 && config.require.length) {

    for (var i=0, l=config.require.length; i<l; i++) {
        document.head.appendChild(
            createScript('modules/' + config.require[i] + '.js')
        );
    }
}

window.addEventListener('load', function() {

    if (config.ready) {
        config.ready();
    }
});

function createScript(src) {

    var script = document.createElement('script');
    script.src = src;

    return script;
}

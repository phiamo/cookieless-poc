/**
 * this POC js is used in conjunction with the client-id.js API to identify customers
 */

if(typeof clientSideFingerPrint === 'undefined') {
    var clientSideFingerPrint;
}

(function () {
    'use strict';
    const loadTracking = function (clientSideFingerPrint) {
        var my_awesome_script = document.createElement('script');
        my_awesome_script.setAttribute('src', "http://api.local:8000/api/client-id.js?clientSideFingerPrint=" + clientSideFingerPrint);
        document.head.appendChild(my_awesome_script);
    }
    if (window.requestIdleCallback) {
        requestIdleCallback(function () {
            Fingerprint2.get(function (components) {
                const values = components.map(function (component) {
                    return component.value
                })
                clientSideFingerPrint = Fingerprint2.x64hash128(values.join(''), 31)
                loadTracking(clientSideFingerPrint)

            })
        })
    } else {
        setTimeout(function () {
            Fingerprint2.get(function (components) {
                const values = components.map(function (component) {
                    return component.value
                })
                clientSideFingerPrint = Fingerprint2.x64hash128(values.join(''), 31)
                loadTracking(clientSideFingerPrint)
            })
        }, 500)
    }
})();

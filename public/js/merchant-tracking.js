/**
 * A POC js for the merchant site to load the ID's via identify.js and display it here
 */

(function () {
    'use strict';

    setTimeout(() => {
        let clientUUIDs = document.getElementsByClassName("clientUUID");
        clientUUIDs[0].innerHTML = clientUUID;
        let clientSideFingerPrints = document.getElementsByClassName("clientSideFingerPrint");
        clientSideFingerPrints[0].innerHTML = clientSideFingerPrint;
        let serverSideFingerPrints = document.getElementsByClassName("serverSideFingerPrint");
        serverSideFingerPrints[0].innerHTML = serverSideFingerPrint;
    }, 1000)
})();

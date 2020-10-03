/**
 * A poc js for the affiliate site, adding the click listener to EVERY link
 */

(function () {
    'use strict';

    let classes = document.getElementsByClassName("tracked-link");
    for (let i = 0; i < classes.length; i++) {
        classes[i].addEventListener('click', function(e) {
            e.preventDefault();
            fetch('http://api.local:8000/api/trackingId?clientSideFingerPrint='+clientSideFingerPrint+'&clientUUID='+clientUUID)
                .then(async response => {
                    let json = await response.json();
                    this.href = this.href + '&clientSideFingerPrint='+clientSideFingerPrint;
                    this.href = this.href + '&serverSideFingerPrint='+json.serverSideFingerPrint;
                    this.href = this.href + '&clientUUID='+clientUUID;
                    this.href = this.href + '&clickId='+json.clickId;
                    window.location.href = this.href;
                })
            return false;
        });
    }
})();

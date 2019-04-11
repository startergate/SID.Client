/*jshint esversion: 9 */

class SID {
  constructor(clientname) {
    if (typeof jQuery == 'undefined') {
      throw new Error("SID.js for Client requires jQuery");
    }
    if (navigator.userAgent.indexOf('MSIE') !== -1 || info.indexOf('Trident') !== -1) { //Client Browser Detection
      throw new Error("SID.js for Client don't support IE");
    }
    this.clientname = clientname;
  }
  getProfile(sessid) {

  }
  createClientID(devicedata) {
    $.ajax({
      url: 'http://sid.donote.co:3000/api/create/clientid',
      type: 'POST',
      dataType: 'json',
      data: {
        'type': 'create',
        'data': 'clientid',
        'devicedata': devicedata
      },
      success: (data) => {
        this.indexedDBCreater({
          'sid_clientid': requested_data
        });
      }
    });
  }

  indexedDBCreater(data) {

  }
}
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
  getProfile(clientid, sessid) {
    $.ajax({
      url: 'http://sid.donote.co:3000/api/get/pfimg',
      type: 'POST',
      dataType: 'json',
      data: {
        'type': 'get',
        'data': 'pfimg',
        'clientid': clientid,
        'sessid': sessid
      },
      success: (data) => {
        data = JSON.parse(data);
        dosomething(data.requested_data);
      }
    });
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
        data = JSON.parse(data);
        this.indexedDBCreater({
          'sid_clientid': requested_data
        });
      }
    });
  }

  indexedDBModifier(data, mod = 'create') {

  }
}
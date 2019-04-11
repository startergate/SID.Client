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

  }
  indexedDBCreater(data) {

  }
}
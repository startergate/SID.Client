/*jshint esversion: 9 */

const axios = require('axios');

class SID {
  constructor() {
    this.clientName = clientName;
    this.sidServerInstance = axios.create({
      baseURL: 'http://sid.donote.co:3000/api/v1/',
      timeout: 3000,
      headers: {
        'Host': 'sid.donote.co:3000'
      }
    });
  }

  login(clientid, id, pw) {
    this.sidServerInstance.post('session', )
  }

  loginAuth(clientid, sessid) {

  }

  logout(clientid, sessid) {

  }

  register(clientid, id, pw, nickname = 'User') {

  }

  getUserNickname(clientid, sessid) {

  }

  loginCheck(target) {

  }

  passwordCheck(clientid, sessid, pw) {

  }

  getClientName() {
    return this.clientName;
  }
}

module.exports = new SID();
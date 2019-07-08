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
    this.sidServerInstance.post('/session/', {
      type: 'login',
      clientid: clinetid,
      userid: id,
      password: pw,
      isPermanent: false,
      isWeb: true
    }).then(response => {});
  }

  loginAuth(clientid, sessid) {
    this.sidServerInstance.post('/session/', {
      type: 'login',
      clientid: clientid,
      sessid: sessid
    }).then(response => {});
  }

  logout(clientid, sessid) {
    this.sidServerInstance.delete('/session/', {
      type: 'logout',
      clientid: clientid,
      sessid: sessid
    }).then(response => {});
  }

  register(clientid, id, pw, nickname = 'User') {
    this.sidServerInstance.post('/user/', {
      type: 'register',
      clientid: clientid,
      userid: userid,
      nickname: nickname,
      password: pw
    }).then(response => {});
  }

  getUserNickname(clientid, sessid) {
    this.sidServerInstance.post('/session/', {

    }).then(response => {});
  }

  loginCheck(target) {
    this.sidServerInstance.post('/session/', {

    }).then(response => {});
  }

  passwordCheck(clientid, sessid, pw) {
    this.sidServerInstance.post('/session/', {

    }).then(response => {});
  }

  getClientName() {
    return this.clientName;
  }
}

module.exports = new SID();
<?php
  // SID LIBRARY
  // ------------------------------------------------------
  // Copyright by 2017 ~ 2019 STARTERGATE
  // This library follows CC BY-SA 4.0. Please refer to ( https://creativecommons.org/licenses/by-sa/4.0/ )
  class SID
  {
      private $clientName;

      // basic curl
      private function curlPost($url, $data)
      {
          $ch = curl_init();

          curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_URL, $url);

          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

          if (!is_null($header)) {
              curl_setopt($ch, CURLOPT_HEADER, true);
          }
          curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
          curl_setopt($ch, CURLOPT_VERBOSE, true);
          $response = curl_exec($ch);

          $body = null;
          // error
          if (!$response) {
              $body = curl_error($ch);
              // HostNotFound, No route to Host, etc  Network related error
              $http_status = -1;
          } else {
              //parsing http status code
              $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
              if (!is_null($header)) {
                  $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                  $header = substr($response, 0, $header_size);
                  $body = substr($response, $header_size);
              } else {
                  $body = $response;
              }
          }
          curl_close($ch);
          return $body;
      }

      // Construct and Destruct Function
      public function __construct()
      {
          if (func_get_args()) {
              $this->clientName = func_get_args()[0];

              return;
          } else {
              SID::__destruct()
          }
      }

      private function __destruct()
      {
      }

      // Login functions
      public function login($clientid, $id, $pw)
      {
          try {
              $userdata = curlPost('http://localhost:3000/api/login', json_encode(array(
                  "type" => "login",
                  "clientid" => $clientid,
                  "userid" => $id,
                  "password" => $pw,
                  "isPermanent" => $isPermanent,
                  "isWeb" => true
              )));
              $userdata = json_decode($userdata)
              if ($userdata['type'] === 'error') {
                  return 0;
              }
              $output = 1;
              $_SESSION['sid_sessid'] = $userdata['requested_data'][0];
              $_SESSION['sid_pid'] = $userdata['requested_data'][1];
              $_SESSION['sid_nickname'] = strip_tags($userdata['requested_data'][2]);

              return 1;

          } catch (\Exception $e) {
              return -1;
          }
      }

      public function logout($clientid, $sessid)
      {
          $userdata = curlPost('http://localhost:3000/api/logout', json_encode(array(
              "type" => "logout",
              "clientid" => $clientid,
              "sessid" => $sessid
          )));
          $userdata = json_decode($userdata)
          if ($userdata['type'] === 'error') {
              return 0;
          }
          if (!$userdata['is_succeed']) {
              return 0;
          }
          session_destroy();

          // legacy support
          setcookie('sidAutorizeRikka', 0, time() - 3600, '/');
          setcookie('sidAutorizeYuuta', 0, time() - 3600, '/');

          return 1;
      }

      public function register(String $id, String $pw, String $nickname = 'User')
      {
          try {
              $userdata = curlPost('http://localhost:3000/api/login', json_encode(array(
                  "type" => "register",
                  "clientid" => $clientid,
                  "userid" => $id,
                  "nickname" => $nickname,
                  "password" => $pw
              )));
              $userdata = json_decode($userdata)
              if ($userdata['type'] === 'error') {
                  return 0;
              }
              if (!$userdata['is_succeed']) {
                  return 0;
              }
              return $userdata['private_id'];
          } catch (\Exception $e) {
              return -1;
          }
      }

      // User Info Getter
      public function getUserNickname($clientid, $sessid)
      {
          try {
              $userdata = curlPost('http://localhost:3000/api/get/usname', json_encode(array(
                  "type" => "get",
                  "data" => 'usname',
                  "clientid" => $clientid,
                  "sessid" => $sessid
              )));
              $userdata = json_decode($userdata)
              if ($userdata['type'] === 'error') {
                  return '';
              }
              if (!$userdata['isvaild']) {
                  return '';
              }
              return $userdata['response_data'];
          } catch (\Exception $e) {
              return '';
          }
      }

      // Auto login cookie functions
      public function loginCookie($pw, $pid, $locater)
      {
          $conn = new mysqli('sid.donote.co', 'root', 'Wb4H9nn542', 'sid_userdata');
          unset($_COOKIE['sidAutorizeRikka']);
          unset($_COOKIE['sidAutorizeYuuta']);
          $pid = $conn->real_escape_string($pid);
          $cookie_raw = $this->generateRenStr(10);
          $cookie_data = hash('sha256', $pw);

          try {
              $sql = "UPDATE userdata SET autorize_tag='$cookie_raw' WHERE pid = '$pid'";
              $conn->query($sql);
          } catch (\Exception $e) {
              return -1;
          }
          $cookieTest1 = setcookie('sidAutorizeRikka', $cookie_raw, time() + 86400 * 30, $locater);
          $cookieTest2 = setcookie('sidAutorizeYuuta', $cookie_data, time() + 86400 * 30, $locater);

          return $cookieTest1 && $cookieTest2;
      }

      public function authCheck($clientid, $sessid)
      {
        try {
            $userdata = curlPost('http://localhost:3000/api/login', json_encode(array(
                "type" => "login",
                "clientid" => $clientid,
                "sessid" => $sessid
            )));
            $userdata = json_decode($userdata)
            if ($userdata['type'] === 'error') {
                return 0;
            }
            $output = 1;
            $_SESSION['sid_sessid'] = $userdata['requested_data'][0];
            $_SESSION['sid_pid'] = $userdata['requested_data'][1];
            $_SESSION['sid_nickname'] = strip_tags($userdata['requested_data'][2]);

            return 1;

        } catch (\Exception $e) {
            return -1;
        }
      }

      // Editional checking functions
      public function loginCheck($target)
      {
          ob_start();
          session_start();
          if (empty($_SESSION['sid_sessid'])) {
              header('Location: '.$target);
              exit;
          }

          return 1;
      }

      public function passwordCheck(String $clientid, String $sessid, String $pw)
      {
        try {
            $userdata = curlPost('http://localhost:3000/api/verify/', json_encode(array(
                "type" => "verify",
                "data" => "password",
                "clientid" => $clientid,
                "sessid" => $sessid,
                "value" => $pw
            )));
            $userdata = json_decode($userdata)
            if ($userdata['type'] === 'error') {
                return 0;
            }
            if ($userdata['is_vaild'] === true) {
                return 1;
            }

            return 0;

        } catch (\Exception $e) {
            return -1;
        }
      }

      // Editional Etc functions
      public function getClientName()
      {
          return $this->$clientName;
      }
  }

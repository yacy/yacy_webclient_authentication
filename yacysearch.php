<?php
/*
Copyright 2014 Michael Peter Christen

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

---
WHAT IS THIS?
The purpose of this service is the authentication of search users;
it does an authentication of users and identifies their user rights.
Such rights are expressed with sets of specific collections names
which had been used to index data in the YaCy search engine.
This is done using a reverse proxy to a YaCy search server. It
serves as a filter for search requests from a JSON search
client like https://gitorious.org/yacy/searchpage_template_yaml4/
The server attribute within the searchpage_template_yaml4 must point
to the location of this file on a php-enabled server.

HOW TO INSTALL
- copy this file on a web space with a php interpreter
- cUrl is required, please refer to http://php.net/manual/en/book.curl.php
- modify the server address in searchpage_template_yaml4 and point
  it to the location of this file
- modify the settings in class/config.class.php
*/

  require 'class/config.class.php';
  require 'class/user_privs.class.php';
  
  session_start();

  // prevent session fixation attempts
  if (!(isset($_SESSION['init']))) {
    session_regenerate_id();
    $_SESSION['init'] = true;
  }

  // determine user's collections and set them as additional get param
  // to ensure that this is a suitable way, the yacy server must not respond to direct calls
  // which a user might request by just entering a get request
  // this isn't a topic we cover over here, it's solved by restricting the connectivity to the yacy backend
  // at network level
trigger_error('request received');
  try {
    $userPrivs = new UserPrivs();              
  } catch (Exception $e) {
    trigger_error($e->getMessage(), E_USER_ERROR);
  }  
    
  $_GET['collection'] = $userPrivs->getAsUrlParam();
  
  // construct a new get request; pass-through any additional get params                                
  $curlParams = http_build_query($_GET);

  // determine, if search or suggest action is required, see suggest.php
  if (isset($_suggest_action) && $_suggest_action) {
    $script = Config::YACY_SUGGESTSCRIPT;
  } else {
    $script = Config::YACY_SEARCHSCRIPT;
  }
  
  // init and execue the curl request; curl_exec returns the received data automatically to the browser
  $ch = curl_init(Config::YACY_SERVER_SEARCH . $script . '?' . $curlParams);
trigger_error(Config::YACY_SERVER_SEARCH . $script . '?' . $curlParams);  
  curl_setopt($ch, CURLOPT_HEADER, false);
  curl_setopt($ch, CURLOPT_PROXY, '');
trigger_error('curl executed');  
  curl_exec($ch);
  curl_close($ch);
                                                
?>
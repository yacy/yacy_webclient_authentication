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
- modify the php.ini file and set the attribute allow_url_fopen to 1
- change the external_url path in this file and let it point to your
  own server
- modify the server address in searchpage_template_yaml4 and point
  it to the location of this file
*/

  // switch on error reporting -- please disable this in production environments
  error_reporting(~0);
  ini_set('display_errors', 1);
  // the following line is a stub -- modify it to apply filters as you need
  $external_url = 'http://search.yacy.net/yacysearch.json?' . $_SERVER['QUERY_STRING'];

  // rewrite the content using a remote YaCy search
  print file_get_contents($external_url);
?>
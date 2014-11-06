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

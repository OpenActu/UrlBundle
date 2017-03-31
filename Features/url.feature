Feature: URL bundle
  
  Scenario: URL's HTTP checking

    Scenario Outline: Sanitizing URL

      Given The port mode is <port_mode>
      And The URL encoding is <url_encoding>

      When I sanitize <http_link> URL
      Then I should have <scheme> as Scheme
      And I should have <domain> as Domain
      And I should have <response_url> as Response URL
      And The result analysis is <status>
      And The port is <port>
      And The folder is <folder>
      And The filename is <filename>
      And The filename extension is <ext>
      And The top level domain is <tld>
      And The fragment is <fragment>

      Examples:
        | port_mode | http_link                                                                | scheme | domain | response_url                                                      | status | port | folder          | filename       | ext  | subdomain | tld  | fragment | url_encoding |
        | normal    | http://www.google.fr/folder1/folder2/filename.txt?a=b&c=d&ping=pong#test | http   | google | http://www.google.fr/folder1/folder2/filename.txt?c=d&ping=pong&a=b#test | true   | null | folder1/folder2 | filename       | txt  | www       | fr   | test     | true         |
        | normal    | www.sub.google.fr?a=b                                                    | http   | google | http://www.sub.google.fr/?a=b                                            | true   | null | null            | null           | null | www.sub   | fr   | null     | true         |
        | normal    | file:///path/subpath/filename.txt                                        | file   | null   | file:///path/subpath/filename.txt                                         | true   | null | path/subpath    | filename       | txt  | null      | null | null     | true         |
        | normal    | /path/subpath/filename.txt                                               | file   | null   | file:///path/subpath/filename.txt                                         | true   | null | path/subpath    | filename       | txt  | null      | null | null     | true         |
        | normal    | C:\path\subpath\the filename.txt                                         | file   | null   | file:///C:/path/subpath/the%20filename.txt                                   | true   | null | C:/path/subpath | the%20filename | txt  | null      | null | null     | true         |

    Scenario Outline: sending URL

      Given The port mode is <port_mode>
      And The URL encoding is <url_encoding>
      And The URL Entity is <url_entity> entity
      When I send <http_link> URL
      Then I should retrieve an not empty http response

      Examples:
        | port_mode | http_link                                                                | url_encoding | url_entity |
        | normal    | http://www.lepoint.fr/                                                   | false        | Url        |

  Scenario: I store an URL
    Scenario: I want to add a new URL entity and forbiden to update it
      Given there is an URL entity with http://www.lepoint.fr/
      And The port mode is normal
      And The URL Entity is Url entity
      And The update mode is setting at off
      When I call a new sending with the same response URL of URL entity with http://www.lepoint.fr/
      Then The URL entity with http://www.lepoint.fr/ has no update
      And there is a new URL entity with http://www.lepoint.fr/ created
    Scenario: I want to add a new URL entity and continue to update it

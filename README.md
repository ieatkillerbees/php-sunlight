php-sunlight
============
[![Build Status](https://travis-ci.org/squinones/php-sunlight.png?branch=0.1.0)](https://travis-ci.org/squinones/php-sunlight)

php-sunlight is a wrapper for the Sunlight Foundation APIs, modeled loosely after the python-sunlight implementation.
The primary goal is to provide a simple and minimalistic interface to the APIs via PHP.


Congress API
------------

[Committees](https://sunlightlabs.github.io/congress/committees.html)
```php
$committees = new Committees("<sunlight api key");

$filter = [
    "query"               => "<A string fragment to match against committees' names.>",
    "committee_id"        => "<Official ID of the committee, as it appears in various official sources (Senate, House, and Library of Congress).>,
    "chamber"             => "<The chamber this committee is part of. 'house', 'senate', or 'joint'.>,
    "subcommittee"        => "<Whether or not the committee is a subcommittee. 'true' or 'false'.>,
    "member_ids"          => ["<Array of legislator bioguide IDs>"],
    "parent_committee_id" => "<If the committee is a subcommittee, the ID of its parent committee.>"
]
$results = $committees->find($filter);
```

[Congressional Districts](https://sunlightlabs.github.io/congress/districts.html)
```php
$districts = new Districts("<sunlight api key>");

$results = $districts->locate(<zipcode>);
$results = $districts->locate([<longitude>, <latitude>]);
```

[Members](https://sunlightlabs.github.io/congress/legislators.html)
```php
$legislators = new Legislators("<sunlight api key>");

$results = $legislators->find(["last_name" => "Pelosi"]);
$results = $legislators->locate(<zipcode>);
$results = $legislators->locate([<longitude>, <latitude>]);
```

Roadmap
-------
0.2.0 - Open States API Support

0.3.0 - Capitol Words API Support

0.4.0 - Political Party Time API Support

0.5.0 - Influence Explorer API Support

0.6.0 - Docket Wrench API Support
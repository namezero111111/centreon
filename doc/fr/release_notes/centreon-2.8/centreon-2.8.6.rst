##############
Centreon 2.8.6
##############

Bug Fixes
=========

KB
--

* Downtimes - Display real BA name instead of _Module_ - #5014, PR #5094
* InfluxDB broker output config: metric columns not stored properly - #5058, PR #5089
* Poller status still working when the poller is disabled - #5126
* Filter on the status host/service on the motiroring isn't working #5131, #5140
* Fix acl on host categories for inheritance
* Avoid infinite loop in acl category
* Fix error message in install process
* Fix path to centengine and cbd init scripts
* Fix topcounter must count all meta services - #5071, PR #5100
* Fix access downtime page for users with ACL - #4952, #5025, PR #5093
* Centreon > Services - Services listed twice - #5158, PR #5010
* Custom views - problem with multiselect users when sharing View - #5029, PR #5074
* Massive change  - impossible to add servive group - #5132
* Fix URL decode probelm with character '+' in object's name - #5128, PR #4883
* Fix CLAPI import
* Poller status still working when the poller is disabled - #5126, PR #5133

Enhancements
============

* Display inherited categories in host details page
* Do not check modification of configuration on disabled poller for better performance - PR #4928
* Improve access to services configuration page - PR #5077, PR #5076
* Improve global performance - PR #4900
* Improve Knowledge Base configuration
* Fix wiki links of objects with spaces in their name - #4306
* Improve documentation
* Set geo_coords parameter with clapi

If you already used a knowledge base, please execute following script :
::

	php /usr/share/centreon/bin/migrateWikiPages.php

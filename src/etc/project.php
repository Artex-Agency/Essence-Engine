<?php declare(strict_types=1);
 #
 #            ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
 #            ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
 #            ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
 #            |_____|_____|_____|_____|__|╲__|_____|_____|
 #            ARTEX ESSENCE ⦙⦙⦙⦙ PHP META-FRAMEWORK & ENGINE
 #
 # ====================================================================
 # PROJECT STATUS
 # ====================================================================





/** @var integer ESS_PROJECT_STATUS Artex Essence project status. */
(defined('ESS_BUILD_STATUS') OR define('ESS_STATUS', ATX_PROJ_CONSTRUCTION));

 # --------------------------------------------------------------------
 # PACKAGE
 # --------------------------------------------------------------------
/** @var boolean ESS_PACKAGE_STABLE Artex Essence version stability. */
(defined('ESS_PACKAGE_STABLE') OR define('ESS_PACKAGE_STABLE', false));



/** @var string ESS_VERSION The Artex Essence meta-framework version. */
(defined('ESS_VERSION') OR define('ESS_VERSION', '1.0.0-Dev.1'));
 # --------------------------------------------------------------------
 # PROJECT
 # --------------------------------------------------------------------
/** @var string ESS_PROJECT The Artex Essence project data. */
(defined('ESS_PROJECT') OR define('ESS_PROJECT', (ESS_PACKAGE . ' v:' . ESS_VERSION)));


  /** @var boolean ESS_BUILD_RELEASED Artex Essence version stability. */
(defined('ESS_VERSION_STABLE') OR define('ESS_VERSION_STABLE', 'dev'));

/** @var string ESS_WEBSITE The Artex Essence project website. */
(defined('ESS_WEBSITE') OR define('ESS_WEBSITE', 'https://artexessence.com/engine/'));
 # --------------------------------------------------------------------
 # ESSENCE PACKAGE
 # --------------------------------------------------------------------




 # ====================================================================
 #
 #
 # ====================================================================
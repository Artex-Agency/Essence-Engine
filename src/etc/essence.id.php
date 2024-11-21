<?php
 # ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
 # ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
 # ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
 # |_____|_____|_____|_____|__|╲__|_____|_____|
 # ARTEX ESSENCE ⦙⦙⦙⦙ PHP META-FRAMEWORK & ENGINE
/**
 * This file is part of the Artex Essence meta-framework.
 * 
 * @link      https://artexessence.com/engine/ Project Website
 * @link      https://artexsoftware.com/ Artex Software
 * @license   Artex Permissive Software License (APSL)
 * @copyright 2024 Artex Agency Inc.
 */
declare(strict_types=1);

# ---------------------------------------------------------------------
# ESSENCE PACKAGE
# ---------------------------------------------------------------------
require(ETC_PATH .'artex.dev.php');
require(ETC_PATH .'project.php');



# ---------------------------------------------------------------------
# ESSENCE PACKAGE
# ---------------------------------------------------------------------
/** @var string ESS_PACKAGE The Artex Essence meta-framework name. */
(defined('ESS_PACKAGE') OR define('ESS_PACKAGE', 'Artex Essence'));

/** @var string ESS_VERSION The Artex Essence meta-framework version. */
(defined('ESS_VERSION') OR define('ESS_VERSION', '1.0.0-Dev.1'));

/** @var string ESS_PROJECT The Artex Essence project data. */
(defined('ESS_PROJECT') OR define('ESS_PROJECT', (ESS_PACKAGE . ' v:' . ESS_VERSION)));

/** @var string ESS_WEBSITE The Artex Essence project website. */
(defined('ESS_WEBSITE') OR define('ESS_WEBSITE', 'https://artexessence.com/engine/'));



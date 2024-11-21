<?php

 # ====================================================================
 # PROJECT STATUS FLAGS
 # ====================================================================
/** @var int ATX_PROJ_ABANDONED Project Abandoned. */
defined('ATX_PROJ_ABANDONED')    || define('ATX_PROJ_ABANDONED',    0);

/** @var int ATX_PROJ_CONCEPTION Project Concept & Planning. */
defined('ATX_PROJ_CONCEPTION')   || define('ATX_PROJ_CONCEPTION',   1);

/** @var int ATX_PROJ_PROTOTYPING Research & Development + Trials. */
defined('ATX_PROJ_PROTOTYPING')  || define('ATX_PROJ_PROTOTYPING',  2);

/** @var int ATX_PROJ_CONSTRUCTION Creating the base structure. */
defined('ATX_PROJ_CONSTRUCTION') || define('ATX_PROJ_CONSTRUCTION', 3);

/** @var int ATX_PROJ_DEVELOPMENT Developing functionality. */
defined('ATX_PROJ_DEVELOPMENT')  || define('ATX_PROJ_DEVELOPMENT',  4);

/** @var int ATX_PROJ_PRODUCTION Final Assembly & Design. */
defined('ATX_PROJ_PRODUCTION')   || define('ATX_PROJ_PRODUCTION',   5);

/** @var int ATX_PROJ_FINISHING Finishing touches: Design, Content, etc. */
defined('ATX_PROJ_FINISHING')    || define('ATX_PROJ_FINISHING',    6);

/** @var int ATX_PROJ_TESTING Testing, Debugging, Q/A. */
defined('ATX_PROJ_TESTING')      || define('ATX_PROJ_TESTING',      7);

/** @var int ATX_PROJ_RELEASED Monitored Release. */
defined('ATX_PROJ_RELEASED')     || define('ATX_PROJ_RELEASED',     8);

/** @var int ATX_PROJ_COMPLETE Project Complete. */
defined('ATX_PROJ_COMPLETE')     || define('ATX_PROJ_COMPLETE',     9);
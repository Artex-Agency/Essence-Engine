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
 */
declare(strict_types=1);

namespace Essence\Utils;

use \strtr;
use \InvalidArgumentException;

/**
 * Interpolate utility class 
 * 
 * Provides functionality for interpolating values between two endpoints.
 *
 * @package    Essence\Utils
 * @category   Utility
 * @version    1.0.0
 * @since      1.0.0
 * @link       https://artexessence.com/core/ Project Website
 * @license    Artex Permissive Software License (APSL)
 */
class Version
{
    /** @var array An array of version release types. */
    private const VALID_RELEASE_TYPES = ['dev', 'alpha', 'beta', 'rc', 'stable'];

    /** @var array An array of version release values. */
    private const RELEASE_TYPE_VALUES = [
        'dev'    => 0,
        'alpha'  => 1,
        'beta'   => 2,
        'rc'     => 3,
        'stable' => 4
    ];

    /** @var int The major version number. */
    private int $major;

    /** @var int The minor version number. */
    private int $minor;

    /** @var int The version patch number. */
    private int $patch;

    /** @var string The version release type. */
    private string $releaseType;

    /** @var int|null The version release number. */
    private ?int $releaseNumber = null;

    /**
     * Constructor
     * 
     * parses the version string on load.
     *
     * @param string $version The full version string (e.g., "1.0.0-Dev.1").
     */
    public function __construct(string $version)
    {
        // Default release to stable if not provided
        $this->releaseType = 'stable';

        // Parse the version string
        $this->parseVersion($version);
    }

    /**
     * Parse version
     * 
     * Parses the version string into components.
     *
     * @param string $version The version string (e.g., "1.0.0-Dev.1").
     * @throws InvalidArgumentException If the version string is invalid.
     */
    private function parseVersion(string $version): void
    {
        // Match the version format: major.minor.patch[-releaseType.releaseVersion]
        if (!preg_match('/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})(?:-(dev|alpha|beta|rc|stable)(?:\.(\d+))?)?$/i', $version, $matches)) {
            throw new InvalidArgumentException("Invalid version format: $version");
        }

        // Assign the components
        $this->major = (int) $matches[1];
        $this->minor = (int) $matches[2];
        $this->patch = (int) $matches[3];

        // Check if a release type is provided (default is 'stable')
        if (isset($matches[4])) {
            $this->releaseType = strtolower($matches[4]);
        }

        // Check if a release version is provided (e.g., Dev.1, Beta.2)
        if (isset($matches[5])) {
            $this->releaseNumber = (int) $matches[5];
        }
    }

    /**
     * Get Major
     * 
     * Get the major version number.
     *
     * @return int The major version number.
     */
    public function getMajor(): int
    {
        return $this->major;
    }

    /**
     * Get Minor
     * 
     * Get the minor version number.
     *
     * @return int The minor version number.
     */
    public function getMinor(): int
    {
        return $this->minor;
    }

    /**
     * Get Patch
     * 
     * Get the patch version number.
     *
     * @return int The version patch number.
     */
    public function getPatch(): int
    {
        return $this->patch;
    }

    /**
     * Get Release
     * 
     * Get the release type (e.g., Dev, Alpha, Beta, RC, Stable).
     *
     * @return string The version release.
     */
    public function getRelease(): string
    {
        return $this->releaseType;
    }

    /**
     * Get Release Number
     * 
     * Get the release version (e.g., 1 in "Dev.1").
     *
     * @return int|null The release version, or null if none.
     */
    public function getReleaseNumber(): ?int
    {
        return $this->releaseNumber;
    }

    /**
     * Get the full version string
     * 
     * Get the full version string including the release information.
     *
     * @return string The full version.
     */
    public function getFull(): string
    {
        $version = "{$this->major}.{$this->minor}.{$this->patch}";
        if ($this->releaseType !== 'stable') {
            $version .= '-' . ucfirst($this->releaseType);
            if ($this->releaseNumber !== null) {
                $version .= '.' . $this->releaseNumber;
            }
        }
        return $version;
    }

    /**
     * Get the clean version
     * 
     * Get the clean version (without the release type and version).
     *
     * @return string The base version without release information.
     */
    public function getClean(): string
    {
        return "{$this->major}.{$this->minor}.{$this->patch}";
    }

    /**
     * Get Version ID
     * 
     * Calculate the unique version ID based on the version components.
     *
     * @return int The version ID.
     */
    public function getID(): int
    {
        $releaseValue = self::RELEASE_TYPE_VALUES[$this->releaseType] ?? 4; // Default to 'stable'

        // Calculate version ID based on the formula:
        // [Major Version (2 digits)] [Minor Version (2 digits)] [Patch Version (3 digits)] [Release Type (1 digit)]
        $versionId = ($this->major * 10000000)
                   + ($this->minor * 100000)
                   + ($this->patch * 10)
                   + $releaseValue;

        return $versionId;
    }
}
<?php
 # ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
 # ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
 # ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
 # |_____|_____|_____|_____|__|╲__|_____|_____|
 # ARTEX ESSENCE ENGINE ⦙⦙⦙⦙⦙ A PHP META-FRAMEWORK
/**
 * This file is part of the Artex Essence Engine 
 * Meta-Framework.
 *
 * @link      https://artexessence.com/core/ Artex Software
 * @license   Artex Permissive Software License (APSL)
 * @copyright 2024 Artex Agency Inc.
 */
declare(strict_types=1);

namespace Essence\Http;

/**
 * Request
 *
 * A core component of the Essence meta-framework, this class provides 
 * an abstraction for handling HTTP request data in a structured and 
 * secure manner. It parses data from superglobals ($_GET, $_POST, 
 * $_SERVER, etc.) and organizes it for easy access within any 
 * application framework using Essence. It includes methods for 
 * retrieving query parameters, form data, headers, cookies, and more. 
 * Additionally, it provides utility methods for common request type 
 * checks, such as POST, GET, and AJAX.
 *
 * @package    Essence\Http
 * @category   Request
 * @version    1.0.0
 * @since      1.0.0
 * @access     public
 * @author     James Gober <james@jamesgober.com>
 * @link       https://artexessence.com/core/ Project Website
 */
class Request
{
    /** @var array array for $_GET dat.a    */
    protected array $get;

    /** @var array array for $_POST data.   */
    protected array $post;

    /** @var array array for $_SERVER data. */
    protected array $server;

    /** @var array array for $_COOKIE data. */
    protected array $cookie;

    /** @var array array for $_FILES data.  */
    protected array $files;
    
    /** @var array array for $headers.      */
    protected array $headers;

    /**
     * Constructor
     *
     * Initializes the request object with superglobal data.
     *
     * @param array $get      Optional array for $_GET data
     * @param array $post     Optional array for $_POST data
     * @param array $server   Optional array for $_SERVER data
     * @param array $cookie   Optional array for $_COOKIE data
     * @param array $files    Optional array for $_FILES data
     */
    public function __construct(
        array $get = [],
        array $post = [],
        array $server = [],
        array $cookie = [],
        array $files = []
    ) {
        $this->get = $get ?: $_GET;
        $this->post = $post ?: $_POST;
        $this->server = $server ?: $_SERVER;
        $this->cookie = $cookie ?: $_COOKIE;
        $this->files = $files ?: $_FILES;
        $this->headers = $this->initializeHeaders();
    }

    /**
     * Initializes headers from the $_SERVER superglobal.
     *
     * @return array The headers parsed from $_SERVER
     */
    protected function initializeHeaders(): array
    {
        $headers = [];
        foreach ($this->server as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $headerName = str_replace('_', '-', substr($key, 5));
                $headers[$headerName] = $value;
            }
        }
        return $headers;
    }

    /**
     * Retrieves a query parameter from $_GET.
     *
     * @param string $key      The key for the query parameter
     * @param mixed $default   Optional default value if key does not exist
     * @return mixed           The value of the query parameter or default if not set
     */
    public function query(string $key, $default = null)
    {
        return $this->get[$key] ?? $default;
    }

    /**
     * Retrieves a parameter from $_POST.
     *
     * @param string $key      The key for the parameter
     * @param mixed $default   Optional default value if key does not exist
     * @return mixed           The value of the parameter or default if not set
     */
    public function input(string $key, $default = null)
    {
        return $this->post[$key] ?? $default;
    }

    /**
     * Retrieves a server parameter from $_SERVER.
     *
     * @param string $key      The key for the server parameter
     * @param mixed $default   Optional default value if key does not exist
     * @return mixed           The value of the server parameter or 
     *                         default if not set
     */
    public function server(string $key, $default = null)
    {
        return $this->server[$key] ?? $default;
    }

    /**
     * Retrieves a cookie value from $_COOKIE.
     *
     * @param string $key     The key for the cookie
     * @param mixed $default  Optional default value if key does not exist
     * @return mixed          The cookie value or default if not set
     */
    public function cookie(string $key, $default = null)
    {
        return $this->cookie[$key] ?? $default;
    }

    /**
     * Retrieves an uploaded file from $_FILES.
     *
     * @param string $key  The key for the uploaded file
     * @return array|null  The file data array or null if not set
     */
    public function file(string $key)
    {
        return $this->files[$key] ?? null;
    }

    /**
     * Retrieves a header value.
     *
     * @param string $key    The key for the header
     * @param mixed $default Optional default value if key does not exist
     * @return mixed         The header value or default if not set
     */
    public function header(string $key, $default = null)
    {
        return $this->headers[$key] ?? $default;
    }

    /**
     * Retrieves the HTTP method of the request.
     *
     * @return string The HTTP method (e.g., GET, POST)
     */
    public function getMethod(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    /**
     * Checks if the request method matches a specific method.
     *
     * @param string $method   The HTTP method to check (e.g., POST)
     * @return bool True if method matches, otherwise false
     */
    public function isMethod(string $method): bool
    {
        return $this->getMethod() === strtoupper($method);
    }

    /**
     * Checks if the request method is POST.
     *
     * @return bool True if method is POST, otherwise false
     */
    public function isPost(): bool
    {
        return $this->isMethod('POST');
    }

    /**
     * Checks if the request method is GET.
     *
     * @return bool True if method is GET, otherwise false
     */
    public function isGet(): bool
    {
        return $this->isMethod('GET');
    }

    /**
     * Checks if the request was made via AJAX.
     *
     * @return bool True if request is an AJAX request, otherwise false
     */
    public function isAjax(): bool
    {
        return strtolower($this->header('X-Requested-With')) === 'xmlhttprequest';
    }

    /**
     * Retrieves the full URI of the request.
     *
     * @return string The full URI (e.g., "/path?query=string")
     */
    public function getUri(): string
    {
        return $this->server['REQUEST_URI'] ?? '/';
    }

    /**
     * Retrieves the path of the request URI.
     *
     * @return string The URI path (e.g., "/path")
     */
    public function getPath(): string
    {
        return parse_url($this->getUri(), PHP_URL_PATH) ?? '/';
    }

    /**
     * Retrieves the query string of the request URI.
     *
     * @return string|null The query string or null if not set
     */
    public function getQueryString(): ?string
    {
        return parse_url($this->getUri(), PHP_URL_QUERY) ?? null;
    }
}
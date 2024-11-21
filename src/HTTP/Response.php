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
 * Response
 *
 * Manages the HTTP response sent to the client, including headers, 
 * status codes,and content. This class is designed to simplify setting 
 * response details and sending a finalized response back to the client.
 *
 * @package    Essence\Http
 * @category   Response
 * @version    1.0.0
 * @since      1.0.0
 * @access     public
 * @author     James Gober <james@jamesgober.com>
 * @link       https://artexessence.com/core/ Project Website
 */
class Response
{
    /**
     * The HTTP status code of the response.
     *
     * Represents the HTTP response status, with a default of `200` for 
     * "OK".This can be modified to indicate different response statuses 
     * as needed.
     *
     * @var int $statusCode The HTTP status code (e.g., 200 for OK, 404 for Not Found).
     */
    protected int $statusCode = 200;

    /**
     * An associative array of HTTP headers for the response.
     *
     * Headers allow for setting additional response parameters, such 
     * as content type, caching policies, or custom headers. Each header 
     * is a key-value pair, where the
     * key is the header name and the value is the header value.
     *
     * @var array $headers Array of headers, with each entry as `header => value`.
     */
    protected array $headers = [];

    /**
     * The body content of the HTTP response.
     *
     * Contains the main output or content returned to the client. Typically 
     * this will be the HTML, JSON, or other content that constitutes the 
     * response body.
     *
     * @var string $body The response body as a string.
     */
    protected string $body = '';

    /**
     * Set the HTTP status code for the response.
     *
     * @param int $code The HTTP status code (e.g., 200, 404, 500)
     * @return self
     */
    public function setStatusCode(int $code): self
    {
        $this->statusCode = $code;
        return $this;
    }

    /**
     * Get the HTTP status code for the response.
     *
     * @return int The current HTTP status code
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Set a header for the response.
     *
     * @param string $name The name of the header (e.g., 'Content-Type')
     * @param string $value The value of the header
     * @return self
     */
    public function setHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * Get a header value by name.
     *
     * @param string $name The name of the header
     * @return string|null The header value or null if not set
     */
    public function getHeader(string $name): ?string
    {
        return $this->headers[$name] ?? null;
    }

    /**
     * Set multiple headers for the response.
     *
     * @param array $headers Associative array of headers (e.g., ['Content-Type' => 'application/json'])
     * @return self
     */
    public function setHeaders(array $headers): self
    {
        foreach ($headers as $name => $value) {
            $this->setHeader($name, $value);
        }
        return $this;
    }

    /**
     * Get all headers for the response.
     *
     * @return array An associative array of headers
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Set the body content for the response.
     *
     * @param string $content The content for the response body
     * @return self
     */
    public function setBody(string $content): self
    {
        $this->body = $content;
        return $this;
    }

    /**
     * Get the body content of the response.
     *
     * @return string The response body content
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Send the headers, status code, and body content as the HTTP response.
     */
    public function send(): void
    {
        // Set the HTTP status code
        http_response_code($this->statusCode);

        // Set each header
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        // Output the body content
        echo $this->body;
    }

    /**
     * Send a JSON response.
     *
     * @param mixed $data The data to be encoded as JSON
     * @param int $statusCode Optional HTTP status code for the response
     */
    public function json($data, int $statusCode = 200): void
    {
        $this->setStatusCode($statusCode)
             ->setHeader('Content-Type', 'application/json')
             ->setBody(json_encode($data));

        $this->send();
    }
}
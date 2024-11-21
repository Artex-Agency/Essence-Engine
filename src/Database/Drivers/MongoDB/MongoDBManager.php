<?php
# ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
# ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
# ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
# |_____|_____|_____|_____|__|╲__|_____|_____|
# ARTEX ESSENCE ⦙⦙⦙⦙ PHP META-FRAMEWORK & ENGINE
/**
 * This file is part of the Artex Essence meta-framework.
 * 
 * @link      https://artexessence.com/ Project Website
 * @link      https://artexsoftware.com/ Artex Software
 * @license   Artex Permissive Software License (APSL)
 * @copyright 2024 Artex Agency Inc.
 */
declare(strict_types=1);

namespace Essence\Database\Drivers\MongoDB;

use \MongoDB\Client;
use \MongoDB\Database;
use \RuntimeException;
use \MongoDB\Collection;
use \InvalidArgumentException;
use \MongoDB\Exception\Exception as MongoDBException;
use \Essence\Database\Drivers\MongoDB\Exceptions\QueryException;
use \Essence\Database\Drivers\MongoDB\Exceptions\ConnectionException;

/**
 * MongoDB Manager
 *
 * Provides a comprehensive interface to interact with MongoDB,
 * including connection management, CRUD operations, and collection
 * handling.
 *
 * @package    Essence\Database\Drivers\MongoDB
 * @category   MongoDB Driver
 * @access     public
 * @version    1.0.0
 * @since      1.0.0
 * @author     James Gober <james@jamesgober.com>
 * @link       https://artexessence.com/ Project Website
 * @license    Artex Permissive Software License (APSL)
 * @copyright  2024 Artex Agency Inc.
 */
class MongoDBManager
{
    /** @var Client|null MongoDB client instance */
    private ?Client $client = null;

    /** @var Database|null MongoDB database instance */
    private ?Database $database = null;

    /** @var array Configuration options for MongoDB */
    private array $config;

    /** @var bool Connection status */
    private bool $connected = false;

/**
 * Constructor
 * 
 * @param array $config Configuration for the MongoDB connection.
 *                      Required keys:
 *                      - 'dsn': MongoDB connection string.
 *                      - 'database': Name of the database to use.
 *                      Optional keys:
 *                      - 'options': Additional MongoDB client options.
 *
 * @throws InvalidArgumentException When configuration is invalid.
 */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->validateConfig();
    }

    /**
     * Validates the configuration parameters.
     *
     * @return void
     *
     * @throws InvalidArgumentException If configuration is invalid.
     */
    private function validateConfig(): void
    {
        if (empty($this->config['dsn']) || empty($this->config['database'])) {
            throw new InvalidArgumentException("MongoDB configuration must include 'dsn' and 'database'.");
        }
    }

    /**
     * Establishes a connection to MongoDB.
     *
     * @return void
     * @throws ConnectionException If the connection fails.
     */
    private function connect(): void
    {
        if ($this->connected) {
            return;
        }

        try {
            $this->client = new Client($this->config['dsn'], $this->config['options'] ?? []);
            $this->database = $this->client->selectDatabase($this->config['database']);
            $this->connected = true;
        } catch (MongoDBException $e) {
            throw new ConnectionException("Failed to connect to MongoDB: " . $e->getMessage());
        }
    }

    /**
     * Returns a collection instance from the current database.
     *
     * @param string $collectionName Name of the collection to retrieve.
     * @return Collection MongoDB collection instance.
     */
    public function getCollection(string $collectionName): Collection
    {
        return $this->database->selectCollection($collectionName);
    }

    /**
     * Inserts a document into a specified collection.
     *
     * @param string $collectionName The collection to insert into.
     * @param array  $document       Document data to insert.
     * @return string The inserted document's ID.
     * @throws QueryException On insert failure.
     */
    public function insert(string $collectionName, array $document): string
    {
        try {
            $collection = $this->getCollection($collectionName);
            $result = $collection->insertOne($document);
            return (string) $result->getInsertedId();
        } catch (MongoDBException $e) {
            throw new QueryException("Insert failed: " . $e->getMessage());
        }
    }

    /**
     * Finds a single document in a collection.
     *
     * @param string $collectionName Collection name to search.
     * @param array  $filter         Filter conditions.
     * @param array  $options        Query options.
     * @return array|null The found document or null.
     */
    public function findOne(string $collectionName, array $filter, array $options = []): ?array
    {
        try {
            $collection = $this->getCollection($collectionName);
            return $collection->findOne($filter, $options)?->getArrayCopy();
        } catch (MongoDBException $e) {
            throw new QueryException("Find failed: " . $e->getMessage());
        }
    }

    /**
     * Finds multiple documents in a collection.
     *
     * @param string $collectionName Collection name to search.
     * @param array  $filter         Filter conditions for the search.
     * @param array  $options        Additional query options.
     *
     * @return array Array of documents that match the filter.
     */
    public function find(string $collectionName, array $filter = [], array $options = []): array
    {
        $collection = $this->getCollection($collectionName);
        $cursor = $collection->find($filter, $options);
        return iterator_to_array($cursor);
    }

    /**
     * Updates a single document in a collection based on a filter.
     *
     * @param string $collectionName Name of the collection.
     * @param array  $filter         Filter to locate the document.
     * @param array  $update         Update operations to apply.
     * @param array  $options        Additional options for the update.
     *
     * @return bool True if a document was updated; false otherwise.
     */
    public function updateOne(string $collectionName, array $filter, array $update, array $options = []): bool
    {
        $collection = $this->getCollection($collectionName);
        $result = $collection->updateOne($filter, $update, $options);
        return $result->getModifiedCount() > 0;
    }

    /**
     * Replaces a single document in a collection.
     *
     * @param string $collectionName Name of the collection.
     * @param array  $filter         Filter to locate the document.
     * @param array  $replacement    The replacement document.
     * @param array  $options        Additional options for the replace.
     *
     * @return bool True if the replacement modified a document.
     */
    public function replaceOne(string $collectionName, array $filter, array $replacement, array $options = []): bool
    {
        try {
            $collection = $this->getCollection($collectionName);
            $result = $collection->replaceOne($filter, $replacement, $options);
            return $result->getModifiedCount() > 0;
        } catch (MongoDBException $e) {
            throw new QueryException("Replace failed: " . $e->getMessage());
        }
    }

    /**
     * Deletes a single document in a collection.
     *
     * @param string $collectionName Collection name to delete from.
     * @param array  $filter         Filter conditions to locate the document.
     *
     * @return bool True if the delete operation removed at least one document.
     */
    public function deleteOne(string $collectionName, array $filter): bool
    {
        $collection = $this->getCollection($collectionName);
        $result = $collection->deleteOne($filter);
        return $result->getDeletedCount() > 0;
    }

    /**
     * Deletes multiple documents in a collection.
     *
     * @param string $collectionName Collection name to delete from.
     * @param array  $filter         Filter conditions to match documents.
     *
     * @return int The number of documents deleted.
     */
    public function deleteMany(string $collectionName, array $filter): int
    {
        $collection = $this->getCollection($collectionName);
        $result = $collection->deleteMany($filter);
        return $result->getDeletedCount();
    }

    /**
     * Performs an upsert operation: updates a document or inserts if not found.
     *
     * @param string $collectionName Name of the collection.
     * @param array  $filter         Filter to locate the document.
     * @param array  $update         Update operations to apply.
     * @param array  $options        Additional options for the operation.
     *
     * @return string|null The ID of the upserted or updated document.
     */
    public function upsert(string $collectionName, array $filter, array $update, array $options = []): ?string
    {
        try {
            $collection = $this->getCollection($collectionName);
            $result = $collection->updateOne($filter, $update, array_merge($options, ['upsert' => true]));
            return $result->getUpsertedId()?->__toString();
        } catch (MongoDBException $e) {
            throw new QueryException("Upsert failed: " . $e->getMessage());
        }
    }

    /**
     * Executes an aggregation pipeline on a collection.
     *
     * @param string $collectionName Collection name to aggregate.
     * @param array  $pipeline       Aggregation pipeline stages.
     * @param array  $options        Additional options for aggregation.
     *
     * @return array Aggregated results as an array.
     */
    public function aggregate(string $collectionName, array $pipeline, array $options = []): array
    {
        $collection = $this->getCollection($collectionName);
        $cursor = $collection->aggregate($pipeline, $options);
        return iterator_to_array($cursor);
    }

    /**
     * Counts documents matching the given filter in a collection.
     *
     * @param string $collectionName Name of the collection.
     * @param array  $filter         Filter conditions for the count.
     * @param array  $options        Additional options for the count.
     *
     * @return int The number of matching documents.
     */
    public function countDocuments(string $collectionName, array $filter = [], array $options = []): int
    {
        try {
            $collection = $this->getCollection($collectionName);
            return $collection->countDocuments($filter, $options);
        } catch (MongoDBException $e) {
            throw new QueryException("Count failed: " . $e->getMessage());
        }
    }

    /**
     * Checks if the connection to MongoDB is active.
     *
     * @return bool True if connected, false otherwise.
     */
    public function isConnected(): bool
    {
        return $this->connected;
    }

    /**
     * Disconnects from MongoDB by unsetting client and database instances.
     *
     * @return void
     */
    public function disconnect(): void
    {
        $this->client = null;
        $this->database = null;
        $this->connected = false;
    }
}
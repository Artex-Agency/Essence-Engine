<?php 
 # ¸_____¸_____¸_____¸_____¸__¸ __¸_____¸_____¸
 # ┊   __┊  ___┊  ___┊   __┊   \  ┊   __┊   __┊
 # ┊   __┊___  ┊___  ┊   __┊  \   ┊  |__|   __┊
 # |_____|_____|_____|_____|__|╲__|_____|_____|
 # ARTEX ESSENCE ENGINE ⦙⦙⦙⦙⦙ A PHP META-FRAMEWORK
/**
 * This file is part of the Artex Essence Core framework.
 *
 * @link      https://artexessence.com/engine/ Project Website
 * @link      https://artexsoftware.com/ Artex Software
 * @license   Artex Permissive Software License (APSL)
 * @version   1.0.0
 * @since     1.0.0
 * @category  Data Service
 * @package   Artex\Essence\Engine\Services\MongoDB
 */
declare(strict_types=1);

namespace Artex\Essence\Engine\Services\MongoDB;

use MongoDB\Client;
use MongoDB\Database;
use RuntimeException;
use MongoDB\Collection;
use InvalidArgumentException;
use MongoDB\Exception\Exception as MongoDBException;

/**
 * MongoDB Manager
 *
 * Provides a comprehensive interface to interact with MongoDB,
 * including connection management, CRUD operations, and collection
 * handling.
 *
 * @package    Artex\Essence\Engine\Services\MongoDB
 * @category   Data Service
 */
class MongoDBManager
{
    /** @var Client MongoDB client instance */
    private Client $client;

    /** @var Database MongoDB database instance */
    private Database $database;

    /** @var array Configuration options for MongoDB */
    private array $config;

    /**
     * MongoDBManager constructor.
     *
     * @param array $config Configuration options for the MongoDB connection.
     *
     * @throws InvalidArgumentException If required configuration options are missing.
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->validateConfig();
        $this->connect();
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
     * Establishes a connection to MongoDB using the provided configuration.
     *
     * @return void
     *
     * @throws RuntimeException If the MongoDB client cannot connect.
     */
    private function connect(): void
    {
        try {
            $this->client = new Client($this->config['dsn'], $this->config['options'] ?? []);
            $this->database = $this->client->selectDatabase($this->config['database']);
        } catch (MongoDBException $e) {
            throw new RuntimeException("Failed to connect to MongoDB: " . $e->getMessage());
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
     *
     * @return string The inserted document's ID.
     */
    public function insert(string $collectionName, array $document): string
    {
        $collection = $this->getCollection($collectionName);
        $result = $collection->insertOne($document);
        return (string) $result->getInsertedId();
    }

    /**
     * Finds a single document in a collection.
     *
     * @param string $collectionName Collection name to search.
     * @param array  $filter         Filter conditions for the search.
     * @param array  $options        Additional query options.
     *
     * @return array|null The found document or null if none is found.
     */
    public function findOne(string $collectionName, array $filter, array $options = []): ?array
    {
        $collection = $this->getCollection($collectionName);
        return $collection->findOne($filter, $options)->getArrayCopy() ?? null;
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
     * Updates a single document in a collection.
     *
     * @param string $collectionName Collection name to update.
     * @param array  $filter         Filter conditions to locate the document.
     * @param array  $update         Update operations to apply.
     * @param array  $options        Additional options for the update.
     *
     * @return bool True if the update modified at least one document.
     */
    public function updateOne(string $collectionName, array $filter, array $update, array $options = []): bool
    {
        $collection = $this->getCollection($collectionName);
        $result = $collection->updateOne($filter, $update, $options);
        return $result->getModifiedCount() > 0;
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
     * Disconnects from MongoDB by unsetting the client and database instances.
     *
     * @return void
     */
    public function disconnect(): void
    {
        $this->client = null;
        $this->database = null;
    }

    /**
     * Checks if the connection to MongoDB is active.
     *
     * @return bool True if connected, false otherwise.
     */
    public function isConnected(): bool
    {
        return isset($this->client) && isset($this->database);
    }
}
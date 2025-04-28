<?php

namespace System\Handlers;

use System\Database\Connection;

/**
 * Class ApiAuth
 *
 * Handles API token validation and caching using file-based cache.
 *
 * @package System\Handlers
 */
class ApiAuth
{
    /**
     * PDO database connection instance.
     *
     * @var \PDO
     */
    protected $pdo;

    /**
     * Path where token cache files are stored.
     *
     * @var string
     */
    protected $cachePath;

    /**
     * ApiAuth constructor.
     * Initializes database connection and sets cache path.
     */
    public function __construct()
    {
        $this->pdo = (new Connection())->getConnection();
        $this->cachePath = __DIR__ . '/../../../cache/api_tokens/';

        if (!is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0755, true);
        }
    }

    /**
     * Validate the provided API token.
     *
     * First checks the file cache. If not found, queries the database.
     *
     * @param string $token The API token to validate.
     * @return array The token data if valid.
     *
     * @throws \Exception If the token is invalid or expired.
     */
    public function validateToken(string $token): array
    {
        $cacheKey = md5($token);
        $cacheFile = $this->cachePath . $cacheKey . '.php';

        // Check from file cache first
        if (file_exists($cacheFile)) {
            $data = include $cacheFile;
            if ($data && isset($data['token']) && $data['token'] === $token) {
                return $data;
            }
        }

        // Fallback to database query
        $stmt = $this->pdo->prepare("
            SELECT * FROM api_detail
            WHERE token = :token AND is_active = 1 AND is_delete = 0
              AND (expire_at IS NULL OR expire_at > NOW())
        ");
        $stmt->bindParam(':token', $token);
        $stmt->execute();

        $tokenData = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($tokenData) {
            // Save into file cache
            file_put_contents($cacheFile, '<?php return ' . var_export($tokenData, true) . ';');
            return $tokenData;
        }

        throw new \Exception("Invalid or expired token.");
    }

    /**
     * Cache all active tokens into file cache.
     *
     * Fetches all active, non-expired tokens and saves them individually.
     *
     * @return void
     */
    public function cacheAllActiveTokens(): void
    {
        $stmt = $this->pdo->query("
            SELECT * FROM api_detail
            WHERE is_active = 1 
              AND is_delete = 0 
              AND (expire_at IS NULL OR expire_at > NOW())
        ");
        $tokens = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($tokens as $tokenRow) {
            $cacheKey = md5($tokenRow['token']);
            $cacheFile = $this->cachePath . $cacheKey . '.php';
            file_put_contents($cacheFile, '<?php return ' . var_export($tokenRow, true) . ';');
        }
    }

    /**
     * Delete a specific token from file cache.
     *
     * @param string $token The API token to delete from cache.
     * @return void
     */
    public function deleteTokenFromCache(string $token): void
    {
        $cacheKey = md5($token);
        $cacheFile = $this->cachePath . $cacheKey . '.php';

        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }
    }

    /**
     * Clean all invalid tokens from file cache.
     *
     * Finds inactive or deleted tokens from the database and removes them from cache.
     *
     * @return void
     */
    public function cleanInvalidTokens(): void
    {
        $stmt = $this->pdo->query("
            SELECT token FROM api_detail
            WHERE is_active = 0 OR is_delete = 1
        ");
        $tokens = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($tokens as $tokenRow) {
            $this->deleteTokenFromCache($tokenRow['token']);
        }
    }
}

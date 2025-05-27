<?php

namespace App\Model;

use System\Model;
use PDO;

class Queries extends Model
{
    /**
     * Queries constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Insert a new page.
     *
     * @param array $page
     * @return bool
     */
    public function insertPage(array $page): bool
    {
        return $this->insert('page', $page);
    }

    /**
     * Fetch all pages.
     *
     * @return array
     */
    public function fetchPages(): array
    {
        return $this->select('page');
    }

    /**
     * Insert API token data.
     *
     * @param array $data
     * @return bool
     */
    public function insertTokenAPI(array $data): bool
    {
        return $this->insert('api_detail', $data);
    }

    /**
     * Fetch all API tokens.
     *
     * @return array
     */
    public function fetchAPIToken(): array
    {
        return $this->select('api_detail');
    }

    /**
     * Fetch all schedulers.
     *
     * @return array
     */
    public function fetchSchedulers(): array
    {
        return $this->select('schedulers');
    }

    /**
     * Fetch all users with user details using manual join.
     *
     * @return array
     */
    public function fetchUsers(): array
    {
        $sql = "SELECT * FROM user 
                LEFT JOIN user_detail 
                ON user.user_id = user_detail.user_id";
        $stmt = $this->getPDO()->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Delete a page by page_id.
     *
     * @param int|string $id
     * @return bool
     */
    public function deleteRoute(int|string $id): bool
    {
        return $this->delete('page', ['page_id' => $id]);
    }
}

<div class="card">
    <!-- Users in Record -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3><i class="fa-solid fa-users"></i> Users</h3>
        </div>

        <div class="panel-body">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>User ID</th>
                    <th>Email</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Status</th>
                    <th>Delete</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($user)) : ?>
                    <?php foreach ($user as $row) : ?>
                        <tr>
                            <td><?= $row['user_id'] ?></td>
                            <td><?= $row['email'] ?></td>
                            <td><?= $row['first_name'] ?></td>
                            <td><?= $row['last_name'] ?></td>
                            <td><?= $row['status'] ?></td>
                            <td>
                                <a href="<?= base_url("admin/delete_user/{$row['user_id']}") ?>">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="9">No users found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

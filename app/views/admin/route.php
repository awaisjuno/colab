
<div class="main">

    <div class="card">

        <!--Insert New Page Details-->
        <div class="panel panel-default">

            <div class="panel-heading">
                <h3>Add New Page</h3>
            </div>

            <div class="panel-body">

                <?= form_open() ?>

                <!-- Page Name -->
                <div class="form-group">
                    <?= form_label('Page Name') ?>
                    <?= form_input('page_name', '', [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Page Name'
                    ]) ?>
                </div>

                <!-- Page Route -->
                <div class="form-group">
                    <?= form_label('Page Route') ?>
                    <?= form_input('page_route', '', [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Page Route (e.g. /about)'
                    ]) ?>
                </div>

                <!-- Page Title (required) -->
                <div class="form-group">
                    <?= form_label('Page Title *') ?>
                    <?= form_input('page_title', '', [
                        'class' => 'form-control',
                        'required' => 'required',
                        'placeholder' => 'Enter Page Title'
                    ]) ?>
                </div>

                <!-- Page Description -->
                <div class="form-group">
                    <?= form_label('Page Description') ?>
                    <?= form_textarea('page_description', '', [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Page Description',
                        'rows' => 4
                    ]) ?>
                </div>

                <!-- Page Keywords -->
                <div class="form-group">
                    <?= form_label('Page Keywords') ?>
                    <?= form_input('page_keywords', '', [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Keywords (comma-separated)'
                    ]) ?>
                </div>

                <!-- Submit Button -->
                <div class="form-group">
                    <button type="submit" name="submit" class="btn btn-default">Save Route</button>
                </div>

                <?= form_close() ?>

            </div>

        </div>

        <!--Pages in Record-->
        <div class="panel panel-default">

            <div class="panel-heading">
                <h3>Routes</h3>
            </div>

            <div class="panel-body">

                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Page ID</th>
                        <th>Page Name</th>
                        <th>Page Route</th>
                        <th>Page Title</th>
                        <th>Page Description</th>
                        <th>Page Keywords</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($route)) : ?>
                        <?php foreach ($route as $row) : ?>
                            <tr>
                                <td><?= $row['page_id'] ?></td>
                                <td><?= $row['page_name'] ?></td>
                                <td><?= $row['page_route'] ?></td>
                                <td><?= $row['page_title'] ?></td>
                                <td><?= $row['page_description'] ?></td>
                                <td><?= $row['page_keywords'] ?></td>
                                <td><a href="<?= base_url("admin/edit/{$row['page_id']}") ?>" class="btn btn-sm btn-warning">Edit</a></td>
                                <td><a href="<?= base_url("admin/delete/{$row['page_id']}") ?>" class="btn btn-sm btn-danger">Delete</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="8">No pages found.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>


            </div>

        </div>

    </div>

</div>
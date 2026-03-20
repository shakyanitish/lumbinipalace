<?php

// Download Module - Case Study Integration
$download_categories = array(
    1 => 'Research',
    2 => 'Case Study',
    3 => 'Medical Study'
);

// Get all published downloads from database
$moduleTablename = "tbl_download";
$records = Download::find_by_sql("SELECT * FROM " . $moduleTablename . " WHERE status = 1 ORDER BY sortorder DESC");

$downloadRows = '';
$sn = 1;

if (!empty($records)) {
    foreach ($records as $key => $record) {
        $file_ext = strtoupper(pathinfo($record->image, PATHINFO_EXTENSION));
        $file_date = !empty($record->case_date) ? date('jS F Y', strtotime($record->case_date)) : 'N/A';
        $file_path = BASE_URL . "images/download/docs/" . $record->image;

        $downloadRows .= '
                                <tr>
                                    <th scope="row">' . $sn++ . '</th>
                                    <td>' . $record->title . '</td>
                                    <td>' . $file_ext . '</td>
                                    <td><button><a href="' . $file_path . '" download><img src="' . BASE_URL . 'template/web/images/download.png" alt="download">Download</a></button></td>
                                </tr>';
    }
}
else {
    $downloadRows = '
                                <tr>
                                    <td colspan="4" class="text-center">No downloads available</td>
                                </tr>';
}

$resource_list = '
    <section class="about-company inner-about">
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-md-8">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">S.N</th>
                                <th scope="col">Resources</th>
                                <th scope="col">Type</th>
                                <th scope="col">Download</th>
                            </tr>
                        </thead>
                        <tbody>
                            ' . $downloadRows . '
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>';

$jVars['module:resource-list'] = $resource_list;
$jVars['module:case-study'] = $resource_list;

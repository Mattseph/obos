<div class="modal fade w-100" id="business-list" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Business</h5>
                <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

            <div class="modal-body">
                <table class="table table-borderless d-flex flex-column justify-content-center" id="businessModalTable" width="100%" cellspacing="0">
                    <thead>
                        <tr class="d-flex justify-content-between border-bottom">
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
                        $businessQuery = "SELECT DISTINCT schedule_id, bus_id, bus_name, bus_img_url, schedule_date FROM business_inspection_schedule_view WHERE schedule_date = CURDATE() ORDER BY schedule_date";
                        $businessStatement = $pdo->query($businessQuery);
                        $businesses = $businessStatement->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($businesses as $business) {
                        ?>
                            <tr class="d-flex justify-content-between align-businesss-center border-bottom py-1 select-business" data-business-id="<?php echo $business['bus_id'] ?>">
                                <td class="p-0 m-0 w-100">
                                    <a href="#" class="d-flex align-businesss-center text-decoration-none
                                text-gray-700 flex-gap w-100">
                                        <div class=" image-container img-fluid">
                                            <img src="./../business/images/<?php echo $business['bus_img_url'] ?? 'default-img.png' ?>" alt="inspector-image" class="img-fluid rounded-circle" />
                                        </div>

                                        <div>
                                            <div class="text">
                                                <?php echo $business['bus_name'] ?>
                                            </div>
                                            <div class="sub-title d-none d-md-flex"> Inspection Schedule
                                                <?php echo $business['schedule_date'] ?></div>

                                        </div>
                                    </a>

                                </td>
                                <td>
                                    <a class="btn btn-primary py-1" data-business-id="<?php echo $business['bus_id'] ?>">
                                        Select
                                    </a>
                                </td>
                            </tr>
                        <?php
                        }

                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
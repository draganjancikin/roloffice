<div class="card mb-4">
    <div class="card-header p-2">
        <h6 class="d-inline m-0 text-dark">Aktivni projekti</h6>
        <div class="float-right">
            <form method="get">
                <div class="form-group row">
                    <div class="col-sm-7">
                        <select class="form-control" name="city_id">
                            <option value="">Izaberi naselje</option>
                            <?php
                            $citys = $project->getCitysByActiveProject();
                            foreach ($citys as $city) {
                                echo '<option value="' .$city['id']. '">' .$city['name']. '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-5">
                        <button type="submit" class="btn btn-sm btn-outline-secondary">Prikaži projekte</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card-body p-2">
        <div class="table-responsive">
            <table class="table table-hover" id="" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th class="w-25 text-center">projekti</th>
                        <th class="w-25 text-center">za realizaciju</th>
                        <th class="w-25 text-center">u realizaciji</th>
                        <th class="w-25 text-center">realizovano</th>
                    </tr>
                </thead>
                <tfoot class="thead-light">
                    <tr>
                        <th class="w-25">projekti</th>
                        <th class="w-25">za realizaciju</th>
                        <th class="w-25">u realizaciji</th>
                        <th class="w-25">realizovano</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    $status = 1;
                    $project_list = $project->projectTracking($status);
                    foreach( $project_list as $project_item):
                        $project_id = $project_item['id'];
                        $project_tasks = $project->projectTasks($project_id);
                        ?>
                        <tr>
                            <td>
                                <a href="?view&project_id=<?php echo $project_item['id']; ?>" class="d-block card-link" title='<?php echo date('d M Y', strtotime($project_item['date']));?>'>
                                    #<?php echo str_pad($project_item['pr_id'], 4, "0", STR_PAD_LEFT).' - '.$project_item['title']; ?>
                                </a>
                                <?php echo $project_item['client_name']. ', <span style="font-size: 0.9em;">' .$project_item['client_city_name']. '</span>'; ?>
                            </td>
                            <td>
                                <?php
                                $count1 = 0;
                                foreach($project_tasks as $project_task):
                                    if($project_task['status_id'] == 1):
                                        ?>
                                        <a href="?editTask&task_id=<?php echo $project_task['id']; ?>&project_id=<?php echo $project_id; ?>">
                                            <span class="badge badge-<?php echo $project_task['class']; ?>">
                                                <?php echo $project_task['tip']; ?>
                                            </span>
                                            <?php echo $project_task['title']; ?>
                                        </a><br />
                                        <?php
                                        $count1 ++;
                                        if ($count1 == 4):
                                            ?>
                                            <a class="" data-toggle="collapse" href="#collapseExample1<?php echo $project_id?>" role="button" aria-expanded="false" aria-controls="collapseExample1">
                                                <i class="fas fa-caret-down"></i>
                                            </a>
                                            <div class="collapse" id="collapseExample1<?php echo $project_id?>">
                                            <?php
                                        endif;
                                    endif;
                                endforeach;
                                if($count1 > 3) echo '</div>';
                                ?>
                            </td>
                            <td>
                                <?php
                                $count2 = 0;
                                foreach($project_tasks as $project_task):
                                    if($project_task['status_id'] == 2):
                                        ?>
                                        <a href="?editTask&task_id=<?php echo $project_task['id']; ?>&project_id=<?php echo $project_id; ?>">
                                            <span class="badge badge-<?php echo $project_task['class']; ?>">
                                                <?php echo $project_task['tip']; ?>
                                            </span>
                                            <?php echo $project_task['title']; ?>
                                        </a><br />
                                        <?php
                                        $count2 ++;
                                        if ($count2 == 4):
                                            ?>
                                            <a class="" data-toggle="collapse" href="#collapseExample2<?php echo $project_id?>" role="button" aria-expanded="false" aria-controls="collapseExample2">
                                                <i class="fas fa-caret-down"></i>
                                            </a>
                                            <div class="collapse" id="collapseExample2<?php echo $project_id?>">
                                            <?php
                                        endif;
                                    endif;
                                endforeach;
                                if($count2 > 3) echo '</div>';
                                ?>
                            </td>
                            <td>
                                <?php
                                $count3 = 0;
                                foreach($project_tasks as $project_task):
                                    if($project_task['status_id'] == 3):
                                        ?>
                                        <a href="?editTask&task_id=<?php echo $project_task['id']; ?>&project_id=<?php echo $project_id; ?>">
                                        <span class="badge badge-<?php echo $project_task['class']; ?>">
                                            <?php echo $project_task['tip']; ?>
                                        </span>
                                        <?php echo $project_task['title']; ?>
                                        </a><br />
                                        <?php
                                        $count3 ++;
                                        if ($count3 == 4):
                                            ?>
                                            <a class="" data-toggle="collapse" href="#collapseExample3<?php echo $project_id?>" role="button" aria-expanded="false" aria-controls="collapseExample3">
                                                <i class="fas fa-caret-down"></i>
                                            </a>
                                            <div class="collapse" id="collapseExample3<?php echo $project_id?>">
                                            <?php
                                        endif;
                                    endif;
                                endforeach;
                                if($count3 > 3) echo '</div>';
                                ?>
                            </td>
                        </tr>
                        <?php
                    endforeach;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- End Card Body -->
</div>
<!-- End Card -->

<div class="card mb-4">
    <div class="card-header p-2">
        <h6 class="d-inline m-0 text-dark">Projekti na čekanju</h6>
    </div>
    <div class="card-body p-2">
        <div class="table-responsive">
            <table class="dataTable table table-hover" id="" width="100%" cellspacing="0">
                <thead class="thead-light">
                    <tr>
                        <th class="w-25 text-center">projekti</th>
                        <th class="w-25 text-center">za realizaciju</th>
                        <th class="w-25 text-center">u realizaciji</th>
                        <th class="w-25 text-center">realizovano</th>
                    </tr>
                </thead>
                <tfoot class="thead-light">
                    <tr>
                        <th class="w-25">projekti</th>
                        <th class="w-25">za realizaciju</th>
                        <th class="w-25">u realizaciji</th>
                        <th class="w-25">realizovano</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php
                    $status = 2;
                    $project_list = $project->projectTracking($status);
                    foreach( $project_list as $project_item):
                        $project_id = $project_item['id'];
                        $project_tasks = $project->projectTasks($project_id);
                        ?>
                        <tr>
                            <td>
                                <a href="?view&project_id=<?php echo $project_item['id']; ?>" class="d-block card-link" title='<?php echo date('d M Y', strtotime($project_item['date']));?>'>
                                    #<?php echo str_pad($project_item['pr_id'], 4, "0", STR_PAD_LEFT).' - '.$project_item['title']; ?>
                                </a>
                                <?php echo $project_item['client_name']. ', <span style="font-size: 0.9em;">' .$project_item['client_city_name']. '</span>'; ?>
                            </td>
                            <td>
                                <?php
                                $count1 = 0;
                                foreach($project_tasks as $project_task):
                                    if($project_task['status_id'] == 1):
                                        ?>
                                        <a href="?editTask&task_id=<?php echo $project_task['id']; ?>&project_id=<?php echo $project_id; ?>">
                                        <span class="badge badge-<?php echo $project_task['class']; ?>">
                                            <?php echo $project_task['tip']; ?>
                                        </span>
                                        <?php echo $project_task['title']; ?>
                                        </a><br />
                                        <?php
                                        $count1 ++;
                                        if ($count1 == 4):
                                        ?>
                                        <a class="" data-toggle="collapse" href="#collapse1<?php echo $project_id?>" role="button" aria-expanded="false" aria-controls="collapse1">
                                            <i class="fas fa-caret-down"></i>
                                        </a>
                                        <div class="collapse" id="collapse1<?php echo $project_id?>">
                                        <?php
                                        endif;
                                    endif;
                                endforeach;
                                if($count1 > 3) echo '</div>';
                                ?>
                            </td>
                            <td>
                                <?php
                                $count2 = 0;
                                foreach($project_tasks as $project_task):
                                    if($project_task['status_id'] == 2):
                                        ?>
                                        <a href="?editTask&task_id=<?php echo $project_task['id']; ?>&project_id=<?php echo $project_id; ?>">
                                        <span class="badge badge-<?php echo $project_task['class']; ?>">
                                            <?php echo $project_task['tip']; ?>
                                        </span>
                                        <?php echo $project_task['title']; ?>
                                        </a><br />
                                        <?php
                                        $count2 ++;
                                        if ($count2 == 4):
                                        ?>
                                        <a class="" data-toggle="collapse" href="#collapse2<?php echo $project_id?>" role="button" aria-expanded="false" aria-controls="collapse2">
                                            <i class="fas fa-caret-down"></i>
                                        </a>
                                        <div class="collapse" id="collapse2<?php echo $project_id?>">
                                        <?php
                                        endif;
                                    endif;
                                endforeach;
                                if($count2 > 3) echo '</div>';
                                ?>
                            </td>
                            <td>
                                <?php
                                $count3 = 0;
                                foreach($project_tasks as $project_task):
                                    if($project_task['status_id'] == 3):
                                        ?>
                                        <a href="?editTask&task_id=<?php echo $project_task['id']; ?>&project_id=<?php echo $project_id; ?>">
                                        <span class="badge badge-<?php echo $project_task['class']; ?>">
                                            <?php echo $project_task['tip']; ?>
                                        </span>
                                        <?php echo $project_task['title']; ?>
                                        </a><br>
                                        <?php
                                        $count3 ++;
                                        if ($count3 == 4):
                                        ?>
                                        <a class="" data-toggle="collapse" href="#collapse3<?php echo $project_id?>" role="button" aria-expanded="false" aria-controls="collapse3">
                                            <i class="fas fa-caret-down"></i>
                                        </a>
                                        <div class="collapse" id="collapse3<?php echo $project_id?>">
                                        <?php
                                        endif;
                                    endif;
                                endforeach;
                                if($count3 > 3) echo '</div>';
                                ?>
                            </td>
                        </tr>
                        <?php
                        endforeach;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- End Card Body -->
</div>
<!-- End Card -->

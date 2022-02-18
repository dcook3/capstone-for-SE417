<div id="dateFilterDiv" class="col-12 collapse">
                <h3 class="fw-bold fs-3">Date Filter</h3>
                <form method="get" action="orders_view.php" id="dateFilter" class="p-2 col-6 border d-flex flex-column">
                    <div class="d-flex flex-row row justify-content-evenly">
                        <div class="form-group col-4">
                            <label for="year" class="form-label">Year</label>
                            <select class="dateFilter form-control" name="year">
                                <option selected hidden>Year</option>
                                <?php 
                                    for($i = $selectedYear ; $i>=$selectedYear-40; $i--)
                                    {
                                        if($i == 1 && !isset($selectedYear))
                                        {
                                            echo '<option selected hidden>Year</option>';
                                        }
                                        else
                                        {
                                            echo "<option selected hidden>$selectedYear</option>";
                                        }
                                        echo '<option value="' . $i . '">' . $i . '</option>';
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="form-group col-4">
                            <label for="month" class="form-label">Month</label>
                            <select name="month" class="dateFilter form-control">
                                <option selected hidden><?= $selectedMonth ?></option>
                                <option>January</option>
                                <option>February</option>
                                <option>March</option>
                                <option>April</option>
                                <option>May</option>
                                <option>June</option>
                                <option>July</option>
                                <option>August</option>
                                <option>September</option>
                                <option>October</option>
                                <option>November</option>
                                <option>December</option>
                            </select>
                        </div>
                        
                        <div class="form-group col-4">
                            <label for="day" class="form-label">Day</label>
                            <select name="day" class="dateFilter form-control">
                                <?php
                                    echo "<option selected hidden>{$selectedDay}</option>";
                                    for($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, $selectedMonthInt, $selectedYear); $i++)
                                    {
                                        echo "<option>{$i}</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

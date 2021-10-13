<?php

namespace App\Controllers;

use App\Libraries\Cron_job;

class Cron extends App_Controller {

    private $cron_job;

    function __construct() {
        parent::__construct();
        $this->cron_job = new Cron_job();
    }

    function index() {
        ini_set('max_execution_time', 300); //execute maximum 300 seconds 
        //wait at least 5 minute befor starting new cron job
        $last_cron_job_time = get_setting('last_cron_job_time');
        echo "last cron job time: " + $last_cron_job_time;
        $current_time = strtotime(get_current_utc_time());

        echo "Current Time: " + $current_time;
        echo "Calculated Last Cron Time: " + $last_cron_job_time * 1 + 300;
        if ($last_cron_job_time == "" || ($current_time > ($last_cron_job_time * 1 + 300))) {
            echo "Running Cron Job";
            $this->cron_job->run();
            app_hooks()->do_action("app_hook_after_cron_run");
            $this->Settings_model->save_setting("last_cron_job_time", $current_time);
        } else {
            echo "Don't Run Cron job";
        }
    }

}

/* End of file Cron.php */
/* Location: ./app/controllers/Cron.php */
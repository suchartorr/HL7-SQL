<?php

/*
 * The MIT License
 *
 * Copyright 2559 it.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
require_once './lib/hl7.php';

/**
 * Description of hl7_2_db
 *
 * @author suchart bunhachirat
 */
class hl7_2_db {
    
    private $hl7;
    private $order_file = array();

    public function __construct() {
        $path_filename = "./lis/res/151010206004213.hl7";
        try {
            $this->hl7 = new HL7($path_filename);
            echo $this->insert_order();
            //$this->test_order();
            //print_r($this->hl7->segment_count);
        } catch (Exception $ex) {
            echo 'Caught exception: ', $ex->getMessage(), "\n";
        }
    }
    
    public function test_order(){
        $message = $this->hl7->get_message();
        $this->order_file['message_date'] = $message[0]->fields[5];
        $this->order_file['patient_id'] = $message[1]->fields[2];
        //$this->record['order_number'] = $message[4]->fields[1];
        $this->order_file['transection_date'] = $message[3]->fields[8];
        $this->order_file['order_comment'] = $message[5]->fields[8];
        
        print_r($this->order_file);
        print_r($this->hl7->get_message());
    }
    
    protected function insert_order() {
        $message = $this->hl7->get_message();
        $dsn = 'mysql:host=10.1.99.6;dbname=theptarin_utf8';
        $username = 'orr-projects';
        $password = 'orr-projects';
        $options = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        );
        $db_conn = new PDO($dsn, $username, $password, $options);
        $sql = "INSERT INTO `theptarin_utf8`.`lis_order` (`id`, `message_date`, `patient_id`, `sec_user`, `sec_time`, `sec_ip`, `sec_script`) VALUES (NULL, :message_date , :patient_id, \'\', CURRENT_TIMESTAMP, \'\', \'\')";
        $stmt = $db_conn->prepare($sql);
        $stmt->execute(array(":message_date" => $message[0]->fields[5] ,":patient_id" => $message[1]->fields[2]));
        return $db_conn->lastInsertId();
    }
}

$my = new hl7_2_db();
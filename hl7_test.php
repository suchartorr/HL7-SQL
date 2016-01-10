<?php

/*
 * The MIT License
 *
 * Copyright 2559 suchart.orr@gmail.com.
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
 * การอ่านไฟล์ข้อมูลผลแลปผู้ป่วยจาก LIS
 * 1. อ่านไฟล์ HL7 ผลแลปอยู่ในโฟลเดอร์
 * 2. วิเคราะห์ไฟล์แยกส่วนข้อมูลเพื่อสามารถจัดเตรียมนำเข้าฐานข้อมูลได้
 * 3. ส่งข้อมูลเข้าฐานข้อมูล
 * 
 */
$path_foder = "./lis/res/*.hl7";
$list_files = glob($path_foder);
foreach ($list_files as $filename) {
    printf("$filename size " . filesize($filename) . "  " . date('Ymd H:i:s') . "\n");
    try {
        $hl7 = new HL7($filename);
        $msg = $hl7->get_msg();
        foreach ($msg as $seg => $idx) {
            foreach ($idx as $key => $content){
                echo $seg,";",$key,"\n";
            }
        }
        /**
         * update sql
         */
    } catch (Exception $ex) {
        echo 'Caught exception: ', $ex->getMessage(), "\n";
    }
}


<?php


call_user_func($_GET['func']);


function select_account(){
    $API = new SalesforceRestApi();
    $sql_str = "SELECT Id,Name FROM ACCOUNT LIMIT 100";
    $res = $API->query($sql_str);
    echo "<pre>";
    print_r($res);
    echo "</pre>";
}

function select_contact(){
    $API = new SalesforceRestApi();
    $sql_str = "SELECT Id,Name,Position__c,AHPRA_Number__c,AccountId,Phone,Email FROM Contact LIMIT 100";
    $res = $API->query($sql_str);
    echo "<pre>";
    print_r($res);
    echo "</pre>";
}

function select_Commercial_Approval__c(){
    $API = new SalesforceRestApi();
    $sql_str = "SELECT Id,Name,Contact__c,Account__c,RecordTypeId,Type__c,Patient_Ref__c,Patient_Condition__c,Reason_s_for_request__c,Requested_Delivery_Date__c,Approved_Required_By__c,Special_Instructions__c,Contract_Partner__c FROM Commercial_Approval__c LIMIT 100";
    $res = $API->query($sql_str);
    echo "<pre>";
    print_r($res);
    echo "</pre>";
}

function select_Commercial_Approval_SKU__c(){
    $API = new SalesforceRestApi();
    $sql_str = "SELECT Commercial_Approval__c,SKU__c,Quantity__c FROM Commercial_Approval_SKU__c LIMIT 100";
    $res = $API->query($sql_str);
    echo "<pre>";
    print_r($res);
    echo "</pre>";
}


function create_Commercial_Approval__c(){
    $API = new SalesforceRestApi();
    $data = array(
        'RecordTypeId'                  => '01290000000swuoAAA',
        'Type__c'                       => 'Compassionate Stock',  // either Samples or Compassionate Stock
        'Reason_s_for_request__c'       => 'Reason for request',
        'Approved_Required_By__c'       => '2022-06-20',
        'Contact__c'                    => '0032N00000IyJqoQAF',
        'Requested_Delivery_Date__c'    => '2022-07-01',
        'Patient_Ref__c'                => 'AB - 12/12/2000',
        'Patient_Condition__c'          => 'Patient condition',
        'Special_Instructions__c'       => 'Address here',
    );
    $res = $API->create('Commercial_Approval__c', $data);
    echo "<pre>";
    print_r($res);
    echo "</pre>";

}

function create_Commercial_Approval__c_2(){
    $API = new SalesforceRestApi();
    $data = array(
        'RecordTypeId'                  => '01290000000swuoAAA',
        'Type__c'                       => 'Compassionate Stock',  // either Samples or Compassionate Stock
        'Reason_s_for_request__c'       => 'Reason for request',
        'Approved_Required_By__c'       => '2022-06-20',
        'Contact__c'                    => '0032N00000IyJqoQAF',
        'Requested_Delivery_Date__c'    => '2022-07-01',
        'Patient_Ref__c'                => 'AB - 12/12/2000',
        'Patient_Condition__c'          => 'Patient condition',
        'Special_Instructions__c'       => 'Address here',
        "Contract_Partner__c"           => "",
        "HCP_Employer_Approval__c"      => "",
        "HCP_Employment_Status__c"      => "",
        "HCP_Employer_Notification__c"  => ""
    );
    $res = $API->create('Commercial_Approval__c', $data);
    echo "<pre>";
    print_r($res);
    echo "</pre>";

}
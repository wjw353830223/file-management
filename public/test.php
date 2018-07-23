<?php
$arr = [
           '0' => [
                   'id' => '12',
                   'name' => '测试三',
                   'pid' => '0',
                   'state' => '0',
                   'deep' => '1',
                   'organization_id' => '2',
               ],

           '1' => [
                   'id' => '13',
                   'name' => '|-测试3',
                   'pid' => '12',
                   'state' => '0',
                   'deep' => '2',
                   'organization_id' => '2',
               ],

           '2' => [
                   'id' => '15',
                   'name' => '|-测试4',
                   'pid' => '12',
                   'state' => '0',
                   'deep' => '2',
                   'organization_id' => '2',
               ],

           '3' => [
                   'id' => '14',
                   'name' => '||-测试（3）',
                   'pid' => '13',
                   'state' => '0',
                   'deep' => '3',
                   'organization_id' => '2',
               ],

           '4' => [
                   'id' => '16',
                   'name' => '||-测试（4）',
                   'pid' => '13',
                   'state' => '0',
                   'deep' => '3',
                   'organization_id' => '2',
               ],

       ];
       $return = [];
foreach($arr as $key=>$value){
    $return[$value['pid']][$value['id']]=$value;
}
$return1=[];
foreach ($return as $key => $value) {
  foreach($value as $k=>$val){
     if($key == $k){
       $value[$k]['child'][] = $value
     }
  }
}
print_r($return);

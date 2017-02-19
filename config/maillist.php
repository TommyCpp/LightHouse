<?php
 return array (
  'notify' => 
  array (
    'seat_exchange_applied' => 
    array (
      'sender' => 'sender@test.com',
      'ccs' => 
      array (
        0 => 'info@test.com',
      ),
      'initiator_subject' => '名额交换请求创建成功',
      'target_subject' => '收到新的名额交换请求',
      'emergence_contact' => 'emergence@test.com',
    ),
    'seat_exchanged' => 
    array (
      'sender' => 'sender@test.com',
      'ccs' => 
      array (
        0 => 'info@test.com',
      ),
      'initiator_subject' => '名额交换完成',
      'target_subject' => '名额交换完成',
      'emergence_contact' => 'emergence@test.com',
    ),
  ),
) ;
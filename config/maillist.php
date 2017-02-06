<?php
return [
    "notify" => [
        "seat_exchange_applied" => [
            "sender" => "sender@test.com",
            "ccs" => ["info@test.com"],
            "initiator_subject" => "名额交换请求创建成功",
            "target_subject" => "收到新的名额交换请求",
            "emergence_connector" => "emergence@test.com"
        ],
        "seat_exchanged" => [
            "sender" => "sender@test.com",
            "ccs" => ["info@test.com"],
            "initiator_subject" => "名额交换完成",
            "target_subject" => "名额交换完成",
            "emergence_connector" => "emergence@test.com"
        ]
    ]
];
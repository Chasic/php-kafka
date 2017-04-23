<?php

echo 'Starting consumer ..' . PHP_EOL;

$rk = new RdKafka\Consumer();
$rk->setLogLevel(LOG_DEBUG);
$rk->addBrokers(getenv('KAFKA_HOST'));


$topic = $rk->newTopic("test");
$topic->consumeStart(0, RD_KAFKA_OFFSET_BEGINNING);


while (true) {
    $msg = $topic->consume(0, 1000);

    if ($msg !== null) {
        if ($msg->err) {
            if ($msg->err === RD_KAFKA_RESP_ERR__PARTITION_EOF) {
                sleep(1);
            } else {
                echo $msg->errstr() . PHP_EOL;
            }
        } else {
            echo $msg->payload . PHP_EOL;
        }
    } else {
        $err = rd_kafka_errno2err(rd_kafka_errno());

        if ($err !== RD_KAFKA_RESP_ERR__TIMED_OUT) {
            echo 'Error: ' . $err . PHP_EOL;
        }
    }


}


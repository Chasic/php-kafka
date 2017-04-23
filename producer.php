<?php

echo 'Starting producer ..' . PHP_EOL;

$rk = new RdKafka\Producer();
$rk->setLogLevel(LOG_DEBUG);
$rk->addBrokers(getenv('KAFKA_HOST'));

$topic = $rk->newTopic("test");

$i = 0;
while (true) {
    $topic->produce(RD_KAFKA_PARTITION_UA, 0, "Test message #" . $i++);
    sleep(1);
}

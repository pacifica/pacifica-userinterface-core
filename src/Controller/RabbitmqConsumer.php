<?php

namespace Drupal\pacifica\Controller;

use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Class RabbitmqConsumer.
 *
 * @package Drupal\pacifica\Controller
 */
class RabbitmqConsumer {

  protected $rabbitmqHost;
  protected $rabbitmqPort;
  protected $rabbitmqUser;
  protected $rabbitmqPass;
  protected $rabbitmqQueue;

  protected $consumerTimeout;

  /**
   * RabbitmqConsumer constructor.
   */
  public function __construct() {

    \Drupal::messenger()->addStatus(t("Executed the constructor function."));

    $rabbitmq_config = \Drupal::config('pacifica.rabbitmq.settings');
    $consumer_config = \Drupal::config('pacifica.consumer.settings');

//    $this->rabbitmqHost = $config->get('rabbitmq_host');
    $this->rabbitmqHost = 'localhost';
    $this->rabbitmqPort = $rabbitmq_config->get('rabbitmq_port');
    $this->rabbitmqUser = $rabbitmq_config->get('rabbitmq_user');
    $this->rabbitmqPass = $rabbitmq_config->get('rabbitmq_pass');
    $this->rabbitmqQueue = $rabbitmq_config->get('rabbitmq_queue');

    $this->consumerTimeout = $consumer_config->get('consumer_timeout');

    $this->connection = new AMQPStreamConnection(
      $this->rabbitmqHost,
      $this->rabbitmqPort,
      $this->rabbitmqUser,
      $this->rabbitmqPass
    );

    $this->channel = $this->connection->channel();
    $this->channel->queue_declare($this->rabbitmqQueue, FALSE, FALSE, FALSE, FALSE);

  }

  /**
   * Consumes messages from RabbitMQ queue.
   */
  public function consume() {

    \Drupal::logger('pacifica')->info(t("Executed the consumer function"));
    \Drupal::messenger()->addStatus(t("Executed the consumer function"));


    $callback = function ($msg) {
      \Drupal::logger('pacifica')->info(t("@message", ['@message' => html_entity_decode($msg->body)]), $context = []);
    };

    $this->channel->basic_consume($this->rabbitmqQueue, '', FALSE, TRUE, FALSE, FALSE, $callback);

    while (count($this->channel->callbacks)) {
      try {
        $this->channel->wait(NULL, FALSE, $this->consumerTimeout);
      }
      catch (AMQPTimeoutException $e) {
        $this->channel->close();
        $this->connection->close();
        exit;
      }
    }

  }

}

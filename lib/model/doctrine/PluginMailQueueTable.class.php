<?php
/**
 * PluginDiaryTable
 *
 * @package    opMailQueuePlugin
 * @author     Hiromi Hishida <info@77-web.com>
 */

class PluginMailQueueTable extends Doctrine_Table
{
  public function getSpool()
  {
    $limit = sfConfig::get("app_mailq_spool_limit", 1000);
    
    return $this->createQuery()->orderBy("queued_at")->limit($limit)->execute();
  }
  
  public function queue($subject, $to, $body, $encoding, $queued_at)
  {
    $mailq = new MailQueue();
    $mailq->set('subject', $subject);
    $mailq->set('mailto', $to);
    $mailq->set('body', $body);
    $mailq->set('encoding', $encoding);
    $mailq->set('queued_at', date("Y-m-d H:i:s"));
    return $mailq->save();
  }
}
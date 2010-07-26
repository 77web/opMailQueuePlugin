<?php
/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opMailqExecuteTask
 *
 * @package    opMailQueuePlugin
 * @author     Hiromi Hishida <info@77web.com>
 */


class opMailqExecuteTask extends sfDoctrineBaseTask
{
  protected function configure()
  {
    $this->namespace = "opMailq";
    $this->name = "execute";
    $this->briefDescription = 'send queued emails';
    $this->detailedDescription = <<<EOF
Call it with:

 [./symfony opMailq:execute]
EOF;
  }
  
  protected function execute($arguments = array(), $options=array())
  {
    $configuration = $this->createConfiguration('pc_frontend', 'cli');
    new sfDatabaseManager($this->configuration);
    
    $from = opConfig::get('admin_mail_address');
    foreach(Doctrine::getTable("MailQueue")->getSpool() as $mail)
    {
      if(sfOpenPNEMailSend::execute($mail->getSubject(), $mail->getMailto(), $from, $mail->getBody(), $mail->getEncoding()))
      {
        $mail->delete();
      }
    }
  }
}
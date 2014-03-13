<?php
/**
 * Author:  Mark O'Keeffe

 * Date:    13/02/14
 *
 * [Yii Workbench] EmailSenderInterface.php
 */

namespace MOK\EmailSender;


interface EmailSenderInterface {

  public function send($from, $to, $subject, $body);

  public function buildView($view, $params=array(), $layout=null);

}

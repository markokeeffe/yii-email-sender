<?php
/**
 * Author:  Mark O'Keeffe

 * Date:    13/02/14
 *
 * [Yii Workbench] EmailSender.php
 */

namespace MOK\EmailSender;

use Yii;

class EmailSender  extends \CApplicationComponent implements EmailSenderInterface
{

  /**
   * smtp|sendmail
   * @var string
   */
  public $transportType;

  /**
   * Transport options e.g. SMTP login
   * @var array
   */
  public $transportOptions = array();

  /**
   * Path to view files
   * @var string
   */
  public $viewPath = 'application.views.mail';

  /**
   * Layout file for all emails
   * @var string
   */
  public $layout = '//layouts/mail';


  /**
   * Initialisation
   */
  public function init()
  {
    $mail = Yii::createComponent(array(
      'class' => 'YiiMail',
      'transportType' => $this->transportType,
      'transportOptions' => $this->transportOptions,
      'viewPath' => $this->viewPath,
    ));

    Yii::app()->setComponent('mail', $mail);

    parent::init();
  }

  /**
   * Send an email
   *
   * @param string|array  $from
   * @param string|array  $to
   * @param string        $subject
   * @param string        $body
   */
  public function send($from, $to, $subject, $body)
  {
    $message = $this->prepareMessage($from, $to, $subject);
    $message->setBody($body, 'text/html');
    Yii::app()->mail->send($message);
  }

  /**
   * Generate email source by passing a view, parameters and layout
   *
   * @param string        $view
   * @param array         $params
   * @param null|string   $layout
   * @return string
   */
  public function buildView($view, $params = array(), $layout=null)
  {
    $message = new \YiiMailMessage;
    $message->layout = ($layout ? $layout : $this->layout);
    $message->view = $view;
    $message->setBody($params, 'text/html');

    return $message->message->getBody();
  }

  /**
   * Prepare a new \YiiMailMessage
   *
   * @param $from
   * @param $to
   * @param $subject
   *
   * @return \YiiMailMessage
   */
  private function prepareMessage($from, $to, $subject)
  {
    $message = new \YiiMailMessage;
    $message->subject = $subject;

    // Add senders
    if (is_array($from)) {
      foreach ($from as $email) {
        $message->addFrom($email);
      }
    } else {
      $message->addFrom($from);
    }

    // Add recipients
    if (is_array($to)) {
      foreach ($to as $email) {
        $message->addTo($email);
      }
    } else {
      $message->addTo($to);
    }

    return $message;
  }


}

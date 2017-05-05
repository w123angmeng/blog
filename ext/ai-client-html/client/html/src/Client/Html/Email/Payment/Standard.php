<?php

/**
 * @copyright Metaways Infosystems GmbH, 2013
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015
 * @package Client
 * @subpackage Html
 */


namespace Aimeos\Client\Html\Email\Payment;


/**
 * Default implementation of payment emails.
 *
 * @package Client
 * @subpackage Html
 */
class Standard
	extends \Aimeos\Client\Html\Common\Client\Factory\Base
	implements \Aimeos\Client\Html\Common\Client\Factory\Iface
{
	/** client/html/email/payment/standard/subparts
	 * List of HTML sub-clients rendered within the email payment section
	 *
	 * The output of the frontend is composed of the code generated by the HTML
	 * clients. Each HTML client can consist of serveral (or none) sub-clients
	 * that are responsible for rendering certain sub-parts of the output. The
	 * sub-clients can contain HTML clients themselves and therefore a
	 * hierarchical tree of HTML clients is composed. Each HTML client creates
	 * the output that is placed inside the container of its parent.
	 *
	 * At first, always the HTML code generated by the parent is printed, then
	 * the HTML code of its sub-clients. The order of the HTML sub-clients
	 * determines the order of the output of these sub-clients inside the parent
	 * container. If the configured list of clients is
	 *
	 *  array( "subclient1", "subclient2" )
	 *
	 * you can easily change the order of the output by reordering the subparts:
	 *
	 *  client/html/<clients>/subparts = array( "subclient1", "subclient2" )
	 *
	 * You can also remove one or more parts if they shouldn't be rendered:
	 *
	 *  client/html/<clients>/subparts = array( "subclient1" )
	 *
	 * As the clients only generates structural HTML, the layout defined via CSS
	 * should support adding, removing or reordering content by a fluid like
	 * design.
	 *
	 * @param array List of sub-client names
	 * @since 2014.03
	 * @category Developer
	 */
	private $subPartPath = 'client/html/email/payment/standard/subparts';

	/** client/html/email/payment/text/name
	 * Name of the text part used by the email payment client implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Client\Html\Email\Payment\Text\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the client class name
	 * @since 2014.03
	 * @category Developer
	 */

	/** client/html/email/payment/html/name
	 * Name of the html part used by the email payment client implementation
	 *
	 * Use "Myname" if your class is named "\Aimeos\Client\Html\Email\Payment\Html\Myname".
	 * The name is case-sensitive and you should avoid camel case names like "MyName".
	 *
	 * @param string Last part of the client class name
	 * @since 2014.03
	 * @category Developer
	 */
	private $subPartNames = array( 'text', 'html' );


	/**
	 * Returns the HTML code for insertion into the body.
	 *
	 * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
	 * @param array &$tags Result array for the list of tags that are associated to the output
	 * @param string|null &$expire Result variable for the expiration date of the output (null for no expiry)
	 * @return string HTML code
	 */
	public function getBody( $uid = '', array &$tags = array(), &$expire = null )
	{
		$view = $this->setViewParams( $this->getView(), $tags, $expire );

		$content = '';
		foreach( $this->getSubClients() as $subclient ) {
			$content .= $subclient->setView( $view )->getBody( $uid, $tags, $expire );
		}
		$view->paymentBody = $content;


		/** client/html/email/payment/attachments
		 * List of file paths whose content should be attached to all payment e-mails
		 *
		 * This configuration option allows you to add files to the e-mails that are
		 * sent to the customer when the payment status changes, e.g. for the order
		 * confirmation e-mail. These files can't be customer specific.
		 *
		 * @param array List of absolute file paths
		 * @since 2016.10
		 * @category Developer
		 * @category User
		 * @see client/html/email/delivery/attachments
		 */
		$files = $view->config( 'client/html/email/payment/attachments', array() );

		$this->addAttachments( $view->mail(), $files );


		/** client/html/email/payment/standard/template-body
		 * Relative path to the HTML body template of the email payment client.
		 *
		 * The template file contains the HTML code and processing instructions
		 * to generate the result shown in the body of the frontend. The
		 * configuration string is the path to the template file relative
		 * to the templates directory (usually in client/html/templates).
		 *
		 * You can overwrite the template file configuration in extensions and
		 * provide alternative templates. These alternative templates should be
		 * named like the default one but with the string "standard" replaced by
		 * an unique name. You may use the name of your project for this. If
		 * you've implemented an alternative client class as well, "standard"
		 * should be replaced by the name of the new class.
		 *
		 * The email payment HTML client allows to use a different template for
		 * each payment status value. You can create a template for each payment
		 * status and store it in the "email/payment/<status number>/" directory
		 * below the "templates" directory (usually in client/html/templates). If no
		 * specific layout template is found, the common template in the
		 * "email/payment/" directory is used.
		 *
		 * @param string Relative path to the template creating code for the HTML page body
		 * @since 2014.03
		 * @category Developer
		 * @see client/html/email/payment/standard/template-header
		 */
		$tplconf = 'client/html/email/payment/standard/template-body';

		$status = $view->extOrderItem->getPaymentStatus();
		$default = array( 'email/payment/' . $status . '/body-default.php', 'email/payment/body-default.php' );

		return $view->render( $view->config( $tplconf, $default ) );
	}


	/**
	 * Returns the HTML string for insertion into the header.
	 *
	 * @param string $uid Unique identifier for the output if the content is placed more than once on the same page
	 * @param array &$tags Result array for the list of tags that are associated to the output
	 * @param string|null &$expire Result variable for the expiration date of the output (null for no expiry)
	 * @return string|null String including HTML tags for the header on error
	 */
	public function getHeader( $uid = '', array &$tags = array(), &$expire = null )
	{
		$view = $this->setViewParams( $this->getView(), $tags, $expire );

		$content = '';
		foreach( $this->getSubClients() as $subclient ) {
			$content .= $subclient->setView( $view )->getHeader( $uid, $tags, $expire );
		}
		$view->paymentHeader = $content;


		$addr = $view->extAddressItem;

		$msg = $view->mail();
		$msg->addHeader( 'X-MailGenerator', 'Aimeos' );
		$msg->addTo( $addr->getEMail(), $addr->getFirstName() . ' ' . $addr->getLastName() );


		/** client/html/email/from-name
		 * @see client/html/email/payment/from-email
		 */
		$fromName = $view->config( 'client/html/email/from-name' );

		/** client/html/email/payment/from-name
		 * Name used when sending payment e-mails
		 *
		 * The name of the person or e-mail account that is used for sending all
		 * shop related payment e-mails to customers. This configuration option
		 * overwrite the name set in "client/html/email/from-name".
		 *
		 * @param string Name shown in the e-mail
		 * @since 2014.03
		 * @category User
		 * @see client/html/email/from-name
		 * @see client/html/email/from-email
		 * @see client/html/email/reply-email
		 * @see client/html/email/bcc-email
		 */
		$fromNamePayment = $view->config( 'client/html/email/payment/from-name', $fromName );

		/** client/html/email/from-email
		 * @see client/html/email/payment/from-email
		 */
		$fromEmail = $view->config( 'client/html/email/from-email' );

		/** client/html/email/payment/from-email
		 * E-Mail address used when sending payment e-mails
		 *
		 * The e-mail address of the person or account that is used for sending
		 * all shop related payment emails to customers. This configuration option
		 * overwrites the e-mail address set via "client/html/email/from-email".
		 *
		 * @param string E-mail address
		 * @since 2014.03
		 * @category User
		 * @see client/html/email/payment/from-name
		 * @see client/html/email/from-email
		 * @see client/html/email/reply-email
		 * @see client/html/email/bcc-email
		 */
		if( ( $fromEmailPayment = $view->config( 'client/html/email/payment/from-email', $fromEmail ) ) != null ) {
			$msg->addFrom( $fromEmailPayment, $fromNamePayment );
		}


		/** client/html/email/reply-name
		 * @see client/html/email/payment/reply-email
		 */
		$replyName = $view->config( 'client/html/email/reply-name', $fromName );

		/** client/html/email/payment/reply-name
		 * Recipient name displayed when the customer replies to payment e-mails
		 *
		 * The name of the person or e-mail account the customer should
		 * reply to in case of payment related questions or problems. This
		 * configuration option overwrites the name set via
		 * "client/html/email/reply-name".
		 *
		 * @param string Name shown in the e-mail
		 * @since 2014.03
		 * @category User
		 * @see client/html/email/payment/reply-email
		 * @see client/html/email/reply-name
		 * @see client/html/email/reply-email
		 * @see client/html/email/from-email
		 * @see client/html/email/bcc-email
		 */
		$replyNamePayment = $view->config( 'client/html/email/payment/reply-name', $replyName );

		/** client/html/email/reply-email
		 * @see client/html/email/payment/reply-email
		 */
		$replyEmail = $view->config( 'client/html/email/reply-email', $fromEmail );

		/** client/html/email/payment/reply-email
		 * E-Mail address used by the customer when replying to payment e-mails
		 *
		 * The e-mail address of the person or e-mail account the customer
		 * should reply to in case of payment related questions or problems.
		 * This configuration option overwrites the e-mail address set via
		 * "client/html/email/reply-email".
		 *
		 * @param string E-mail address
		 * @since 2014.03
		 * @category User
		 * @see client/html/email/payment/reply-name
		 * @see client/html/email/reply-email
		 * @see client/html/email/from-email
		 * @see client/html/email/bcc-email
		 */
		if( ( $replyEmailPayment = $view->config( 'client/html/email/payment/reply-email', $replyEmail ) ) != null ) {
			$msg->addReplyTo( $replyEmailPayment, $replyNamePayment );
		}


		/** client/html/email/bcc-email
		 * @see client/html/email/payment/bcc-email
		 */
		$bccEmail = $view->config( 'client/html/email/bcc-email' );

		/** client/html/email/payment/bcc-email
		 * E-Mail address all payment e-mails should be also sent to
		 *
		 * Using this option you can send a copy of all payment related e-mails
		 * to a second e-mail account. This can be handy for testing and checking
		 * the e-mails sent to customers.
		 *
		 * It also allows shop owners with a very small volume of orders to be
		 * notified about payment changes. Be aware that this isn't useful if the
		 * order volumne is high or has peeks!
		 *
		 * This configuration option overwrites the e-mail address set via
		 * "client/html/email/bcc-email".
		 *
		 * @param string E-mail address
		 * @since 2014.03
		 * @category User
		 * @category Developer
		 * @see client/html/email/bcc-email
		 * @see client/html/email/reply-email
		 * @see client/html/email/from-email
		 */
		if( ( $bccEmailPayment = $view->config( 'client/html/email/payment/bcc-email', $bccEmail ) ) != null ) {
			$msg->addBcc( $bccEmailPayment );
		}


		/** client/html/email/payment/standard/template-header
		 * Relative path to the HTML header template of the email payment client.
		 *
		 * The template file contains the HTML code and processing instructions
		 * to generate the HTML code that is inserted into the HTML page header
		 * of the rendered page in the frontend. The configuration string is the
		 * path to the template file relative to the templates directory (usually
		 * in client/html/templates).
		 *
		 * You can overwrite the template file configuration in extensions and
		 * provide alternative templates. These alternative templates should be
		 * named like the default one but with the string "standard" replaced by
		 * an unique name. You may use the name of your project for this. If
		 * you've implemented an alternative client class as well, "standard"
		 * should be replaced by the name of the new class.
		 *
		 * The email payment HTML client allows to use a different template for
		 * each payment status value. You can create a template for each payment
		 * status and store it in the "email/payment/<status number>/" directory
		 * below the "templates" directory (usually in client/html/templates). If no
		 * specific layout template is found, the common template in the
		 * "email/payment/" directory is used.
		 *
		 * @param string Relative path to the template creating code for the HTML page head
		 * @since 2014.03
		 * @category Developer
		 * @see client/html/email/payment/standard/template-body
		 */
		$tplconf = 'client/html/email/payment/standard/template-header';

		$status = $view->extOrderItem->getPaymentStatus();
		$default = array( 'email/payment/' . $status . '/header-default.php', 'email/payment/header-default.php' );

		return $view->render( $view->config( $tplconf, $default ) ); ;
	}


	/**
	 * Returns the sub-client given by its name.
	 *
	 * @param string $type Name of the client type
	 * @param string|null $name Name of the sub-client (Default if null)
	 * @return \Aimeos\Client\Html\Iface Sub-client object
	 */
	public function getSubClient( $type, $name = null )
	{
		/** client/html/email/payment/decorators/excludes
		 * Excludes decorators added by the "common" option from the email payment html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to remove a decorator added via
		 * "client/html/common/decorators/default" before they are wrapped
		 * around the html client.
		 *
		 *  client/html/email/payment/decorators/excludes = array( 'decorator1' )
		 *
		 * This would remove the decorator named "decorator1" from the list of
		 * common decorators ("\Aimeos\Client\Html\Common\Decorator\*") added via
		 * "client/html/common/decorators/default" to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2014.05
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/email/payment/decorators/global
		 * @see client/html/email/payment/decorators/local
		 */

		/** client/html/email/payment/decorators/global
		 * Adds a list of globally available decorators only to the email payment html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap global decorators
		 * ("\Aimeos\Client\Html\Common\Decorator\*") around the html client.
		 *
		 *  client/html/email/payment/decorators/global = array( 'decorator1' )
		 *
		 * This would add the decorator named "decorator1" defined by
		 * "\Aimeos\Client\Html\Common\Decorator\Decorator1" only to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2014.05
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/email/payment/decorators/excludes
		 * @see client/html/email/payment/decorators/local
		 */

		/** client/html/email/payment/decorators/local
		 * Adds a list of local decorators only to the email payment html client
		 *
		 * Decorators extend the functionality of a class by adding new aspects
		 * (e.g. log what is currently done), executing the methods of the underlying
		 * class only in certain conditions (e.g. only for logged in users) or
		 * modify what is returned to the caller.
		 *
		 * This option allows you to wrap local decorators
		 * ("\Aimeos\Client\Html\Email\Decorator\*") around the html client.
		 *
		 *  client/html/email/payment/decorators/local = array( 'decorator2' )
		 *
		 * This would add the decorator named "decorator2" defined by
		 * "\Aimeos\Client\Html\Email\Decorator\Decorator2" only to the html client.
		 *
		 * @param array List of decorator names
		 * @since 2014.05
		 * @category Developer
		 * @see client/html/common/decorators/default
		 * @see client/html/email/payment/decorators/excludes
		 * @see client/html/email/payment/decorators/global
		 */

		return $this->createSubClient( 'email/payment/' . $type, $name );
	}


	/**
	 * Adds the given list of files as attachments to the mail message object
	 *
	 * @param \Aimeos\MW\Mail\Message\Iface $msg Mail message
	 * @param array $files List of absolute file paths
	 */
	protected function addAttachments( \Aimeos\MW\Mail\Message\Iface $msg, array $files )
	{
		foreach( $files as $filename )
		{
			if( ( $content = @file_get_contents( $filename ) ) === false ) {
				throw new \Aimeos\Client\Html\Exception( sprintf( 'File "1%s" doesn\'t exist', $filename ) );
			}

			if( class_exists( 'finfo' ) )
			{
				try
				{
					$finfo = new \finfo( FILEINFO_MIME_TYPE );
					$mimetype = $finfo->file( $filename );
				}
				catch( \Exception $e )
				{
					throw new \Aimeos\Client\Html\Exception( $e->getMessage() );
				}
			}
			else if( function_exists( 'mime_content_type' ) )
			{
				$mimetype = mime_content_type( $filename );
			}
			else
			{
				$mimetype = 'application/binary';
			}

			$msg->addAttachment( $content, $mimetype, basename( $filename ) );
		}
	}


	/**
	 * Returns the list of sub-client names configured for the client.
	 *
	 * @return array List of HTML client names
	 */
	protected function getSubClientNames()
	{
		return $this->getContext()->getConfig()->get( $this->subPartPath, $this->subPartNames );
	}
}
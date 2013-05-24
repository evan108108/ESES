ESES
====

Amazon SES Component for Yii.

The code was modified from [orderingdisorder.com](http://www.orderingdisorder.com/aws/ses/)


###Install
Place ESES in your extensions directory.

Then, in your main.php config, add this code:

```php
'import' => array(
  'ext.ESES.*',
),

...

'components'=>array(
	'ses'=>array(
		'class'=>'ext.ESES.ESES',
		'access_key'=>'xxxxxxxxxxxxxxx',
		'secret_key'=>'xxxxxxxxxxxxxxxxxxxxxxxxxxxx',
		'host'=>'email.us-east-1.amazonaws.com',
		//Might be this email-smtp.us-east-1.amazonaws.com
	)
)
```

###Examples

####Sending Email

```php
	$response = Yii::app()->ses->email()
		->addTo('recipient@example.com')
		->setFrom('user@example.com')
		->setSubject('Hello, world!')
		->setMessageFromString($text, $html) //Pass a string body and html body
		->addCC(array('kelly@example.com', 'ryan@example.com'))
		->addBCC('michael@example.com')
		->addReplyTo('andy@example.com')
		->setReturnPath('noreply@example.com')
		->setSubjectCharset('ISO-8859-1')
		->setMessageCharset('ISO-8859-1')
	->send();
		
	print_r($response);
	/*
	Array
	(
  		[MessageId] => 0000012dc5e4b4c0-b2c566ad-dcd0-4d23-bea5-f40da774033c-000000
  		[RequestId] => 4953a96e-29d4-11e0-8907-21df9ed6ffe3
	)
	*/
```

####Verify Email Address
```php
	Yii::app()->ses->verifyEmailAddress('user@example.com');
	
	Yii::app()->ses->deleteVerifiedEmailAddress('user@example.com');
	
	Yii::app()->ses->listVerifiedEmailAddresses();
```

####Your IAM user policy will need to look something like this if you want to have access to all of these methods:
```json
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Action": "ses:SendRawEmail",
      "Resource": "*"
    },
    {
      "Effect": "Allow",
      "Action": "ses:SendEmail",
      "Resource": "*"
    },
    {
      "Effect": "Allow",
      "Action": "ses:ListVerifiedEmailAddresses",
      "Resource": "*"
    },
    {
      "Effect": "Allow",
      "Action": "ses:VerifyEmailAddress",
      "Resource": "*"
    },
    {
      "Effect": "Allow",
      "Action": "ses:DeleteVerifiedEmailAddress",
      "Resource": "*"
    }
  ]
}
```

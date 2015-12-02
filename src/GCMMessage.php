<?php
/**
 * @author: Albaraa Mishlawi
 * @package: albaraam/php-gcm
 */

namespace albaraam\gcm;


class GCMMessage
{

	/**
	 * An arbitrary string (such as "Updates Available") that is used to collapse a group of like messages
	 * when the device is offline, so that only the last message gets sent to the client.
	 * This is intended to avoid sending too many messages to the phone when it comes back online.
	 * Note that since there is no guarantee of the order in which messages get sent, the "last" message
	 * may not actually be the last message sent by the application server.
	 * Collapse keys are also called send-to-sync messages.
	 *
	 * Optional.
	 *
	 * @var string|null
	 */
	private $collapseKey = null;

	/**
	 * Key-value pairs od messages payload data.
	 * Max payload size is 4kB
	 *
	 * Optional.
	 *
	 * @var array|null
	 */
	private $data = null;

	/**
	 * Key-value pairs od messages payload data.
	 * Max payload size is 4kB
	 *
	 * Optional.
	 *
	 * @var Notification|null
	 */
	private $notification = null;

	/**
	 * If included, indicates that the message should not be sent immediately if the device is idle.
	 * The server will wait for the device to become active, and then only the last message
	 * for each collapse_key value will be sent.
	 *
	 * Optional.
	 *
	 * @var boolean
	 */
	private $delayWhileIdle = false;

	/**
	 * How long (in seconds) the message should be kept on GCM storage if the device is offline.
	 *
	 * Optional (default time-to-live is 4 weeks).
	 *
	 * @var int
	 */
	private $timeToLive = null;

	/**
	 * A string containing the package name of your application.
	 * When set, messages will only be sent to registration IDs that match the package name.
	 *
	 * Optional.
	 *
	 * @var string|null
	 */
	private $restrictedPackageName = null;

	/**
	 * If included, allows developers to test their request without actually sending a message.
	 *
	 * Optional.
	 *
	 * @var boolean
	 */
	private $dryRun = false;

	/**
	 * Validation errors
	 *
	 * @var array
	 */

	private $_errors = [];


	public function __construct($notification = null, $data = null, $collapseKey = null)
	{

		$this->setData($data);
		$this->setCollapseKey($collapseKey);
	}


	public function getCollapseKey()
	{
		return $this->collapseKey;
	}


	public function setCollapseKey($collapseKey)
	{
		$this->collapseKey = $collapseKey;
		return $this;
	}


	public function getData()
	{
		return $this->data;
	}


	public function setData($data)
	{
		$this->data = $data;
		return $this;
	}

	public function getNotification()
	{
		if($this->notification == null){
			$this->notification = new Notification();
		}
		return $this->notification;
	}


	public function setNotification($notification)
	{
		$this->notification = $notification;
		return $this;
	}


	public function getDelayWhileIdle()
	{
		return $this->delayWhileIdle;
	}


	public function setDelayWhileIdle($delayWhileIdle)
	{
		$this->delayWhileIdle = $delayWhileIdle;
		return $this;
	}


	public function getTimeToLive()
	{
		return $this->timeToLive;
	}


	public function setTimeToLive($timeToLive)
	{
		$this->timeToLive = $timeToLive;
		return $this;
	}


	public function getRestrictedPackageName()
	{
		return $this->restrictedPackageName;
	}


	public function setRestrictedPackageName($restrictedPackageName)
	{
		$this->restrictedPackageName = $restrictedPackageName;
		return $this;
	}


	public function getDryRun()
	{
		return $this->dryRun;
	}


	public function setDryRun($dryRun)
	{
		$this->dryRun = $dryRun;
		return $this;
	}

	public function isValid()
	{
		$errors = [];
		if($this->getNotification()->getTitle() == ""){
			$this>_errors[] = "Notification title is required";
		}
		return (count($this->_errors) > 0) ? false : true;
	}

	public function getErrors()
	{
		return $this->_errors;
	}

	/**
     * Returns the value of an object property.
     *
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when executing `$value = $object->property;`.
     * @param string $name the property name
     * @return mixed the property value
     * @throws UnknownPropertyException if the property is not defined
     * @throws InvalidCallException if the property is write-only
     * @see __set()
     */
    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter();
        }/* elseif (method_exists($this, 'set' . $name)) {
            throw new InvalidCallException('Getting write-only property: ' . get_class($this) . '::' . $name);
        } else {
            throw new UnknownPropertyException('Getting unknown property: ' . get_class($this) . '::' . $name);
        }*/
    }

    /**
     * Sets value of an object property.
     *
     * Do not call this method directly as it is a PHP magic method that
     * will be implicitly called when executing `$object->property = $value;`.
     * @param string $name the property name or the event name
     * @param mixed $value the property value
     * @throws UnknownPropertyException if the property is not defined
     * @throws InvalidCallException if the property is read-only
     * @see __get()
     */
    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        }/* elseif (method_exists($this, 'get' . $name)) {
            throw new InvalidCallException('Setting read-only property: ' . get_class($this) . '::' . $name);
        } else {
            throw new UnknownPropertyException('Setting unknown property: ' . get_class($this) . '::' . $name);
        }*/
    }

}

/**
* GCM Payload Notification
*/
class Notification
{
	/*
     * @var string (required) Indicates notification title.
     * */
    private $title;


    /*
     * @var string (optional) Indicates notification body text.
     * */
    private $body;

    /*
     * @var string (required) Indicates notification icon. sets value to myicon for drawable resource myicon.
     * */
    private $icon;

    /*
     * @var string (optional) Indicates sound to be played
     * */
    private $sound;

    /*
     * @var string (optional)
     *
     * Indicates whether each notification message results in a new entry on the notification center on Android.
     * If not set, each request creates a new notification. If set, and a notification with the same tag is already
     *  being shown, the new notification replaces the existing one in notification center.
     *
     * */
    private $tag;

    /*
     * @var string (optional) Indicates color of the icon, expressed in #rrggbb format
     *
     * */
    private $color;

    /*
     * @var string (optional)
     *
     * The action associated with a user click on the notification.
     * On Android, if this is set, an activity with a matching intent filter is launched when user clicks the notification. For example, if one of your Activities includes the intent filter:
     * <intent-filter>
     *      <action android:name="OPEN_ACTIVITY_1" />
     *      <category android:name="android.intent.category.DEFAULT" />
     * </intent-filter>
     * Set click_action to OPEN_ACTIVITY_1 to open it.
     * If set, corresponds to category in APNS payload.
     *
     * */
    private $click_action;

    /*
     * @var string (optional) Indicates the key to the body string for localization.
     *
     * On Android, use the key in the app's string resources when populating this value.
     *
     * */
    private $body_loc_key;

    /*
     * @var JSON array as string (optional) Indicates the string value to replace format specifiers in body string
     *  for localization.
     *
     * On Android, these are the format arguments for the string resource. For more information, see:
     * http://developer.android.com/guide/topics/resources/string-resource.html#FormattingAndStyling
     *
     * */
    private $body_loc_args;

    /*
     * @var string (optional) Indicates the key to the title string for localization.
     *
     * On Android, use the key in the app's string resources when populating this value.
     *
     * */
    private $title_loc_key;

    /*
     * @var JSON array as string (optional) Indicates the string value to replace format specifiers in title string
     *  for localization.
     *
     * On Android, these are the format arguments for the string resource. For more information, see:
     * http://developer.android.com/guide/topics/resources/string-resource.html#FormattingAndStyling
     *
     * */
    private $title_loc_args;



	function __construct() { }


	/******************************************** Getters & Setters *************************************************/

	/**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param mixed $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * @return mixed
     */
    public function getSound()
    {
        return $this->sound;
    }

    /**
     * @param mixed $sound
     */
    public function setSound($sound)
    {
        $this->sound = $sound;
    }

    /**
     * @return mixed
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param mixed $tag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param mixed $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return mixed
     */
    public function getClickAction()
    {
        return $this->click_action;
    }

    /**
     * @param mixed $click_action
     */
    public function setClickAction($click_action)
    {
        $this->click_action = $click_action;
    }

    /**
     * @return mixed
     */
    public function getBodyLocKey()
    {
        return $this->body_loc_key;
    }

    /**
     * @param mixed $body_loc_key
     */
    public function setBodyLocKey($body_loc_key)
    {
        $this->body_loc_key = $body_loc_key;
    }

    /**
     * @return mixed
     */
    public function getBodyLocArgs()
    {
        return $this->body_loc_args;
    }

    /**
     * @param mixed $body_loc_args
     */
    public function setBodyLocArgs($body_loc_args)
    {
        $this->body_loc_args = $body_loc_args;
    }

    /**
     * @return mixed
     */
    public function getTitleLocKey()
    {
        return $this->title_loc_key;
    }

    /**
     * @param mixed $title_loc_key
     */
    public function setTitleLocKey($title_loc_key)
    {
        $this->title_loc_key = $title_loc_key;
    }

    /**
     * @return mixed
     */
    public function getTitleLocArgs()
    {
        return $this->title_loc_args;
    }

    /**
     * @param mixed $title_loc_args
     */
    public function setTitleLocArgs($title_loc_args)
    {
        $this->title_loc_args = $title_loc_args;
    }
}
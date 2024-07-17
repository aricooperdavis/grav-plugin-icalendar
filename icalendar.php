<?php
namespace Grav\Plugin;

use Composer\Autoload\ClassLoader;
use DateTimeZone;
use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;
use RocketTheme\Toolbox\File\File;
use Twig\Markup;

use GuzzleHttp\Client;
use om\IcalParser;

/**
 * Class IcalendarPlugin
 * @package Grav\Plugin
 */
class IcalendarPlugin extends Plugin
{
	/**
	 * Make use of Composer Autoloader
	 */
	public function autoload(): ClassLoader
    {
        return require __DIR__ . '/vendor/autoload.php';
    }

	/*
	 *
	 */
	public function onPluginsInitialized()
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }

        // Enable the main event we are interested in
        $this->enable([
            'onTwigInitialized' => ['onTwigInitialized', 0]
        ]);
    }

    /**
     * @param Event $e
     */
    public function onTwigInitialized(Event $e)
    {
		$this->grav['twig']->twig()->addFunction(
			new \Twig_SimpleFunction('eventlist', [$this, 'eventList'])
		);
    }

	public function eventList(?string $icsUrl = "")
	{
		// Get plugin config
		$config = $this->config();

		// Parse iCal file
		$icsUrl = $icsUrl ?: $config['icsurl'];
		if (!$icsUrl) {
			return new Markup('<em>iCal URL not set</em>', 'UTF-8');
		}
		$cal = new IcalParser();
		$cal->parseFile($icsUrl);

		// Get current unix time
		$today = (int) date('U');
		$tz = new DateTimeZone(date_default_timezone_get());

		$eventList = '';
		$eventCount = 0;
		foreach ($cal->getEvents()->sorted() as $event) {
			// Ensure event is in future
			if (((int) $event['DTSTART']->format('U')) > $today) {
				// Convert components to string
				$eventDate = $event['DTSTART']->setTimeZone($tz)->format($config['dateformat']);
				$eventSummary = $event['SUMMARY'] ?? 'No title';
				$eventUrl = $event['URL'] ?? '';
				$eventUrl = ($eventUrl ? "<a href='{$eventUrl}' target='_blank'>{$eventUrl}</a> - " : '');
				$eventDesc = $event['DESCRIPTION'] ?? '';

				// Append event to string
				$eventList .= "<li title='{$eventDesc}'>{$eventDate} - {$eventUrl}{$eventSummary}</li>";
				$eventCount++;
			}

			// Only get first numevents events
			if ($eventCount >= $config['numevents']) {
				break;
			}
		}

		if (!$eventList) {
			return new Markup('<em>No upcoming events</em>', 'UTF-8');
		}

		return new Markup($eventList, 'UTF-8');
	}
}

# Icalendar Plugin

The **Icalendar** Plugin is for [Grav CMS](http://github.com/getgrav/grav). It adds a new Twig function which fetches events from a remote ICS file and displays them in a list.

> ℹ️ It is a fork of [Werner Joss's "Icalendar" plugin](https://github.com/wernerjoss/grav-plugin-icalendar), but with a significant-enough rewrite for me to publish it seperately under my name.

## Installation

As this is a fork you'll have to install it manually:

### Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `icalendar`. You can find these files on [GitHub](https://github.com/aricooperdavis/grav-plugin-icalendar).

You should now have all the plugin files under

    /your/site/grav/user/plugins/icalendar
    
> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav) and the [Error](https://github.com/getgrav/grav-plugin-error) and [Problems](https://github.com/getgrav/grav-plugin-problems) to operate.

To ensure you've got all the dependencies you could run `composer update` from the plugin folder.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/icalendar/icalendar.yaml` to `user/config/plugins/icalendar.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:

```yaml

enabled: true
icsurl: https://example.com/example.ics    # Default ICS url, if not passed to eventlist() function
numevents: 2    # max, No. of Events to include in List
dateformat: d.m.Y   # default Date Format

```

Note that if you use the admin plugin, a file with your configuration, and named icalendar.yaml will be saved in the `user/config/plugins/` folder once the configuration is saved in the admin.

## Usage

Once installed and enabled, you can use this Plugin to read events from a remote ICS file. To do this include the following twig function

    {{ eventlist() }} 
    
in the appropriate template (or page, if twig processing is enabled).

You can wrap in order to change the list type, e.g.

    <ul>{{ eventlist() }}</ul>
    
or 

    <ol>{{ eventlist() }}</ol> 

If you pass a URL to the function it will read that one, as opposed to the one in the plugin configuration, e.g.

    {{ eventlist("https://example.com/example2.ics") }}

## Credits

- [om/icalparser](https://github.com/OzzyCzech/icalparser)
- [wernerjoss/grav-plugin-icalendar](https://github.com/wernerjoss/grav-plugin-icalendar)

## To Do

- [ ] add Calendar Widget (not just Events List)
- [ ] add Option to also show Events from the Past
- [ ] add more rendering options


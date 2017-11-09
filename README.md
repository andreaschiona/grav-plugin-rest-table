# Rest Table Plugin

**This README.md file should be modified to describe the features, installation, configuration, and general usage of this plugin.**

The **Rest Table** Plugin is for [Grav CMS](http://github.com/getgrav/grav). A dynamic table populates from a REST service output

You can found a live demo here: [http://gravtest.hostfree.pw/test](http://gravtest.hostfree.pw/test)

## Installation

Installing the Rest Table plugin can be done in one of two ways. The GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file.

### GPM Installation (Preferred)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's terminal (also called the command line).  From the root of your Grav install type:

    bin/gpm install rest-table

This will install the Rest Table plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/rest-table`.

### Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `rest-table`. You can find these files on [GitHub](https://github.com/andrea-schiona/grav-plugin-rest-table) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/rest-table
	
> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav) and the [Error](https://github.com/getgrav/grav-plugin-error) and [Problems](https://github.com/getgrav/grav-plugin-problems) to operate.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/rest-table/rest-table.yaml` to `user/config/plugins/rest-table.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:

```yaml
enabled: true
```

This plugin extends the Shortcode Core infrastructure. See that documentation to learn how 
to disable/enable shortcode processing on a page-by-page basis.

## Usage

This plugin converts the JSON response of a REST service into HTML code. 
Just insert

```
[rest2table url=<the service url> <options>][/rest2table]
```

at this moments, the support options are:

- `list` [default null] the list name
- `headers` [default `true`] for include or not the table headers. The headers name, 
if no `heandernames`is not defined, is recuperated from the service response fields name.
- `heandernames` [default null] the list of the headers name
- `caption` [default null] insert a `<caption>` tag containing the value of this option 
- `fields` [default all the fields returned] the list of the fields to show. Others fields will be hidden  

## Example

For example, if you have a rest service that returns a json as following

```JSON
{
    [
        {"name": "Mario Rossi", "age": "30" },
        {"name": "Luca Bianchi", "age": "28" },
        {"name": "Luigi Verdi", "age": "34" }
    ]
}
```
For include in your page a simple table like this


| Name         | Age |
|--------------|-----|
| Mario Rossi  | 30  |
| Luca Bianchi | 28  |
| Luigi Verdi  | 34  |  


you just insert the following line

```
[rest2table url=http://host:port/res/user header=false][/rest2table]
```



## To Do


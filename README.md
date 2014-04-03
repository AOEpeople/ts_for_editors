ts_for_editors
==============

A TYPO3 extension to allow editors modification of some TypoScript Constants.

Access to this feature can be configured with ACLs in be_users and be_groups.


Configuration
-------------

Create a FlexForm for the values you want your editors to modify.

There is an example in [Configuration/FlexForm/sys_template.tx_tsforeditors_constants.xml](Configuration/FlexForm/sys_template.tx_tsforeditors_constants.xml).
Make sure that the name of the field reflects the name of the constant you want to override.

Then include that file by adding the following line to ext_tables.php:

    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][$_EXTKEY]['flexForm'] = 'path/to/your/flexform.xml';

When editing a sys_template now there is a new tab "Constant Wizard" where your FlexForm shows up.
As soon as you modify values there and save the sys_template, the constants will be available in TypoScript.

To give you editors access, edit the corresponding be_groups or be_user entry to grant access to
sys_template and the tx_tsforeditors_constants field.


Lowlevel Cleaner Task
---------------------

There is a lowlevel cleaner task that goes through all sys_template records and removes FlexForm data
that was removed from the FlexForm definition file. In order to run this you need to have the
system extension "lowlevel" installed and configured.

    php typo3/cli_dispatch.phpsh lowlevel_cleaner tsforeditors_clean_xml_data -r

You should always run this script after modifications on the FlexForm definition file, especially if you
removed configurations.

If you don't run that script you might get constants overridden by the extension even though they are
no longer configured in the xml file.
This is due to the TYPO3 behavior of not removing values from FlexForm data.

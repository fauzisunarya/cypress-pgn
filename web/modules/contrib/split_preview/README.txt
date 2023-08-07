DESCRIPTION
-----------

This module provide a preview content feature on same page, with addition split preview for Mobile, Tablet and Desktop.

INSTALL
-------

If your site is managed via Composer, use Composer to download the module.

composer require drupal/split_preview
drush en split_preview
drush cr

Or

Enable the module in 'Extend' page (/admin/modules).

Configuration:
1] Make sure that your default preview feature is enabled, to enable go to Structure => Content Types => Select Content
Type => Select Edit, Select radio button preview optional or required.
2] Enable the module split_preview
3] On your content type Live Preview Button Appears.

USAGE
-----

Instead of opening  (default) node preview on independent page, This split preview provides the feature to open that
preview at same page (Ajax Load) in a IFrame, Also provides a split previews like Mobile, Tablet and Desktop preview.

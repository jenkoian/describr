<?php
/**
 * Package file for creating PEAR packages. This file defines how the PEAR
 * package should be constructed.
 *
 * Before a new tag is made, VERSION should be incremented to the new tag identifier.
 *
 * @author    Box UK <opensource@boxuk.com>
 * @copyright Copyright (c) 2011, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.html GPL license
 * @link      http://github.com/boxuk/describr
 * @since     1.0.0
 */
define( 'VERSION', '1.0.1' );

require_once( 'PEAR/PackageFileManager2.php' );

$packagexml = new PEAR_PackageFileManager2;
$packagexml->setOptions(array(
    'packagedirectory' => 'lib',
    'baseinstalldir' => '/'
));
        
$packagexml->setPackage( 'describr' );
$packagexml->setSummary( 'So, tell me about your file...' );
$packagexml->setDescription( 'Given any file, describr will describe it. Can be used as a command line script or within a PHP project' );
$packagexml->setChannel( 'pear.boxuk.com' );
$packagexml->setAPIVersion( VERSION );
$packagexml->setReleaseVersion( VERSION );
$packagexml->setReleaseStability( 'stable' );
$packagexml->setAPIStability( 'stable' );
$packagexml->setNotes( "-" );
$packagexml->setPackageType( 'php' );
$packagexml->setPhpDep( '5.3.0' );
$packagexml->setPearinstallerDep( '1.3.0' );
$packagexml->addMaintainer( 'lead', 'Open', 'Source', 'opensource@boxuk.com' );
$packagexml->setLicense( 'MIT License', 'http://opensource.org/licenses/mit-license.php' );
$packagexml->generateContents();
$packagexml->writePackageFile();
<?php
/**
 * Static Sites Main CSS
 *
 * @package StaticSites
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Jeff Tilson
 * @copyright THINK Global School 2010 - 2015
 * @link http://www.thinkglobalschool.org/
 *
 */
 ?>

/***********************************************************
 * Layout Styles
 **********************************************************/
#staticsites-manage-body {

}

#staticsites-manager-app {
	width: 960px;
	margin: 40px auto;
	box-shadow: 0 0 14px #222222;
}


#staticsites-manager-app > header {
	padding: 10px;
}

#staticsites-manager-app > header h1 {
    color: #FFFFFF;
    display: inline-block;
    font-size: 50px;
    font-weight: bold;
    line-height: 50px;
    text-shadow: 0 0 10px #222222;
    text-transform: none;
}

#staticsites-manager-app > header p {
	color: #FFFFFF;
	padding-top: 10px;
	border-top: 1px dotted #555;
	margin-top: 10px;
}

#staticsites-manager-app section#main {
	background: none repeat scroll 0 0 #FFFFFF;
	padding: 10px;
	float: left;
	width: 730px;
}



#staticsites-manager-app > footer {
    color: #000000;
    padding: 5px 10px;
} 

#staticsites-manager-app > footer a {
	color: #FFF;
	cursor: pointer;
	height:18px;
}

#staticsites-manager-app nav#pages-nav {
	float: left;
	width: 200px;
	padding: 10px;
	width: 180px;
}

#staticsites-manager-app > #staticsites-manager-core {
	overflow: hidden;
}

#staticsites-manager-app .gradient {
	/* Firefox v3.6+ */
	background-image:-moz-linear-gradient(50% 0% -180deg,rgb(55,72,79) 0%,rgb(29,40,45) 100%); 
	/* safari v4.0+ and by Chrome v3.0+ */
	background-image:-webkit-gradient(linear,50% 0%,50% 167%,color-stop(0, rgb(55,72,79)),color-stop(1, rgb(29,40,45)));
	/* Chrome v10.0+ and by safari nightly build*/
	background-image:-webkit-linear-gradient(-180deg,rgb(55,72,79) 0%,rgb(29,40,45) 100%);
	/* Opera v11.10+ */
	background-image:-o-linear-gradient(-180deg,rgb(55,72,79) 0%,rgb(29,40,45) 100%);
	/* IE v10+ */
	background-image:-ms-linear-gradient(-180deg,rgb(55,72,79) 0%,rgb(29,40,45) 100%);
	background-image:linear-gradient(-180deg,rgb(55,72,79) 0%,rgb(29,40,45) 100%);
	-ms-filter:"progid:DXImageTransform.Microsoft.gradient(startColorstr=#ff37484f,endColorstr=#ff1d282d,GradientType=0)";
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#ff37484f,endColorstr=#ff1d282d,GradientType=0);

}

/***********************************************************
 * Editor Styles
 **********************************************************/

 #staticsites-page-editor {
 	border: dotted 1px #ccc;
    border-width: 0 1px;
    margin-bottom: 50px;
    margin-top: 30px;

    padding-right: 15px;
    padding-left: 15px;
    margin-right: auto;
    margin-left: auto;
 }

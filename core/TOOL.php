<?php

function	is_ajax_query()
{
	if (isset($_GET['is_ajax']))
		return ((intval($_GET['is_ajax'])) ? TRUE : FALSE);
	else
		return (FALSE);
}

function	is_loggued()
{
	if (!isset($_SESSION['user']))
		return (FALSE);
	else
		return (TRUE);
}

function	loggued_username()
{
	if (is_loggued())
		return ($_SESSION['user']['username']);
	return (NULL);
}

function	loggued_id()
{
	if (is_loggued())
		return ($_SESSION['user']['id']);
	return (NULL);
}

function	redirect($path)
{
//	header ('location:'.SITE_ROOT. $path);
	header( "refresh:1;url=" .SITE_ROOT. $path);
	die();
}


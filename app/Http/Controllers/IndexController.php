<?php

namespace App\Http\Controllers;

use Spatie\DbDumper\Databases\MySql;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class IndexController extends Controller
{
	public function index()
	{
		return view('welcome');
	}

	/**
	 * Export database
	 *
	 * @return BinaryFileResponse
	 */
	public function exportDatabase()
	{
		$dbName = config('database.connections.mysql.database');

		$file = "{$dbName}_dump.sql";

		MySql::create()
			->setHost(config('database.connections.mysql.host'))
			->setUserName(config('database.connections.mysql.username'))
			->setPassword(config('database.connections.mysql.password'))
			->setDbName($dbName)
			->dumpToFile($file);

		$headers = [
			'Content-Type: application/sql',
		];

		return response()->download(public_path() . "/{$file}", "{$dbName}.sql", $headers);
	}
}

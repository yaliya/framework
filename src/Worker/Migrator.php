<?php 

namespace Tau\Worker;

interface Migrator
{
	public function up($schema);
	public function down($schema);
}
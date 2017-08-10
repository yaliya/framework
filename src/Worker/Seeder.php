<?php 

namespace Tau\Worker;

interface Seeder 
{
	public function run();
	public function clear();
}
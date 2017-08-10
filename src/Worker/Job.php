<?php 

namespace Tau\Worker;

interface Job 
{
	public function run();
}
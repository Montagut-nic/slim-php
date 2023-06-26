<?php
interface IApiUsable
{
	public function ListarTodos($request, $response, $args);
	public function Alta($request, $response, $args);
}
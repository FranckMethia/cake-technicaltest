<?php

require(__DIR__ . '/Router.class.php');
/**
 * Permet de définir un dossier racine de l'application
 *
 */
define ('BASEURL', "/cake-technicaltest");

Router::addRoute('GET', '/clients', 'Clients', 'doList');
Router::addRoute('GET', '/clients/{id}', 'Clients', 'doGet');
Router::addRoute('PUT', '/clients/{id}', 'Clients', 'doSet');
Router::addRoute('POST', '/clients', 'Clients', 'doCreate');
Router::addRoute('DELETE', '/clients/{id}', 'Clients', 'doDelete');

Router::doRoute($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
